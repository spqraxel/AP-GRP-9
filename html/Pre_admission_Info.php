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

    // Vérification des champs obligatoires
    if (empty($secu_sociale) || empty($civ) || empty($nom_naissance) || empty($prenom) || empty($date_naissance) || empty($adresse) || empty($cp) || empty($ville) || empty($email) || empty($telephone)) {
        echo "<p>Veuillez remplir tous les champs requis.</p>";
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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pré admission</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<header class="navbar">
        <nav>
            <img src="img/LPFS_logo.png" alt="Logo" class="logo">
            <div class="nav-links">
                <a href="admin.php">Accueil</a>
                <a href="Pre_admission_Info.php">Pré-admission</a>
            </div>
        </nav>
</header>
<body>
    <form method="POST" action=""></form>
    <h6>INFORMATIONS CONCERNANT LE PATIENT</h6>

    <label for="secu_sociale">Numéro de sécurité sociale<span class= "requis">*</span></label>
    <input type="text" id="secu_sociale" name="secu_sociale" required>
    <br><br>

    <label for="civ">Civilité<span class= "requis">*</span></label>
    <select id="civ" name="civ" required>
        <option value="choissir" selected disabled hidden>choix</option>
        <option value="Femme">Femme</option>
        <option value="Homme">Homme</option>      
    </select>
    <br><br>

    <label for="Nom_naissance">Nom de naissance<span class= "requis">*</span></label>
    <input type="text" id="Nom_naissance" name="Nom_naissance" required>
    <br><br>

    <label for="Nom_epouse">Nom d'épouse</label>
    <input type="text" id="Nom_epouse" name="Nom_epouse" >
    <br><br> 
   
    <label for="prenom">Prénom<span class= "requis">*</span></label>
    <input type="text" id="prenom" name="prenom" required>
    <br><br>

    <label for="date_naissance">Date de naissance<span class= "requis">*</span></label>
    <input type="date" id="date_naissance" name="date_naissance" required>
    <br><br>

    <label for="Adresse">Adresse<span class= "requis">*</span></label>
    <input type="text" id="Adresse" name="Adresse" required>
    <br><br>

    <label for="CP">CP<span class= "requis">*</span></label>
    <input type="text" id="CP" name="CP" required>
    <br><br>

    <label for="Ville">Ville<span class= "requis">*</span></label>
    <input type="text" id="Ville" name="Ville" required>
    <br><br>

    <label for="Email">Email <span class= "requis">*</span></label>
    <input type="text" id="Email" name="Email" required>
    <br><br>

    <label for="Téléphone">Téléphone<span class= "requis">*</span></label>
    <input type="text" id="Téléphone" name="Téléphone" required>
    <br><br>
    
    <a href=""><button>Retour</button></a>
    <a href="Pre_admission_Inscription.php"><button>Suivant</button></a>

    <form action="Logout.php" method="post">
        <button type="submit">Se déconnecter</button>
    </form>
    </form>
</body>