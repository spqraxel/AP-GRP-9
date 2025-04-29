<?php
require('logs/logs.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_service = trim($_POST["nom_service"]);

    try {
        $sql = "INSERT INTO Service ( nom_service) 
                VALUES (:nom_service)";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':nom_service', $nom_service);
        $stmt->execute();

        header("Location: admin.php");
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
        <br><br>
        <form action="ajout_serv.php" method="post">
            <label for="nom_service">Nom du Service :</label>
            <input type="text" id="nom_service" name="nom_service" required><br><br>

            <div class="button-container">
                <button type="submit" class="btn-submit">Ajouter</button>
            </div>
        </form>
    </div>
</body>
</html>
