<?php
require('logs/logs.php');

// Vérification de l'existence de 'num_secu' dans l'URL
if (!isset($_GET['num_secu']) || empty($_GET['num_secu'])) {
    echo "Numéro de sécurité sociale invalide.";
    exit;
}

$num_secu = $_GET['num_secu'];

try {
    // Vérification si le patient existe
    $stmt = $connexion->prepare("SELECT * FROM Patient WHERE num_secu = :num_secu");
    $stmt->bindParam(':num_secu', $num_secu, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        echo "Aucun patient trouvé avec ce numéro de sécurité sociale.";
        exit;
    }

    // Vérification des dépendances dans la table couverture_sociale
    $stmtCheck = $connexion->prepare("SELECT COUNT(*) FROM couverture_sociale WHERE id_patient = :num_secu");
    $stmtCheck->bindParam(':num_secu', $num_secu, PDO::PARAM_STR);
    $stmtCheck->execute();
    $count_check = $stmtCheck->fetchColumn();

    if ($count_check > 0) {
        echo "<script>alert('Impossible de supprimer ce patient car il est lié à des données dans la table couverture_sociale.'); window.location.href = 'admin.php';</script>";
        exit;
    }

    // Suppression du patient
    $stmtDelete = $connexion->prepare("DELETE FROM Patient WHERE num_secu = :num_secu");
    $stmtDelete->bindParam(':num_secu', $num_secu, PDO::PARAM_STR);

    if ($stmtDelete->execute()) {
        header("Location: admin.php?message=patient_supprime");
        exit;
    } else {
        echo "Aucune suppression effectuée. ID introuvable.";
    }

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
