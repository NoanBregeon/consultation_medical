<?php
require_once '../models/BDD.php';
require_once '../models/Ordonnance.php';
require_once '../lib/TCPDF/tcpdf.php'; // Chemin corrigé vers votre bibliothèque TCPDF

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../views/impression.php");
    exit;
}

$ordonnance_id = $_GET['id'];

try {
    // Récupérer les données de l'ordonnance
    $pdo = BDD::getPDO();
    
    // Récupérer l'ordonnance et les infos du patient
    $stmt = $pdo->prepare("
        SELECT o.*, p.nom, p.prenom, p.ville, p.adresse, p.code_postal
        FROM ordonnance o
        JOIN patient p ON o.Numero_patient = p.Numero_patient
        WHERE o.Numero_ordonnance = :id
    ");
    $stmt->execute(['id' => $ordonnance_id]);
    $ordonnance = $stmt->fetch(PDO::FETCH_ASSOC);       
    
    if (!$ordonnance) {
        throw new Exception("Ordonnance non trouvée");
    }
    
    // Récupérer les médicaments de l'ordonnance
    $stmt = $pdo->prepare("
        SELECT m.Designation, m.Preparation, m.Laboratoire, d.Posologie
        FROM detail d
        JOIN medicament m ON d.Code_medicament = m.Code_medicament
        WHERE d.Numero_ordonnance = :id
    ");
    $stmt->execute(['id' => $ordonnance_id]);
    $medicaments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Créer un nouveau document PDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    
    // Configuration du document
    $pdf->SetCreator('Gestion Médicale');
    $pdf->SetAuthor('Dr Médecin');
    $pdf->SetTitle('Ordonnance médicale');
    $pdf->SetSubject('Ordonnance');
    $pdf->SetKeywords('Ordonnance, Médicament, Patient');
    
    // Supprimer les en-têtes et pieds de page par défaut
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
    // Ajouter une page
    $pdf->AddPage();
    
    // En-tête
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'ORDONNANCE MÉDICALE', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Dr Médecin - Cabinet Médical', 0, 1, 'C');
    $pdf->Cell(0, 5, '123 Avenue de la Santé, 75000 Paris', 0, 1, 'C');
    $pdf->Cell(0, 5, 'Tél: 01.23.45.67.89', 0, 1, 'C');
    
    // Ligne de séparation
    $pdf->Line(10, $pdf->GetY() + 5, 200, $pdf->GetY() + 5);
    $pdf->Ln(10);
    
    // Informations patient
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'PATIENT:', 0, 1);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 5, 'Nom: ' . $ordonnance['nom'] . ' ' . $ordonnance['prenom'], 0, 1);
    if (!empty($ordonnance['date_naissance'])) {
        $pdf->Cell(0, 5, 'Date de naissance: ' . $ordonnance['date_naissance'], 0, 1);
    }
    if (!empty($ordonnance['adresse'])) {
        $pdf->Cell(0, 5, 'Adresse: ' . $ordonnance['adresse'] . ', ' . $ordonnance['ville'], 0, 1);
    }
    $pdf->Ln(5);
    
    // Date de l'ordonnance
    $pdf->Cell(0, 5, 'Date: ' . date('d/m/Y', strtotime($ordonnance['Date'])), 0, 1);
    $pdf->Ln(10);
    
    // Médicaments prescrits
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'PRESCRIPTION:', 0, 1);
    $pdf->SetFont('helvetica', '', 12);
    
    // Afficher chaque médicament avec sa posologie
    foreach ($medicaments as $med) {
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 10, $med['Preparation'] . ' - ' . $med['Designation'] . ' (' . $med['Laboratoire'] . ')', 0, 1);
        $pdf->SetFont('helvetica', 'I', 10);
        $pdf->Cell(0, 5, 'Posologie: ' . $med['Posologie'], 0, 1);
        $pdf->Ln(5);
    }
    
    // Pied de page avec signature
    $pdf->Ln(20);
    $pdf->SetFont('helvetica', 'I', 10);
    $pdf->Cell(100, 5, "Signature et cachet du médecin:", 0, 0);
    
    // Générer le PDF et l'envoyer au navigateur
    $pdf->Output('ordonnance_' . $ordonnance_id . '.pdf', 'I');
    
} catch (Exception $e) {
    echo "Erreur lors de la génération du PDF: " . $e->getMessage();
}
