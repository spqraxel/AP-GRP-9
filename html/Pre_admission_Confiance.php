<?php
require('logs/Logout_Secretaire.php');
session_start();

require('logs/logs.php');

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$nomBDD", $utilisateur, $motdepasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $erreur = "Erreur de connexion : " . $e->getMessage();
    die($erreur);
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['form_data'] = $_POST;

    // Récupération et nettoyage des données
    $nom_naissance_personne2 = htmlspecialchars($_POST['Nom_naissance_personne2']);
    $prenom_personne2 = htmlspecialchars($_POST['prenom_personne2']);
    $telephone_personne2 = htmlspecialchars($_POST['Téléphone_personne2']);
    $adresse_personne2 = htmlspecialchars($_POST['Adresse_personne2']);

    // Vérification des champs obligatoires
    if (empty($nom_naissance_personne2)) {
        $erreur = "Le nom de naissance est obligatoire.";
    } elseif (empty($prenom_personne2)) {
        $erreur = "Le prénom est obligatoire.";
    } elseif (empty($telephone_personne2) || !ctype_digit($telephone_personne2) || strlen($telephone_personne2) !== 10) {
        $erreur = "Le numéro de téléphone doit contenir 10 chiffres.";
    } elseif (empty($adresse_personne2)) {
        $erreur = "L'adresse est obligatoire.";
    } else {
        // Après avoir inséré la personne de confiance dans la table `Personne`
        $stmtPersonne = $connexion->prepare("
        INSERT INTO Personne (nom_pers, prenom_pers, telephone_pers, adresse_pers)
        VALUES (:nom_pers, :prenom_pers, :telephone_pers, :adresse_pers)
        ");
        $stmtPersonne->execute([
        'nom_pers' => $nom_naissance_personne2,
        'prenom_pers' => $prenom_personne2,
        'telephone_pers' => $telephone_personne2,
        'adresse_pers' => $adresse_personne2,
        ]);

        // Récupérer l'ID de la personne insérée
        $id_pers2 = $connexion->lastInsertId();

        // Stocker les données dans la session
        $_SESSION['etape3'] = [
            'id_pers2' => $id_pers2,
            'nom_naissance_personne2' => $nom_naissance_personne2,
            'prenom_personne2' => $prenom_personne2,
            'telephone_personne2' => $telephone_personne2,
            'adresse_personne2' => $adresse_personne2,
        ];

        // Rediriger vers l'étape 4
        header('Location: Pre_admission_Prevenir.php');
        exit();
    }
}

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
    <?php require('require/navbar.php'); ?>
    
    <div class="container-pre-admission">
        <!-- Formulaire -->
        <form method="POST" action="">
            <h6>COORDONÉES PERSONNES DE CONFIANCE <br>Etape 3 sur 6</h6>
            <br>

            <!-- Affichage des erreurs -->
            <?php if (!empty($erreur)): ?>
                <div class="error-message">
                    <p><?php echo htmlspecialchars($erreur); ?></p>
                </div>
            <?php endif; ?>

            <label for="Nom_naissance_personne2">Nom de naissance :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="Nom_naissance_personne2" name="Nom_naissance_personne2" value="<?php echo htmlspecialchars($_SESSION['form_data']['Nom_naissance_personne2'] ?? ''); ?>" required>
            <br><br>

            <label for="prenom_personne2">Prénom :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="prenom_personne2" name="prenom_personne2" value="<?php echo htmlspecialchars($_SESSION['form_data']['prenom_personne2'] ?? ''); ?>" required>
            <br><br>

            <label for="Téléphone_personne2">Téléphone :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="Téléphone_personne2" name="Téléphone_personne2" value="<?php echo htmlspecialchars($_SESSION['form_data']['Téléphone_personne2'] ?? ''); ?>" required>
            <br><br>

            <label for="Adresse_personne2">Adresse :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="Adresse_personne2" name="Adresse_personne2" value="<?php echo htmlspecialchars($_SESSION['form_data']['Adresse_personne2'] ?? ''); ?>" required>
            <br><br>

            <div class="navigation">
                <button type="button" onclick="window.location.href='Pre_admission_Inscription.php'" class="button-next">Retour</button>
                <button type="submit" class="button-next">Suivant</button>
            </div>
        </form>
    </div>
</body>
</html>