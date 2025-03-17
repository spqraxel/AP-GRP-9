<?php
session_start();
require('Logout.php');
require('logs.php');

$table = "Service"; // Table modifiée pour correspondre à la table Service

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$nomBDD", $utilisateur, $motDePasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $id_service = $_POST["id_service"];
    $VLAN = $_POST["VLAN"];
    $nom_service = $_POST["nom_service"];
    $addr_reseau = $_POST["addr_reseau"];

    // Insertion dans la base de données
    $requete = $connexion->prepare("INSERT INTO $table (id_service, VLAN, nom_service, addr_reseau) 
                                    VALUES (:id_service, :VLAN, :nom_service, :addr_reseau)");

    $requete->bindParam(':id_service', $id_service);
    $requete->bindParam(':VLAN', $VLAN);
    $requete->bindParam(':nom_service', $nom_service);
    $requete->bindParam(':addr_reseau', $addr_reseau);

    if ($requete->execute()) {
        header("Location: Modif_admin.php");
        exit;
    } else {
        echo "Erreur lors de l'ajout du service.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout d'un service</title>
    <link rel="stylesheet" href="style/style.css">
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

    <div class="container-modif">
        <h2>Ajout d'un service</h2>
        <form action="Ajout_service.php" method="post">
            <label for="id_service">ID Service :</label>
            <input type="number" id="id_service" name="id_service" required><br><br>

            <label for="VLAN">VLAN :</label>
            <input type="text" id="VLAN" name="VLAN" required><br><br>

            <label for="nom_service">Nom du service :</label>
            <input type="text" id="nom_service" name="nom_service" required><br><br>

            <label for="addr_reseau">Adresse réseau :</label>
            <input type="text" id="addr_reseau" name="addr_reseau" required><br><br>

            <div class="button-container">
                <button><a href="Modif_admin.php" class="btn-shine">Retour</a></button>
                <button type="submit" class="btn-submit">Ajouter le service</button>
            </div>
        </form>
    </div>
</body>
</html>
