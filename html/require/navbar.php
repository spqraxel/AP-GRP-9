<?php
// Vérifie si l'utilisateur est connecté et a un id_metier défini
$id_metier = $_SESSION['id_metier'] ?? null;
?>

<header class="navbar">
    <div class="logo-container">
        <img src="img/LPFS_logo.png" alt="Logo" class="logo">
    </div>
    <div class="page">
        <?php if ($id_metier == 2): ?>
            <!-- Navbar pour l'admin -->
            <a href="secretaire.php">Accueil</a>
            <a href="Pre_admission_Choix.php">Pré-admission</a>
            <a href="medecin.php">Médecin</a>
            <a href="?logout=true">Se déconnecter</a>

        <?php elseif ($id_metier == 1): ?>
            <!-- Navbar pour la secrétaire -->
            <a href="admin.php">Accueil</a>
            <a href="Pre_admission_Choix.php">Pré-admission</a>
            <a href="?logout=true">Se déconnecter</a>

        <?php elseif ($id_metier == 3 || $id_metier == 4): ?>
            <a href="medecin.php">Accueil</a>
            <a href="?logout=true">Se déconnecter</a>
        <?php endif; ?>
    </div>
</header>