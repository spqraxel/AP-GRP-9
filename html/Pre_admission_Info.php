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
    $secu_sociale = htmlspecialchars($_POST['secu_sociale']);
    $civ = htmlspecialchars($_POST['civ']);
    $nom_naissance = htmlspecialchars($_POST['Nom_naissance']);
    $nom_epouse = htmlspecialchars($_POST['Nom_epouse']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $date_naissance = htmlspecialchars($_POST['date_naissance']);
    $adresse = htmlspecialchars($_POST['Adresse']);
    $cp = htmlspecialchars($_POST['CP']);
    $ville = htmlspecialchars($_POST['Ville']);
    $email = htmlspecialchars($_POST['Email']);
    $telephone = htmlspecialchars($_POST['Téléphone']);

    $limiteAgeMax = strtotime('-120 years');

    // Vérification des champs obligatoires
    if (empty($secu_sociale) || strlen($secu_sociale) !== 15 || !ctype_digit($secu_sociale)) {
        $erreur = "Le numéro de sécurité sociale doit contenir exactement 15 chiffres.";
    } elseif (empty($nom_naissance)) {
        $erreur = "Le prénom est obligatoires.";
    } elseif (empty($prenom)) {
        $erreur = "Le nom est obligatoires.";
    } elseif (empty($date_naissance) || strtotime($date_naissance) > time() || strtotime($date_naissance) < $limiteAgeMax) {
        $erreur = "La date de naissance n'est pas valide.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = "L'adresse e-mail n'est pas valide.";
    } elseif (empty($telephone) || strlen($telephone) > 10 || !ctype_digit($telephone)) {
        $erreur = "Le numéro de téléphone doit contenir 10 chiffres.";
    } elseif (empty($cp) || strlen($cp) > 5 || !ctype_digit($cp)) {
        $erreur = "Le code postal doit contenir 5 chiffres.";
    } elseif (empty($civ)) {
        $erreur = "Le champ civilité est obligatoire.";
    } elseif (empty($adresse)) {
        $erreur = "Le champ adresse est obligatoire.";
    } elseif (empty($ville)) {
        $erreur = "Le champ ville est obligatoire.";
    } elseif (!verifierCodePostalVille($cp, $ville)) {
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
                        INSERT INTO Patient (secu_sociale, civilite, nom_naissance, nom_epouse, prenom, date_naissance, adresse, cp, ville, email, telephone)
                        VALUES (:secu_sociale, :civ, :nom_naissance, :nom_epouse, :prenom, :date_naissance, :adresse, :cp, :ville, :email, :telephone)
                    ");

                    // Lier les paramètres
                    $stmt->bindParam(':secu_sociale', $secu_sociale);
                    $stmt->bindParam(':civ', $civ);
                    $stmt->bindParam(':nom_naissance', $nom_naissance);
                    $stmt->bindParam(':nom_epouse', $nom_epouse);
                    $stmt->bindParam(':prenom', $prenom);
                    $stmt->bindParam(':date_naissance', $date_naissance);
                    $stmt->bindParam(':adresse', $adresse);
                    $stmt->bindParam(':cp', $cp);
                    $stmt->bindParam(':ville', $ville);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':telephone', $telephone);

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

        <label for="secu_sociale">Numéro de sécurité sociale :<span class= "requis"> *</span></label>
        <br>
        <input type="text" id="secu_sociale" name="secu_sociale" required>
        <br><br>

        <label for="civ">Civilité :<span class= "requis">*</span></label>
        <br>
        <select id="civ" name="civ" required>
            <option value="choissir" selected disabled hidden>choix</option>
            <option value="Femme">Femme</option>
            <option value="Homme">Homme</option>      
        </select>
        <br><br>

        <label for="Nom_naissance">Nom de naissance :<span class= "requis"> *</span></label>
        <br>
        <input type="text" id="Nom_naissance" name="Nom_naissance" required>
        <br><br>

        <label for="Nom_epouse">Nom d'épouse :</label>
        <br>
        <input type="text" id="Nom_epouse" name="Nom_epouse" >
        <br><br> 
   
        <label for="prenom">Prénom :<span class= "requis"> *</span></label>
        <br>
        <input type="text" id="prenom" name="prenom" required>
        <br><br>

        <label for="date_naissance">Date de naissance :<span class= "requis"> *</span></label>
        <br>
        <input type="date" id="date_naissance" name="date_naissance" required>
        <br><br>

        <label for="Adresse">Adresse :<span class= "requis"> *</span></label>
        <br>
        <input type="text" id="Adresse" name="Adresse" required>
        <br><br>

        <label for="CP">CP :<span class= "requis"> *</span></label>
        <br>
        <input type="text" id="CP" name="CP" required>
        <br><br>

        <label for="Ville">Ville :<span class= "requis"> *</span></label>
        <br>
        <input type="text" id="Ville" name="Ville" required>
        <br><br>

        <label for="Email">Email :<span class= "requis"> *</span></label>
        <br>
        <input type="text" id="Email" name="Email" required>
        <br><br>

        <label for="Téléphone">Téléphone :<span class= "requis">*</span></label>
        <br>
        <input type="text" id="Téléphone" name="Téléphone" required>
        <br><br>
    
    <div class="navigation">
        <a href="Pre_admission_Inscription.php" class="fleche-droite" title="Suivant"></a>
    </div>

    </form>
    </div>
</body>
</html>