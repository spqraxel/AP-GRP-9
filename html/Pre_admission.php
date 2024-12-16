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
    <h6>Connexion</h6>
    <form method="POST">
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
        
        <input type="submit" value="Suivant">

        <h6>INFORMATIONS CONCERNANT LE PATIENT</h6>
        <label for="civ">Civ.<span class= "requis">*</span></label>
        <select id="civ" name="civ" required>
            <option value="choissir" selected disabled hidden>choix</option>
            <option value="Fille">Fille</option>
            <option value="Garcon">Garçon</option>      
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

        <h6>COORDONÉES PERSONNES À PRÉVENIR </h6>

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

        <h6>COORDONÉES PERSONNES DE CONFIANCE</h6>

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

        <h6>COUVERTURE SOCIALE</h6>

        <label for="org_secu">Organisme de sécurité sociale / Nom de la caisse d'assurance maladie<span class= "requis">*</span></label>
        <input type="text" id="org_secu" name="org_secu" required>
        <br><br>

        <label for="secu_sociale">Numéro de sécurité sociale<span class= "requis">*</span></label>
        <input type="text" id="secu_sociale" name="secu_sociale" required>
        <br><br>

        <label for="assure">Le patient est-il l'assuré?"<span class= "requis">*</span></label>
        <select id="assure" name="assure" required>
            <option value="choissir" selected disabled hidden>choix</option>    
        </select>
        <br><br>

        <label for="ALD">Le patient est-il en ALD?<span class= "requis">*</span></label>
        <select id="ALD" name="ALD" required>
            <option value="choissir" selected disabled hidden>choix</option>    
        </select>
        <br><br>

        <label for="mut_ass">Nom de la mutuelle ou de l'assurance<span class= "requis">*</span></label>
        <input type="text" id="mut_ass" name="mut_ass" required>
        <br><br>

        <label for="Nadherent">Numéro d'adhérent<span class= "requis">*</span></label>
        <input type="text" id="Nadherent" name="Nadherent" required>
        <br><br>

        <label for="Chambre_particuliere">Chambre particulière?<span class= "requis">*</span></label>
        <select id="Chambre_particuliere" name="Chambre_particuliere" required>
            <option value="choissir" selected disabled hidden>choix</option>    
        </select>
        <br><br>

        <h6>PIÈCE JOINTE</h6>

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

    </form>
    
</body>
<footer>

</footer>
</html>