<?php
require('Logout.php');
session_start();

$serveur = "192.168.100.27";
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
    <form method="POST">
    
    <h6>COORDONÉES PERSONNES DE CONFIANCE</h6>

    <label for="secu_sociale">Numéro de sécurité sociale<span class= "requis">*</span></label>
    <input type="text" id="secu_sociale" name="secu_sociale" required>
    <br><br>

    <label for="Nom_naissance_personne2">Nom de naissance<span class= "requis">*</span></label>
    <input type="text" id="Nom_naissance_personne2" name="Nom_naissance_personne2" required>
    <br><br>

    <label for="prenom_personne2">Prénom<span class= "requis">*</span></label>
    <input type="text" id="prenom_personne2" name="prenom_personne2" required>
    <br><br>

    <label for="Téléphone_personne2">Téléphone<span class= "requis">*</span></label>
    <input type="text" id="Téléphone_personne2" name="Téléphone_personne2" required>
    <br><br>

    <label for="Adresse_personne2">Adresse<span class= "requis">*</span></label>
    <input type="text" id="Adresse_personne2" name="Adresse_personne2" required>
    <br><br>

    <button>Suivant</button>
    
    <button>Deconnexion</button>
</form>
</body>


