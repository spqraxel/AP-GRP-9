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
    <title>Pré admission | Etape 3 sur 6</title>
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
    <h6>Pré-admission <br>Etape 3 sur 6</h6>
    <form method="POST" action="">

    <label for="Pré-admission">Pré-admission pour :<span class= "requis"> *</span></label>
    <br>
    <select id="Pré-admission" name="Pré-admission" required>
        <option value="choix" selected disabled hidden>choix</option>   
        <option value="Ambulatoire">Ambulatoire chirurgie</option>
        <option value="Hospitalisation">Hospitalisation (au moins une nuit)</option>  
    </select>
    <br><br>

    <label for="hospitalisation">Date d'hospitalisation :<span class= "requis"> *</span></label>
    <br>
    <input type="date" id="date_hospitalisation" name="date_hospitalisation" required>
    <br><br>

    <label for="H_intervention">Heure de l'intervention :<span class= "requis"> *</span></label>
    <br>
    <input type="time" id="H_intervention" name="H_intervention" required>
    <br><br>

    <label for="medecin">Nom du médecin :<span class= "requis"> *</span></label>
    <br>
    <select id="medecin" name="medecin" required>
        <option value="choissir" selected disabled hidden>choix</option>    
    </select>
    <br><br>

    <label for="Chambre_particuliere">Chambre particulière ? :<span class= "requis"> *</span></label>
    <br>
    <select id="Chambre_particuliere" name="Chambre_particuliere" required>
        <option value="choissir" selected disabled hidden>choix</option> 
        <option value="chambre_simple">chambre simple</option>
        <option value="chambre_double">chambre double</option>    
    </select>
    <br><br>

    <div class="navigation">
        <a href="Pre_admission_Inscription.php" class="fleche-gauche" title="Retour"></a>
        <a href="Pre_admission_Confiance.php" class="fleche-droite" title="Suivant"></a>
    </div>

    </form>
</div>
</body>
</html>

