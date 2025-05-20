<?php
require_once 'BDD.php';

class Patient {
    public static function getAll() {
        $pdo = BDD::getPDO();
        $stmt = $pdo->query("SELECT * FROM patient ORDER BY nom");
        return $stmt->fetchAll();
    }

    public static function create($data) {
        $pdo = BDD::getPDO();
        $stmt = $pdo->prepare("INSERT INTO patient (nom, prenom, adresse, code_postal, ville, pays, numero_securite_sociale, telephone, adresse_mail)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data["nom"], $data["prenom"], $data["adresse"], $data["code_postal"], $data["ville"],
            $data["pays"], $data["numero_securite_sociale"], $data["telephone"], $data["adresse_mail"]
        ]);
    }

    /**
     * Vérifie si un patient peut être supprimé (n'a pas d'ordonnances)
     * @param int $id Identifiant du patient
     * @return bool True si le patient peut être supprimé, false sinon
     */
    public static function canDelete($id) {
        $pdo = BDD::getPDO();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM ordonnance WHERE Numero_patient = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();
        return $count == 0;
    }

    /**
     * Supprime un patient s'il n'a pas d'ordonnances
     * @param int $id Identifiant du patient
     * @return array Résultat de l'opération avec statut et message
     */
    public static function delete($id) {
        if (!self::canDelete($id)) {
            return [
                'success' => false,
                'message' => 'Impossible de supprimer ce patient car il possède des ordonnances.'
            ];
        }
        
        try {
            $pdo = BDD::getPDO();
            $stmt = $pdo->prepare("DELETE FROM patient WHERE Numero_patient = ?");
            $stmt->execute([$id]);
            
            return [
                'success' => true,
                'message' => 'Patient supprimé avec succès.'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
            ];
        }
    }

    public static function search($term, $limit = 50, $offset = 0) {
        try {
            $pdo = BDD::getPdo();
            $stmt = $pdo->prepare("
                SELECT Numero_patient, nom, prenom, ville 
                FROM patient 
                WHERE nom LIKE :search OR prenom LIKE :search OR ville LIKE :search 
                ORDER BY nom, prenom 
                LIMIT :limit OFFSET :offset
            ");
            $searchTerm = '%' . $term . '%';
            $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur dans Patient::search: ' . $e->getMessage());
            return [];
        }
    }
}
?>
