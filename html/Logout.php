<?php
    session_start();

    //Vérifier si l'utilisateur est connecté 
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
        header("location: index.php");
        exit;
    }

    if ($_SESSION['id_metier'] !== 2 && $_SESSION['id_metier'] !== 1){
        header("location: index.php");
        exit;
    }

    if (isset($_GET['logout'])){
        session_unset();
        session_destroy();
        header("location: index.php");
        exit;
    }
    
    function verifierCodePostalVille($cp, $ville) {
        $url = "http://api.zippopotam.us/fr/$cp"; // URL de l'API avec le code postal
        $response = @file_get_contents($url);
    
        if ($response === FALSE) {
            return false; // API inaccessible ou code postal invalide
        }
    
        $data = json_decode($response, true);
    
        if (!empty($data['places'])) {
            foreach ($data['places'] as $place) {
                // Vérifie si une des villes correspond (en ignorant la casse)
                if (strtolower($place['place name']) === strtolower($ville)) {
                    return true;
                }
            }
        }
    
        return false;
    }