<?php
require('Logout.php');
session_start();

// Connexion à la base de données
require('logs.php');

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$nomBDD", $utilisateur, $motdepasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $erreur = "Erreur de connexion : " . $e->getMessage();
    die($erreur); // Arrête le script si la connexion échoue
}

// Récupérer l'ID de la personne de confiance (supposons qu'il est stocké en session)
$id_pers2 = $_SESSION['etape3']['id_pers2'] ?? null;

// Vérifier que l'ID de la personne de confiance est défini
if ($id_pers2 === null) {
    // Rediriger l'utilisateur vers l'étape 3
    header('Location: Pre_admission_Confiance.php');
    exit();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['form_data'] = $_POST;

    // Récupération et nettoyage des données
    $choix_personne = htmlspecialchars($_POST['choix_personne']);
    $nom_naissance_personne1 = htmlspecialchars($_POST['Nom_naissance_personne1'] ?? '');
    $prenom_personne1 = htmlspecialchars($_POST['prenom_personne1'] ?? '');
    $telephone_personne1 = htmlspecialchars($_POST['Téléphone_personne1'] ?? '');
    $adresse_personne1 = htmlspecialchars($_POST['Adresse_personne1'] ?? '');

    // Vérification des champs obligatoires
    if (empty($choix_personne)) {
        $erreur = "Veuillez faire un choix.";
    } elseif ($choix_personne === "nouvelle" && (empty($nom_naissance_personne1) || empty($prenom_personne1) || empty($telephone_personne1) || empty($adresse_personne1 || !ctype_digit($telephone_personne1) || strlen($telephone_personne1) !== 10))) {
        $erreur = "Tous les champs sont obligatoires si vous choisissez une nouvelle personne.";
    } else {
        try {
            // Commencer une transaction pour garantir l'intégrité des données
            $connexion->beginTransaction();

            // Gestion de l'ID de la personne à prévenir (id_pers1)
            if ($choix_personne === "meme") {
                $id_pers1 = $id_pers2; // Utiliser l'ID de la personne de confiance
            } else {
                // Insérer la nouvelle personne dans la table `Personne`
                $stmtPersonne = $connexion->prepare("
                    INSERT INTO Personne (nom_pers, prenom_pers, telephone_pers, adresse_pers)
                    VALUES (:nom_pers, :prenom_pers, :telephone_pers, :adresse_pers)
                ");
                $stmtPersonne->execute([
                    'nom_pers' => $nom_naissance_personne1,
                    'prenom_pers' => $prenom_personne1,
                    'telephone_pers' => $telephone_personne1,
                    'adresse_pers' => $adresse_personne1,
                ]);

                // Récupérer l'ID de la nouvelle personne insérée
                $id_pers1 = $connexion->lastInsertId();
            }

            // Insérer les données dans la table `Patient`
            $stmtPatient = $connexion->prepare("
                INSERT INTO Patient (
                    num_secu, nom_patient, prenom_patient, date_naissance, adresse, CP, ville, 
                    email_patient, telephone_patient, nom_epouse, civilite, id_pers1, id_pers2
                ) VALUES (
                    :num_secu, :nom_patient, :prenom_patient, :date_naissance, :adresse, :CP, :ville, 
                    :email_patient, :telephone_patient, :nom_epouse, :civilite, :id_pers1, :id_pers2
                )
            ");
            $stmtPatient->execute([
                'num_secu' => $_SESSION['etape1']['num_secu'], // Données de l'étape 1
                'nom_patient' => $_SESSION['etape1']['nom_patient'],
                'prenom_patient' => $_SESSION['etape1']['prenom_patient'],
                'date_naissance' => $_SESSION['etape1']['date_naissance'],
                'adresse' => $_SESSION['etape1']['adresse'],
                'CP' => $_SESSION['etape1']['CP'],
                'ville' => $_SESSION['etape1']['ville'],
                'email_patient' => $_SESSION['etape1']['email_patient'],
                'telephone_patient' => $_SESSION['etape1']['telephone_patient'],
                'nom_epouse' => $_SESSION['etape1']['nom_epouse'],
                'civilite' => $_SESSION['etape1']['civilite'],
                'id_pers1' => $id_pers1, // ID de la personne à prévenir (jamais NULL)
                'id_pers2' => $id_pers2, // ID de la personne de confiance (jamais NULL)
            ]);

            // Insérer les données dans la table `Couverture_sociale`
            $stmtCouverture = $connexion->prepare("
                INSERT INTO Couverture_sociale (
                    id_patient, org_secu, assure, ALD, nom_mut_ass, num_adherent
                ) VALUES (
                    :id_patient, :org_secu, :assure, :ALD, :mut_ass, :Nadherent
                )
            ");
            $stmtCouverture->execute([
                'id_patient' => $_SESSION['etape1']['num_secu'], // Utilisation de num_secu comme id_patient
                'org_secu' => $_SESSION['etape2']['org_secu'],
                'assure' => $_SESSION['etape2']['assure'],
                'ALD' => $_SESSION['etape2']['ALD'],
                'mut_ass' => $_SESSION['etape2']['mut_ass'],
                'Nadherent' => $_SESSION['etape2']['Nadherent'],
            ]);

            // Valider la transaction
            $connexion->commit();

            $_SESSION['etape4'] = [
                'id_pers1' => $id_pers1,
                'nom_naissance_personne1' => $nom_naissance_personne1,
                'prenom_personne1' => $prenom_personne1,
                'telephone_personne1' => $telephone_personne1,
                'adresse_personne1' => $adresse_personne1,
            ];

            // Rediriger vers une page de confirmation
            header('Location: Pre_admission_Piece_Jointe.php');
            exit();
        } catch (PDOException $e) {
            $connexion->rollBack();
            $erreur = "Erreur lors de l'insertion : " . $e->getMessage();
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
    <title>Pré admission | Etape 4 sur 6</title>
    <link rel="stylesheet" href="style/style.css">
    <script>
        function toggleFormulaire() {
            var choixPersonne = document.getElementById('choix_personne').value;
            var formulaire = document.getElementById('formulaire-personne');
            if (choixPersonne === "meme") {
                formulaire.classList.add('hidden');
            } else {
                formulaire.classList.remove('hidden');
            }
        }
    </script>
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
        <form method="POST" action="">
            <h6>COORDONÉES PERSONNES À PRÉVENIR <br>Etape 4 sur 6</h6>
            <br>

            <!-- Affichage des erreurs -->
            <?php if (!empty($erreur)): ?>
                <div class="error-message">
                    <p><?php echo htmlspecialchars($erreur); ?></p>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="choix_personne">Choisissez une option :<span class= "requis"> *</span></label>
                <br>
                <select id="choix_personne" name="choix_personne" onchange="toggleFormulaire()" required>
                    <option value="meme" <?php echo (isset($_SESSION['form_data']['choix_personne']) && $_SESSION['form_data']['choix_personne'] === 'meme' ? 'selected' : ''); ?>>Même personne que la personne de confiance</option>
                    <option value="nouvelle" selected <?php echo (isset($_SESSION['form_data']['choix_personne']) && $_SESSION['form_data']['choix_personne'] === 'nouvelle' ? 'selected' : ''); ?>>Nouvelle personne</option>
                </select>
            </div>

            <div id="formulaire-personne" class="form-group <?php echo (isset($_SESSION['form_data']['choix_personne']) && $_SESSION['form_data']['choix_personne'] === 'meme' ? 'hidden' : ''); ?>">
                <label for="Nom_naissance_personne1">Nom de naissance :<span class= "requis"> *</span></label>
                <br>
                <input type="text" id="Nom_naissance_personne1" name="Nom_naissance_personne1" value="<?php echo htmlspecialchars($_SESSION['form_data']['Nom_naissance_personne1'] ?? ''); ?>">
                <br><br>

                <label for="prenom_personne1">Prénom :<span class= "requis"> *</span></label>
                <br>
                <input type="text" id="prenom_personne1" name="prenom_personne1" value="<?php echo htmlspecialchars($_SESSION['form_data']['prenom_personne1'] ?? ''); ?>">
                <br><br>

                <label for="Téléphone_personne1">Téléphone :<span class= "requis"> *</span></label>
                <br>
                <input type="text" id="Téléphone_personne1" name="Téléphone_personne1" value="<?php echo htmlspecialchars($_SESSION['form_data']['Téléphone_personne1'] ?? ''); ?>">
                <br><br>

                <label for="Adresse_personne1">Adresse :<span class= "requis"> *</span></label>
                <br>
                <input type="text" id="Adresse_personne1" name="Adresse_personne1" value="<?php echo htmlspecialchars($_SESSION['form_data']['Adresse_personne1'] ?? ''); ?>">
                <br><br>
            </div>

            <div class="navigation">
                <button type="button" onclick="window.location.href='Pre_admission_Confiance.php'" class="button-next">Retour</button>
                <button type="submit" class="button-next">Terminer</button>
            </div>
        </form>
    </div>
</body>
</html>