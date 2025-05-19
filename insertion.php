<?php
$servername = "localhost";
$username = "root";
$password = ""; // mets ton mot de passe MySQL ici
$dbname = "consultation_medical"; // remplace par le nom de ta base

// Connexion à la BDD
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

while (($ligne = fgets($fichier)) !== false) {
    $colonnes = explode("\t", $ligne);

    // Vérification minimale que la ligne est correcte
    if (count($colonnes) >= 12 && is_numeric($colonnes[0])) {
        $code = $conn->real_escape_string($colonnes[0]);
        $designation = implode(" ", array_slice($colonnes, 1, 9));
        $designation = $conn->real_escape_string($designation);
        $laboratoire = $conn->real_escape_string(trim($colonnes[10]));


        $sql = "INSERT INTO Medicament (Code_medicament, Designation, Laboratoire)
                VALUES ('$code', '$designation', '$laboratoire')
                ON DUPLICATE KEY UPDATE Designation='$designation', Laboratoire='$laboratoire'";

        $conn->query($sql);
    }
}

fclose($fichier);
$conn->close();

echo "Importation terminée !";
?>
