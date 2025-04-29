<?php
require('logs/Logout_Secretaire.php');

require('logs/logs.php');

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
        // Récupérer l'ID du patient sélectionné
        $selectedPatient = $_POST['existing-patient'];
        if ($selectedPatient !== "-1") {
            // Récupérer les informations du patient sélectionné
            $query = "SELECT num_secu, nom_patient, prenom_patient, date_naissance, adresse, CP, ville, email_patient, telephone_patient, nom_epouse, civilite FROM Patient WHERE num_secu = :num_secu";
            $stmt = $connexion->prepare($query);
            $stmt->bindParam(':num_secu', $selectedPatient, PDO::PARAM_STR);
            $stmt->execute();
            $patient = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($patient) {
                // Stocker les informations du patient dans la session etape1
                $_SESSION['etape1'] = [
                    'num_secu' => $patient['num_secu'],
                    'nom_patient' => $patient['nom_patient'],
                    'prenom_patient' => $patient['prenom_patient'],
                    'date_naissance' => $patient['date_naissance'],
                    'adresse' => $patient['adresse'],
                    'CP' => $patient['CP'],
                    'ville' => $patient['ville'],
                    'email_patient' => $patient['email_patient'],
                    'telephone_patient' => $patient['telephone_patient'],
                    'nom_epouse' => $patient['nom_epouse'],
                    'civilite' => $patient['civilite']
                ];

                // Rediriger vers Pre_admission_Hospitalisation_Patient.php
                header('Location: Pre_admission_Hospitalisation_Patient.php');
                exit();
            } else {
                $erreur = "Patient non trouvé.";
            }
        } else {
            $erreur = "Veuillez sélectionner un patient existant.";
        }
    }
}

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
    <?php require('require/navbar.php'); ?>
    
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