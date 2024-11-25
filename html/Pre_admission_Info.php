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
    <form method="POST"></form>
    <h6>INFORMATIONS CONCERNANT LE PATIENT</h6>

    <label for="secu_sociale">Numéro de sécurité sociale<span class= "requis">*</span></label>
    <input type="text" id="secu_sociale" name="secu_sociale" required>
    <br><br>

    <label for="civ">Civilité<span class= "requis">*</span></label>
    <select id="civ" name="civ" required>
        <option value="choissir" selected disabled hidden>choix</option>
        <option value="Femme">Femme</option>
        <option value="Homme">Homme</option>      
    </select>
    <br><br>

    <label for="Nom_naissance">Nom de naissance<span class= "requis">*</span></label>
    <input type="text" id="Nom_naissance" name="Nom_naissance" required>
    <br><br>

    <label for="Nom_epouse">Nom d'épouse</label>
    <input type="text" id="Nom_epouse" name="Nom_epouse" >
    <br><br> 
   
    <label for="prenom">Prénom<span class= "requis">*</span></label>
    <input type="text" id="prenom" name="prenom" required>
    <br><br>

    <label for="date_naissance">Date de naissance<span class= "requis">*</span></label>
    <input type="date" id="date_naissance" name="date_naissance" required>
    <br><br>

    <label for="Adresse">Adresse<span class= "requis">*</span></label>
    <input type="text" id="Adresse" name="Adresse" required>
    <br><br>

    <label for="CP">CP<span class= "requis">*</span></label>
    <input type="text" id="CP" name="CP" required>
    <br><br>

    <label for="Ville">Ville<span class= "requis">*</span></label>
    <input type="text" id="Ville" name="Ville" required>
    <br><br>

    <label for="Email">Email <span class= "requis">*</span></label>
    <input type="text" id="Email" name="Email" required>
    <br><br>

    <label for="Téléphone">Téléphone<span class= "requis">*</span></label>
    <input type="text" id="Téléphone" name="Téléphone" required>
    <br><br>
    
    <button>Suivant</button>
    
    <button>Deconnexion</button>
    </form>
</body>