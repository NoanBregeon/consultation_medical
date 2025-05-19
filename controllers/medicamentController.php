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
}
