<?php
require_once '../models/BDD.php';

// Initialisation
$pdo = BDD::getPDO();
$patient_selectionne = null;
$ordonnances = [];

// Si un patient est sélectionné
if (isset($_GET['patient_id']) && !empty($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
    
    try {
        // Récupérer les informations du patient sélectionné
        $stmt = $pdo->prepare("SELECT * FROM patient WHERE Numero_patient = ?");
        $stmt->execute([$patient_id]);
        $patient_selectionne = $stmt->fetch();
        
        if ($patient_selectionne) {
            // Récupérer les ordonnances du patient avec le nombre de médicaments
            $stmt = $pdo->prepare("
                SELECT o.Numero_ordonnance, o.Date, 
                       (SELECT COUNT(*) FROM detail WHERE Numero_ordonnance = o.Numero_ordonnance) AS nombre_medicaments
                FROM ordonnance o
                WHERE o.Numero_patient = ?
                ORDER BY o.Date DESC
            ");
            $stmt->execute([$patient_id]);
            $ordonnances = $stmt->fetchAll();
        }
    } catch (PDOException $e) {
        $error = "Erreur lors de la récupération des ordonnances : " . $e->getMessage();
    }
} else {
    // Récupération de tous les patients
    try {
        $stmt = $pdo->query("SELECT * FROM patient ORDER BY nom, prenom");
        $patients = $stmt->fetchAll();
    } catch (PDOException $e) {
        $patients = [];
        $error = "Erreur lors de la récupération des patients : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordonnances - Gestion Médicale</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<header class="main-header">
    <div class="header-content">
        <?php if ($patient_selectionne): ?>
            <h1><i class="fas fa-file-medical"></i> Ordonnances de <?= htmlspecialchars($patient_selectionne["nom"] . " " . $patient_selectionne["prenom"]) ?></h1>
        <?php else: ?>
            <h1><i class="fas fa-users"></i> Liste des patients</h1>
        <?php endif; ?>
    </div>
    <?php include '../layouts/header.php'; ?>
</header>
<main>
    <?php if (isset($error)): ?>
        <div class="error-message"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($patient_selectionne): ?>
        <!-- Affichage des ordonnances du patient -->
        <div class="back-link">
            <a href="impression.php"><i class="fas fa-arrow-left"></i> Retour à la liste des patients</a>
        </div>

        <?php if (count($ordonnances) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Nombre de médicaments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ordonnances as $o): ?>
                        <tr>
                            <td><?= htmlspecialchars($o["Numero_ordonnance"]) ?></td>
                            <td><?= date("d/m/Y", strtotime($o["Date"])) ?></td>
                            <td><?= htmlspecialchars($o["nombre_medicaments"]) ?></td>
                            <td>
                                <a href="../controllers/pdfController.php?id=<?= $o["Numero_ordonnance"] ?>" target="_blank" class="btn">
                                    <i class="fas fa-print"></i> Imprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="info-message">Ce patient n'a pas encore d'ordonnance.</p>
            <div class="action-buttons">
                <a href="../views/ordonnance.php?patient_id=<?= $patient_selectionne["Numero_patient"] ?>" class="btn">
                    <i class="fas fa-plus-circle"></i> Créer une ordonnance pour ce patient
                </a>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- Affichage de la liste des patients -->
        <?php if (!empty($patients)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Ville</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patients as $patient): ?>
                        <tr>
                            <td><?= htmlspecialchars($patient["nom"]) ?></td>
                            <td><?= htmlspecialchars($patient["prenom"]) ?></td>
                            <td><?= htmlspecialchars($patient["ville"] ?? "") ?></td>
                            <td><?= htmlspecialchars($patient["telephone"] ?? "") ?></td>
                            <td>
                                <a href="?patient_id=<?= $patient["Numero_patient"] ?>" class="btn">
                                    <i class="fas fa-file-medical"></i> Voir ordonnances
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun patient enregistré.</p>
            <a href="patient.php" class="btn">Ajouter un patient</a>
        <?php endif; ?>
    <?php endif; ?>
</main>

<footer>
    <?php include '../layouts/footer.php'; ?>
</footer>
</body>
</html>
