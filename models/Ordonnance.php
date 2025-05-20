<?php
require_once 'BDD.php';

class Ordonnance {
    public static function create($data) {
        $pdo = BDD::getPDO();
        $stmt = $pdo->prepare("INSERT INTO ordonnance (Numero_patient, Date) VALUES (:patient, :date)");
        $stmt->execute([
            'patient' => $data['Numero_patient'],
            'date' => $data['Date']
        ]);
        return $pdo->lastInsertId();
    }

    public static function ajouterMedicament($ordonnance_id, $medicament_id, $posologie) {
        $pdo = BDD::getPDO();
        $stmt = $pdo->prepare("INSERT INTO detail (Numero_ordonnance, Code_medicament, Posologie) VALUES (:ordonnance, :medicament, :posologie)");
        $stmt->execute([
            'ordonnance' => $ordonnance_id,
            'medicament' => $medicament_id,
            'posologie' => $posologie
        ]);
        return true;
    }

    public static function getAll() {
        $pdo = getPDO();
        return $pdo->query("SELECT o.Numero_ordonnance, o.Date, p.nom, p.prenom
                            FROM ordonnance o JOIN patient p ON o.Numero_patient = p.Numero_patient
                            ORDER BY o.Date DESC")->fetchAll();
    }
}
?>
