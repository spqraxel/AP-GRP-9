<?php

require('logs/Logout_Secretaire.php');
require('logs/logs.php');

$id_metier = $_SESSION['id_metier']; 

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID invalide.";
    exit;
}

$id_pread = intval($_GET['id']);

if ($id_metier == 1 || $id_metier == 2) {
    try {
        $stmt = $connexion->prepare("DELETE FROM Pre_admission WHERE id_pre_admission = :id");
        $stmt->bindParam(':id', $id_pread, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            if ($id_metier == 1) {
                header("Location: secretaire.php?message=suppression_pread_ok");
            } else {
                header("Location: admin.php?message=suppression_pread_ok");
            }
            exit;
        } else {
            echo "Aucune suppression effectuée. ID introuvable.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Vous n'avez pas les permissions nécessaires pour supprimer cette pré-admission.";
}
