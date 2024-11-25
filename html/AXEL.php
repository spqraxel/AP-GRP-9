<?php
$host = '192.168.100.27'; // Adresse IP du serveur de base de données
$dbname = 'AP_BTS2'; // Nom de la base de données
$username = 'dev'; // Nom d'utilisateur de la base
$password = 'sio2425'; // Mot de passe de la base

try {
    // Création d'une connexion PDO
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);

    // Configurer les options PDO (facultatif mais recommandé)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    echo "Connexion réussie à la base de données.";
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
