<?php
    session_start();

    //Vérifier si l'utilisateur est connecté 
    if (!isset($_SESSION['loggedin']) || !isset($_SESSION['id_pro']) || $_SESSION['loggedin'] !== true){
        header("location: index.php");
        exit;
    }

    if (isset($_GET['logout'])){
        session_unset();
        session_destroy();
        header("location: index.php");
        exit;
    }