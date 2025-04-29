
<?php
require('logs/Logout_Secretaire.php');
require('logs/logs.php');

try {
    $sql_Pre_admission = "
        SELECT pa.*, 
            s.nom_service, 
            pr.nom_pro, pr.prenom_pro, 
            c.type_chambre,
            p.nom_patient, p.prenom_patient
        FROM Pre_admission pa
        LEFT JOIN Service s ON pa.id_service = s.id_service
        LEFT JOIN Professionnel pr ON pa.id_pro = pr.id_pro
        LEFT JOIN Chambre c ON pa.id_chambre = c.id_chambre
        LEFT JOIN Patient p ON pa.id_patient = p.num_secu";
    $result_Pre_admission = $connexion->query($sql_Pre_admission);

    $sql_Patient = "SELECT * FROM Patient";
    $result_Patient = $connexion->query($sql_Patient);

} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Secrétaire</title>
    <link rel="stylesheet" href="style/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>
    <?php require('require/navbar.php'); ?>

    <!-- Liste des Pré-admissions -->
    <div class="table-container">
        <h2>Liste des Pré-admissions</h2>
        <table class="table-style">
            <tr>
                <th>ID Pré-admission</th>
                <th>ID Patient</th>
                <th>Type Admission</th>
                <th>Date Hospitalisation</th>
                <th>Heure Intervention</th>
                <th>Professionnel</th>
                <th>Service</th>
                <th>Chambre</th>
                <th>PDF</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
            <?php while ($row = $result_Pre_admission->fetch(PDO::FETCH_ASSOC)) : 
                // Formatage des données pour le PDF
                $dateFormatted = date('d/m/Y', strtotime($row["date_hospitalisation"]));
                $heureFormatted = substr($row["heure_intervention"], 0, 5);
                $typeAdmission = $row["id_choix_pre_admission"] == 1 ? 'Chirurgie ambulatoire' : 'Hospitalisation';
            ?>
                <tr>
                    <td><?= htmlspecialchars($row["id_pre_admission"]) ?></td>
                    <td><?= htmlspecialchars($row["id_patient"]) ?></td>
                    <td><?= htmlspecialchars($typeAdmission) ?></td>
                    <td><?= htmlspecialchars($dateFormatted) ?></td>
                    <td><?= htmlspecialchars($heureFormatted) ?></td>
                    <td><?= htmlspecialchars($row["prenom_pro"] . " " . $row["nom_pro"]) ?></td>
                    <td><?= htmlspecialchars($row["nom_service"]) ?></td>
                    <td><?= htmlspecialchars($row["type_chambre"]) ?></td>
                    <td>
                        <button onclick="genererPDF(
                            '<?= $row['nom_patient'] ?? '' ?>',
                            '<?= $row['prenom_patient'] ?? '' ?>',
                            '<?= $row['id_patient'] ?? '' ?>',
                            '<?= $dateFormatted ?>',
                            '<?= $heureFormatted ?>',
                            '<?= $row['nom_service'] ?? '' ?>',
                            '<?= $typeAdmission ?>',
                            '<?= $row['prenom_pro'] ?? '' ?>',
                            '<?= $row['nom_pro'] ?? '' ?>'
                        )" class="btn-pdf">
                            Télécharger
                        </button>
                    </td>
                    <td>
                        <a href="modifier_pread.php?id=<?= $row['id_pre_admission'] ?>">
                            <img src="img/icon_modifier.png" alt="Modifier" class="icon-action">
                        </a>
                    </td>
                    <td>
                        <a href="supprimer_pread.php?id=<?= $row['id_pre_admission'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette pré-admission ?');">
                            <img src="img/icon_supprimer.png" alt="Supprimer" class="icon-action">
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- Liste des Patients -->
    <div class="table-container">
        <h2>Liste des Patients</h2>
        <table class="table-style">
            <tr>
                <th>Numéro Sécurité Sociale</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Date de naissance</th>
                <th>Adresse</th>
                <th>Code Postal</th>
                <th>Ville</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Nom d'épouse</th>
                <th>Civilité</th>
                <th>ID Pers. 1</th>
                <th>ID Pers. 2</th>
                <th>Modifier</th>
            </tr>
            <?php while ($row = $result_Patient->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row["num_secu"]) ?></td>
                    <td><?= htmlspecialchars($row["nom_patient"]) ?></td>
                    <td><?= htmlspecialchars($row["prenom_patient"]) ?></td>
                    <td><?= htmlspecialchars($row["date_naissance"]) ?></td>
                    <td><?= htmlspecialchars($row["adresse"]) ?></td>
                    <td><?= htmlspecialchars($row["CP"]) ?></td>
                    <td><?= htmlspecialchars($row["ville"]) ?></td>
                    <td><?= htmlspecialchars($row["email_patient"]) ?></td>
                    <td><?= htmlspecialchars($row["telephone_patient"]) ?></td>
                    <td><?= htmlspecialchars($row["nom_epouse"]) ?></td>
                    <td><?= htmlspecialchars($row["civilite"]) ?></td>
                    <td><?= htmlspecialchars($row["id_pers1"]) ?></td>
                    <td><?= htmlspecialchars($row["id_pers2"]) ?></td>
                    <td>
                        <a href="modifier_patient.php?num_secu=<?= $row['num_secu'] ?>">
                            <img src="img/icon_modifier.png" alt="Modifier" class="icon-action">
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

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
            doc.text(`Nom du patient: ${nomPatient} ${prenomPatient}`, 20, 50);
            if(numSecu && numSecu !== 'Inconnu') doc.text(`N° Sécurité Sociale: ${numSecu}`, 20, 60);
            doc.text(`Date: ${datePreAdmission}`, 20, 70);
            if(heureIntervention && heureIntervention !== 'Inconnu') doc.text(`Heure: ${heureIntervention}`, 20, 80);
            if(service && service !== 'Inconnu') doc.text(`Service: ${service}`, 20, 90);
            if(typeAdmission && typeAdmission !== 'Inconnu') doc.text(`Type: ${typeAdmission}`, 20, 100);
            if(prenomMedecin && nomMedecin) doc.text(`Médecin: Dr. ${nomMedecin} ${prenomMedecin}`, 20, 110);

            // Sauvegarder le PDF
            const fileName = `pre_admission_${nomPatient}_${datePreAdmission.replace(/\//g, '-')}.pdf`;
            doc.save(fileName);
        };
    }
    </script>
</body>
</html>
