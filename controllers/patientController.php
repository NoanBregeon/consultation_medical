<?php
require_once __DIR__ . '/../models/Patient.php';

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- AJOUT POUR LA RECHERCHE AJAX SELECT2 ---
if (isset($_GET['action']) && $_GET['action'] === 'search' && isset($_GET['q'])) {
    header('Content-Type: application/json');
    try {
        $searchTerm = trim($_GET['q']);
        if (strlen($searchTerm) < 3) {
            echo json_encode([]);
            exit;
        }
        
        $patients = Patient::search($searchTerm, 50, 0);
        $results = array_map(function($p) {
            return [
                'id' => $p['Numero_patient'],
                'text' => $p['nom'] . ' ' . $p['prenom'] . ' - ' . $p['ville']
            ];
        }, $patients);
        
        echo json_encode($results);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
// --- FIN AJOUT ---

// Gestion des actions POST (création de patient)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    Patient::create($_POST);
    header("Location: ../views/impression.php?message=Patient ajouté avec succès&status=success");
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
