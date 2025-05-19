<?php
require_once '../models/BDD.php';
$pdo = BDD::getPdo();
$patients = $pdo->query("SELECT * FROM patient ORDER BY nom, prenom")->fetchAll();
$medicaments = $pdo->query("SELECT * FROM medicament ORDER BY Designation")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordonnances - Gestion Médicale</title>
    <link rel="stylesheet" href="../css/style.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<header class="main-header">
    <div class="header-content">
        <h1><i class="fas fa-file-prescription"></i> Création d'ordonnance</h1>
    </div>
    <?php include '../layouts/header.php';?>
</header>
<main>
    <h2>Nouvelle ordonnance</h2>
    <form method="post" action="../controllers/ordonnanceController.php">
        <div>
            <label for="patient">Patient :</label>
            <select name="patient" id="patient" required>
                <option value="">-- Sélectionner un patient --</option>
                <?php foreach ($patients as $p): ?>
                    <option value="<?= $p["Numero_patient"] ?>"><?= $p["nom"] . " " . $p["prenom"] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div>
            <label for="date">Date :</label>
            <input type="date" name="date" id="date" value="<?= date('Y-m-d') ?>" required>
        </div>

        <div id="medicaments">
            <h3>Médicaments et posologies</h3>
            <div class="medicament-item">
                <select name="medicament[]">
                    <?php foreach ($medicaments as $m): ?>
                        <option value="<?= $m["Code_medicament"] ?>"><?= $m["Designation"] ?> (<?= $m["Laboratoire"] ?>)</option>
                    <?php endforeach; ?>
                </select>
                <input name="posologie[]" placeholder="Posologie (ex: 1 comprimé matin et soir)" required>
            </div>
        </div>
        
        <button type="button" onclick="ajouterMedicament()">+ Ajouter un médicament</button>
        <button type="submit">Créer l'ordonnance</button>
    </form>
</main>

<footer>
    <?php include '../layouts/footer.php';?>
</footer>

<script>
function ajouterMedicament() {
    const container = document.getElementById('medicaments');
    const newItem = document.createElement('div');
    newItem.className = 'medicament-item';
    
    // Récupérer le contenu du premier élément et le cloner
    const firstItem = container.querySelector('.medicament-item');
    newItem.innerHTML = firstItem.innerHTML;
    
    // Vider les valeurs des inputs dans le nouveau bloc
    const inputs = newItem.querySelectorAll('input');
    inputs.forEach(input => input.value = '');
    
    // Ajouter le nouvel élément
    container.appendChild(newItem);
}
</script>
</body>
</html>
