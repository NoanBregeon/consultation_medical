<?php
require_once 'models/BDD.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Gestion Médicale</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Ton CSS externe -->
</head>
<body>

    <header>
        <h1>🩺 Système de Gestion Médicale</h1>
        <p>Bienvenue dans l'application de gestion des consultations, ordonnances et médicaments.</p>
        <?php include 'layouts/header.php';?>
    </header>

    

    <main>
        <section>
            <h2>Statistiques rapides</h2>
            <ul>
                <li>Total de patients : <strong><!-- ?php echo $nbPatients; ? --></strong></li>
                <li>Total d'ordonnances : <strong><!-- ?php echo $nbOrdonnances; ? --></strong></li>
                <li>Médicaments en base : <strong><!-- ?php echo $nbMedicaments; ? --></strong></li>
            </ul>
        </section>
    </main>
    <?php include 'layouts/footer.php';?>

</body>
</html>
