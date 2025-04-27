<?php
require('logs/Logout_admin.php');
require('logs/logs.php');

// Vérification de la session et du métier
if (!isset($_SESSION['id_metier']) || !in_array($_SESSION['id_metier'], [1, 2])) {
    header("Location: index.php");
    exit;
}

// Vérifier si le numéro de sécurité sociale est passé en paramètre
if (isset($_GET['num_secu'])) {
    $num_secu = $_GET['num_secu'];

    // Récupérer les données du patient depuis la base de données
    $requete = $connexion->prepare("SELECT * FROM Patient WHERE num_secu = :num_secu");
    $requete->bindParam(':num_secu', $num_secu);
    $requete->execute();
    $patient = $requete->fetch(PDO::FETCH_ASSOC);

    if (!$patient) {
        die("Patient introuvable.");
    }
} else {
    die("Numéro de sécurité sociale non spécifié.");
}

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_patient = trim($_POST["nom_patient"]);
    $prenom_patient = trim($_POST["prenom_patient"]);
    $date_naissance = $_POST["date_naissance"];
    $adresse = trim($_POST["adresse"]);
    $CP = trim($_POST["CP"]);
    $ville = trim($_POST["ville"]);
    $email_patient = trim($_POST["email_patient"]);
    $telephone_patient = trim($_POST["telephone_patient"]);
    $nom_epouse = trim($_POST["nom_epouse"]);
    $civilite = trim($_POST["civilite"]);
    $id_pers1 = intval($_POST["id_pers1"]);
    $id_pers2 = intval($_POST["id_pers2"]);

    // Mise à jour des données dans la base
    $requete = $connexion->prepare("UPDATE Patient SET nom_patient = :nom_patient, prenom_patient = :prenom_patient, date_naissance = :date_naissance, adresse = :adresse, CP = :CP, ville = :ville, email_patient = :email_patient, telephone_patient = :telephone_patient, nom_epouse = :nom_epouse, civilite = :civilite, id_pers1 = :id_pers1, id_pers2 = :id_pers2 WHERE num_secu = :num_secu");

    $requete->bindParam(':num_secu', $num_secu);
    $requete->bindParam(':nom_patient', $nom_patient);
    $requete->bindParam(':prenom_patient', $prenom_patient);
    $requete->bindParam(':date_naissance', $date_naissance);
    $requete->bindParam(':adresse', $adresse);
    $requete->bindParam(':CP', $CP);
    $requete->bindParam(':ville', $ville);
    $requete->bindParam(':email_patient', $email_patient);
    $requete->bindParam(':telephone_patient', $telephone_patient);
    $requete->bindParam(':nom_epouse', $nom_epouse);
    $requete->bindParam(':civilite', $civilite);
    $requete->bindParam(':id_pers1', $id_pers1);
    $requete->bindParam(':id_pers2', $id_pers2);

    if ($requete->execute()) {
        header("Location: admin.php");
        exit;
    } else {
        echo "Erreur lors de la mise à jour.";
    }
    
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le patient</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php require('require/navbar.php'); ?>

    <div class="container-modif">
        <h2>Modifier un patient</h2>
        <form action="modifier_patient.php?num_secu=<?= htmlspecialchars($num_secu) ?>" method="post">
            <label for="nom_patient">Nom :</label>
            <input type="text" id="nom_patient" name="nom_patient" value="<?= htmlspecialchars($patient['nom_patient']) ?>" required><br><br>

            <label for="prenom_patient">Prénom :</label>
            <input type="text" id="prenom_patient" name="prenom_patient" value="<?= htmlspecialchars($patient['prenom_patient']) ?>" required><br><br>

            <label for="date_naissance">Date de naissance :</label>
            <input type="date" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($patient['date_naissance']) ?>" required><br><br>

            <label for="adresse">Adresse :</label>
            <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars($patient['adresse']) ?>" required><br><br>

            <label for="CP">Code Postal :</label>
            <input type="text" id="CP" name="CP" value="<?= htmlspecialchars($patient['CP']) ?>" required><br><br>

            <label for="ville">Ville :</label>
            <input type="text" id="ville" name="ville" value="<?= htmlspecialchars($patient['ville']) ?>" required><br><br>

            <label for="email_patient">Email :</label>
            <input type="email" id="email_patient" name="email_patient" value="<?= htmlspecialchars($patient['email_patient']) ?>" required><br><br>

            <label for="telephone_patient">Téléphone :</label>
            <input type="text" id="telephone_patient" name="telephone_patient" value="<?= htmlspecialchars($patient['telephone_patient']) ?>" required><br><br>

            <label for="nom_epouse">Nom d'épouse :</label>
            <input type="text" id="nom_epouse" name="nom_epouse" value="<?= htmlspecialchars($patient['nom_epouse']) ?>"><br><br>

            <label for="civilite">Civilité :</label>
            <input type="text" id="civilite" name="civilite" value="<?= htmlspecialchars($patient['civilite']) ?>" required><br><br>

            <label for="id_pers1">ID Pers. 1 :</label>
            <input type="number" id="id_pers1" name="id_pers1" value="<?= htmlspecialchars($patient['id_pers1']) ?>"><br><br>

            <label for="id_pers2">ID Pers. 2 :</label>
            <input type="number" id="id_pers2" name="id_pers2" value="<?= htmlspecialchars($patient['id_pers2']) ?>"><br><br>

            <div class="button-container">
                <button type="button" class="btn-shine" onclick="history.back();">Retour</button>
                <button type="submit" class="btn-submit">Modifier</button>
            </div>
        </form>
    </div>
</body>
</html>
