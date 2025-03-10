<?php
session_start();
require('Logout.php');
require('logs.php');

// Vérifier si la connexion à la base de données est établie
if (!isset($connexion)) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

// Récupérer les données des tables Service et Professionnel
try {
    $sql_Service = "SELECT * FROM Service";
    $result_Service = $connexion->query($sql_Service);

    $sql_Professionnel = "SELECT * FROM Professionnel";
    $result_Professionnel = $connexion->query($sql_Professionnel);
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
    <header class="navbar">
        <div class="logo-container">
            <img src="img/LPFS_logo.png" alt="Logo" class="logo">
        </div>
        <div class="page">
            <a href="admin.php">Accueil</a>
            <a href="Pre_admission_Choix.php">Pré-admission</a>
            <a href="?logout=true">Se déconnecter</a>
        </div>
    </header>

    <main>
        <div class="table-container">
            <h2>Liste des Professionnels</h2>
            <table class="table-style">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>ID Métier</th>
                    <th>ID Service</th>
                    <th>Première Connexion</th>
                    <th>Actions</th>
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
                            <a href="modifier.php?id=<?= $row['id_pro'] ?>">
                                <img src="img/icon_modifier.png" alt="Modifier" class="icon-action">
                            </a>
                            <a href="supprimer.php?id=<?= $row['id_pro'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                <img src="img/icon_supprimer.png" alt="Supprimer" class="icon-action">
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="table-container">
            <h2>Liste des Services</h2>
            <table class="table-style">
                <tr>
                    <th>ID Service</th>
                    <th>VLAN</th>
                    <th>Nom du Service</th>
                    <th>Adresse Réseau</th>
                    <th>Actions</th>
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
                            <a href="supprimer_service.php?id=<?= $row['id_service'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?');">
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
