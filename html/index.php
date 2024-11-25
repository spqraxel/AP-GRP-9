<?php
session_start();

$serveur = "192.168.100.27:3306";
$utilisateur = "dev";
$motdepasse = "sio2425";
$nomBDD = "AP_BTS2";
$erreur = "";

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$nomBDD", $utilisateur, $motdepasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $mail = $_POST['mail'];
        $mdp = $_POST['mdp'];
        $recaptcha_response = $_POST['g-recaptcha-response'];

        $secretKey = '6Ld7XUwqAAAAAFBhJh81TTUAqvtD0SepI5eZTtk7';
        $remoteIp = $_SERVER['REMOTE_ADDR'];
        $request = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptcha_response}&remoteip={$remoteIp}");
        $result = json_decode($request);

        if ($result->success) {
            $requete = $connexion->prepare("SELECT * FROM Professionnel WHERE mail_pro = :mail");
            $requete->bindParam(':mail', $mail);
            $requete->execute();
            $resultat = $requete->fetch();

            if ($resultat) {
                if ($mdp === $resultat['mdp_pro']) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['mail_pro'] = $mail;
                    $_SESSION['id_metier'] = $resultat['id_metier'];

                    // Vérification de la première connexion
                    if ($resultat['premiere_connection'] == 1) {
                        header("Location: Page_motdepasse.php");
                        exit();
                    }

                    if ($resultat['id_metier'] == 1) {
                        header("Location: Site_clinique.php");
                        exit();
                    } elseif ($resultat['id_metier'] == 2) {
                        header("Location: site_clinique_admin.php");
                        exit();
                    } else {
                        $erreur = "Vous n'avez pas les droits.";
                    }
                } else {
                    $erreur = "Mot de passe incorrect.";
                }
            } else {
                $erreur = "Adresse e-mail incorrect.";
            }
        } else {
            $erreur = "Erreur de vérification reCAPTCHA.";
        }
    }
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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="container-index">
        <img src="img/LPFS_logo.png" alt="Logo" class="image-au-dessus">
        <h1>Bienvenue sur la page de connexion</h1>
        
        <?php
        if (!empty($erreur)) {
            echo "<p class='erreur'>$erreur</p>";
        }
        ?>
        
        <form method="POST" action="">
            <div class= "label-login">
                <label for="email">Adresse mail :</label>
                <input type="email" id="email" name="mail" class="information-login" placeholder="Saisissez votre Adresse mail" required>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="mdp" class="information-login" placeholder="Saisissez votre mot de passe" required>
                <div class="g-recaptcha" data-sitekey="6Ld7XUwqAAAAAFtyYWKefN4cktaecl2LdHoHPORk"></div>
                <button type="submit" class="button">Se connecter</button>
            </div>
        </form>
    </div>
</body>
</html>
