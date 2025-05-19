<?php
/**
 * Classe BDD - Gestion de la connexion à la base de données
 */
class BDD {
    private static $instance = null;
    private $pdo;
    
    /**
     * Constructeur privé pour empêcher l'instanciation directe
     */
    private function __construct() {
        // Configuration de la base de données
        $host = 'localhost';
        $dbname = 'consultation_medical';
        $username = 'root'; // Utilisateur par défaut de WAMP
        $password = '';     // Mot de passe par défaut (vide sous WAMP)
        
        try {
            // Création de l'objet PDO
            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }
    
    /**
     * Obtenir l'instance de la connexion PDO
     * @return PDO
     */
    public static function getPDO() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}
?>