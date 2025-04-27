<?php
require('logs/Logout_Secretaire.php');

require('logs/logs.php');

$donneesPatient = []; // Initialiser un tableau pour stocker les données

try {
    // Vérifier que nous avons toutes les données nécessaires
    if (isset($_SESSION['etape1']['num_secu']) && isset($_SESSION['form_data'])) {
        $num_secu = $_SESSION['etape1']['num_secu'];
        $form_data = $_SESSION['form_data'];

        // Requête SQL pour récupérer les données du patient
        $query = "
            SELECT 
                p.num_secu, 
                p.nom_patient, 
                p.prenom_patient, 
                pa.date_hospitalisation, 
                pa.heure_intervention, 
                CONCAT(pr.nom_pro, ' ', pr.prenom_pro) AS medecin, 
                s.nom_service AS service
            FROM 
                Patient p
            INNER JOIN 
                Pre_admission pa ON pa.id_patient = p.num_secu
            INNER JOIN 
                Professionnel pr ON pr.id_pro = pa.id_pro
            INNER JOIN 
                Service s ON s.id_service = pa.id_service
            WHERE 
                pa.id_patient = :num_secu
                AND pa.id_choix_pre_admission = :id_choix_pre_admission
                AND pa.date_hospitalisation = :date_hospitalisation
                AND pa.heure_intervention = :heure_intervention
                AND pa.id_pro = :id_pro
                AND pa.id_service = :id_service
                AND pa.id_chambre = :id_chambre
            ORDER BY 
                pa.date_hospitalisation DESC 
            LIMIT 1
        ";

        // Préparation et exécution de la requête
        $stmt = $connexion->prepare($query);
        $stmt->bindParam(':num_secu', $num_secu, PDO::PARAM_STR);
        $stmt->bindParam(':id_choix_pre_admission', $form_data['pre_admission'], PDO::PARAM_INT);
        $stmt->bindParam(':date_hospitalisation', $form_data['date_hospitalisation'], PDO::PARAM_STR);
        $stmt->bindParam(':heure_intervention', $form_data['heure_intervention'], PDO::PARAM_STR);
        $stmt->bindParam(':id_pro', $form_data['medecin'], PDO::PARAM_INT);
        $stmt->bindParam(':id_service', $form_data['service'], PDO::PARAM_INT);
        $stmt->bindParam(':id_chambre', $form_data['chambre_particuliere'], PDO::PARAM_INT);
        $stmt->execute();

        $donneesPatient = $stmt->fetch(PDO::FETCH_ASSOC);

        // Formater la date d'hospitalisation en français
        $datePreAdmission = isset($donneesPatient['date_hospitalisation']) 
            ? date('d/m/Y', strtotime($donneesPatient['date_hospitalisation'])) 
            : 'Inconnu';
    } else {
        $erreur = "Données de session incomplètes. Veuillez recommencer le processus de pré-admission.";
    }
} catch (PDOException $e) {
    $erreur = "Erreur de connexion : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pré admission | Fin</title>
    <link rel="stylesheet" href="style/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>
    <?php require('require/navbar.php'); ?>

    <div class="container">
        <?php if (!empty($erreur)): ?>
            <div class="error-message">
                <p><?php echo htmlspecialchars($erreur); ?></p>
            </div>
        <?php endif; ?>

        <h1>Fin de pré-admission</h1>
        <p>Merci d'avoir complété la pré-admission. Veuillez choisir une action ci-dessous :</p>

        <div class="button-container">
            <a href="Pre_admission_Choix.php">
                <button class="primary">Nouvelle pré-admission</button>
            </a>
            <button onclick="genererPDF()" class="secondary">Générer un PDF</button>
            <form method="POST" action="" style="display: inline;">
                <button type="submit" name="deconnexion" class="danger">Se déconnecter</button>
            </form>
        </div>
    </div>

    <script>
    function genererPDF() {
        // Récupérer les données depuis PHP
        const nomPatient = "<?php echo $donneesPatient['nom_patient'] ?? 'Inconnu'; ?>";
        const prenomPatient = "<?php echo $donneesPatient['prenom_patient'] ?? 'Inconnu'; ?>";
        const numSecu = "<?php echo $donneesPatient['num_secu'] ?? 'Inconnu'; ?>";
        const datePreAdmission = "<?php echo $datePreAdmission ?? 'Inconnu'; ?>";
        const heureIntervention = "<?php echo $donneesPatient['heure_intervention'] ?? 'Inconnu'; ?>";
        const service = "<?php echo $donneesPatient['service'] ?? 'Inconnu'; ?>";
        const medecin = "<?php echo $donneesPatient['medecin'] ?? 'Inconnu'; ?>";
        const typeAdmission = "<?php echo isset($form_data['pre_admission']) ? ($form_data['pre_admission'] == 1 ? 'Chirurgie ambulatoire' : 'Hospitalisation') : 'Inconnu'; ?>";

        // Séparer le nom et prénom du médecin
        const medecinParts = medecin.split(' ');
        const prenomMedecin = medecinParts.length > 0 ? medecinParts[0] : '';
        const nomMedecin = medecinParts.length > 1 ? medecinParts.slice(1).join(' ') : '';

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
            if(numSecu && numSecu !== 'Inconnu') doc.text(`N° Sécurité Sociale: ${numSecu}`, 20, 60);
            doc.text(`Date: ${datePreAdmission}`, 20, 70);
            if(heureIntervention && heureIntervention !== 'Inconnu') doc.text(`Heure: ${heureIntervention}`, 20, 80);
            if(service && service !== 'Inconnu') doc.text(`Service: ${service}`, 20, 90);
            if(typeAdmission && typeAdmission !== 'Inconnu') doc.text(`Type: ${typeAdmission}`, 20, 100);
            if(prenomMedecin && nomMedecin) doc.text(`Médecin: Dr. ${prenomMedecin} ${nomMedecin}`, 20, 110);

            // Sauvegarder le PDF
            const fileName = `pre_admission_${nomPatient}_${datePreAdmission.replace(/\//g, '-')}.pdf`;
            doc.save(fileName);
        };
    }

    // Détecter lorsque l'utilisateur quitte la page
    window.addEventListener('beforeunload', function() {
        // Envoyer une requête au serveur pour supprimer la session etape1
        fetch('unset_etape1.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ action: 'unset_etape1' })
        });
    });
    </script>
</body>
</html>