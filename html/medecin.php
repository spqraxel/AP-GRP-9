<?php
session_start();
require('Logout.php');
require('logs.php');

// Vérifier si la connexion à la base de données est établie
if (!isset($connexion)) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

// Vérifier si l'utilisateur est connecté et récupérer son ID
if (!isset($_SESSION["id_pro"])) {
    die("Accès refusé. Veuillez vous connecter.");
}

$id_pro = $_SESSION["id_pro"];

// Récupérer le service du médecin connecté
try {
    $sql_ServiceMedecin = "SELECT id_service FROM Professionnel WHERE id_pro = ?";
    $stmt = $connexion->prepare($sql_ServiceMedecin);
    $stmt->execute([$id_pro]);
    $serviceMedecin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$serviceMedecin) {
        die("Erreur : Médecin non trouvé.");
    }

    $id_service_medecin = $serviceMedecin["id_service"];

    // Récupérer les pré-admissions correspondant au service du médecin
    $sql_PreAdmission = "SELECT * FROM pre_admission WHERE id_service = ?";
    $stmt = $connexion->prepare($sql_PreAdmission);
    $stmt->execute([$id_service_medecin]);
    $result_PreAdmission = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pré-admission</title>
    <link rel="stylesheet" href="style/style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <header class="navbar">
        <div class="logo-container">
            <img src="img/LPFS_logo.png" alt="Logo" class="logo">
        </div>
        <div class="page">
            <a href="admin.php">Accueil</a>
            <a href="?logout=true">Se déconnecter</a>
        </div>
    </header>

    <main>
        <div class="table-container">
            <h2>Liste des Pré-admissions</h2>
            <table class="table-style">
                <tr>
                    <th>ID Pré-admission</th>
                    <th>ID Patient</th>
                    <th>ID Choix Pré-admission</th>
                    <th>Date Hospitalisation</th>
                    <th>Heure Intervention</th>
                    <th>ID Professionnel</th>
                    <th>ID Service</th>
                    <th>ID Chambre</th>
                </tr>
                <?php foreach ($result_PreAdmission as $row) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row["id_pre_admission"]) ?></td>
                        <td><?= htmlspecialchars($row["id_patient"]) ?></td>
                        <td><?= htmlspecialchars($row["id_choix_pre_admission"]) ?></td>
                        <td><?= htmlspecialchars($row["date_hospitalisation"]) ?></td>
                        <td><?= htmlspecialchars($row["heure_intervention"]) ?></td>
                        <td><?= htmlspecialchars($row["id_pro"]) ?></td>
                        <td><?= htmlspecialchars($row["id_service"]) ?></td>
                        <td><?= htmlspecialchars($row["id_chambre"]) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </main>
</body>
</html>
