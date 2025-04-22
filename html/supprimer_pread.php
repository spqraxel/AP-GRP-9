<?php
require('logs/logs.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID invalide.";
    exit;
}

$id_pread = intval($_GET['id']);

try {
    $stmt = $connexion->prepare("DELETE FROM Pre_admission WHERE id_pre_admission = :id");
    $stmt->bindParam(':id', $id_pread, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        header("Location: admin.php?message=suppression_pread_ok");
        exit;
    } else {
        echo "Aucune suppression effectuée. ID introuvable.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
