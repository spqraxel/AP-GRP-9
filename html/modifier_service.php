<?php
session_start(); // Démarrer la session si nécessaire
ob_start(); // Évite les problèmes de redirection

require('logs/Logout_admin.php');
require('logs/logs.php');

$table = "Service"; // Table modifiée pour correspondre à la table Service

// Vérifier si un ID de service est passé en paramètre
if (isset($_GET['id_service'])) {
    $id_service = intval($_GET['id_service']);

    // Récupérer les informations du service depuis la base de données
    $requete = $connexion->prepare("SELECT * FROM $table WHERE id_service = :id_service");
    $requete->bindParam(':id_service', $id_service);
    $requete->execute();
    $service = $requete->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        die("Service introuvable.");
    }
} else {
    die("ID de service non spécifié.");
}

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification de la connexion à la base de données
    if (!isset($connexion)) {
        die("Erreur : connexion à la base de données non établie.");
    }

    // Récupération et sécurisation des données du formulaire
    $VLAN = trim($_POST["VLAN"]);
    $nom_service = trim($_POST["nom_service"]);
    $addr_reseau = trim($_POST["addr_reseau"]);

    // Préparation de la requête SQL pour la mise à jour
    $requete = $connexion->prepare("UPDATE $table SET VLAN = :VLAN, nom_service = :nom_service, addr_reseau = :addr_reseau WHERE id_service = :id_service");

    // Liaison des paramètres
    $requete->bindParam(':id_service', $id_service);
    $requete->bindParam(':VLAN', $VLAN);
    $requete->bindParam(':nom_service', $nom_service);
    $requete->bindParam(':addr_reseau', $addr_reseau);

    // Exécution de la requête et gestion des erreurs
    if ($requete->execute()) {
        header("Location: Modif_admin.php");
        exit;
    } else {
        $errorInfo = $requete->errorInfo();
        die("Erreur lors de la mise à jour du service : " . $errorInfo[2]);
    }
}
ob_end_flush(); // Libère le tampon de sortie
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification d'un service</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php require('require/navbar.php'); ?>

    <div class="container-modif">
        <h2>Modification d'un service</h2>
        <form action="Modifier_service.php?id_service=<?= $id_service ?>" method="post">
            <label for="VLAN">VLAN :</label>
            <input type="text" id="VLAN" name="VLAN" value="<?= htmlspecialchars($service['VLAN']) ?>" required><br><br>

            <label for="nom_service">Nom du service :</label>
            <input type="text" id="nom_service" name="nom_service" value="<?= htmlspecialchars($service['nom_service']) ?>" required><br><br>

            <label for="addr_reseau">Adresse réseau :</label>
            <input type="text" id="addr_reseau" name="addr_reseau" value="<?= htmlspecialchars($service['addr_reseau']) ?>" required><br><br>

            <div class="button-container">
                <button type="button" class="btn-shine" onclick="history.back();">Retour</button>
                <button type="submit" class="btn-submit">Modifier le service</button>
            </div>
        </form>
    </div>
</body>
</html>
