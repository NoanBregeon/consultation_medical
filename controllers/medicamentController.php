<?php
require_once '../models/Medicament.php';

class MedicamentController {
    
    /**
     * Récupère les médicaments avec pagination et gestion de la recherche
     */
    public static function getMedicaments($page, $searchTerm = null) {
        $resultsPerPage = 100;
        $currentPage = $page > 0 ? $page : 1;
        $offset = ($currentPage - 1) * $resultsPerPage;
        
        try {
            // Vérifier s'il y a une recherche
            if ($searchTerm) {
                // Si une recherche est effectuée, filtrer les médicaments
                $totalResults = Medicament::countSearch($searchTerm);
                $medicaments = Medicament::search($searchTerm, $resultsPerPage, $offset);
            } else {
                // Sinon, récupérer tous les médicaments avec pagination
                $totalResults = Medicament::count();
                $medicaments = Medicament::getAll($resultsPerPage, $offset);
            }
            
            // Calculer le nombre total de pages
            $totalPages = ceil($totalResults / $resultsPerPage);
            
            return [
                'medicaments' => $medicaments,
                'totalResults' => $totalResults,
                'totalPages' => $totalPages,
                'currentPage' => $currentPage
            ];
            
        } catch (Exception $e) {
            // En cas d'erreur, retourner un tableau vide avec message d'erreur
            return [
                'medicaments' => [],
                'totalResults' => 0,
                'totalPages' => 0,
                'currentPage' => $currentPage,
                'error' => "Une erreur est survenue lors de la récupération des médicaments: " . $e->getMessage()
            ];
        }
    }
    
    /**
     * Recherche des médicaments pour Select2
     */
    public static function searchMedicaments($searchTerm) {
        try {
            // Vérifier que le terme de recherche a au moins 3 caractères
            if (strlen($searchTerm) < 3) {
                return json_encode([]); // Retourner un tableau vide si le terme est trop court
            }

            // Appeler la méthode de recherche dans le modèle Medicament
            $medicaments = Medicament::search($searchTerm, 50, 0); // Limite à 50 résultats

            // Formater les résultats pour Select2
            $results = array_map(function ($med) {
                return [
                    'id' => $med['Code_medicament'],
                    'text' => $med['Preparation'] . ' - ' . $med['Designation'] . ' (' . $med['Laboratoire'] . ')'
                ];
            }, $medicaments);

            // Retourner les résultats au format JSON
            return json_encode($results);
        } catch (Exception $e) {
            // En cas d'erreur, retourner un tableau vide
            error_log('Erreur de recherche de médicament : ' . $e->getMessage());
            return json_encode([]);
        }
    }
}

// Traiter la requête de recherche si elle existe
if (isset($_GET['action']) && $_GET['action'] === 'search' && isset($_GET['q'])) {
    header('Content-Type: application/json');
    try {
        $searchTerm = trim($_GET['q']);
        if (strlen($searchTerm) < 3) {
            echo json_encode([]);
            exit;
        }
        
        $medicaments = Medicament::search($searchTerm, 50, 0);
        $results = array_map(function($m) {
            return [
                'id' => $m['Code_medicament'],
                'text' => $m['Preparation'] . ' - ' . $m['Designation'] . ' (' . $m['Laboratoire'] . ')'
            ];
        }, $medicaments);
        
        echo json_encode($results);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
