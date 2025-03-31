<?php
require('logs/Logout_admin.php');
require('logs/logs.php');

$table = "professionnel";
$erreur = "";

// Vérifier si un ID est passé dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupérer les informations du professionnel depuis la base de données
    $requete = $connexion->prepare("SELECT * FROM Professionnel WHERE id = :id");
    $requete->bindParam(':id', $id);
    $requete->execute();
    $professionnel = $requete->fetch(PDO::FETCH_ASSOC);

    if (!$professionnel) {
        $erreur = "Professionnel non trouvé.";
    }
} else {
    $erreur = "ID non fourni.";
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_pro = $_POST["nom_pro"];
    $prenom_pro = $_POST["prenom_pro"];
    $mail_pro = $_POST["mail_pro"];
    $id_metier = $_POST["id_metier"];
    $id_service = $_POST["id_service"];

    // Mise à jour dans la base de données
    $requete = $connexion->prepare("UPDATE Professionnel SET nom_pro = :nom_pro, prenom_pro = :prenom_pro, mail_pro = :mail_pro, id_metier = :id_metier, id_service = :id_service WHERE id = :id");
    $requete->bindParam(':nom_pro', $nom_pro);
    $requete->bindParam(':prenom_pro', $prenom_pro);
    $requete->bindParam(':mail_pro', $mail_pro);
    $requete->bindParam(':id_metier', $id_metier);
    $requete->bindParam(':id_service', $id_service);
    $requete->bindParam(':id', $id);

    if ($requete->execute()) {
        header("Location: /html/admin.php");
        exit;
    } else {
        $erreur = "Erreur lors de la mise à jour du professionnel.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification Médecin</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php require('require/navbar.php'); ?>

    <div class="container-modif">
        <h2>Modification d'un médecin</h2>
        <?php if ($erreur) { echo "<p class='erreur'>$erreur</p>"; } ?>
        <form action="Modification_professionnel.php?id=<?php echo $id; ?>" method="post">
            <label for="nom_pro">Nom :</label>
            <input type="text" id="nom_pro" name="nom_pro" value="<?php echo $professionnel['nom_pro']; ?>" required><br><br>

            <label for="prenom_pro">Prénom :</label>
            <input type="text" id="prenom_pro" name="prenom_pro" value="<?php echo $professionnel['prenom_pro']; ?>" required><br><br>

            <label for="mail_pro">Email :</label>
            <input type="email" id="mail_pro" name="mail_pro" value="<?php echo $professionnel['mail_pro']; ?>" required><br><br>

            <label for="id_metier">ID Métier :</label>
            <input type="number" id="id_metier" name="id_metier" value="<?php echo $professionnel['id_metier']; ?>" required><br><br>

            <label for="id_service">ID Service :</label>
            <input type="number" id="id_service" name="id_service" value="<?php echo $professionnel['id_service']; ?>" required><br><br>

            <div class="button-container">
                <button type="button" class="btn-shine" onclick="history.back();">Retour</button>
                <button type="submit" class="btn-submit">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</body>
</html>
