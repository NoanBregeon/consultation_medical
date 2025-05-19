<?php
require_once '../models/BDD.php';
$pdo = BDD::getPdo();
$patients = $pdo->query("SELECT * FROM patient ORDER BY nom, prenom")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients - Gestion Médicale</title>
    <link rel="stylesheet" href="../css/style.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<header class="main-header">
    <div class="header-content">
        <h1><i class="fas fa-user-alt"></i> Gestion des patients</h1>
    </div>
    <?php include '../layouts/header.php';?>
</header>
<main>
    <h2>Ajouter un patient</h2>
    <form method="post" action="../controllers/patientController.php">
        <input name="nom" placeholder="Nom" required>
        <input name="prenom" placeholder="Prénom" required>
        <input name="adresse" placeholder="Adresse">
        <input name="code_postal" placeholder="Code Postal">
        <input name="ville" placeholder="Ville">
        <input name="pays" placeholder="Pays" value="France">
        <input name="numero_securite_sociale" placeholder="N° Sécurité Sociale">
        <input name="telephone" placeholder="Téléphone">
        <input name="adresse_mail" placeholder="Adresse mail">
        <button type="submit">Ajouter le patient</button>
    </form>

    <h2>Liste des patients (<?= count($patients) ?>)</h2>
    <table>
        <tr><th>Nom</th><th>Prénom</th><th>Ville</th><th>Téléphone</th><th>Email</th></tr>
        <?php foreach ($patients as $patient): ?>
            <tr>
                <td><?= htmlspecialchars($patient["nom"]) ?></td>
                <td><?= htmlspecialchars($patient["prenom"]) ?></td>
                <td><?= htmlspecialchars($patient["ville"]) ?></td>
                <td><?= htmlspecialchars($patient["telephone"]) ?></td>
                <td><?= htmlspecialchars($patient["adresse_mail"]) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>

<footer>
    <?php include '../layouts/footer.php';?>
</footer>
</body>
</html>
