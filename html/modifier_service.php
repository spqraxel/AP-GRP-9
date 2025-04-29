<?php
require('logs/Logout_admin.php');
require('logs/logs.php');

// Vérification de la session et du métier
if (!isset($_SESSION['id_metier']) || !in_array($_SESSION['id_metier'], [1, 2])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_service = intval($_GET['id']);

    $requete = $connexion->prepare("SELECT * FROM Service WHERE id_service = :id_service");
    $requete->bindParam(':id_service', $id_service, PDO::PARAM_INT);
    $requete->execute();
    $service = $requete->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        die("Service introuvable.");
    }
} else {
    die("ID du service non spécifié.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_service = trim($_POST["nom_service"]);

    // Mise à jour du nom du service
    $requete = $connexion->prepare("UPDATE Service SET nom_service = :nom_service WHERE id_service = :id_service");
    $requete->bindParam(':id_service', $id_service, PDO::PARAM_INT);
    $requete->bindParam(':nom_service', $nom_service, PDO::PARAM_STR);

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
    <title>Modifier un service</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php require('require/navbar.php'); ?>

    <div class="container-modif">
        <h2>Modifier un service</h2>
        <br><br>
        <form action="modifier_service.php?id=<?= $id_service ?>" method="post">

            <label for="nom_service">Nom du service :</label>
            <input type="text" id="nom_service" name="nom_service" value="<?= htmlspecialchars($service['nom_service']) ?>" required><br><br>

            <div class="button-container">
                <button type="button" class="btn-shine" onclick="history.back();">Retour</button>
                <button type="submit" class="btn-submit">Modifier</button>
            </div>
        </form>
    </div>
</body>
</html>
