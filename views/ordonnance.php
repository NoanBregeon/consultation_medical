<?php
require_once '../models/BDD.php';
$pdo = BDD::getPdo();
$patients = $pdo->query("SELECT * FROM patient ORDER BY nom, prenom")->fetchAll();
$medicaments = $pdo->query("SELECT * FROM medicament ORDER BY Preparation")->fetchAll();

// Récupérer l'ID du patient si disponible
$patient_id = isset($_GET['patient_id']) ? $_GET['patient_id'] : null;
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
                    <option value="<?= $p["Numero_patient"] ?>" <?= ($patient_id && $p["Numero_patient"] == $patient_id) ? 'selected' : '' ?>>
                        <?= $p["nom"] . " " . $p["prenom"] ?> - <?= $p["ville"] ?>
                    </option>
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
                <!-- Partie où les médicaments sont affichés dans le select initial -->
                <select name="medicament[]" class="select2-medicament">
                    <option value="">-- Sélectionner un médicament --</option>
                    <?php foreach ($medicaments as $m): ?>
                        <option value="<?= $m["Code_medicament"] ?>"><?= $m['Preparation'] ?> - <?= $m["Designation"] ?> (<?= $m["Laboratoire"] ?>)</option>
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
            <i class="fas fa-print"></i> Imprimer l'ordonnance
        </button>
    </form>

    <?php if (isset($_GET['error'])): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>
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
        allowClear: true,
        width: 'resolve',
        minimumInputLength: 3,
        ajax: {
            url: '../controllers/patientController.php?action=search',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        }
    });
    
    // Initialisation de Select2 pour les médicaments
    $('.select2-medicament').select2({
        language: "fr",
        placeholder: "Rechercher un médicament...",
        allowClear: true,
        width: 'resolve',
        minimumInputLength: 3,
        ajax: {
            url: '../controllers/MedicamentController.php?action=search',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        }
    });
    
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
        allowClear: true,
        width: 'resolve',
        dropdownAutoWidth: true,
        minimumInputLength: 3,
        ajax: {
            url: '../controllers/MedicamentController.php?action=search',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        }
    });
}

function ajouterMedicament() {
    const container = document.getElementById('medicaments');
    const newItem = document.createElement('div');
    newItem.className = 'medicament-item';

    // Créer un nouvel élément HTML pour le médicament
    newItem.innerHTML = `
        <select name="medicament[]" class="select2-medicament" required>
            <option value=""></option>
        </select>
        <input name="posologie[]" placeholder="Posologie (ex: 1 comprimé matin et soir)" required>
        <button type="button" class="btn-remove"><i class="fas fa-times"></i></button>
    `;

    // Ajouter le nouvel élément au conteneur
    container.appendChild(newItem);

    // Réinitialiser Select2 pour le nouvel élément
    $(newItem).find('.select2-medicament').select2({
        language: "fr",
        placeholder: "Rechercher un médicament...",
        allowClear: true,
        width: 'resolve',
        dropdownAutoWidth: true,
        minimumInputLength: 3,
        ajax: {
            url: '../controllers/MedicamentController.php?action=search',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        }
    });
}
</script>
</body>
</html>
