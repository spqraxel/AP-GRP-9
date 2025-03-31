<?php
session_start();
require('logs/Logout_admin.php');
require('logs/logs.php');

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_pro = trim($_POST["nom_pro"]);
    $prenom_pro = trim($_POST["prenom_pro"]);
    $mail_pro = trim($_POST["mail_pro"]);
    $id_metier = intval($_POST["id_metier"]);
    $id_service = intval($_POST["id_service"]);

    // Insérer les données dans la base
    try {
        $sql = "INSERT INTO Professionnel (nom_pro, prenom_pro, mail_pro, id_metier, id_service)
                VALUES (:nom_pro, :prenom_pro, :mail_pro, :id_metier, :id_service)";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':nom_pro', $nom_pro);
        $stmt->bindParam(':prenom_pro', $prenom_pro);
        $stmt->bindParam(':mail_pro', $mail_pro);
        $stmt->bindParam(':id_metier', $id_metier);
        $stmt->bindParam(':id_service', $id_service);
        $stmt->execute();

        header("Location: admin.php"); // Redirige vers la page admin après l'ajout
        exit;
    } catch (PDOException $e) {
        die("Erreur lors de l'ajout du médecin : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Médecin</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php require('require/navbar.php'); ?>

    <div class="container">
        <h2>Ajouter un Médecin</h2>
        <form action="ajout_pro.php" method="post">
            <label for="nom_pro">Nom :</label>
            <input type="text" id="nom_pro" name="nom_pro" required><br><br>

            <label for="prenom_pro">Prénom :</label>
            <input type="text" id="prenom_pro" name="prenom_pro" required><br><br>

            <label for="mail_pro">Email :</label>
            <input type="email" id="mail_pro" name="mail_pro" required><br><br>

            <label for="id_metier">ID Métier :</label>
            <input type="number" id="id_metier" name="id_metier" required><br><br>

            <label for="id_service">ID Service :</label>
            <input type="number" id="id_service" name="id_service" required><br><br>

            <button type="submit">Ajouter</button>
        </form>
    </div>
</body>
</html>
