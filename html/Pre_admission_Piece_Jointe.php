<?php
require('Logout.php');
session_start();

require('logs.php');

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$nomBDD", $utilisateur, $motdepasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $erreur = "Erreur de connexion : " . $e->getMessage();
}

$table1 = "Chambre";
$table2 = "Couverture_sociale";
$table3 = "Metier";
$table4 = "Patient";
$table5 = "Personne";
$table6 = "Piece_jointe";
$table7 = "Pre_admission";
$table8 = "Professionnel";
$table9 = "Service";
$table10 = "Type_pre_admission";

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pré admission</title>
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
    <div class="container-pre-admission">
    <form method="POST" action="">

    <h6>PIECE JOINT DU PATIENT</h6>
    
    <label for="secu_sociale">Numéro de sécurité sociale :<span class= "requis"> *</span></label>
    <br>
    <input type="text" id="secu_sociale" name="secu_sociale" required>
    <br><br>

    <label for="file1">Carte d'identité(recto/verso) :</label>
    <br>
    <input type="file" id="file1" name="identité">
    <br><br>

    <label for="file2">Carte vitale :</label>
    <br>
    <input type="file" id="file2" name="vitale">
    <br><br>

    <label for="file2">Carte de mutuelle :</label>
    <br>
    <input type="file" id="file3" name="mutuelle">
    <br><br>

    <label for="file2">Livret de famille (pour enfants mineurs) :</label>
    <br>
    <input type="file" id="file4" name="Livret">
    <br><br>

    <div class="navigation">
        <a href="Pre_admission_Prevenir.php" class="fleche-gauche" title="Retour"></a>
        <a href="Pre_admission_Fin.php" class="fleche-droite" title="Suivant"></a>
    </div>

    <a href="?logout=true">Se déconnecter</a>
</form>
<div>
</body>
</html>


