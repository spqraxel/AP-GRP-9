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
    <label for="secu_sociale">Numéro de sécurité sociale<span class= "requis">*</span></label>
    <input type="text" id="secu_sociale" name="secu_sociale" required>
    <br><br>

    <label for="Pré-admission">Pré-admission pour:<span class= "requis">*</span></label>
    <select id="Pré-admission" name="Pré-admission" required>
        <option value="choix" selected disabled hidden>choix</option>   
        <option value="Ambulatoire">Ambulatoire chirurgie</option>
        <option value="Hospitalisation">Hospitalisation (au moins une nuit)</option>  
    </select>
    <br><br>

    <label for="hospitalisation">Date d'hospitalisation<span class= "requis">*</span></label>
    <input type="date" id="date_hospitalisation" name="date_hospitalisation" required>
    <br><br>

    <label for="H_intervention">Heure de l'intervention<span class= "requis">*</span></label>
    <input type="time" id="H_intervention" name="H_intervention" required>
    <br><br>

    <label for="medecin">Nom du médecin<span class= "requis">*</span></label>
    <select id="medecin" name="medecin" required>
        <option value="choissir" selected disabled hidden>choix</option>    
    </select>
    <br><br>

    <label for="Chambre_particuliere">Chambre particulière?<span class= "requis">*</span></label>
    <select id="Chambre_particuliere" name="Chambre_particuliere" required>
        <option value="choissir" selected disabled hidden>choix</option> 
        <option value="chambre_simple">chambre simple</option>
        <option value="chambre_double">chambre double</option>    
    </select>
    <br><br>

    <button>Suivant</button>
    
    <button>Deconnexion</button>
    </form>
</body>


