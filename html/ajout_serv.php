<?php
// Connexion à la base de données
require('logs/logs.php');

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $VLAN = trim($_POST["VLAN"]);
    $nom_service = trim($_POST["nom_service"]);
    $addr_reseau = trim($_POST["addr_reseau"]);

    // Insérer le service dans la base
    try {
        $sql = "INSERT INTO Service (VLAN, nom_service, addr_reseau) 
                VALUES (:VLAN, :nom_service, :addr_reseau)";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':VLAN', $VLAN);
        $stmt->bindParam(':nom_service', $nom_service);
        $stmt->bindParam(':addr_reseau', $addr_reseau);
        $stmt->execute();

        // Rediriger vers la page liste des services après l'ajout
        header("Location: services.php");
        exit;
    } catch (PDOException $e) {
        die("Erreur lors de l'ajout du service : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Service</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php require('require/navbar.php'); ?>

    <div class="container">
        <h2>Ajouter un Service</h2>
        <form action="ajout_serv.php" method="post">
            <label for="VLAN">VLAN :</label>
            <input type="text" id="VLAN" name="VLAN" required><br><br>

            <label for="nom_service">Nom du Service :</label>
            <input type="text" id="nom_service" name="nom_service" required><br><br>

            <label for="addr_reseau">Adresse Réseau :</label>
            <input type="text" id="addr_reseau" name="addr_reseau" required><br><br>

            <div class="button-container">
                <button type="submit" class="btn-submit">Ajouter</button>
            </div>
        </form>
    </div>
</body>
</html>
