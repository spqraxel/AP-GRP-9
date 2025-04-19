<?php
session_start();
require('logs/Logout_admin.php');
require('logs/logs.php');

// Vérifier si la session est bien démarrée et si le métier de l'utilisateur est autorisé
if (!isset($_SESSION['id_metier']) || !in_array($_SESSION['id_metier'], [1, 2])) {
    header("Location: index.php");
    exit;
}

// Vérifier si l'id de la pré-admission est passé en paramètre
if (isset($_GET['id'])) {
    $id_pre_admission = intval($_GET['id']);

    // Récupérer les données de la pré-admission depuis la base de données
    $requete = $connexion->prepare("SELECT * FROM Pre_admission WHERE id_pre_admission = :id_pre_admission");
    $requete->bindParam(':id_pre_admission', $id_pre_admission);
    $requete->execute();
    $pre_admission = $requete->fetch(PDO::FETCH_ASSOC);

    if (!$pre_admission) {
        die("Pré-admission introuvable.");
    }
} else {
    die("ID de pré-admission non spécifié.");
}

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_patient = intval($_POST["id_patient"]);
    $id_choix_pre_admission = intval($_POST["id_choix_pre_admission"]);
    $date_hospitalisation = $_POST["date_hospitalisation"];
    $heure_intervention = $_POST["heure_intervention"];
    $id_pro = intval($_POST["id_pro"]);
    $id_service = intval($_POST["id_service"]);
    $id_chambre = intval($_POST["id_chambre"]);

    // Mise à jour des données dans la base
    $requete = $connexion->prepare("UPDATE Pre_admission SET id_patient = :id_patient, id_choix_pre_admission = :id_choix_pre_admission, date_hospitalisation = :date_hospitalisation, heure_intervention = :heure_intervention, id_pro = :id_pro, id_service = :id_service, id_chambre = :id_chambre WHERE id_pre_admission = :id_pre_admission");

    $requete->bindParam(':id_pre_admission', $id_pre_admission);
    $requete->bindParam(':id_patient', $id_patient);
    $requete->bindParam(':id_choix_pre_admission', $id_choix_pre_admission);
    $requete->bindParam(':date_hospitalisation', $date_hospitalisation);
    $requete->bindParam(':heure_intervention', $heure_intervention);
    $requete->bindParam(':id_pro', $id_pro);
    $requete->bindParam(':id_service', $id_service);
    $requete->bindParam(':id_chambre', $id_chambre);

    if ($requete->execute()) {
        // Redirection en fonction du métier de l'utilisateur après modification
        if ($_SESSION['id_metier'] == 1) {
            header("Location: secretaire.php");
            exit;
        } elseif ($_SESSION['id_metier'] == 2) {
            header("Location: admin.php");
            exit;
        } else {
            header("Location: index.php");
            exit;
        }
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
    <title>Modifier la pré-admission</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php require('require/navbar.php'); ?>

    <div class="container-modif">
        <h2>Modifier la pré-admission</h2>
        <form action="modifier_preadmission.php?id=<?= $id_pre_admission ?>" method="post">
            <label for="id_patient">ID Patient :</label>
            <input type="number" id="id_patient" name="id_patient" value="<?= htmlspecialchars($pre_admission['id_patient']) ?>" required><br><br>

            <label for="id_choix_pre_admission">ID Choix Pré-admission :</label>
            <input type="number" id="id_choix_pre_admission" name="id_choix_pre_admission" value="<?= htmlspecialchars($pre_admission['id_choix_pre_admission']) ?>" required><br><br>

            <label for="date_hospitalisation">Date d'hospitalisation :</label>
            <input type="date" id="date_hospitalisation" name="date_hospitalisation" value="<?= htmlspecialchars($pre_admission['date_hospitalisation']) ?>" required><br><br>

            <label for="heure_intervention">Heure d'intervention :</label>
            <input type="time" id="heure_intervention" name="heure_intervention" value="<?= htmlspecialchars($pre_admission['heure_intervention']) ?>" required><br><br>

            <label for="id_pro">ID Professionnel :</label>
            <input type="number" id="id_pro" name="id_pro" value="<?= htmlspecialchars($pre_admission['id_pro']) ?>" required><br><br>

            <label for="id_service">ID Service :</label>
            <input type="number" id="id_service" name="id_service" value="<?= htmlspecialchars($pre_admission['id_service']) ?>" required><br><br>

            <label for="id_chambre">ID Chambre :</label>
            <input type="number" id="id_chambre" name="id_chambre" value="<?= htmlspecialchars($pre_admission['id_chambre']) ?>" required><br><br>

            <div class="button-container">
                <button type="button" class="btn-shine" onclick="history.back();">Retour</button>
                <button type="submit" class="btn-submit">Modifier</button>
            </div>
        </form>
    </div>
</body>
</html>
