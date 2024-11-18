<html lang="fr">
    
<?php
    session_start();

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        // Rediriger l'utilisateur vers la page de connexion
        header("location: index.php");
        exit;
    }

    // Vérifier les autorisations d'accès à la page
    if ( $_SESSION['id_metier'] !== 2) {
    // Rediriger l'utilisateur vers une autre page ou afficher un message d'erreur
    header("location: index.php"); // Ou une autre page appropriée
    exit;
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Personnel</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <header>
        <div class="nav-bar">
            <div class="left">
                <img src="./img/LPFS_logo.png" alt="LPFS logo">
                <h1>Menu Secretaire</h1>
            </div>
            <div class="bouton">
                <a onclick="history.back()" class="btn-shine">Retour</a>
                <a href="inscription.php" class="btn-shine">Ajout Patient</a>
                <a href="patients.php" class="btn-shine">Liste Patient</a>
                <a href="deconnexion.php" class="btn-shine">Deconnexion</a>
            </div>
        </div>
    </header>

    
<div class="container">
    
</div>

</body>
</html>
