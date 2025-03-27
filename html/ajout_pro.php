<?php
require('logs/Logout_admin.php');
require('logs/logs.php');

$table = "professionnel"; // Modification pour correspondre à la table professionnel

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom_pro = $_POST["nom_pro"];
    $prenom_pro = $_POST["prenom_pro"];
    $mail_pro = $_POST["mail_pro"];
    $mdp_pro = password_hash($_POST["mdp_pro"], PASSWORD_BCRYPT); // Hachage du mot de passe
    $id_metier = $_POST["id_metier"];
    $id_service = $_POST["id_service"];
    $premiere_connection = 1; // Première connexion est toujours à 1

    // Insertion dans la base de données
    $requete = $connexion->prepare("INSERT INTO Professionnel (nom_pro, prenom_pro, mail_pro, mdp_pro, id_metier, id_service, premiere_connection) 
                                VALUES (:nom_pro, :prenom_pro, :mail_pro, :mdp_pro, :id_metier, :id_service, :premiere_connection)");

    $requete->bindParam(':premiere_connection', $premiere_connection);

    $requete->bindParam(':nom_pro', $nom_pro);
    $requete->bindParam(':prenom_pro', $prenom_pro);
    $requete->bindParam(':mail_pro', $mail_pro);
    $requete->bindParam(':mdp_pro', $mdp_pro);
    $requete->bindParam(':id_metier', $id_metier);
    $requete->bindParam(':id_service', $id_service);

    if ($requete->execute()) {
        header("Location: admin.php");
        exit;
    } else {
        echo "Erreur lors de l'ajout du professionnel.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification Professionnel</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php require('require/navbar.php'); ?>

    <div class="container-modif">
        <h2>Ajout d'un professionnel</h2>
        <form action="Ajout_professionnel.php" method="post">
            <label for="nom_pro">Nom :</label>
            <input type="text" id="nom_pro" name="nom_pro" required><br><br>

            <label for="prenom_pro">Prénom :</label>
            <input type="text" id="prenom_pro" name="prenom_pro" required><br><br>

            <label for="mail_pro">Email :</label>
            <input type="email" id="mail_pro" name="mail_pro" required><br><br>

            <label for="mdp_pro">Mot de passe :</label>
            <input type="password" id="mdp_pro" name="mdp_pro" required><br><br>

            <label for="id_metier">ID Métier :</label>
            <input type="number" id="id_metier" name="id_metier" required><br><br>

            <label for="id_service">ID Service :</label>
            <input type="number" id="id_service" name="id_service" required><br><br>

            <div class="button-container">
                <button type="button" class="btn-shine" onclick="history.back();">Retour</button>
                <button type="submit" class="btn-submit">Ajouter le professionnel</button>
            </div>
        </form>
    </div>
</body>
</html>
