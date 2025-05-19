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
}
?>
