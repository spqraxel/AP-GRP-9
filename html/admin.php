<?php
require('logs/Logout_admin.php');
require('logs/logs.php');

if (!isset($connexion)) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

try {
    $sql_Service = "SELECT * FROM Service";
    $result_Service = $connexion->query($sql_Service);

    $sql_Professionnel = "
        SELECT p.*, s.nom_service, m.nom_metier 
        FROM Professionnel p
        LEFT JOIN Service s ON p.id_service = s.id_service
        LEFT JOIN Metier m ON p.id_metier = m.id_metier";
    $result_Professionnel = $connexion->query($sql_Professionnel);

    $sql_Pre_admission = "
        SELECT pa.*, 
               s.nom_service, 
               c.type_chambre, 
               pr.nom_pro, pr.prenom_pro
        FROM Pre_admission pa
        LEFT JOIN Service s ON pa.id_service = s.id_service
        LEFT JOIN Chambre c ON pa.id_chambre = c.id_chambre
        LEFT JOIN Professionnel pr ON pa.id_pro = pr.id_pro";
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
    <title>Page Admin</title>
    <link rel="stylesheet" href="style/style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
<?php require('require/navbar.php'); ?>

<main>

    <!-- Liste des professionnels -->
    <div class="table-container">
        <h2>Liste des professionnels</h2>
        <a href="ajout_pro.php">
            <button type="button" class="button-pro">Ajouter un professionnel</button>
            <br><br>
        </a>

        <table class="table-style">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Métier</th>
                <th>Service</th>
                <th>Première Connexion</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
            <?php while ($row = $result_Professionnel->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row["id_pro"]) ?></td>
                    <td><?= htmlspecialchars($row["nom_pro"]) ?></td>
                    <td><?= htmlspecialchars($row["prenom_pro"]) ?></td>
                    <td><?= htmlspecialchars($row["mail_pro"]) ?></td>
                    <td><?= htmlspecialchars($row["nom_metier"]) ?></td>
                    <td><?= htmlspecialchars($row["nom_service"]) ?></td>
                    <td><?= htmlspecialchars($row["premiere_connection"]) ?></td>
                    <td>
                        <a href="modifier_pro.php?id=<?= $row['id_pro'] ?>">
                            <img src="img/icon_modifier.png" alt="Modifier" class="icon-action">
                        </a>
                    </td>
                    <td>
                        <a href="supprimer_pro.php?id=<?= $row['id_pro'] ?>">
                            <img src="img/icon_supprimer.png" alt="Supprimer" class="icon-action">
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- Liste des Services -->
    <div class="table-container">
    <h2>Liste des services</h2>
    <a href="ajout_serv.php">
        <button type="button" class="button-serv">Ajouter un service</button>
        <br><br>
    </a>
    <table class="table-style">
        <tr>
            <th>ID Service</th>
            <th>Nom du Service</th>
            <th>Modifier</th>
            <th>Supprimer</th>
        </tr>
        <?php while ($row = $result_Service->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <td><?= htmlspecialchars($row["id_service"]) ?></td>
                <td><?= htmlspecialchars($row["nom_service"]) ?></td>
                <td>
                    <a href="modifier_service.php?id=<?= urlencode($row['id_service']) ?>">
                        <img src="img/icon_modifier.png" alt="Modifier" class="icon-action">
                    </a>
                </td>
                <td>
                    <a href="supprimer_service.php?id=<?= urlencode($row['id_service']) ?>">
                        <img src="img/icon_supprimer.png" alt="Supprimer" class="icon-action">
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    </div>


    <!-- Liste des Pré-admissions -->
    <div class="table-container">
        <h2>Liste des pré-admissions</h2>
        <table class="table-style">
        <br><br>
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
                        <a href="modifier_pread.php?id=<?= $row['id_pre_admission'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette pré-admission ?');">
                            <img src="img/icon_modifier.png" alt="Modifier" class="icon-action">
                        </a>
                    </td>
                    <td>
                        <a href="supprimer_pread.php?id=<?= $row['id_pre_admission'] ?>">
                            <img src="img/icon_supprimer.png" alt="Supprimer" class="icon-action">
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- Liste des Patients -->
    <div class="table-container">
        <h2>Liste des patients</h2>
        <a href="ajout_patient.php">
            <br><br>
        </a>
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
                        <a href="modifier_patient.php?num_secu=<?= $row['num_secu'] ?>" >
                            <img src="img/icon_modifier.png" alt="Modifier" class="icon-action">
                        </a>
                    </td>
                    <td>
                        <a href="supprimer_patient.php?num_secu=<?= $row['num_secu'] ?>"onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ?');">
                            <img src="img/icon_supprimer.png" alt="Supprimer" class="icon-action">
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

</main>
</body>
</html>
