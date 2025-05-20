<?php
require_once '../models/BDD.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un patient - Gestion Médicale</title>
    <link rel="stylesheet" href="../css/style.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<header class="main-header">
    <div class="header-content">
        <h1><i class="fas fa-user-plus"></i> Ajouter un patient</h1>
    </div>
    <?php include '../layouts/header.php';?>
</header>
<main>
    <form method="post" action="../controllers/patientController.php">
        <div class="form-grid">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input id="nom" name="nom" placeholder="Nom" required>
            </div>
            
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input id="prenom" name="prenom" placeholder="Prénom" required>
            </div>
            
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input id="adresse" name="adresse" placeholder="Adresse">
            </div>
            
            <div class="form-group">
                <label for="code_postal">Code postal</label>
                <input id="code_postal" name="code_postal" placeholder="Code Postal">
            </div>
            
            <div class="form-group">
                <label for="ville">Ville</label>
                <input id="ville" name="ville" placeholder="Ville">
            </div>
            
            <div class="form-group">
                <label for="pays">Pays</label>
                <input id="pays" name="pays" placeholder="Pays" value="France">
            </div>
            
            <div class="form-group">
                <label for="numero_securite_sociale">N° Sécurité sociale</label>
                <input id="numero_securite_sociale" name="numero_securite_sociale" placeholder="N° Sécurité Sociale">
            </div>
            
            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input id="telephone" name="telephone" placeholder="Téléphone">
            </div>
            
            <div class="form-group">
                <label for="adresse_mail">Email</label>
                <input id="adresse_mail" name="adresse_mail" placeholder="Adresse mail" type="email">
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn">
                <i class="fas fa-save"></i> Ajouter le patient
            </button>
            <a href="impression.php" class="btn secondary">
                <i class="fas fa-list"></i> Voir la liste des patients
            </a>
        </div>
    </form>

    <!-- Affichage des messages -->
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-<?= $_GET['status'] === 'success' ? 'success' : 'error' ?>">
            <?= htmlspecialchars($_GET['message']) ?>
        </div>
    <?php endif; ?>
</main>

<footer>
    <?php include '../layouts/footer.php';?>
</footer>
</body>
</html>
