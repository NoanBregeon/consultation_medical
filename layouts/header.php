<nav class="main-nav">
    <ul>
        <?php 
        // Détermine si nous sommes dans le dossier principal ou un sous-dossier
        $rootPath = "";
        $currentPath = $_SERVER['PHP_SELF'];
        
        // Si nous sommes dans un sous-dossier (comme /views/), ajouter le bon nombre de "../" pour remonter
        if (strpos($currentPath, '/views/') !== false) {
            $rootPath = "../";
        }
        ?>
        <li><a href="<?php echo $rootPath; ?>index.php" class="nav-link home-link"><i class="fas fa-home"></i> Accueil</a></li>
        <li><a href="<?php echo $rootPath; ?>views/patient.php" class="nav-link"><i class="fas fa-user-alt"></i> Gestion des patients</a></li>
        <li><a href="<?php echo $rootPath; ?>views/medicament.php" class="nav-link"><i class="fas fa-pills"></i> Liste des médicaments</a></li>
        <li><a href="<?php echo $rootPath; ?>views/ordonnance.php" class="nav-link"><i class="fas fa-file-prescription"></i> Création d'ordonnance</a></li>
        <li><a href="<?php echo $rootPath; ?>views/impression.php" class="nav-link"><i class="fas fa-print"></i> Impression</a></li>
    </ul>
</nav>