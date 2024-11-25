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
    <title>Page de connexion</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="container">
        <img src="img/LPFS_logo.png" alt="Logo" class="image-au-dessus">
        <h1>Bienvenue sur la page de connexion</h1>
        <form method="POST" action="">
            <label for="email">Adresse mail :</label>
            <input type="email" id="email" name="mail" class="information" placeholder="Saisissez votre Adresse mail" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="mdp" class="information" placeholder="Saisissez votre mot de passe" required>

            <button type="submit" class="button">Se connecter</button>
        </form>
    </div>
</body>
</html>