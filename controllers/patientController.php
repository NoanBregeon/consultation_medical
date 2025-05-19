<?php
require_once __DIR__ . '/../models/Patient.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    Patient::create($_POST);
    header("Location: ../views/patient.php");
    exit;
}

$patients = Patient::getAll();
require_once __DIR__ . '/../views/patient.php';
