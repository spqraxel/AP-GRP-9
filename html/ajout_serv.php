<?php
session_start(); // Démarrer la session si nécessaire
ob_start(); // Évite les problèmes de redirection

require('Logout.php');
require('logs.php');

$table = "Service"; // Table modifiée pour correspondre à la table Service

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification de la connexion à la base de données
    if (!isset($connexion)) {
        die("Erreur : connexion à la base de données non établie.");
    }

    // Récupération et sécurisation des données du formulaire
    $id_service = intval($_POST["id_service"]);
    $VLAN = trim($_POST["VLAN"]);
    $nom_service = trim($_POST["nom_service"]);
    $addr_reseau = trim($_POST["addr_reseau"]);

    // Préparation de la requête SQL
    $requete = $connexion->prepare("INSERT INTO $table (id_service, VLAN, nom_service, addr_reseau) 
                                    VALUES (:id_service, :VLAN, :nom_service, :addr_reseau)");

    // Liaison des paramètres
    $requete->bindParam(':id_service', $id_service);
    $requete->bindParam(':VLAN', $VLAN);
    $requete->bindParam(':nom_service', $nom_service);
    $requete->bindParam(':addr_reseau', $addr_reseau);

    // Exécution de la requête et gestion des erreurs
    if ($requete->execute()) {
        header("Location: Modif_admin.php");
        exit;
    } else {
        $errorInfo = $requete->errorInfo();
        die("Erreur lors de l'ajout du service : " . $errorInfo[2]);
    }
}
ob_end_flush(); // Libère le tampon de sortie
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
                <button type="button" class="btn-shine" onclick="history.back();">Retour</button>
                <button type="submit" class="btn-submit">Ajouter le service</button>
            </div>
        </form>
    </div>
</body>
</html>
