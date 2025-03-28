<?php
require('logs/Logout_admin.php');

require('logs/logs.php');

// Récupérer les métiers depuis la table "Metier"
$requete_metier = $connexion->prepare("SELECT * FROM $table_metier");
$requete_metier->execute();
$metiers = $requete_metier->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les services depuis la table "Service"
$requete_service = $connexion->prepare("SELECT * FROM $table_service");
$requete_service->execute();
$services = $requete_service->fetchAll(PDO::FETCH_ASSOC);

// Tableau pour stocker les valeurs du Professionnel modifié
$Professionnel = [
    'id_pro' => '',
    'nom_pro' => '',
    'prenom_pro' => '',
    'mail_pro' => '',
    'mdp_pro' => '',
    'id_metier' => '',
    'id_service' => '',
    'premiere_connection' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $id_pro = $_POST["id_pro"];
    $nom_pro = $_POST["nom_pro"];
    $prenom_pro = $_POST["prenom_pro"];
    $mail_pro = $_POST["mail_pro"];
    $mdp_pro = password_hash($_POST["mdp_pro"], PASSWORD_BCRYPT);
    $id_metier = $_POST["id_metier"];
    $id_service = $_POST["id_service"];
    $premiere_connection = $_POST["premiere_connection"];

    // Stocker les valeurs dans le tableau $Professionnel
    $Professionnel = compact('id_pro', 'nom_pro', 'prenom_pro', 'mail_pro', 'mdp_pro', 'id_metier', 'id_service', 'premiere_connection');

    // Mise à jour dans la base de données
    $requete = $connexion->prepare("UPDATE $table_professionnels 
                                    SET nom_pro = :nom_pro, 
                                        prenom_pro = :prenom_pro, 
                                        mail_pro = :mail_pro, 
                                        mdp_pro = :mdp_pro, 
                                        id_metier = :id_metier, 
                                        id_service = :id_service, 
                                        premiere_connection = :premiere_connection 
                                    WHERE id_pro = :id_pro");

    if ($requete->execute($Professionnel)) {
        header("Location: Modif_admin.php");
        exit;
    } else {
        $_SESSION['message'] = "Erreur lors de la mise à jour.";
        $_SESSION['alert_type'] = 'alert-error';
    }
} else if (isset($_GET['id_pro'])) {
    $id_pro = $_GET['id_pro'];

    $requete_info = $connexion->prepare("SELECT * FROM $table_professionnels WHERE id_pro = :id_pro");
    $requete_info->bindParam(':id_pro', $id_pro);
    $requete_info->execute();
    $Professionnel = $requete_info->fetch(PDO::FETCH_ASSOC);

    if (!$Professionnel) {
        $_SESSION['message'] = "Professionnel non trouvé.";
        $_SESSION['alert_type'] = 'alert-error';
    }
} else {
    $_SESSION['message'] = "Identifiant du Professionnel non spécifié.";
    $_SESSION['alert_type'] = 'alert-error';
    header("Location: Modif_admin.php");
    exit;
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
        <h2>Modification du Professionnel</h2>
        <form action="Modification_professionnel.php" method="post">
            <input type="hidden" name="id_pro" value="<?php echo htmlspecialchars($Professionnel['id_pro']); ?>">

            <label for="nom_pro">Nom :</label>
            <input type="text" id="nom_pro" name="nom_pro" value="<?php echo htmlspecialchars($Professionnel['nom_pro']); ?>" required><br><br>

            <label for="prenom_pro">Prénom :</label>
            <input type="text" id="prenom_pro" name="prenom_pro" value="<?php echo htmlspecialchars($Professionnel['prenom_pro']); ?>" required><br><br>

            <label for="mail_pro">Email :</label>
            <input type="email" id="mail_pro" name="mail_pro" value="<?php echo htmlspecialchars($Professionnel['mail_pro']); ?>" required><br><br>

            <label for="mdp_pro">Mot de passe :</label>
            <input type="password" id="mdp_pro" name="mdp_pro" required><br><br>

            <label for="id_metier">Métier :</label>
            <select id="id_metier" name="id_metier" required>
                <?php foreach ($metiers as $metier): ?>
                    <option value="<?php echo htmlspecialchars($metier['id_metier']); ?>" <?php echo ($metier['id_metier'] == $Professionnel['id_metier']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($metier['nom_metier']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="id_service">Service :</label>
            <select id="id_service" name="id_service" required>
                <?php foreach ($services as $service): ?>
                    <option value="<?php echo htmlspecialchars($service['id_service']); ?>" <?php echo ($service['id_service'] == $Professionnel['id_service']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($service['nom_service']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="premiere_connection">Première connexion :</label>
            <input type="number" id="premiere_connection" name="premiere_connection" value="<?php echo htmlspecialchars($Professionnel['premiere_connection']); ?>" required><br><br>

            <input type="submit" value="Modifier le Professionnel">
        </form>
    </div>
</body>
</html>