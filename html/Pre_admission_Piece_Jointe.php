<?php
require('logs/Logout_Secretaire.php');
session_start();

// Connexion à la base de données
require('logs/logs.php');

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$nomBDD", $utilisateur, $motdepasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $erreur = "Erreur de connexion : " . $e->getMessage();
    die($erreur); // Arrête le script si la connexion échoue
}

// Types MIME autorisés
$allowedMimeTypes = ['image/png', 'image/jpeg', 'image/jpg'];

// Fonction pour vérifier le type MIME d'un fichier
function isFileTypeAllowed($file, $allowedMimeTypes) {
    $fileMimeType = mime_content_type($file['tmp_name']);
    return in_array($fileMimeType, $allowedMimeTypes);
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['form_data'] = $_POST;

    // Récupération des fichiers téléchargés
    $carte_identite_recto = $_FILES['carte_identite_recto'];
    $carte_identite_verso = $_FILES['carte_identite_verso'];
    $carte_vitale = $_FILES['carte_vitale'];
    $carte_mutuelle = $_FILES['carte_mutuelle'];
    $piece_livret = $_FILES['piece_livret'];
    $piece_autorisation = $_FILES['piece_autorisation'];

    // Vérification des champs obligatoires
    if (empty($carte_identite_recto['name'])) {
        $erreur = "La carte d'identité (recto) est obligatoire.";
    } elseif (empty($carte_identite_verso['name'])) {
        $erreur = "La carte d'identité (verso) est obligatoire.";
    } elseif (empty($carte_vitale['name'])) {
        $erreur = "La carte vitale est obligatoire.";
    } elseif (empty($carte_mutuelle['name'])) {
        $erreur = "La carte de mutuelle est obligatoire.";
    } else {
        // Vérification des types de fichiers
        if (!isFileTypeAllowed($carte_identite_recto, $allowedMimeTypes)) {
            $erreur = "Le fichier de la carte d'identité (recto) doit être une image (PNG, JPG, JPEG).";
        } elseif (!isFileTypeAllowed($carte_identite_verso, $allowedMimeTypes)) {
            $erreur = "Le fichier de la carte d'identité (verso) doit être une image (PNG, JPG, JPEG).";
        } elseif (!isFileTypeAllowed($carte_vitale, $allowedMimeTypes)) {
            $erreur = "Le fichier de la carte vitale doit être une image (PNG, JPG, JPEG).";
        } elseif (!isFileTypeAllowed($carte_mutuelle, $allowedMimeTypes)) {
            $erreur = "Le fichier de la carte de mutuelle doit être une image (PNG, JPG, JPEG).";
        } elseif (!empty($piece_livret['name']) && !isFileTypeAllowed($piece_livret, $allowedMimeTypes)) {
            $erreur = "Le fichier du livret de famille doit être une image (PNG, JPG, JPEG).";
        } elseif (!empty($piece_autorisation['name']) && !isFileTypeAllowed($piece_autorisation, $allowedMimeTypes)) {
            $erreur = "Le fichier de l'autorisation doit être une image (PNG, JPG, JPEG).";
        } else {
            try {
                // Lire le contenu binaire des fichiers
                $carte_identite_recto_bin = file_get_contents($carte_identite_recto['tmp_name']);
                $carte_identite_verso_bin = file_get_contents($carte_identite_verso['tmp_name']);
                $carte_vitale_bin = file_get_contents($carte_vitale['tmp_name']);
                $carte_mutuelle_bin = file_get_contents($carte_mutuelle['tmp_name']);
                $piece_livret_bin = !empty($piece_livret['tmp_name']) ? file_get_contents($piece_livret['tmp_name']) : null;
                $piece_autorisation_bin = !empty($piece_autorisation['tmp_name']) ? file_get_contents($piece_autorisation['tmp_name']) : null;

                // Préparer la requête SQL pour insérer les pièces jointes
                $stmt = $connexion->prepare("
                    INSERT INTO Piece_jointe (
                        id_patient, carte_identite_recto, carte_identite_verso, carte_vitale, carte_mutuelle, livret_famille, autorisation_mineur
                    ) VALUES (
                        :num_secu, :carte_identite_recto, :carte_identite_verso, :carte_vitale, :carte_mutuelle, :livret_famille, :autorisation_mineur
                    )
                ");

                // Lier les paramètres
                $stmt->bindParam(':num_secu', $_SESSION['etape1']['num_secu']); // Données de l'étape 1
                $stmt->bindParam(':carte_identite_recto', $carte_identite_recto_bin, PDO::PARAM_LOB);
                $stmt->bindParam(':carte_identite_verso', $carte_identite_verso_bin, PDO::PARAM_LOB);
                $stmt->bindParam(':carte_vitale', $carte_vitale_bin, PDO::PARAM_LOB);
                $stmt->bindParam(':carte_mutuelle', $carte_mutuelle_bin, PDO::PARAM_LOB);
                $stmt->bindParam(':livret_famille', $piece_livret_bin, PDO::PARAM_LOB);
                $stmt->bindParam(':autorisation_mineur', $piece_autorisation_bin, PDO::PARAM_LOB);

                // Exécuter la requête
                $stmt->execute();

                // Rediriger vers la page suivante
                header('Location: Pre_admission_Hospitalisation.php');
                exit();
            } catch (PDOException $e) {
                $erreur = "Erreur lors de l'insertion : " . $e->getMessage();
            }
        }
    }
}

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pré admission | Etape 5 sur 6</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <header class="navbar">
        <div class="logo-container">
            <img src="img/LPFS_logo.png" alt="Logo" class="logo">
        </div>
        <div class="page">
            <a href="admin.php">Accueil</a>
            <a href="Pre_admission_Choix.php">Pré-admission</a>        
            <a href="?logout=true">Se déconnecter</a>
        </div>
    </header>
    <div class="container-pre-admission">
        <!-- Formulaire -->
        <form method="POST" action="" enctype="multipart/form-data">
            <h6>PIÈCES JOINTES DU PATIENT <br>Etape 5 sur 6</h6>
            <br>

            <!-- Affichage des erreurs -->
            <?php if (!empty($erreur)): ?>
                <div class="error-message">
                    <p><?php echo htmlspecialchars($erreur); ?></p>
                </div>
            <?php endif; ?>

            <label for="carte_identite_recto">Carte d'identité (recto) :<span class= "requis"> *</span></label>
            <br>
            <input type="file" id="carte_identite_recto" name="carte_identite_recto" accept=".png, .jpg, .jpeg" required>
            <br><br>

            <label for="carte_identite_verso">Carte d'identité (verso) :<span class= "requis"> *</span></label>
            <br>
            <input type="file" id="carte_identite_verso" name="carte_identite_verso" accept=".png, .jpg, .jpeg" required>
            <br><br>

            <label for="carte_vitale">Carte vitale :<span class= "requis"> *</span></label>
            <br>
            <input type="file" id="carte_vitale" name="carte_vitale" accept=".png, .jpg, .jpeg" required>
            <br><br>

            <label for="carte_mutuelle">Carte de mutuelle :<span class= "requis"> *</span></label>
            <br>
            <input type="file" id="carte_mutuelle" name="carte_mutuelle" accept=".png, .jpg, .jpeg" required>
            <br><br>

            <label for="piece_livret">Livret de famille (pour enfants mineurs) :</label>
            <br>
            <input type="file" id="piece_livret" name="piece_livret" accept=".png, .jpg, .jpeg">
            <br><br>

            <label for="piece_autorisation">Autorisation (pour enfants mineurs) :</label>
            <br>
            <input type="file" id="piece_autorisation" name="piece_autorisation" accept=".png, .jpg, .jpeg">
            <br><br>

            <div class="navigation">
                <button type="button" onclick="window.location.href='Pre_admission_Prevenir.php'" class="button-next">Retour</button>
                <button type="submit" class="button-next">Suivant</button>
            </div>
        </form>
    </div>
</body>
</html>