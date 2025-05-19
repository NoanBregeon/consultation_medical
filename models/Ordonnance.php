<?php
require_once 'BDD.php';

class Ordonnance {
    public static function create($patientId, $medicaments) {
        $pdo = BDD::getPDO();
        $pdo->prepare("INSERT INTO ordonnance (Date, Numero_patient) VALUES (?, ?)")
            ->execute([date("Y-m-d"), $patientId]);
        $id = $pdo->lastInsertId();

        foreach ($medicaments as $m) {
            $pdo->prepare("INSERT INTO detail (Numero_ordonnance, Code_medicament, Posologie)
                           VALUES (?, ?, ?)")->execute([$id, $m["code"], $m["posologie"]]);
        }
    }

    public static function getAll() {
        $pdo = getPDO();
        return $pdo->query("SELECT o.Numero_ordonnance, o.Date, p.nom, p.prenom
                            FROM ordonnance o JOIN patient p ON o.Numero_patient = p.Numero_patient
                            ORDER BY o.Date DESC")->fetchAll();
    }
}
?>
