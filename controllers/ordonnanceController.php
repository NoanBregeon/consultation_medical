<?php
require_once '../models/Medicament.php';
require_once '../models/Patient.php';
require_once '../models/Ordonnance.php'; // Ajout de cette ligne
require_once '../models/BDD.php';

// Récupération des données
try {
    // Utiliser la pagination avec des valeurs très grandes pour récupérer tous les médicaments
    $medicaments = Medicament::getAll(10000, 0); // Récupère jusqu'à 10000 médicaments à partir de l'index 0
    $patients = Patient::getAll();
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $data = [];
        foreach ($_POST["medicament"] as $i => $code) {
            $data[] = ["code" => $code, "posologie" => $_POST["posologie"][$i] ?? ""];
        }
        Ordonnance::create($_POST["patient"], $data);
        header("Location: ../views/ordonnance.php");
        exit;
    }
} catch (Exception $e) {
    // Gestion des erreurs...
}

require_once __DIR__ . '/../views/ordonnance.php';
