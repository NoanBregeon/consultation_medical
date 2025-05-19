<?php
require_once __DIR__ . '/../models/Patient.php';

// Gestion des actions POST (création de patient)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    Patient::create($_POST);
    header("Location: ../views/patient.php");
    exit;
}

// Gestion des actions DELETE (suppression de patient)
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $response = Patient::delete($_GET['id']);
    
    // Rediriger avec un message de notification
    $redirectUrl = "../views/patient.php?message=" . urlencode($response['message']);
    if ($response['success']) {
        $redirectUrl .= "&status=success";
    } else {
        $redirectUrl .= "&status=error";
    }
    
    header("Location: $redirectUrl");
    exit;
}

// Récupération de tous les patients pour l'affichage
$patients = Patient::getAll();
require_once __DIR__ . '/../views/patient.php';
