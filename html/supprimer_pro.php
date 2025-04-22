<?php
require('logs/logs.php'); // Contient la connexion PDO $connexion

// Vérifier si l'ID du professionnel est passé en paramètre GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID invalide.";
    exit;
}

$id_pro = intval($_GET['id']); // Sécurise l'ID

try {
    // Préparer la requête de suppression
    $stmt = $connexion->prepare("DELETE FROM Professionnel WHERE id_pro = :id");
    $stmt->bindParam(':id', $id_pro, PDO::PARAM_INT);

    // Exécuter la requête
    if ($stmt->execute()) {
        // Redirection après la suppression
        header("Location: admin.php?message=suppression_reussie");
        exit;
    } else {
        echo "Erreur lors de la suppression.";
    }

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
