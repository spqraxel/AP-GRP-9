<?php
require('logs/Logout_medecin.php');
require('logs/logs.php');

// Fonction pour obtenir le nom du mois en français
function getFrenchMonthName($date) {
    $months = [
        'January' => 'janvier',
        'February' => 'février',
        'March' => 'mars',
        'April' => 'avril',
        'May' => 'mai',
        'June' => 'juin',
        'July' => 'juillet',
        'August' => 'août',
        'September' => 'septembre',
        'October' => 'octobre',
        'November' => 'novembre',
        'December' => 'décembre'
    ];
    
    $english_month = date('F', strtotime($date));
    return $months[$english_month] ?? $english_month;
}

if (!isset($connexion)) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

if (!isset($_SESSION["id_pro"]) || !isset($_SESSION["id_metier"])) {
    die("Accès refusé. Veuillez vous connecter.");
}

$id_pro = $_SESSION["id_pro"];
$id_metier = $_SESSION["id_metier"];

// Récupérer les infos du professionnel connecté
$stmt_pro = $connexion->prepare("SELECT nom_pro, prenom_pro, id_service FROM Professionnel WHERE id_pro = ?");
$stmt_pro->execute([$id_pro]);
$pro_info = $stmt_pro->fetch(PDO::FETCH_ASSOC);
$nom_medecin = $pro_info['nom_pro'] ?? '';
$prenom_medecin = $pro_info['prenom_pro'] ?? '';
$id_service_pro = $pro_info['id_service'] ?? '';

// Déterminer si on veut voir tous les rendez-vous ou filtrer par mois
$show_all = isset($_GET['show_all']) && $_GET['show_all'] == 1;
$selected_month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

// Préparation du titre du mois
$month_title = $show_all ? 'Tous les rendez-vous' : 'Rendez-vous pour ' . getFrenchMonthName($selected_month) . ' ' . date('Y', strtotime($selected_month));

try {
    // Construction de la requête de base (ajout du nom du médecin)
    $sql = "SELECT pa.*, p.num_secu, p.nom_patient, p.prenom_patient, t.type_admission, 
                   s.nom_service AS service, pr.nom_pro, pr.prenom_pro
            FROM Pre_admission pa
            JOIN Patient p ON pa.id_patient = p.num_secu
            JOIN Type_pre_admission t ON pa.id_choix_pre_admission = t.id_type_admission
            JOIN Service s ON pa.id_service = s.id_service
            JOIN Professionnel pr ON pa.id_pro = pr.id_pro";
    
    $params = [];
    
    // Conditions de base (toujours filtrer par service pour les médecins)
    if ($id_metier == 3) {
        $sql .= " WHERE pa.id_service = ?";
        $params[] = $id_service_pro;
    }
    
    // Ajout des conditions supplémentaires selon les filtres
    if (!$show_all) {
        $month_start = date('Y-m-01', strtotime($selected_month));
        $month_end = date('Y-m-t', strtotime($selected_month));
        $sql .= ($id_metier == 3 ? " AND" : " WHERE") . " pa.date_hospitalisation BETWEEN ? AND ?";
        $params = array_merge($params, [$month_start, $month_end]);
    }
    
    $sql .= " ORDER BY pa.date_hospitalisation, pa.heure_intervention";
    
    $stmt = $connexion->prepare($sql);
    $stmt->execute($params);
    $result_PreAdmission = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Médecin</title>
    <link rel="stylesheet" href="style/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>
    <?php require('require/navbar.php'); ?>

    <main>
        <div class="filter-container">
            <div class="filter-group">
                <form method="GET" action="" class="filter-form">
                    <label for="month">Sélectionnez un mois :</label>
                    <input type="month" id="month" name="month" 
                           value="<?= htmlspecialchars($selected_month) ?>" 
                           min="2020-01" max="2030-12">
                    <button type="submit" class="btn">Filtrer</button>
                </form>
            </div>
            
            <div class="filter-group">
                <?php if ($show_all): ?>
                    <a href="?" class="btn btn-secondary">Voir le mois courant</a>
                <?php else: ?>
                    <a href="?show_all=1" class="btn">Voir tous les rendez-vous</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="table-container">
            <h2><?= $month_title ?></h2>
            
            <?php if (empty($result_PreAdmission)): ?>
                <p class="no-data">Aucun rendez-vous trouvé.</p>
            <?php else: ?>
                <table class="table-style">
                    <tr>
                        <?php if ($id_metier == 2 || $id_metier == 3): ?>
                            <th>Service</th>
                        <?php endif; ?>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Patient</th>
                        <th>Type</th>
                        <th>Médecin</th>
                        <th>Chambre</th>
                        <th>PDF</th>
                    </tr>
                    <?php foreach ($result_PreAdmission as $row): ?>
                        <tr>
                            <?php if ($id_metier == 2 || $id_metier == 3): ?>
                                <td><?= htmlspecialchars($row['service'] ?? '') ?></td>
                            <?php endif; ?>
                            <td><?= date('d/m/Y', strtotime($row["date_hospitalisation"] ?? '')) ?></td>
                            <td><?= substr($row["heure_intervention"] ?? '', 0, 5) ?></td>
                            <td><?= htmlspecialchars(($row["prenom_patient"] ?? '') . ' ' . ($row["nom_patient"] ?? '')) ?></td>
                            <td><?= htmlspecialchars($row["type_admission"] ?? '') ?></td>
                            <td><?= htmlspecialchars(($row["prenom_pro"] ?? '') . ' ' . ($row["nom_pro"] ?? '')) ?></td>
                            <td><?= htmlspecialchars(($row["id_chambre"] ?? '') == 1 ? 'Simple' : 'Double') ?></td>
                            <td>
                                <button onclick="genererPDF(
                                    '<?= $row['nom_patient'] ?? '' ?>',
                                    '<?= $row['prenom_patient'] ?? '' ?>',
                                    '<?= $row['num_secu'] ?? '' ?>',
                                    '<?= date('d/m/Y', strtotime($row["date_hospitalisation"] ?? '')) ?>',
                                    '<?= substr($row["heure_intervention"] ?? '', 0, 5) ?>',
                                    '<?= $row['service'] ?? '' ?>',
                                    '<?= $row['type_admission'] ?? '' ?>',
                                    '<?= $row['prenom_pro'] ?? '' ?>',
                                    '<?= $row['nom_pro'] ?? '' ?>'
                                )" class="btn-pdf">
                                    Télécharger
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <script>
    function genererPDF(nomPatient, prenomPatient, numSecu, datePreAdmission, heureIntervention, service, typeAdmission, prenomMedecin, nomMedecin) {
        // Vérification des données obligatoires
        if(!nomPatient || !prenomPatient || !datePreAdmission) {
            alert("Données insuffisantes pour générer le PDF");
            return;
        }

        // Créer une nouvelle instance de jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Ajouter le logo
        const logo = new Image();
        logo.src = 'img/LPFS_logo.png';
        logo.onload = function () {
            doc.addImage(logo, 'PNG', 10, 10, 30, 15);

            // Titre du document
            doc.setFontSize(16);
            doc.setFont('helvetica', 'bold');
            doc.text('Fiche de pré-admission', 105, 30, { align: 'center' });

            // Informations du patient
            doc.setFontSize(12);
            doc.setFont('helvetica', 'normal');
            doc.text(`Nom du patient: ${prenomPatient} ${nomPatient}`, 20, 50);
            if(numSecu) doc.text(`N° Sécurité Sociale: ${numSecu}`, 20, 60);
            doc.text(`Date: ${datePreAdmission}`, 20, 70);
            if(heureIntervention) doc.text(`Heure: ${heureIntervention}`, 20, 80);
            if(service) doc.text(`Service: ${service}`, 20, 90);
            if(typeAdmission) doc.text(`Type: ${typeAdmission}`, 20, 100);
            if(prenomMedecin && nomMedecin) doc.text(`Médecin: Dr. ${nomMedecin} ${prenomMedecin}`, 20, 110);

            // Sauvegarder le PDF
            const fileName = `pre_admission_${nomPatient}_${datePreAdmission.replace(/\//g, '-')}.pdf`;
            doc.save(fileName);
        };
    }
    </script>
</body>
</html>