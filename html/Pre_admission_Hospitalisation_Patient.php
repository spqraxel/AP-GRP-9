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

// Récupérer la liste des services
$query_services = $connexion->query("SELECT id_service, nom_service FROM Service");
$services = $query_services->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['form_data'] = $_POST;

    // Récupération et nettoyage des données
    $pre_admission = htmlspecialchars($_POST['pre_admission']);
    $date_hospitalisation = htmlspecialchars($_POST['date_hospitalisation']);
    $heure_intervention = htmlspecialchars($_POST['heure_intervention']);
    $service = htmlspecialchars($_POST['service']);
    $medecin = htmlspecialchars($_POST['medecin']);
    $chambre_particuliere = htmlspecialchars($_POST['chambre_particuliere']);

    // Vérification des champs obligatoires
    if (empty($pre_admission) || $pre_admission === "-1") {
        $erreur = "Veuillez sélectionner le type de pré-admission.";
    } elseif (empty($date_hospitalisation)) {
        $erreur = "La date d'hospitalisation est obligatoire.";
    } elseif (empty($heure_intervention)) {
        $erreur = "L'heure de l'intervention est obligatoire.";
    } elseif (empty($service) || $service === "-1") {
        $erreur = "Veuillez sélectionner un service.";
    } elseif (empty($medecin) || $medecin === "-1") {
        $erreur = "Veuillez sélectionner un médecin.";
    } elseif (empty($chambre_particuliere) || $chambre_particuliere === "-1") {
        $erreur = "Veuillez sélectionner un type de chambre.";
    } else {
        try {
            // Préparer la requête SQL
            $stmt = $connexion->prepare("
                INSERT INTO Pre_admission (
                    id_patient, id_choix_pre_admission, date_hospitalisation, heure_intervention, id_pro, id_service, id_chambre
                ) VALUES (
                    105065917022066, :id_choix_pre_admission, :date_hospitalisation, :heure_intervention, :id_pro, :id_service, :id_chambre
                )
            ");

            // Lier les paramètres
            $stmt->bindParam(':id_choix_pre_admission', $pre_admission);
            $stmt->bindParam(':date_hospitalisation', $date_hospitalisation);
            $stmt->bindParam(':heure_intervention', $heure_intervention);
            $stmt->bindParam(':id_pro', $medecin); // ID du médecin
            $stmt->bindParam(':id_service', $service); // ID du service
            $stmt->bindParam(':id_chambre', $chambre_particuliere);

            // Exécuter la requête
            $stmt->execute();

            // Nettoyer les données de session après l'insertion
            unset($_SESSION['etape1']);
            unset($_SESSION['etape2']);
            unset($_SESSION['etape3']);
            unset($_SESSION['form_data']);

            header('Location: Pre_admission_Fin.php');
            exit();
        } catch (PDOException $e) {
            $erreur = "Erreur lors de l'insertion : " . $e->getMessage();
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
    <title>Pré admission</title>
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
        <!-- Formulaire -->
        <form method="POST" action="">
            <h6>Pré-admission</h6>
            <br>

            <!-- Affichage des erreurs -->
            <?php if (!empty($erreur)): ?>
                <div class="error-message">
                    <p><?php echo htmlspecialchars($erreur); ?></p>
                </div>
            <?php endif; ?>

            <label for="service">Service :<span class= "requis"> *</span></label>
            <br>
            <select id="service" name="service" required>
                <option value="-1" <?php echo (!isset($_POST['service']) || $_POST['service'] === '-1' ? 'selected' : ''); ?>>choix</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?php echo $service['id_service']; ?>" <?php echo (isset($_POST['service']) && $_POST['service'] == $service['id_service'] ? 'selected' : ''); ?>>
                        <?php echo htmlspecialchars($service['nom_service']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br><br>

            <label for="medecin">Nom du médecin :<span class= "requis"> *</span></label>
            <br>
            <select id="medecin" name="medecin" required>
                <option value="-1" selected disabled hidden>choix</option>
            </select>
            <br><br>

            <label for="pre_admission">Pré-admission pour :<span class= "requis"> *</span></label>
            <br>
            <select id="pre_admission" name="pre_admission" required>
                <option value="-1" <?php echo (isset($_POST['pre_admission']) && $_POST['pre_admission'] === 'choix' ? 'selected' : ''); ?>>choix</option>
                <option value="1" <?php echo (isset($_POST['pre_admission']) && $_POST['pre_admission'] === 'Ambulatoire' ? 'selected' : ''); ?>>Ambulatoire chirurgie</option>
                <option value="2" <?php echo (isset($_POST['pre_admission']) && $_POST['pre_admission'] === 'Hospitalisation' ? 'selected' : ''); ?>>Hospitalisation (au moins une nuit)</option>  
            </select>
            <br><br>

            <label for="date_hospitalisation">Date d'hospitalisation :<span class= "requis"> *</span></label>
            <br>
            <input type="date" id="date_hospitalisation" name="date_hospitalisation" value="<?php echo htmlspecialchars($_SESSION['form_data']['date_hospitalisation'] ?? ''); ?>" required>
            <br><br>

            <label for="heure_intervention">Heure de l'intervention :<span class= "requis"> *</span></label>
            <br>
            <input type="time" id="heure_intervention" name="heure_intervention" value="<?php echo htmlspecialchars($_SESSION['form_data']['heure_intervention'] ?? ''); ?>" required>
            <br><br>

            <label for="chambre_particuliere">Chambre particulière ? :<span class= "requis"> *</span></label>
            <br>
            <select id="chambre_particuliere" name="chambre_particuliere" required>
                <option value="-1" <?php echo (isset($_POST['chambre_particuliere']) && $_POST['chambre_particuliere'] === '-1' ? 'selected' : ''); ?>>choix</option>
                <option value="1" <?php echo (isset($_POST['chambre_particuliere']) && $_POST['chambre_particuliere'] === '1' ? 'selected' : ''); ?>>chambre simple</option>
                <option value="2" <?php echo (isset($_POST['chambre_particuliere']) && $_POST['chambre_particuliere'] === '2' ? 'selected' : ''); ?>>chambre double</option>    
            </select>
            <br><br>

            <div class="navigation">
                <button type="button" onclick="window.location.href='Pre_admission_Choix.php'" class="button-next">Retour</button>
                <button type="submit" class="button-next">Suivant</button>
            </div>
        </form>
    </div>

    <script>
    document.getElementById('service').addEventListener('change', function() {
        var serviceId = this.value;

        // Vider la liste des médecins
        var medecinSelect = document.getElementById('medecin');
        medecinSelect.innerHTML = '<option value="-1" selected disabled hidden>choix</option>';

        if (serviceId !== "-1") {
            // Faire une requête AJAX pour récupérer les médecins du service sélectionné
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_medecins.php?service_id=' + serviceId, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var medecins = JSON.parse(xhr.responseText);
                    medecins.forEach(function(medecin) {
                        var option = document.createElement('option');
                        option.value = medecin.id_pro;
                        option.textContent = medecin.nom_pro + ' ' + medecin.prenom_pro;
                        medecinSelect.appendChild(option);
                    });
                } else {
                    console.error("Erreur lors de la requête AJAX :", xhr.status, xhr.statusText);
                }
            };
            xhr.onerror = function() {
                console.error("Erreur réseau lors de la requête AJAX.");
            };
            xhr.send();
        }
    });
    </script>
</body>
</html>