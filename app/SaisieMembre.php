<?php
$conn = new mysqli("db", "root", "rootpass", "EspaceMembreDB");

if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

$message = "";
$nouveauMatricule = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse = $_POST['adresse'];
    $tel = $_POST['tel'];
    
    $sql = "INSERT INTO Membre (Nom, Prenom, Adresse, Tel) VALUES ('$nom', '$prenom', '$adresse', '$tel')";
    
    if ($conn->query($sql) === TRUE) {
        $nouveauMatricule = $conn->insert_id;
        $message = "<div class='success'>✅ Membre ajouté avec succès! Matricule: <strong>" . $nouveauMatricule . "</strong></div>";
    } else {
        $message = "<div class='error'>❌ Erreur: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Saisie Membre</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <ul>
            <li><a href="Accueil.php">Accueil</a></li>
            <li><a href="SaisieMembre.php">Saisie Membre</a></li>
            <li><a href="SaisieCotisation.php">Saisie Cotisation</a></li>
            <li><a href="ListeCotisation.php">Liste des Cotisations</a></li>
            <li><a href="RechercheCotisation.php">Recherche par Mois</a></li>
        </ul>
    </div>

    <div class="container">
        <h2>Ajouter un Membre</h2>
        
        <?php echo $message; ?>
        
        <form method="post">
            <div class="form-group">
                <label>Nom:</label>
                <input type="text" name="nom" required>
            </div>
            
            <div class="form-group">
                <label>Prénom:</label>
                <input type="text" name="prenom" required>
            </div>
            
            <div class="form-group">
                <label>Adresse:</label>
                <textarea name="adresse" required></textarea>
            </div>
            
            <div class="form-group">
                <label>Téléphone:</label>
                <input type="text" name="tel" required>
            </div>
            
            <button type="submit">Ajouter</button>
        </form>
        
        <div class="links">
            <a href="Accueil.php">Accueil</a>
            <a href="SaisieCotisation.php">Saisie Cotisation</a>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>