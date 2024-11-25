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

    <h6>COUVERTURE SOCIALE</h6>

    <form method="POST">
    <label for="secu_sociale">Numéro de sécurité sociale<span class= "requis">*</span></label>
    <input type="text" id="secu_sociale" name="secu_sociale" required>
    <br><br>

    <label for="assure">Le patient est-il l'assuré?"<span class= "requis">*</span></label>
    <select id="assure" name="assure" required>
        <option value="choissir">choix</option>
        <option value="choissir">Oui</option>  
        <option value="choissir">Non</option>    
    </select>
    <br><br>

    <label for="ALD">Le patient est-il en ALD?<span class= "requis">*</span></label>
    <select id="ALD" name="ALD" required>
        <option value="choissir">choix</option>
        <option value="ALD_oui">Oui</option>  
        <option value="ALD_non">Non</option>

        <label for="org_secu">Organisme de sécurité sociale / Nom de la caisse d'assurance maladie<span class= "requis">*</span></label>
        <input type="text" id="org_secu" name="org_secu" required>
        <br><br>        
        
        <label for="mut_ass">Nom de la mutuelle ou de l'assurance<span class= "requis">*</span></label>
        <input type="text" id="mut_ass" name="mut_ass" required>
        <br><br>

        <label for="Nadherent">Numéro d'adhérent<span class= "requis">*</span></label>
        <input type="text" id="Nadherent" name="Nadherent" required>
        <br><br>
    </select>
    <br><br>

    <button>Suivant</button>
    
    <button>Deconnexion</button>
    </form>
</body>


