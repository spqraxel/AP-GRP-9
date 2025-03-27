<?php
require('logs/Logout_Secretaire.php');
session_start();

require('logs/logs.php');

$donneesPatient = []; // Initialiser un tableau pour stocker les données

try {
    // Récupérer le numéro de sécurité sociale depuis la session etape1
    $num_secu = $_SESSION['etape1']['num_secu'] ?? null;

    if ($num_secu) {
        // Requête SQL pour récupérer les données du patient
        $query = "
            SELECT 
                Patient.num_secu, 
                Patient.nom_patient, 
                Patient.prenom_patient, 
                Pre_admission.date_hospitalisation, 
                Pre_admission.heure_intervention, 
                CONCAT(Professionnel.nom_pro, ' ', Professionnel.prenom_pro) AS medecin, 
                Service.nom_service AS service
            FROM 
                Patient
            INNER JOIN 
                Pre_admission ON Pre_admission.id_patient = Patient.num_secu
            INNER JOIN 
                Professionnel ON Professionnel.id_pro = Pre_admission.id_pro
            INNER JOIN 
                Service ON Service.id_service = Pre_admission.id_service
            WHERE 
                Pre_admission.id_patient = :num_secu
            ORDER BY 
                Pre_admission.date_hospitalisation DESC 
            LIMIT 1
        ";

        // Préparation et exécution de la requête
        $stmt = $connexion->prepare($query);
        $stmt->bindParam(':num_secu', $num_secu, PDO::PARAM_STR);
        $stmt->execute();

        $donneesPatient = $stmt->fetch(PDO::FETCH_ASSOC);

        // Formater la date d'hospitalisation en français
        $datePreAdmission = isset($donneesPatient['date_hospitalisation']) 
            ? date('d/m/Y', strtotime($donneesPatient['date_hospitalisation'])) 
            : 'Inconnu';
    } else {
        $erreur = "Aucun numéro de sécurité sociale trouvé dans la session.";
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
    <header class="navbar">
        <div class="logo-container">
            <img src="img/LPFS_logo.png" alt="Logo" class="logo">
        </div>
        <div class="page">
            <a href="admin.php">Accueil</a>
            <a href="Pre_admission_Choix.php">Pré-admission</a>
            <a href="?logout=true">Se déconnecter</a>
        </div>
    </header>

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

        // Créer une nouvelle instance de jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Ajouter le logo
        const logo = new Image();
        logo.src = 'img/LPFS_logo.png';
        logo.onload = function () {
            doc.addImage(logo, 'PNG', 10, 10, 30, 15); // Position et taille du logo

            // Titre du document
            doc.setFontSize(16);
            doc.setFont('helvetica', 'bold');
            doc.text('Pré-admission du patient', 105, 30, { align: 'center' });

            // Informations du patient
            doc.setFontSize(12);
            doc.setFont('helvetica', 'normal');
            doc.text(`Nom du patient : ${nomPatient} ${prenomPatient}`, 10, 50);
            doc.text(`Numéro de sécurité sociale : ${numSecu}`, 10, 60);
            doc.text(`Date de pré-admission : ${datePreAdmission}`, 10, 70);
            doc.text(`Heure de l'intervention : ${heureIntervention}`, 10, 80);
            doc.text(`Service : ${service}`, 10, 90);
            doc.text(`Médecin : ${medecin}`, 10, 100);

            // Sauvegarder le PDF
            doc.save('pre_admission.pdf');
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