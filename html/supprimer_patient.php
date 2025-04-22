<?php
require('logs/logs.php'); // Connexion à la BDD via $connexion

// Vérifie si le numéro de sécurité sociale est passé en GET
if (!isset($_GET['num_secu']) || empty($_GET['num_secu'])) {
    echo "Numéro de sécurité sociale invalide.";
    exit;
}

$num_secu = $_GET['num_secu']; // On ne le convertit pas en entier car ça peut contenir des zéros en début

try {
    // Préparer la requête de suppression
    $stmt = $connexion->prepare("DELETE FROM Patient WHERE num_secu = :num_secu");
    $stmt->bindParam(':num_secu', $num_secu, PDO::PARAM_STR);

    // Exécuter la requête
    if ($stmt->execute()) {
        // Redirection après suppression
        header("Location: admin.php?message=patient_supprime");
        exit;
    } else {
        echo "Erreur lors de la suppression du patient.";
    }

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
