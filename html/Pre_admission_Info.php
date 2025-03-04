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

function verifierCodePostalVille($cp, $ville) {
    $url = "http://api.zippopotam.us/fr/$cp"; // URL de l'API avec le code postal
    $response = @file_get_contents($url);

    if ($response === FALSE) {
        return false; // API inaccessible ou code postal invalide
    }

    $data = json_decode($response, true);

    if (!empty($data['places'])) {
        foreach ($data['places'] as $place) {
            // Vérifie si une des villes correspond (en ignorant la casse)
            if (strtolower($place['place name']) === strtolower($ville)) {
                return true;
            }
        }
    }

    return false;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération et nettoyage des données
    $num_secu = htmlspecialchars($_POST['num_secu']);
    $civilite = htmlspecialchars($_POST['civilite']);
    $nom_patient = htmlspecialchars($_POST['nom_patient']);
    $nom_epouse = htmlspecialchars($_POST['nom_epouse']);
    $prenom_patient = htmlspecialchars($_POST['prenom_patient']);
    $date_naissance = htmlspecialchars($_POST['date_naissance']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $CP = htmlspecialchars($_POST['CP']);
    $ville = htmlspecialchars($_POST['ville']);
    $email_patient = htmlspecialchars($_POST['email_patient']);
    $telephone_patient = htmlspecialchars($_POST['telephone_patient']);

    $limiteAgeMax = strtotime('-120 years');

    // Vérification des champs obligatoires
    if (empty($num_secu) || strlen($num_secu) !== 15 || !ctype_digit($num_secu)) {
        $erreur = "Le numéro de sécurité sociale doit contenir exactement 15 chiffres.";
    } elseif (empty($nom_patient)) {
        $erreur = "Le prénom est obligatoires.";
    } elseif (empty($prenom_patient)) {
        $erreur = "Le nom est obligatoires.";
    } elseif (empty($date_naissance) || strtotime($date_naissance) > time() || strtotime($date_naissance) < $limiteAgeMax) {
        $erreur = "La date de naissance n'est pas valide.";
    } elseif (!filter_var($email_patient, FILTER_VALIDATE_EMAIL)) {
        $erreur = "L'adresse e-mail n'est pas valide.";
    } elseif (empty($telephone_patient) || strlen($telephone_patient) > 10 || !ctype_digit($telephone_patient)) {
        $erreur = "Le numéro de telephone doit contenir 10 chiffres.";
    } elseif (empty($CP) || strlen($CP) > 5 || !ctype_digit($CP)) {
        $erreur = "Le code postal doit contenir 5 chiffres.";
    } elseif (empty($civilite)) {
        $erreur = "Le champ civilité est obligatoire.";
    } elseif (empty($adresse)) {
        $erreur = "Le champ adresse est obligatoire.";
    } elseif (empty($ville)) {
        $erreur = "Le champ ville est obligatoire.";
    } elseif (!verifierCodePostalVille($CP, $ville)) {
        $error_message = "Le code postal ne correspond pas à la ville.";
    } else {
        // Validation de la clé de contrôle du numéro de sécurité sociale
        $cle = substr($num_secu, -2);
        $numSecuSansCle = substr($num_secu, 0, -2);
        $cleCalculee = 97 - ($numSecuSansCle % 97);

    
        if ($cle != $cleCalculee) {
            $erreur = "La clé de contrôle du numéro de sécurité sociale est incorrecte.";
        } else {
            // Vérification de la correspondance entre civilité et numéro de sécurité sociale
            $premierChiffre = substr($num_secu, 0, 1);
            if (($civilite == "0" && $premierChiffre != "1") || ($civilite == "1" && $premierChiffre != "2")) {
                $erreur = "La civilité sélectionnée ne correspond pas au numéro de sécurité sociale.";
            } else {
                try {
                    // Préparer la requête SQL
                    $stmt = $connexion->prepare("
                        INSERT INTO Patient (num_secu, civilite, nom_patient, nom_epouse, prenom_patient, date_naissance, adresse, CP, ville, email_patient, telephone)
                        VALUES (:num_secu, :civilite, :nom_patient, :nom_epouse, :prenom_patient, :date_naissance, :adresse, :CP, :ville, :email_patient, :telephone_patient)
                    ");

                    // Lier les paramètres
                    $stmt->bindParam(':num_secu', $num_secu);
                    $stmt->bindParam(':civilite', $civilite);
                    $stmt->bindParam(':nom_patient', $nom_patient);
                    $stmt->bindParam(':nom_epouse', $nom_epouse);
                    $stmt->bindParam(':prenom_patient', $prenom_patient);
                    $stmt->bindParam(':date_naissance', $date_naissance);
                    $stmt->bindParam(':adresse', $adresse);
                    $stmt->bindParam(':CP', $CP);
                    $stmt->bindParam(':ville', $ville);
                    $stmt->bindParam(':email_patient', $email_patient);
                    $stmt->bindParam(':telephone_patient', $telephone_patient);

                    // Exécuter la requête
                    $stmt->execute();
                    header('Location: Pre_admission_Inscription.php');
                    exit();
                } catch (PDOException $e) {
                    echo "<p>Erreur lors de l'insertion : " . $e->getMessage() . "</p>";
                }
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
    <title>Pré admission | Etape 1 sur 6</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <header class="navbar">
        <div class="logo-container">
            <img src="img/LPFS_logo.png" alt="Logo" class="logo">
        </div>
        <div class="page">
            <a href="admin.php">Accueil</a>
            <a href="Pre_admission_Info.php">Pré-admission</a>        
            <a href="?logout=true">Se déconnecter</a>
        </div>
    </header>
    <div class="container-pre-admission">
        <!-- Affichage des erreurs -->
        <?php if (!empty($erreur)): ?>
            <div class="error-message"><?php echo $erreur; ?></div>
        <?php endif; ?>

        <!-- Formulaire -->
        <form method="POST" action="Pre_admission_Info.php">
            <h6>INFORMATIONS CONCERNANT LE PATIENT <br>Etape 1 sur 6</h6>
            <br>

            <label for="num_secu">Numéro de sécurité sociale :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="num_secu" name="num_secu" required>
            <br><br>

            <label for="civilite">Civilité:<span class= "requis">*</span></label>
            <br>
            <select id="civilite" name="civilite" required>
                <option value="choissir" selected disabled hidden>choix</option>
                <option value="1">Femme</option>
                <option value="0">Homme</option>      
            </select>
            <br><br>

            <label for="nom_patient">Nom de naissance :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="nom_patient" name="nom_patient" required>
            <br><br>

            <label for="nom_epouse">Nom d'épouse :</label>
            <br>
            <input type="text" id="nom_epouse" name="nom_epouse">
            <br><br> 

            <label for="prenom_patient">Prénom :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="prenom_patient" name="prenom_patient" required>
            <br><br>

            <label for="date_naissance">Date de naissance :<span class= "requis"> *</span></label>
            <br>
            <input type="date" id="date_naissance" name="date_naissance" required>
            <br><br>

            <label for="adresse">Adresse :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="adresse" name="adresse" required>
            <br><br>

            <label for="CP">CP :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="CP" name="CP" required>
            <br><br>

            <label for="ville">Ville :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="ville" name="ville" required>
            <br><br>

            <label for="email_patient">Email :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="email_patient" name="email_patient" required>
            <br><br>

            <label for="telephone_patient">Téléphone :<span class= "requis">*</span></label>
            <br>
            <input type="text" id="telephone_patient" name="telephone_patient" required>
            <br><br>

            <div class="navigation">
                <button type="submit" class="button-next">Suivant</button>
            </div>
        </form>
    </div>
</body>
</html>