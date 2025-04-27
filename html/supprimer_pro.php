<?php
require('logs/logs.php'); 

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID invalide.";
    exit;
}

$id_pro = intval($_GET['id']); 

try {
    $stmt_service = $connexion->prepare("SELECT COUNT(*) FROM Professionnel WHERE id_pro = :id");
    $stmt_service->bindParam(':id', $id_pro, PDO::PARAM_INT);
    $stmt_service->execute();
    $count_service = $stmt_service->fetchColumn();

    $stmt_preadmission = $connexion->prepare("SELECT COUNT(*) FROM Pre_admission WHERE id_pro = :id");
    $stmt_preadmission->bindParam(':id', $id_pro, PDO::PARAM_INT);
    $stmt_preadmission->execute();
    $count_preadmission = $stmt_preadmission->fetchColumn();

    if ($count_preadmission > 0) {
        echo "<script>alert('Impossible de supprimer ce professionnel car il est lié à une pré-admission.'); window.location.href = 'admin.php';</script>";
        exit;
    }

    $stmt = $connexion->prepare("DELETE FROM Professionnel WHERE id_pro = :id");
    $stmt->bindParam(':id', $id_pro, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: admin.php?message=suppression_reussie");
        exit;
    } else {
        echo "Erreur lors de la suppression.";
    }

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
