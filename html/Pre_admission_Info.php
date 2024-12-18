<?php
require('Logout.php');
session_start();

// Connexion à la base de données
$serveur = "192.168.100.27:3306";
$utilisateur = "dev";
$motdepasse = "sio2425";
$nomBDD = "AP_BTS2";
$erreur = "";

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$nomBDD", $utilisateur, $motdepasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $erreur = "Erreur de connexion : " . $e->getMessage();
    die($erreur); // Arrête le script si la connexion échoue
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
    } elseif (empty($telephone) || strlen($telephone) > 10 || !ctype_digit($telephone)) {
        $erreur = "Le numéro de telephone_patient doit contenir 10 chiffres.";
    } elseif (empty($CP) || strlen($CP) > 5 || !ctype_digit($CP)) {
        $erreur = "Le code postal doit contenir 5 chiffres.";
    } elseif (empty($civilite)) {
        $erreur = "Le champ civiliteilité est obligatoire.";
    } elseif (empty($adresse)) {
        $erreur = "Le champ adresse est obligatoire.";
    } elseif (empty($ville)) {
        $erreur = "Le champ ville est obligatoire.";
    } elseif (!verifierCodePostalville($CP, $ville)) {
        $error_message = "Le code postal ne correspond pas à la ville.";
    } else {
        // Validation de la clé de contrôle du numéro de sécurité sociale
        $cle = substr($num_secu, -2);
        $numSecuSansCle = substr($num_secu, 0, -2);
        $cleCalculee = 97 - ($numSecuSansCle % 97);

    
        if ($cle != $cleCalculee) {
            $erreur = "La clé de contrôle du numéro de sécurité sociale est incorrecte.";
        } else {
            // Vérification de la correspondance entre civiliteilité et numéro de sécurité sociale
            $premierChiffre = substr($num_secu, 0, 1);
            if (($civiliteilite == "0" && $premierChiffre != "1") || ($civiliteilite == "1" && $premierChiffre != "2")) {
                $erreur = "La civiliteilité sélectionnée ne correspond pas au numéro de sécurité sociale.";
            } else {
                try {
                    // Préparer la requête SQL
                    $stmt = $connexion->prepare("
                        INSERT INTO Patient (num_secu, civiliteilite, nom_patient, nom_epouse, prenom_patient, date_naissance, adresse, CP, ville, email_patient, telephone)
                        VALUES (:num_secu, :civilite, :nom_patient, :nom_epouse, :prenom_patient, :date_naissance, :adresse, :CP, :ville, :email_patient, :telephone)
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
                    echo "<p>Enregistrement réussi.</p>";
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
<ml lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pré admission</title>
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
        <form method="POST" action=""></form>
        <h6>INFORMATIONS CONCERNANT LE PATIENT</h6>

        <label for="num_secu">Numéro de sécurité sociale :<span class= "requis"> *</span></label>
        <br>
        <input type="text" id="num_secu" name="num_secu" required>
        <br><br>

        <label for="civilite">Civilité:<span class= "requis">*</span></label>
        <br>
        <select id="civilite" name="civilite" required>
            <option value="choissir" selected disabled hidden>choix</option>
            <option value="Femme">Femme</option>
            <option value="Homme">Homme</option>      
        </select>
        <br><br>

        <label for="nom_patient">Nom de naissance :<span class= "requis"> *</span></label>
        <br>
        <input type="text" id="nom_patient" name="nom_patient" required>
        <br><br>

        <label for="nom_epouse">Nom d'épouse :</label>
        <br>
        <input type="text" id="nom_epouse" name="nom_epouse" >
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
        <a href="Pre_admission_Inscription.php" class="fleche-droite" title="Suivant"></a>
    </div>

    </form>
    </div>
</body>
</html>