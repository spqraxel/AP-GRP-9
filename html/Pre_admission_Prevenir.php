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
<header>

</header>
<body>
    <form method="POST" action="">

    <h6>COORDONÉES PERSONNES À PRÉVENIR </h6>

    <label for="secu_sociale">Numéro de sécurité sociale<span class= "requis">*</span></label>
    <input type="text" id="secu_sociale" name="secu_sociale" required>
    <br><br>

    <label for="Nom_naissance_personne1">Nom de naissance<span class= "requis">*</span></label>
    <input type="text" id="Nom_naissance_personne1" name="Nom_naissance_personne1" required>
    <br><br>

    <label for="prenom_personne1">Prénom<span class= "requis">*</span></label>
    <input type="text" id="prenom_personne1" name="prenom_personne1" required>
    <br><br>

    <label for="Téléphone_personne1">Téléphone<span class= "requis">*</span></label>
    <input type="text" id="Téléphone_personne1" name="Téléphone_personne1" required>
    <br><br>

    <label for="Adresse_personne1">Adresse<span class= "requis">*</span></label>
    <input type="text" id="Adresse_personne1" name="Adresse_personne1" required>
    <br><br>

    <a href="Pre_admission_Confiance.php"><button>Retour</button></a>
    
    <a href="Pre_admission_Piece_Jointe.php"><button>Suivant</button></a>
    
    <form action="Logout.php" method="post">
        <button type="submit">Se déconnecter</button>
    </form>
</form>
</body>


