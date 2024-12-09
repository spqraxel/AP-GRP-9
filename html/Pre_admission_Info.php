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
    $id_patient = htmlspecialchars($_POST['id_patient']);
    $civilite = htmlspecialchars($_POST['civilite']);
    $nom_patient = htmlspecialchars($_POST['nom_patient']);
    $prenom_patient = htmlspecialchars($_POST['prenom_patient']);
    $date_naissance = htmlspecialchars($_POST['date_naissance']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $CP = htmlspecialchars($_POST['CP']);
    $ville = htmlspecialchars($_POST['ville']);
    $email_patient = htmlspecialchars($_POST['email_patient']);
    $telephone_patient = htmlspecialchars($_POST['telephone_patient']);
    $nom_epouse = htmlspecialchars($_POST['nom_epouse']);

    // Vérification des champs obligatoires
    if (empty($id_patient) || empty($civilite) || empty($nom_patient) || empty($prenom_patient) || empty($date_naissance) || empty($adresse) || empty($CP) || empty($ville) || empty($email_patient) || empty($telephone_patient)) {
        echo "<p>Veuillez remplir tous les champs requis.</p>";
    } else {
        try {
            // Préparer la requête SQL
            $stmt = $connexion->prepare("
                INSERT INTO Patient(id_patient, civilite, nom_patient, prenom_patient, date_naissance, adresse, CP, ville, email_patient, telephone_patient, nom_epouse) 
                VALUES (:id_patient, :civilite, :nom_patient, :prenom_patient, :date_naissance, :adresse, :CP, :ville, :email_patient, :telephone_patient, :nom_epouse)
            ");

            // Lier les paramètres
            $stmt->bindParam(':id_patient', $id_patient);
            $stmt->bindParam(':civilite', $civilite);
            $stmt->bindParam(':nom_patient', $nom_patient);
            $stmt->bindParam(':prenom_patient', $prenom_patient);
            $stmt->bindParam(':date_naissance', $date_naissance);
            $stmt->bindParam(':adresse', $adresse);
            $stmt->bindParam(':CP', $CP);
            $stmt->bindParam(':ville', $ville);
            $stmt->bindParam(':email_patient', $email_patient);
            $stmt->bindParam(':telephone_patient', $telephone_patient);
            $stmt->bindParam(':nom_epouse', $nom_epouse);

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
    <form method="POST" action="">
        <h6>INFORMATIONS CONCERNANT LE PATIENT</h6>

        <label for="id_patient">Numéro de sécurité sociale<span class= "requis">*</span></label>
        <input type="text" id="id_patient" name="id_patient" required>
        <br><br>

        <label for="civilite">Civilité<span class= "requis">*</span></label>
        <select id="civilite" name="civilite" required>
            <option value="choisir" selected disabled hidden>choix</option>
            <option value="Femme">Femme</option>
            <option value="Homme">Homme</option>      
        </select>
        <br><br>

        <label for="nom_patient">Nom de naissance<span class= "requis">*</span></label>
        <input type="text" id="nom_patient" name="nom_patient" required>
        <br><br>

        <label for="nom_epouse">Nom d'épouse</label>
        <input type="text" id="nom_epouse" name="nom_epouse">
        <br><br> 

        <label for="prenom_patient">Prénom<span class= "requis">*</span></label>
        <input type="text" id="prenom_patient" name="prenom_patient" required>
        <br><br>

        <label for="date_naissance">Date de naissance<span class= "requis">*</span></label>
        <input type="date" id="date_naissance" name="date_naissance" required>
        <br><br>

        <label for="adresse">Adresse<span class= "requis">*</span></label>
        <input type="text" id="adresse" name="adresse" required>
        <br><br>

        <label for="CP">CP<span class= "requis">*</span></label>
        <input type="text" id="CP" name="CP" required>
        <br><br>

        <label for="ville">Ville<span class= "requis">*</span></label>
        <input type="text" id="ville" name="ville" required>
        <br><br>

        <label for="email_patient">Email <span class= "requis">*</span></label>
        <input type="email" id="email_patient" name="email_patient" required>
        <br><br>

        <label for="telephone_patient">Téléphone<span class= "requis">*</span></label>
        <input type="text" id="telephone_patient" name="telephone_patient" required>
        <br><br>
        
        <a href=""><button>Retour</button></a>
        <a href="Pre_admission_Inscription.php"><button>Suivant</button></a>

        <a href="?logout=true">Se déconnecter</a> 
    </form>
</body>
</html>
