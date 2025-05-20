<?php
// Inclusion du contrôleur
require_once '../controllers/MedicamentController.php';

// Récupérer la page courante et le terme de recherche
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$searchTerm = isset($_GET['q']) ? $_GET['q'] : null;

// Utiliser le contrôleur pour obtenir les médicaments
$result = MedicamentController::getMedicaments($currentPage, $searchTerm);

// Extraire les données du résultat
$medicaments = $result['medicaments'];
$totalResults = $result['totalResults'];
$totalPages = $result['totalPages'];
$currentPage = $result['currentPage'];
$error = isset($result['error']) ? $result['error'] : null;

// Redirection si la page demandée est supérieure au nombre total de pages
if ($currentPage > $totalPages && $totalPages > 0) {
    header("Location: ?page=1" . ($searchTerm ? "&q=" . urlencode($searchTerm) : ""));
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Médicaments - Gestion Médicale</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .pagination a, .pagination span {
            padding: 8px 12px;
            margin: 0 5px;
            border-radius: 4px;
            background-color: #f5f7fa;
            text-decoration: none;
            color: #333;
        }
        .pagination a:hover {
            background-color: #e5e5e5;
        }
        .pagination .active {
            background-color: #3498db;
            color: white;
        }
        .pagination .disabled {
            color: #aaa;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
<header class="main-header">
    <div class="header-content">
        <h1><i class="fas fa-pills"></i> Liste des médicaments</h1>
    </div>
    <?php include '../layouts/header.php';?>
</header>
<main>
    <section>
        <h2><i class="fas fa-search"></i> Recherche de médicaments</h2>
        <form method="get" action="" class="search-form">
            <input type="text" name="q" placeholder="Rechercher par nom ou laboratoire..." value="<?= htmlspecialchars($searchTerm ?? '') ?>">
            <button type="submit"><i class="fas fa-search"></i> Rechercher</button>
        </form>

        <?php if (isset($error)): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>

        <h2><i class="fas fa-list"></i> Résultats (<?= number_format($totalResults, 0, ',', ' ') ?>) - Page <?= $currentPage ?>/<?= $totalPages ?></h2>
        
        <?php if (count($medicaments) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Préparation</th>
                        <th>Désignation</th>
                        <th>Laboratoire</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($medicaments as $med): ?>
                        <tr>
                            <td><?= htmlspecialchars($med["Code_medicament"]) ?></td>
                            <td><?= htmlspecialchars($med["Preparation"]) ?></td>
                            <td><?= htmlspecialchars($med["Designation"]) ?></td>
                            <td><?= htmlspecialchars($med["Laboratoire"]) ?></td>
                            <td>
                                <a href="../controllers/MedicamentController.php?action=delete&code=<?= $med["Code_medicament"] ?>&page=<?= $currentPage ?><?= isset($_GET['q']) ? '&q=' . urlencode($_GET['q']) : '' ?>" 
                                   class="btn-delete"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce médicament ?');">
                                    <i class="fas fa-trash"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="pagination">
                <?php 
                // Lien vers la première page
                echo '<a href="?page=1' . ($searchTerm ? '&q=' . urlencode($searchTerm) : '') . '"' . ($currentPage == 1 ? ' class="disabled"' : '') . '>&laquo; Première</a>';
                
                // Lien vers la page précédente
                if ($currentPage > 1) {
                    echo '<a href="?page=' . ($currentPage - 1) . ($searchTerm ? '&q=' . urlencode($searchTerm) : '') . '">&lt; Précédente</a>';
                } else {
                    echo '<span class="disabled">&lt; Précédente</span>';
                }
                
                // Pages numérotées (5 avant et 5 après la page courante)
                $startPage = max(1, $currentPage - 5);
                $endPage = min($totalPages, $currentPage + 5);
                
                for ($i = $startPage; $i <= $endPage; $i++) {
                    $active = $i === $currentPage ? ' class="active"' : '';
                    echo '<a href="?page=' . $i . ($searchTerm ? '&q=' . urlencode($searchTerm) : '') . '"' . $active . '>' . $i . '</a>';
                }
                
                // Lien vers la page suivante
                if ($currentPage < $totalPages) {
                    echo '<a href="?page=' . ($currentPage + 1) . ($searchTerm ? '&q=' . urlencode($searchTerm) : '') . '">Suivante &gt;</a>';
                } else {
                    echo '<span class="disabled">Suivante &gt;</span>';
                }
                
                // Lien vers la dernière page
                echo '<a href="?page=' . $totalPages . ($searchTerm ? '&q=' . urlencode($searchTerm) : '') . '"' . ($currentPage == $totalPages ? ' class="disabled"' : '') . '>Dernière &raquo;</a>';
                ?>
            </div>
            
        <?php else: ?>
            <p class="no-results">Aucun médicament trouvé.</p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <?php include '../layouts/footer.php';?>
</footer>
</body>
</html>
