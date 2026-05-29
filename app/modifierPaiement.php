<?php
// modifierPaiement.php
$conn = new mysqli("db", "root", "rootpass", "EspaceMembreDB");

if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($id == 0) {
    header("Location: ListeCotisation.php");
    exit();
}

$membres = $conn->query("SELECT * FROM Membre ORDER BY Nom");
$mois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", 
              "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

$result = $conn->query("SELECT * FROM Cotisation WHERE NumCotis = $id");
$cotisation = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $mois = $_POST['mois'];
    $motif = $_POST['motif'];
    $montant = $_POST['montant'];
    $matricule = $_POST['matricule'];
    
    $sql = "UPDATE Cotisation SET DateCotis='$date', Mois='$mois', Motif='$motif', 
            Montant='$montant', Matricule='$matricule' WHERE NumCotis=$id";
    
    if ($conn->query($sql) === TRUE) {
        $message = "<div class='success'>Modification réussie!</div>";
        $result = $conn->query("SELECT * FROM Cotisation WHERE NumCotis = $id");
        $cotisation = $result->fetch_assoc();
    } else {
        $message = "<div class='error'>Erreur: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Paiement</title>
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
        <h2>Modifier Cotisation N° <?php echo $id; ?></h2>
        
        <?php if(isset($message)) echo $message; ?>
        
        <form method="post">
            <label>Date:</label>
            <input type="date" name="date" value="<?php echo $cotisation['DateCotis']; ?>" required>
            
            <label>Membre:</label>
            <select name="matricule" required>
                <?php while($m = $membres->fetch_assoc()): ?>
                <option value="<?php echo $m['Matricule']; ?>" <?php echo ($m['Matricule'] == $cotisation['Matricule']) ? 'selected' : ''; ?>>
                    <?php echo $m['Nom']." ".$m['Prenom']; ?>
                </option>
                <?php endwhile; ?>
            </select>
            
            <label>Mois:</label>
            <select name="mois" required>
                <?php foreach($mois as $m): ?>
                <option value="<?php echo $m; ?>" <?php echo ($m == $cotisation['Mois']) ? 'selected' : ''; ?>>
                    <?php echo $m; ?>
                </option>
                <?php endforeach; ?>
            </select>
            
            <label>Motif:</label>
            <select name="motif" required>
                <option value="Inscription" <?php echo ($cotisation['Motif']=='Inscription')?'selected':''; ?>>Inscription</option>
                <option value="Mensualité" <?php echo ($cotisation['Motif']=='Mensualité')?'selected':''; ?>>Mensualité</option>
            </select>
            
            <label>Montant (FCFA):</label>
            <input type="number" name="montant" value="<?php echo $cotisation['Montant']; ?>" required>
            
            <button type="submit">Modifier</button>
        </form>
        
        <div class="links">
            <a href="Accueil.php">Accueil</a>
            <a href="ListeCotisation.php">Retour</a>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>