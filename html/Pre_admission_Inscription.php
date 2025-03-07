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
    <title>Pré admission | Etape 2 sur 6</title>
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
    <h6>COUVERTURE SOCIALE <br>Etape 2 sur 6</h6>
    <br>

    <label for="assure">Le patient est-il l'assuré ? :<span class= "requis"> *</span></label>
    <br>
    <select id="assure" name="assure" required>
        <option value="choissir">choix</option>
        <option value="choissir">Oui</option>  
        <option value="choissir">Non</option>    
    </select>
    <br><br>

    <label for="ALD">Le patient est-il en ALD ? :<span class= "requis"> *</span></label>
    <br>
    <select id="ALD" name="ALD" required>
        <option value="choisir">choix</option>
        <option value="ALD_oui">Oui</option>  
        <option value="ALD_non">Non</option>
    </select>
    <br><br>

    <label for="org_secu">Organisme de sécurité sociale / Nom de la caisse d'assurance maladie :<span class= "requis"> *</span></label>
        <br>
        <input type="text" id="org_secu" name="org_secu" required>
        <br><br>        
        
        <label for="mut_ass">Nom de la mutuelle ou de l'assurance :<span class= "requis"> *</span></label>
        <br>
        <input type="text" id="mut_ass" name="mut_ass" required>
        <br><br>

        <label for="Nadherent">Numéro d'adhérent :<span class= "requis"> *</span></label>
        <br>
        <input type="text" id="Nadherent" name="Nadherent" required>
        <br><br>

    <div class="navigation">
        <a href="Pre_admission_Info.php" class="fleche-gauche" title="Retour"></a>
        <a href="Pre_admission_Hospitalisation.php" class="fleche-droite" title="Suivant"></a>
    </div>

    </form>
    </div>
</body>
</html>


