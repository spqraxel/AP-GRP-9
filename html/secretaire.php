
<?php
require('logs/Logout_Secretaire.php');
require('logs/logs.php');

// Connexion à la base déjà établie dans logs.php (via $connexion)
if (!isset($connexion)) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

// Requêtes SQL pour récupérer les données avec jointures
try {
    $sql_Pre_admission = "
        SELECT pa.*, 
               s.nom_service, 
               pr.nom_pro, pr.prenom_pro, 
               c.type_chambre
        FROM Pre_admission pa
        LEFT JOIN Service s ON pa.id_service = s.id_service
        LEFT JOIN Professionnel pr ON pa.id_pro = pr.id_pro
        LEFT JOIN Chambre c ON pa.id_chambre = c.id_chambre";
    $result_Pre_admission = $connexion->query($sql_Pre_admission);

    $sql_Patient = "SELECT * FROM Patient";
    $result_Patient = $connexion->query($sql_Patient);

} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Secrétaire</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php require('require/navbar.php'); ?>

    <!-- Liste des Pré-admissions -->
    <div class="table-container">
        <h2>Liste des Pré-admissions</h2>
        <table class="table-style">
            <tr>
                <th>ID Pré-admission</th>
                <th>ID Patient</th>
                <th>ID Choix Pré-admission</th>
                <th>Date Hospitalisation</th>
                <th>Heure Intervention</th>
                <th>Professionnel</th>
                <th>Service</th>
                <th>Chambre</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
            <?php while ($row = $result_Pre_admission->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row["id_pre_admission"]) ?></td>
                    <td><?= htmlspecialchars($row["id_patient"]) ?></td>
                    <td><?= htmlspecialchars($row["id_choix_pre_admission"]) ?></td>
                    <td><?= htmlspecialchars($row["date_hospitalisation"]) ?></td>
                    <td><?= htmlspecialchars($row["heure_intervention"]) ?></td>
                    <td><?= htmlspecialchars($row["prenom_pro"] . " " . $row["nom_pro"]) ?></td>
                    <td><?= htmlspecialchars($row["nom_service"]) ?></td>
                    <td><?= htmlspecialchars($row["type_chambre"]) ?></td>
                    <td>
                        <a href="modifier_pread.php?id=<?= $row['id_pre_admission'] ?>">
                            <img src="img/icon_modifier.png" alt="Modifier" class="icon-action">
                        </a>
                    </td>
                    <td>
                        <a href="supprimer_pread.php?id=<?= $row['id_pre_admission'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette pré-admission ?');">
                            <img src="img/icon_supprimer.png" alt="Supprimer" class="icon-action">
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- Liste des Patients -->
    <div class="table-container">
        <h2>Liste des Patients</h2>
        <table class="table-style">
            <tr>
                <th>Numéro Sécurité Sociale</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Date de naissance</th>
                <th>Adresse</th>
                <th>Code Postal</th>
                <th>Ville</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Nom d'épouse</th>
                <th>Civilité</th>
                <th>ID Pers. 1</th>
                <th>ID Pers. 2</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
            <?php while ($row = $result_Patient->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row["num_secu"]) ?></td>
                    <td><?= htmlspecialchars($row["nom_patient"]) ?></td>
                    <td><?= htmlspecialchars($row["prenom_patient"]) ?></td>
                    <td><?= htmlspecialchars($row["date_naissance"]) ?></td>
                    <td><?= htmlspecialchars($row["adresse"]) ?></td>
                    <td><?= htmlspecialchars($row["CP"]) ?></td>
                    <td><?= htmlspecialchars($row["ville"]) ?></td>
                    <td><?= htmlspecialchars($row["email_patient"]) ?></td>
                    <td><?= htmlspecialchars($row["telephone_patient"]) ?></td>
                    <td><?= htmlspecialchars($row["nom_epouse"]) ?></td>
                    <td><?= htmlspecialchars($row["civilite"]) ?></td>
                    <td><?= htmlspecialchars($row["id_pers1"]) ?></td>
                    <td><?= htmlspecialchars($row["id_pers2"]) ?></td>
                    <td>
                        <a href="modifier_patient.php?num_secu=<?= $row['num_secu'] ?>">
                            <img src="img/icon_modifier.png" alt="Modifier" class="icon-action">
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>
