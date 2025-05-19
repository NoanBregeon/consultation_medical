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
    <link rel="stylesheet" href="../css/style.css?v=3">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Ajout des fichiers CSS de Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Ajout de jQuery (nécessaire pour Select2) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Ajout du script Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Ajout de la traduction française pour Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/fr.js"></script>
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
            <select name="patient" id="patient" class="select2-patient" required>
                <option value="">-- Sélectionner un patient --</option>
                <?php foreach ($patients as $p): ?>
                    <option value="<?= $p["Numero_patient"] ?>"><?= $p["nom"] . " " . $p["prenom"] ?> - <?= $p["ville"] ?></option>
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
                <select name="medicament[]" class="select2-medicament">
                    <option value="">-- Sélectionner un médicament --</option>
                    <?php foreach ($medicaments as $m): ?>
                        <option value="<?= $m["Code_medicament"] ?>"><?= $m["Designation"] ?> (<?= $m["Laboratoire"] ?>)</option>
                    <?php endforeach; ?>
                </select>
                <input name="posologie[]" placeholder="Posologie (ex: 1 comprimé matin et soir)" required>
                <button type="button" class="btn-remove" style="display: none;"><i class="fas fa-times"></i></button>
            </div>
        </div>
        
        <button type="button" onclick="ajouterMedicament()" class="btn-add">
            <i class="fas fa-plus"></i> Ajouter un médicament
        </button>
        <button type="submit">
            <i class="fas fa-save"></i> Créer l'ordonnance
        </button>
    </form>
</main>

<footer>
    <?php include '../layouts/footer.php';?>
</footer>

<script>
$(document).ready(function() {
    // Initialisation de Select2 pour les patients
    $('.select2-patient').select2({
        language: "fr",
        placeholder: "Rechercher un patient...",
        allowClear: true
    });
    
    // Initialisation de Select2 pour les médicaments
    initializeSelect2();
    
    // Permettre la suppression des médicaments (sauf le premier)
    $('#medicaments').on('click', '.btn-remove', function() {
        $(this).closest('.medicament-item').remove();
    });
});

// Fonction pour initialiser Select2 sur les nouveaux éléments
function initializeSelect2() {
    $('.select2-medicament').select2({
        language: "fr",
        placeholder: "Rechercher un médicament...",
        allowClear: true
    });
}

function ajouterMedicament() {
    const container = document.getElementById('medicaments');
    const items = container.querySelectorAll('.medicament-item');
    const newItem = document.createElement('div');
    newItem.className = 'medicament-item';
    
    // Cloner le contenu du premier élément
    const firstItem = items[0];
    newItem.innerHTML = firstItem.innerHTML;
    
    // Vider les valeurs et réinitialiser les select
    const selects = newItem.querySelectorAll('select');
    selects.forEach(select => {
        // Détruire l'instance Select2 existante s'il y en a une
        if ($(select).hasClass('select2-hidden-accessible')) {
            $(select).select2('destroy');
        }
        select.value = '';
    });
    
    const inputs = newItem.querySelectorAll('input');
    inputs.forEach(input => input.value = '');
    
    // Afficher le bouton de suppression
    const removeButton = newItem.querySelector('.btn-remove');
    if (removeButton) {
        removeButton.style.display = 'flex';
    }
    
    // Ajouter le nouvel élément
    container.appendChild(newItem);
    
    // Réinitialiser Select2 pour les nouveaux éléments
    initializeSelect2();
    
    // Afficher les boutons de suppression pour tous les éléments sauf le premier
    // si nous avons plus d'un élément
    if (items.length >= 1) {
        items.forEach((item, index) => {
            const btn = item.querySelector('.btn-remove');
            if (btn) {
                btn.style.display = index === 0 && items.length === 1 ? 'none' : 'flex';
            }
        });
    }
}
</script>
</body>
</html>
