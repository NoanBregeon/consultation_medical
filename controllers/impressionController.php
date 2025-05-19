<?php
require_once __DIR__ . '/../models/Ordonnance.php';

$ordonnances = Ordonnance::getAll();
require_once __DIR__ . '/../views/impression.php';
