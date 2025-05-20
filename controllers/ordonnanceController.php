<?php
require_once '../models/Medicament.php';
require_once '../models/Patient.php';
require_once '../models/Ordonnance.php';
require_once '../models/BDD.php';

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Récupération des données du formulaire
        $patient_id = $_POST['patient'];
        $date = $_POST['date'];
        $medicaments = $_POST['medicament'] ?? [];
        $posologies = $_POST['posologie'] ?? [];
        
        // Enregistrer l'ordonnance dans la base de données
        $ordonnance_id = Ordonnance::create([
            'Numero_patient' => $patient_id,
            'Date' => $date
        ]);
        
        // Enregistrer les médicaments et posologies
        if ($ordonnance_id && !empty($medicaments)) {
            for ($i = 0; $i < count($medicaments); $i++) {
                if (!empty($medicaments[$i]) && !empty($posologies[$i])) {
                    Ordonnance::ajouterMedicament(
                        $ordonnance_id,
                        $medicaments[$i],
                        $posologies[$i]
                    );
                }
            }
        }
        
        // Rediriger vers la génération PDF
        header("Location: pdfController.php?id=" . $ordonnance_id);
        exit;
    } catch (Exception $e) {
        // En cas d'erreur, afficher un message
        header("Location: ../views/ordonnance.php?error=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    // Si accès direct au contrôleur sans soumission de formulaire
    header("Location: ../views/ordonnance.php");
    exit;
}
