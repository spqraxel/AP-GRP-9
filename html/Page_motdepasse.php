<?php
require('Logout.php');
session_start();

require('logs.php');
$success = "";

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$nomBDD", $utilisateur, $motdepasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $mail = $_SESSION['mail_pro']; // Récupère l'e-mail de la session

        $pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/";

        if ($new_password !== $confirm_password) {
            $erreur = "Les mots de passe ne correspondent pas.";
        } elseif (!preg_match($pattern, $new_password)) {
            $erreur = "Le mot de passe doit contenir au moins 12 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
        } else {
            // Hachage du mot de passe avant stockage
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Mise à jour du mot de passe et du statut de première connexion
            $requete = $connexion->prepare("UPDATE Professionnel SET mdp_pro = :mdp_pro, premiere_connection = 0 WHERE mail_pro = :mail_pro");
            $requete->bindParam(':mdp_pro', $hashed_password);
            $requete->bindParam(':mail_pro', $mail);
            $requete->execute();

            $success = "Mot de passe mis à jour avec succès !";
            
            // Redirection vers la page appropriée après la mise à jour
            if ($_SESSION['id_metier'] == 1) {
                header("Location: scretaire.php");
                exit();
            } elseif ($_SESSION['id_metier'] == 2) {
                header("Location: admin.php");
                exit();
            }
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
    <title>Création de mot de passe</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="container-mdp">
        <img src="img/LPFS_logo.png" alt="Logo" class="image-au-dessus">
        <h1>Création de votre mot de passe</h1>

        <?php
        if (!empty($erreur)) {
            echo "<p class='erreur'>$erreur</p>";
        }
        if (!empty($success)) {
            echo "<p class='success'>$success</p>";
        }
        ?>
        
        <form method="POST" action="">
            <div class="label-mdp">
                <label for="new-password">Nouveau mot de passe :</label>
                <input type="password" id="new-password" name="new_password" class="information-mdp" placeholder="Saisissez votre nouveau mot de passe" required>

                <label for="confirm-password">Répéter le nouveau mot de passe :</label>
                <input type="password" id="confirm-password" name="confirm_password" class="information-mdp" placeholder="Répétez votre mot de passe" required>

                <button type="submit" class="button-mdp">Valider</button>
            </div>
        </form>
    </div>
</body>
</html>
