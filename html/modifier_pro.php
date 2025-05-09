<?php
require('logs/Logout_admin.php');
require('logs/logs.php');

// Vérification de la session et du métier
if (!isset($_SESSION['id_metier']) || !in_array($_SESSION['id_metier'], [1, 2])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_pro = intval($_GET['id']);

    $requete = $connexion->prepare("SELECT * FROM Professionnel WHERE id_pro = :id_pro");
    $requete->bindParam(':id_pro', $id_pro);
    $requete->execute();
    $professionnel = $requete->fetch(PDO::FETCH_ASSOC);

    if (!$professionnel) {
        die("Professionnel introuvable.");
    }
} else {
    die("ID professionnel non spécifié.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_pro = trim($_POST["nom_pro"]);
    $prenom_pro = trim($_POST["prenom_pro"]);
    $mail_pro = trim($_POST["mail_pro"]);
    $id_service = intval($_POST["id_service"]);

    $requete = $connexion->prepare("UPDATE Professionnel SET nom_pro = :nom_pro, prenom_pro = :prenom_pro, mail_pro = :mail_pro, id_service = :id_service WHERE id_pro = :id_pro");
    $requete->bindParam(':id_pro', $id_pro);
    $requete->bindParam(':nom_pro', $nom_pro);
    $requete->bindParam(':prenom_pro', $prenom_pro);
    $requete->bindParam(':mail_pro', $mail_pro);
    $requete->bindParam(':id_service', $id_service);

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
    <title>Modifier le professionnel</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php require('require/navbar.php'); ?>

    <div class="container-modif">
        <h2>Modifier un professionnel</h2>
        <br><br>
        <form action="modifier_pro.php?id=<?= $id_pro ?>" method="post">
            <label for="nom_pro">Nom :</label>
            <input type="text" id="nom_pro" name="nom_pro" value="<?= htmlspecialchars($professionnel['nom_pro']) ?>" required><br><br>

            <label for="prenom_pro">Prénom :</label>
            <input type="text" id="prenom_pro" name="prenom_pro" value="<?= htmlspecialchars($professionnel['prenom_pro']) ?>" required><br><br>

            <label for="mail_pro">Email :</label>
            <input type="email" id="mail_pro" name="mail_pro" value="<?= htmlspecialchars($professionnel['mail_pro']) ?>" required><br><br>

            <label for="id_service">ID Service :</label>
            <input type="number" id="id_service" name="id_service" value="<?= htmlspecialchars($professionnel['id_service']) ?>" required><br><br>

            <div class="button-container">
                <button type="button" class="btn-shine" onclick="history.back();">Retour</button>
                <button type="submit" class="btn-submit">Modifier</button>
            </div>
        </form>
    </div>
</body>
</html>
