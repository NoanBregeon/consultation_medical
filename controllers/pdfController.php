<?php
require_once __DIR__ . '/../models/BDD.php';
require_once __DIR__ . '/../lib/tcpdf/tcpdf.php';

// Utiliser la méthode statique de la classe BDD plutôt qu'une fonction
$pdo = BDD::getPDO();
$id = $_GET['id'] ?? 0;

// Récupération de l'ordonnance et du patient
$stmt = $pdo->prepare("SELECT o.Date, p.nom, p.prenom 
                     FROM ordonnance o 
                     JOIN patient p ON o.Numero_patient = p.Numero_patient 
                     WHERE o.Numero_ordonnance = ?");
$stmt->execute([$id]);
$ordonnance = $stmt->fetch();

// Récupération des médicaments - utilisez la table "detail" qui existe dans votre BDD
$stmt = $pdo->prepare("SELECT m.Designation, d.Posologie 
                     FROM detail d 
                     JOIN medicament m ON d.Code_medicament = m.Code_medicament 
                     WHERE d.Numero_ordonnance = ?");
$stmt->execute([$id]);
$medicaments = $stmt->fetchAll();

// Création du PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Vérifier si l'ordonnance existe avant de générer le PDF
if ($ordonnance) {
    $html = "<h1>Ordonnance médicale</h1>";
    $html .= "<p><strong>Patient :</strong> " . $ordonnance["prenom"] . " " . $ordonnance["nom"] . "</p>";
    $html .= "<p><strong>Date :</strong> " . $ordonnance["Date"] . "</p>";
    $html .= "<h3>Médicaments prescrits :</h3><ul>";

    foreach ($medicaments as $m) {
        $html .= "<li><strong>" . $m["Designation"] . "</strong><br>Posologie : " . $m["Posologie"] . "</li>";
    }
    $html .= "</ul>";

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output("ordonnance_$id.pdf", 'I'); // 'I' = affiche dans le navigateur
} else {
    // Gérer le cas où l'ordonnance n'existe pas
    echo "L'ordonnance demandée n'existe pas.";
}
