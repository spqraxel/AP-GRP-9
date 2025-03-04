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
    <title>Pré admission | Etape 4 sur 6</title>
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
    
    <h6>COORDONÉES PERSONNES DE CONFIANCE <|br> Etape 4 sur 6</h6>

    <label for="secu_sociale">Numéro de sécurité sociale :<span class= "requis"> *</span></label>
    <br>
    <input type="text" id="secu_sociale" name="secu_sociale" required>
    <br><br>

    <label for="Nom_naissance_personne2">Nom de naissance :<span class= "requis"> *</span></label>
    <br>
    <input type="text" id="Nom_naissance_personne2" name="Nom_naissance_personne2" required>
    <br><br>

    <label for="prenom_personne2">Prénom :<span class= "requis"> *</span></label>
    <br>
    <input type="text" id="prenom_personne2" name="prenom_personne2" required>
    <br><br>

    <label for="Téléphone_personne2">Téléphone :<span class= "requis"> *</span></label>
    <br>
    <input type="text" id="Téléphone_personne2" name="Téléphone_personne2" required>
    <br><br>

    <label for="Adresse_personne2">Adresse :<span class= "requis"> *</span></label>
    <br>
    <input type="text" id="Adresse_personne2" name="Adresse_personne2" required>
    <br><br>

    <div class="navigation">
        <a href="Pre_admission_Hospitalisation.php" class="fleche-gauche" title="Retour"></a>
        <a href="Pre_admission_Prevenir.php" class="fleche-droite" title="Suivant"></a>
    </div>

</form>
    </div>
</body>
</html>

