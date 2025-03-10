<?php
require('logs.php');

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$nomBDD", $utilisateur, $motdepasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$service_id = $_GET['service_id'];

// Récupérer les médecins du service sélectionné
$query_medecins = $connexion->prepare("
    SELECT id_pro, nom_pro, prenom_pro 
    FROM Professionnel 
    WHERE id_service = :service_id
");
$query_medecins->bindParam(':service_id', $service_id);
$query_medecins->execute();
$medecins = $query_medecins->fetchAll(PDO::FETCH_ASSOC);

// Retourner les médecins au format JSON
header('Content-Type: application/json');
echo json_encode($medecins);
?>