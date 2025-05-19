<?php
require_once 'BDD.php';

class Medicament {
    // Propriétés correspondant aux champs de la table
    private $code;
    private $designation;
    private $laboratoire;
    
    /**
     * Récupère tous les médicaments avec pagination optionnelle
     * @param int|null $limit Nombre maximum de résultats à retourner (facultatif)
     * @param int|null $offset Numéro de départ (facultatif)
     * @return array Liste des médicaments
     */
    public static function getAll($limit = null, $offset = null) {
        $pdo = BDD::getPDO();
        
        // Si limit et offset sont fournis, on utilise la pagination
        if ($limit !== null && $offset !== null) {
            $stmt = $pdo->prepare("SELECT * FROM medicament ORDER BY Designation LIMIT :limit OFFSET :offset");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            // Sinon, on récupère tous les médicaments
            $stmt = $pdo->query("SELECT * FROM medicament ORDER BY Designation");
        }
        
        return $stmt->fetchAll();
    }
    
    /**
     * Compte le nombre total de médicaments
     */
    public static function count() {
        $pdo = BDD::getPDO();
        return $pdo->query("SELECT COUNT(*) as total FROM medicament")->fetch()['total'];
    }
    
    /**
     * Recherche des médicaments par désignation ou laboratoire
     */
    public static function search($term, $limit, $offset) {
        $pdo = BDD::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM medicament 
                              WHERE Designation LIKE :search 
                              OR Laboratoire LIKE :search 
                              ORDER BY Designation
                              LIMIT :limit OFFSET :offset");
        $searchTerm = '%' . $term . '%';
        $stmt->bindParam(':search', $searchTerm);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Compte le nombre de résultats pour une recherche
     */
    public static function countSearch($term) {
        $pdo = BDD::getPDO();
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM medicament 
                              WHERE Designation LIKE :search 
                              OR Laboratoire LIKE :search");
        $searchTerm = '%' . $term . '%';
        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
    
    /**
     * Vérifie si un médicament peut être supprimé (n'est pas utilisé dans des ordonnances)
     * @param string $code Code du médicament
     * @return bool True si le médicament peut être supprimé, false sinon
     */
    public static function canDelete($code) {
        $pdo = BDD::getPDO();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM detail WHERE Code_medicament = ?");
        $stmt->execute([$code]);
        $count = $stmt->fetchColumn();
        return $count == 0;
    }

    /**
     * Supprime un médicament s'il n'est pas utilisé dans des ordonnances
     * @param string $code Code du médicament
     * @return array Résultat de l'opération avec statut et message
     */
    public static function delete($code) {
        if (!self::canDelete($code)) {
            return [
                'success' => false,
                'message' => 'Impossible de supprimer ce médicament car il est utilisé dans des ordonnances.'
            ];
        }
        
        try {
            $pdo = BDD::getPDO();
            $stmt = $pdo->prepare("DELETE FROM medicament WHERE Code_medicament = ?");
            $stmt->execute([$code]);
            
            return [
                'success' => true,
                'message' => 'Médicament supprimé avec succès.'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
            ];
        }
    }
}
?>
