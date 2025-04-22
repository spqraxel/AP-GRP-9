<?php
require('logs/logs.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID invalide.";
    exit;
}

$id_service = intval($_GET['id']);

try {
    $stmt = $connexion->prepare("DELETE FROM Service WHERE id_service = :id");
    $stmt->bindParam(':id', $id_service, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        header("Location: admin.php?message=suppression_service_ok");
        exit;
    } else {
        echo "Aucune suppression effectuée. ID introuvable.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
