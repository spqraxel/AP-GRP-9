<?php
session_start();
require('logs/Logout_admin.php');
require('logs/logs.php');

// Vérifier si la connexion à la base de données est établie
if (!isset($connexion)) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

// Récupérer les données des tables Service, Professionnel et Pre_admission
try {
    $sql_Service = "SELECT * FROM Service";
    $result_Service = $connexion->query($sql_Service);

    // Requête pour récupérer uniquement les professionnels avec id_metier = 3
    $sql_Professionnel = "SELECT * FROM Professionnel WHERE id_metier = 3";
    $result_Professionnel = $connexion->query($sql_Professionnel);

    $sql_Pre_admission = "SELECT * FROM Pre_admission";
    $result_Pre_admission = $connexion->query($sql_Pre_admission);
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

        <!-- Liste des médecins -->
        <div class="table-container">
            <h2>Liste des médecins</h2>
            <a href="ajout_pro.php">
                <button type="button" class="button-pro">Ajouter un médecin</button>
                <br><br><br>
            </a>

            <table class="table-style">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>ID Métier</th>
                    <th>ID Service</th>
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
                        <td><?= htmlspecialchars($row["id_metier"]) ?></td>
                        <td><?= htmlspecialchars($row["id_service"]) ?></td>
                        <td><?= htmlspecialchars($row["premiere_connection"]) ?></td>
                        <td>
                            <a href="modifier_pro.php?id=<?= $row['id_pro'] ?>">
                                <img src="img/icon_modifier.png" alt="Modifier" class="icon-action">
                            </a>
                        </td>
                        <td>
                            <a href="supprimer.php?id=<?= $row['id_pro'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                <img src="img/icon_supprimer.png" alt="Supprimer" class="icon-action">
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- Liste des Services -->
        <div class="table-container">
            <h2>Liste des Services</h2>
            <a href="ajout_serv.php">
                <button type="button" class="button-serv">Ajouter un service</button>
                <br><br><br>
            </a>
            <table class="table-style">
                <tr>
                    <th>ID Service</th>
                    <th>VLAN</th>
                    <th>Nom du Service</th>
                    <th>Adresse Réseau</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                </tr>
                <?php while ($row = $result_Service->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row["id_service"]) ?></td>
                        <td><?= htmlspecialchars($row["VLAN"]) ?></td>
                        <td><?= htmlspecialchars($row["nom_service"]) ?></td>
                        <td><?= htmlspecialchars($row["addr_reseau"]) ?></td>
                        <td>
                            <a href="modifier_service.php?id=<?= $row['id_service'] ?>">
                                <img src="img/icon_modifier.png" alt="Modifier" class="icon-action">
                            </a>
                        </td>
                        <td>
                            <a href="supprimer.php?id=<?= $row['id_service'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?');">
                                <img src="img/icon_supprimer.png" alt="Supprimer" class="icon-action">
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

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
                    <th>ID Professionnel</th>
                    <th>ID Service</th>
                    <th>ID Chambre</th>
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
                        <td><?= htmlspecialchars($row["id_pro"]) ?></td>
                        <td><?= htmlspecialchars($row["id_service"]) ?></td>
                        <td><?= htmlspecialchars($row["id_chambre"]) ?></td>
                        <td>
                            <a href="modifier_pre_admission.php?id=<?= $row['id_pre_admission'] ?>">
                                <img src="img/icon_modifier.png" alt="Modifier" class="icon-action">
                            </a>
                        </td>
                        <td>
                            <a href="supprimer.php?id=<?= $row['id_pre_admission'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette pré-admission ?');">
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
