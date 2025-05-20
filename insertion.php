<?php
// filepath: c:\wamp64\www\PHP\consultation_medical\insertion.php

$servername = "localhost";
$username = "root";
$password = ""; // Remplacez par votre mot de passe MySQL
$dbname = "consultation_medical"; // Remplacez par le nom de votre base de données

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Ouvrir le fichier
$fichier = fopen("CIS_bdpm.txt", "r");
if (!$fichier) {
    die("Erreur lors de l'ouverture du fichier");
}

$ligneCount = 0; // Compteur pour suivre les lignes insérées

while (($ligne = fgets($fichier)) !== false) {
    $colonnes = explode("\t", $ligne);

    // Vérification minimale que la ligne est correcte
    if (count($colonnes) >= 12 && is_numeric($colonnes[0])) {
        $code = $conn->real_escape_string($colonnes[0]);
        $preparation = $conn->real_escape_string(trim($colonnes[1]));
        $designation = $conn->real_escape_string(trim($colonnes[2]));
        $laboratoire = $conn->real_escape_string(trim($colonnes[10]));

        // Requête SQL pour insérer ou mettre à jour les données
        $sql = "INSERT INTO Medicament (Code_medicament, Preparation, Designation, Laboratoire)
                VALUES ('$code', '$preparation', '$designation', '$laboratoire')
                ON DUPLICATE KEY UPDATE 
                    Preparation = '$preparation', 
                    Designation = '$designation', 
                    Laboratoire = '$laboratoire'";

        if ($conn->query($sql) === TRUE) {
            $ligneCount++;
        } else {
            echo "Erreur : " . $conn->error . "<br>";
        }
    }
}

fclose($fichier);
$conn->close();

echo "Importation terminée ! $ligneCount lignes insérées ou mises à jour.";
?>
