<?php
require_once 'models/BDD.php';

// Récupération des statistiques
try {
    $pdo = BDD::getPDO();

    $nbPatients = $pdo->query("SELECT COUNT(*) as nb FROM patient")->fetch()['nb'];
    $nbOrdonnances = $pdo->query("SELECT COUNT(*) as nb FROM ordonnance")->fetch()['nb'];
    $nbMedicaments = $pdo->query("SELECT COUNT(*) as nb FROM medicament")->fetch()['nb'];
} catch (Exception $e) {
    // En cas d'erreur de connexion, afficher des valeurs par défaut
    $nbPatients = 0;
    $nbOrdonnances = 0;
    $nbMedicaments = 0;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Gestion Médicale</title>
    <link rel="stylesheet" href="css/style.css?v=2"> <!-- Incrémentation de la version pour forcer le rechargement -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <header class="main-header">
        <div class="header-content">
            <h1><i class="fas fa-stethoscope"></i> Système de Gestion Médicale</h1>
            <p>Bienvenue dans l'application de gestion des consultations, ordonnances et médicaments.</p>
        </div>
        <?php include 'layouts/header.php';?>
    </header>

    <main>
        <section class="dashboard">
            <h2><i class="fas fa-chart-line"></i> Tableau de bord</h2>
            <div class="stats-cards">
                <div class="stat-card">
                    <i class="fas fa-user-alt stat-icon"></i>
                    <h3>Patients</h3>
                    <strong><?php echo $nbPatients; ?></strong>
                </div>
                <div class="stat-card">
                    <i class="fas fa-file-prescription stat-icon"></i>
                    <h3>Ordonnances</h3>
                    <strong><?php echo $nbOrdonnances; ?></strong>
                </div>
                <div class="stat-card">
                    <i class="fas fa-pills stat-icon"></i>
                    <h3>Médicaments</h3>
                    <strong><?php echo $nbMedicaments; ?></strong>
                </div>
            </div>
            
            <h2><i class="fas fa-bolt"></i> Actions rapides</h2>
            <div class="quick-actions">
                <a href="views/patient.php" class="btn"><i class="fas fa-user-plus"></i> Ajouter un patient</a>
                <a href="views/ordonnance.php" class="btn"><i class="fas fa-file-medical"></i> Créer une ordonnance</a>
                <a href="views/impression.php" class="btn"><i class="fas fa-print"></i> Imprimer une ordonnance</a>
            </div>
        </section>
    </main>
    
    <footer>
        <?php include 'layouts/footer.php';?>
    </footer>

</body>
</html>
