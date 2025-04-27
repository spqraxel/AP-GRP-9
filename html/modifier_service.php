<?php
require('logs/Logout_admin.php');
require('logs/logs.php');

// Vérification de la session et du métier
if (!isset($_SESSION['id_metier']) || !in_array($_SESSION['id_metier'], [1, 2])) {
    header("Location: index.php");
    exit;
}

// Vérifier si l'id du service est passé en paramètre
if (isset($_GET['id'])) {
    $id_service = intval($_GET['id']);

    // Récupérer les données du service depuis la base de données
    $requete = $connexion->prepare("SELECT * FROM Service WHERE id_service = :id_service");
    $requete->bindParam(':id_service', $id_service);
    $requete->execute();
    $service = $requete->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        die("Service introuvable.");
    }
} else {
    die("ID du service non spécifié.");
}

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $VLAN = trim($_POST["VLAN"]);
    $nom_service = trim($_POST["nom_service"]);
    $addr_reseau = trim($_POST["addr_reseau"]);

    // Mise à jour des données dans la base
    $requete = $connexion->prepare("UPDATE Service SET VLAN = :VLAN, nom_service = :nom_service, addr_reseau = :addr_reseau WHERE id_service = :id_service");
    $requete->bindParam(':id_service', $id_service);
    $requete->bindParam(':VLAN', $VLAN);
    $requete->bindParam(':nom_service', $nom_service);
    $requete->bindParam(':addr_reseau', $addr_reseau);

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
        <form action="modifier_service.php?id=<?= $id_service ?>" method="post">
            <label for="VLAN">VLAN :</label>
            <input type="text" id="VLAN" name="VLAN" value="<?= htmlspecialchars($service['VLAN']) ?>" required><br><br>

            <label for="nom_service">Nom du service :</label>
            <input type="text" id="nom_service" name="nom_service" value="<?= htmlspecialchars($service['nom_service']) ?>" required><br><br>

            <label for="addr_reseau">Adresse réseau :</label>
            <input type="text" id="addr_reseau" name="addr_reseau" value="<?= htmlspecialchars($service['addr_reseau']) ?>" required><br><br>

            <div class="button-container">
                <button type="button" class="btn-shine" onclick="history.back();">Retour</button>
                <button type="submit" class="btn-submit">Modifier</button>
            </div>
        </form>
    </div>
</body>
</html>
