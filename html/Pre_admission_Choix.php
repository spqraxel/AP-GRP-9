<?php
require('Logout.php');
session_start();

require('logs.php');

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$nomBDD", $utilisateur, $motdepasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $erreur = "Erreur de connexion : " . $e->getMessage();
    die($erreur);
}

// Récupérer la liste des patients existants
$query_patients = $connexion->query("SELECT num_secu, nom_patient, prenom_patient FROM Patient");
$patients = $query_patients->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nouveau_patient'])) {
        // Rediriger vers la page Pre_admission_Info.php pour un nouveau patient
        header('Location: Pre_admission_Info.php');
        exit();
    } elseif (isset($_POST['patient_existant'])) {
        // Rediriger vers Pre_admission_Hospitalisation.php avec l'ID du patient sélectionné
        $selectedPatient = $_POST['existing-patient'];
        if ($selectedPatient !== "-1") {
            header('Location: Pre_admission_Hospitalisation_Patient.php?patient_id=' . $selectedPatient);
            exit();
        } else {
            $erreur = "Veuillez sélectionner un patient existant.";
        }
    }
}

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pré admission | Choix du patient</title>
    <link rel="stylesheet" href="style/style.css">
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
    <div class="container-pre-admission">
        <h6>CHOIX DU PATIENT</h6>
        <br>

        <!-- Affichage des erreurs -->
        <?php if (!empty($erreur)): ?>
            <div class="error-message">
                <p><?php echo htmlspecialchars($erreur); ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="patient-choice">
                <button type="submit" name="nouveau_patient" class="button-choice">Nouveau patient</button>
                <button type="button" id="existing-patient-btn" class="button-choice">Patient existant</button>
            </div>

            <div id="existing-patient-form" class="hidden">
                <label for="existing-patient">Patient existant :<span class= "requis"> *</span></label>
                <br>
                <select id="existing-patient" name="existing-patient" required>
                    <option value="-1" selected disabled hidden>choix</option>
                    <?php foreach ($patients as $patient): ?>
                        <option value="<?php echo $patient['num_secu']; ?>">
                            <?php echo htmlspecialchars($patient['nom_patient'] . ' ' . $patient['prenom_patient']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br><br>

                <div class="navigation">
                    <button type="submit" name="patient_existant" class="button-next">Sélectionner</button>
                </div>
            </div>
        </form>
    </div>

    <script>
    // Afficher le menu déroulant pour les patients existants
    document.getElementById('existing-patient-btn').addEventListener('click', function() {
        document.getElementById('existing-patient-form').classList.remove('hidden');
    });
    </script>
</body>
</html>