<?php
require_once '../models/BDD.php';

// Récupération des ordonnances
try {
    $pdo = BDD::getPDO();
    
    // Requête pour récupérer les ordonnances avec les noms des patients
    $sql = "SELECT o.Numero_ordonnance, o.Date, p.nom, p.prenom 
            FROM ordonnance o
            JOIN patient p ON o.Numero_patient = p.Numero_patient
            ORDER BY o.Date DESC";
            
    $stmt = $pdo->query($sql);
    $ordonnances = $stmt->fetchAll();
} catch (Exception $e) {
    $ordonnances = [];
    $error = "Erreur lors de la récupération des ordonnances : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impression - Gestion Médicale</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<header class="main-header">
    <div class="header-content">
        <h1><i class="fas fa-print"></i> Ordonnances existantes</h1>
    </div>
    <?php include '../layouts/header.php'; ?>
</header>
<main>
    <section>
        <h2>Liste des ordonnances</h2>

        <?php if (isset($error)): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>

        <?php if (count($ordonnances) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ordonnances as $o): ?>
                        <tr>
                            <td><?= htmlspecialchars($o["Numero_ordonnance"]) ?></td>
                            <td><?= htmlspecialchars($o["Date"]) ?></td>
                            <td><?= htmlspecialchars($o["nom"] . " " . $o["prenom"]) ?></td>
                            <td>
                                <a href="imprimer.php?id=<?= $o["Numero_ordonnance"] ?>" class="btn-action">
                                    <i class="fas fa-print"></i> Imprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune ordonnance trouvée.</p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <?php include '../layouts/footer.php'; ?>
</footer>
</body>
</html>
