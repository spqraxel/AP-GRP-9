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
    $assure = htmlspecialchars($_POST['assure']);
    $ALD = htmlspecialchars($_POST['ALD']);
    $org_secu = htmlspecialchars($_POST['org_secu']);
    $mut_ass = htmlspecialchars($_POST['mut_ass']);
    $Nadherent = htmlspecialchars($_POST['Nadherent']);

    // Vérification des champs obligatoires
    if ($assure !== "0" && $assure !== "1") {
        $erreur = "Veuillez indiquer si le patient est assuré.";
    } elseif ($ALD !== "0" && $ALD !== "1") {
        $erreur = "Veuillez indiquer si le patient est en ALD.";
    } elseif (empty($org_secu)) {
        $erreur = "L'organisme de sécurité sociale est obligatoire.";
    } elseif (empty($mut_ass)) {
        $erreur = "Le nom de la mutuelle ou de l'assurance est obligatoire.";
    } elseif (empty($Nadherent) || !ctype_digit($Nadherent)) {
        $erreur = "Le numéro d'adhérent doit être un nombre.";
    } else {
        // Stocker les données dans la session
        $_SESSION['etape2'] = [
            'assure' => $assure,
            'ALD' => $ALD,
            'org_secu' => $org_secu,
            'mut_ass' => $mut_ass,
            'Nadherent' => $Nadherent,
        ];

        // Rediriger vers l'étape 3
        header('Location: Pre_admission_Confiance.php');
        exit();
    }
}

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    <?php require('require/navbar.php'); ?>

    <div class="container-pre-admission">
        <!-- Formulaire -->
        <form method="POST" action="">
            <h6>COUVERTURE SOCIALE <br>Etape 2 sur 6</h6>
            <br>

            <!-- Affichage des erreurs -->
            <?php if (!empty($erreur)): ?>
                <div class="error-message">
                    <p><?php echo htmlspecialchars($erreur); ?></p>
                </div>
            <?php endif; ?>

            <label for="assure">Le patient est-il l'assuré ? :<span class= "requis"> *</span></label>
            <br>
            <select id="assure" name="assure" required>
                <option value="-1" <?php echo (isset($_SESSION['form_data']['assure']) && $_SESSION['form_data']['assure'] === '-1' ? 'selected' : ''); ?>>choix</option>
                <option value="1" <?php echo (isset($_SESSION['form_data']['assure']) && $_SESSION['form_data']['assure'] === '1' ? 'selected' : ''); ?>>Oui</option>  
                <option value="0" <?php echo (isset($_SESSION['form_data']['assure']) && $_SESSION['form_data']['assure'] === '0' ? 'selected' : ''); ?>>Non</option>    
            </select>
            <br><br>

            <label for="ALD">Le patient est-il en ALD ? :<span class= "requis"> *</span></label>
            <br>
            <select id="ALD" name="ALD" required>
                <option value="-1" <?php echo (isset($_SESSION['form_data']['ALD']) && $_SESSION['form_data']['ALD'] === '-1' ? 'selected' : ''); ?>>choix</option>
                <option value="1" <?php echo (isset($_SESSION['form_data']['ALD']) && $_SESSION['form_data']['ALD'] === '1' ? 'selected' : ''); ?>>Oui</option>  
                <option value="0" <?php echo (isset($_SESSION['form_data']['ALD']) && $_SESSION['form_data']['ALD'] === '0' ? 'selected' : ''); ?>>Non</option>
            </select>
            <br><br>

            <label for="org_secu">Organisme de sécurité sociale / Nom de la caisse d'assurance maladie :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="org_secu" name="org_secu" value="<?php echo htmlspecialchars($_SESSION['form_data']['org_secu'] ?? ''); ?>" required>
            <br><br>        
        
            <label for="mut_ass">Nom de la mutuelle ou de l'assurance :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="mut_ass" name="mut_ass" value="<?php echo htmlspecialchars($_SESSION['form_data']['mut_ass'] ?? ''); ?>" required>
            <br><br>

            <label for="Nadherent">Numéro d'adhérent :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="Nadherent" name="Nadherent" value="<?php echo htmlspecialchars($_SESSION['form_data']['Nadherent'] ?? ''); ?>" required>
            <br><br>

            <div class="navigation">
                <button type="button" onclick="window.location.href='Pre_admission_Info.php'" class="button-next">Retour</button>
                <button type="submit" class="button-next">Suivant</button>
            </div>
        </form>
    </div>
</body>
</html>