<?php
$serveur = "db";
$utilisateur = "root";
$motdepasse = "rootpass";
$base = "EspaceMembreDB";

$conn = new mysqli($serveur, $utilisateur, $motdepasse, $base);

if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($id > 0) {
    $sql = "DELETE FROM Cotisation WHERE NumCotis = $id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ListeCotisation.php?message=success");
    } else {
        header("Location: ListeCotisation.php?message=error");
    }
} else {
    header("Location: ListeCotisation.php");
}

$conn->close();
exit();
?>