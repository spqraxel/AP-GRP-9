<?php
require('Logout.php');
session_start();

$serveur = "192.168.100.27:3306";
$utilisateur = "dev";
$motdepasse = "sio2425";
$nomBDD = "AP_BTS2";
$erreur = "";

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
<header class="navbar">
        <nav>
            <img src="img/LPFS_logo.png" alt="Logo" class="logo">
            <div class="nav-links">
                <a href="admin.php">Accueil</a>
                <a href="Pre_admission_Info.php">Pré-admission</a>
            </div>
        </nav>
</header>
<body>
    <div class="container-pre-admission">
    <form method="POST" action="">

    <h6>PIECE JOINT DU PATIENT</h6>
    
    <label for="secu_sociale">Numéro de sécurité sociale<span class= "requis">*</span></label>
    <input type="text" id="secu_sociale" name="secu_sociale" required>
    <br><br>

    <label for="file1">Carte d'identité(recto/verso)</label>
    <input type="file" id="file1" name="identité">
    <br><br>

    <label for="file2">Carte vitale</label>
    <input type="file" id="file2" name="vitale">
    <br><br>

    <label for="file2">Carte de mutuelle</label>
    <input type="file" id="file3" name="mutuelle">
    <br><br>

    <label for="file2">Livret de famille (pour enfants mineurs)</label>
    <input type="file" id="file4" name="Livret">
    <br><br>

    <a href="Pre_admission_Prevenir.php"><button>Retour</button></a>
    <a href="Pre_admission_Fin.php"><button>Suivant</button></a>

    <form action="Logout.php" method="post">
        <button type="submit">Se déconnecter</button>
    </form>
</form>
<div>
</body>


