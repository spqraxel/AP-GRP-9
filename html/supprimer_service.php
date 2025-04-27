<?php
require('logs/logs.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID invalide.";
    exit;
}

$id_service = intval($_GET['id']);

try {
    $stmt_professionnel = $connexion->prepare("SELECT COUNT(*) FROM Professionnel WHERE id_service = :id");
    $stmt_professionnel->bindParam(':id', $id_service, PDO::PARAM_INT);
    $stmt_professionnel->execute();
    $count_professionnel = $stmt_professionnel->fetchColumn();

    $stmt_preadmission = $connexion->prepare("SELECT COUNT(*) FROM Pre_admission WHERE id_service = :id");
    $stmt_preadmission->bindParam(':id', $id_service, PDO::PARAM_INT);
    $stmt_preadmission->execute();
    $count_preadmission = $stmt_preadmission->fetchColumn();

    if ($count_professionnel > 0 || $count_preadmission > 0) {
        echo "<script>alert('Impossible de supprimer ce service car il est lié à un professionnel ou une pré-admission.'); window.location.href = 'admin.php';</script>";
        exit;
    }

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
