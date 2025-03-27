<?php
require('logs/Logout_Secretaire.php');
session_start();

require('logs/logs.php');

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$nomBDD", $utilisateur, $motdepasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $erreur = "Erreur de connexion : " . $e->getMessage();
    die($erreur);
}

function verifierCodePostalVille($cp, $ville) {
    $url = "https://geo.api.gouv.fr/communes?codePostal=" . urlencode($cp);
    $response = @file_get_contents($url);

    if ($response === FALSE) {
        return false;
    }

    $data = json_decode($response, true);

    if (is_array($data) && !empty($data)) {
        foreach ($data as $commune) {
            if (isset($commune['nom']) && strtolower($commune['nom']) === strtolower($ville)) {
                return true;
            }
        }
    }

    return false;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['form_data'] = $_POST;

    // Récupération et nettoyage des données
    $num_secu = htmlspecialchars($_POST['num_secu']);
    $civilite = htmlspecialchars($_POST['civilite']);
    $nom_patient = htmlspecialchars($_POST['nom_patient']);
    $nom_epouse = htmlspecialchars($_POST['nom_epouse']);
    $prenom_patient = htmlspecialchars($_POST['prenom_patient']);
    $date_naissance = htmlspecialchars($_POST['date_naissance']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $CP = htmlspecialchars($_POST['CP']);
    $ville = htmlspecialchars($_POST['ville']);
    $email_patient = htmlspecialchars($_POST['email_patient']);
    $telephone_patient = htmlspecialchars($_POST['telephone_patient']);

    $limiteAgeMax = strtotime('-120 years');

    // Vérification des champs obligatoires
    if (empty($num_secu) || strlen($num_secu) !== 15 || !ctype_digit($num_secu)) {
        $erreur = "Le numéro de sécurité sociale doit contenir exactement 15 chiffres.";
    } elseif (empty($nom_patient)) {
        $erreur = "Le prénom est obligatoire.";
    } elseif (empty($prenom_patient)) {
        $erreur = "Le nom est obligatoire.";
    } elseif (empty($date_naissance) || strtotime($date_naissance) > time() || strtotime($date_naissance) < $limiteAgeMax) {
        $erreur = "La date de naissance n'est pas valide.";
    } elseif (!filter_var($email_patient, FILTER_VALIDATE_EMAIL)) {
        $erreur = "L'adresse e-mail n'est pas valide.";
    } elseif (empty($telephone_patient) || strlen($telephone_patient) !== 10 || !ctype_digit($telephone_patient)) {
        $erreur = "Le numéro de téléphone doit contenir 10 chiffres.";
    } elseif (empty($CP) || strlen($CP) !== 5 || !ctype_digit($CP)) {
        $erreur = "Le code postal doit contenir 5 chiffres.";
    } elseif ($civilite !== "0" && $civilite !== "1") {
        $erreur = "Le champ civilité est obligatoire.";
    } elseif (empty($adresse)) {
        $erreur = "Le champ adresse est obligatoire.";
    } elseif (empty($ville)) {
        $erreur = "Le champ ville est obligatoire.";
    } elseif (!verifierCodePostalVille($CP, $ville)) {
        $erreur = "Le code postal ne correspond pas à la ville.";
    } else {
        // Validation de la clé de contrôle du numéro de sécurité sociale
        $cle = substr($num_secu, -2);
        $numSecuSansCle = substr($num_secu, 0, -2);
        $cleCalculee = 97 - ($numSecuSansCle % 97);

        if ($cle != $cleCalculee) {
            $erreur = "La clé de contrôle du numéro de sécurité sociale est incorrecte.";
        } else {
            // Vérification de la correspondance entre civilité et numéro de sécurité sociale
            $premierChiffre = substr($num_secu, 0, 1);
            if (($civilite == "0" && $premierChiffre != "1") || ($civilite == "1" && $premierChiffre != "2")) {
                $erreur = "La civilité sélectionnée ne correspond pas au numéro de sécurité sociale.";
            } else {
                // Vérification de la correspondance date de naissance / NIR
                $anneeNaissanceNIR = substr($num_secu, 1, 2); // Année dans le NIR
                $moisNaissanceNIR = substr($num_secu, 3, 2);  // Mois dans le NIR

                // Extraction de la date de naissance
                $dateNaissance = new DateTime($date_naissance);
                $anneeNaissance = $dateNaissance->format('y'); // Année sur 2 chiffres
                $moisNaissance = $dateNaissance->format('m'); // Mois sur 2 chiffres

                // Vérification mois valide (01-12)
                if ($moisNaissanceNIR < 1 || $moisNaissanceNIR > 12) {
                    $erreur = "Le mois de naissance dans le NIR est invalide.";
                } 
                // Vérification année (avec gestion post-2000)
                elseif (!in_array($anneeNaissanceNIR, [$anneeNaissance, $anneeNaissance + 20])) {
                    $erreur = "L'année de naissance ne correspond pas au numéro de sécurité sociale.";
                } 
                // Vérification mois
                elseif ($moisNaissanceNIR != $moisNaissance) {
                    $erreur = "Le mois de naissance ne correspond pas au numéro de sécurité sociale.";
                } else {
                    // Stocker les données dans la session
                    $_SESSION['etape1'] = [
                        'num_secu' => $num_secu,
                        'civilite' => $civilite,
                        'nom_patient' => $nom_patient,
                        'nom_epouse' => $nom_epouse,
                        'prenom_patient' => $prenom_patient,
                        'date_naissance' => $date_naissance,
                        'adresse' => $adresse,
                        'CP' => $CP,
                        'ville' => $ville,
                        'email_patient' => $email_patient,
                        'telephone_patient' => $telephone_patient,
                    ];

                    // Rediriger vers l'étape 2
                    header('Location: Pre_admission_Inscription.php');
                    exit();
                }
            }
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
    <title>Pré admission | Etape 1 sur 6</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php require('require/navbar.php'); ?>

    <div class="container-pre-admission">
        <!-- Formulaire -->
        <form method="POST" action="Pre_admission_Info.php">
            <h6>INFORMATIONS CONCERNANT LE PATIENT <br>Etape 1 sur 6</h6>
            <br>

            <!-- Affichage des erreurs -->
            <?php if (!empty($erreur)): ?>
                <div class="error-message">
                    <p><?php echo htmlspecialchars($erreur); ?></p>
                </div>
            <?php endif; ?>

            <label for="num_secu">Numéro de sécurité sociale :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="num_secu" name="num_secu" value="<?php echo htmlspecialchars($_SESSION['form_data']['num_secu'] ?? ''); ?>" required>
            <br><br>

            <label for="civilite">Civilité:<span class= "requis">*</span></label>
            <br>
            <select id="civilite" name="civilite" required>
                <option value="-1" selected disabled hidden>choix</option>
                <option value="1" <?php echo (isset($_SESSION['form_data']['civilite']) && $_SESSION['form_data']['civilite'] == '1' ? 'selected' : ''); ?>>Femme</option>
                <option value="0" <?php echo (isset($_SESSION['form_data']['civilite']) && $_SESSION['form_data']['civilite'] == '0' ? 'selected' : ''); ?>>Homme</option>      
            </select>
            <br><br>

            <label for="nom_patient">Nom de naissance :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="nom_patient" name="nom_patient" value="<?php echo htmlspecialchars($_SESSION['form_data']['nom_patient'] ?? ''); ?>" required>
            <br><br>

            <label for="nom_epouse">Nom d'épouse :</label>
            <br>
            <input type="text" id="nom_epouse" name="nom_epouse" value="<?php echo htmlspecialchars($_SESSION['form_data']['nom_epouse'] ?? ''); ?>">
            <br><br> 

            <label for="prenom_patient">Prénom :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="prenom_patient" name="prenom_patient" value="<?php echo htmlspecialchars($_SESSION['form_data']['prenom_patient'] ?? ''); ?>" required>
            <br><br>

            <label for="date_naissance">Date de naissance :<span class= "requis"> *</span></label>
            <br>
            <input type="date" id="date_naissance" name="date_naissance" value="<?php echo htmlspecialchars($_SESSION['form_data']['date_naissance'] ?? ''); ?>" required>
            <br><br>

            <label for="adresse">Adresse :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="adresse" name="adresse" value="<?php echo htmlspecialchars($_SESSION['form_data']['adresse'] ?? ''); ?>" required>
            <br><br>

            <label for="CP">CP :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="CP" name="CP" value="<?php echo htmlspecialchars($_SESSION['form_data']['CP'] ?? ''); ?>" required>
            <br><br>

            <label for="ville">Ville :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="ville" name="ville" value="<?php echo htmlspecialchars($_SESSION['form_data']['ville'] ?? ''); ?>" required>
            <br><br>

            <label for="email_patient">Email :<span class= "requis"> *</span></label>
            <br>
            <input type="text" id="email_patient" name="email_patient" value="<?php echo htmlspecialchars($_SESSION['form_data']['email_patient'] ?? ''); ?>" required>
            <br><br>

            <label for="telephone_patient">Téléphone :<span class= "requis">*</span></label>
            <br>
            <input type="text" id="telephone_patient" name="telephone_patient" value="<?php echo htmlspecialchars($_SESSION['form_data']['telephone_patient'] ?? ''); ?>" required>
            <br><br>

            <div class="navigation">
                <button type="submit" class="button-next">Suivant</button>
            </div>
        </form>
    </div>
</body>
</html>