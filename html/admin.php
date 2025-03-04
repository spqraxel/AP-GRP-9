<!DOCTYPE html>
<?php
require('Logout.php');
session_start();
require('logs.php'); 

if (!isset($conn) || $conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

$sql_Service = "SELECT * FROM Service";
$result_Service = $conn->query($sql_Service);

$sql_Professionnel = "SELECT * FROM Professionnel";
$result_Professionnel = $conn->query($sql_Professionnel);
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Admin</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <header class="navbar">
        <div class="logo-container">
            <img src="img/LPFS_logo.png" alt="Logo" class="logo">
        </div>
        <div class="page">
            <a href="admin.php">Accueil</a>
            <a href="Pre_admission_Info.php">Pré-admission</a>        
            <a href="?logout=true">Se déconnecter</a>
        </div>
    </header>

    <main>
        <h2>Liste des Professionnels</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Mot de passe</th>
                <th>ID Métier</th>
                <th>ID Service</th>
                <th>Première Connexion</th>
            </tr>
            <?php
            if ($result_Professionnel->num_rows > 0) {
                while ($row = $result_Professionnel->fetch_assoc()) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row["id_pro"]) . "</td>
                        <td>" . htmlspecialchars($row["nom_pro"]) . "</td>
                        <td>" . htmlspecialchars($row["prenom_pro"]) . "</td>
                        <td>" . htmlspecialchars($row["mail_pro"]) . "</td>
                        <td>" . htmlspecialchars($row["mdp_pro"]) . "</td>
                        <td>" . htmlspecialchars($row["id_metier"]) . "</td>
                        <td>" . htmlspecialchars($row["id_service"]) . "</td>
                        <td>" . htmlspecialchars($row["premiere_connection"]) . "</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Aucun professionnel trouvé</td></tr>";
            }
            ?>
        </table>

        <h2>Liste des Services</h2>
        <table border="1">
            <tr>
                <th>ID Service</th>
                <th>VLAN</th>
                <th>Nom du Service</th>
                <th>Adresse Réseau</th>
            </tr>
            <?php
            if ($result_Service->num_rows > 0) {
                while ($row = $result_Service->fetch_assoc()) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row["id_service"]) . "</td>
                        <td>" . htmlspecialchars($row["VLAN"]) . "</td>
                        <td>" . htmlspecialchars($row["nom_service"]) . "</td>
                        <td>" . htmlspecialchars($row["addr_reseau"]) . "</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Aucun service trouvé</td></tr>";
            }
            ?>
        </table>
    </main>

</body>
</html>

<?php
$conn->close();
?>
