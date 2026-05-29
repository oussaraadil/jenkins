<?php
$conn = new mysqli("db", "root", "rootpass", "EspaceMembreDB");

if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

$membres = $conn->query("SELECT Matricule, Nom, Prenom FROM Membre ORDER BY Nom");

$mois_selectionne = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", 
              "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $mois_selectionne = $_POST['mois'];
    $motif = $_POST['motif'];
    $montant = $_POST['montant'];
    $matricule = $_POST['matricule'];
    
    $sql = "INSERT INTO Cotisation (DateCotis, Mois, Motif, Montant, Matricule) 
            VALUES ('$date', '$mois_selectionne', '$motif', '$montant', '$matricule')";
    
    if ($conn->query($sql) === TRUE) {
        $message = "<div class='success'>✅ Cotisation ajoutée avec succès!</div>";
        
        // --- DÉBUT LOGIQUE NOTIFICATION ---
        $to = "admin@unchk.sn"; // Mail fictif pour le test
        $subject = "Notification : Nouvelle Cotisation reçue";
        $body = "Une nouvelle cotisation a été enregistrée :\n\n" .
                "Membre (Matricule) : $matricule\n" .
                "Mois : $mois_selectionne\n" .
                "Motif : $motif\n" .
                "Montant : $montant FCFA\n" .
                "Date : $date";
        $headers = "From: systeme-espacemembre@unchk.sn";

        // Envoi via MailHog
        @mail($to, $subject, $body, $headers);
        // --- FIN LOGIQUE NOTIFICATION ---
    } else {
        $message = "<div class='error'>❌ Erreur: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Saisie Cotisation</title>
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
        <h2>Nouvelle Cotisation</h2>
        
        <?php echo $message; ?>
        
        <form method="post">
            <div class="form-group">
                <label>Date:</label>
                <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Membre (Matricule - Nom):</label>
                <select name="matricule" required>
                    <option value="">-- Choisir un membre --</option>
                    <?php while($m = $membres->fetch_assoc()): ?>
                    <option value="<?php echo $m['Matricule']; ?>">
                        [<?php echo $m['Matricule']; ?>] <?php echo $m['Nom'] . " " . $m['Prenom']; ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Mois:</label>
                <select name="mois" required>
                    <option value="">-- Choisir un mois --</option>
                    <?php foreach($mois_selectionne as $m): ?>
                    <option value="<?php echo $m; ?>"><?php echo $m; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Motif:</label>
                <select name="motif" required>
                    <option value="">-- Choisir un motif --</option>
                    <option value="Inscription">Inscription</option>
                    <option value="Mensualite">Mensualité</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Montant (FCFA):</label>
                <input type="number" name="montant" min="0" required>
            </div>
            
            <button type="submit">Enregistrer</button>
        </form>
        
        <div class="links">
            <a href="Accueil.php">Accueil</a>
            <a href="SaisieMembre.php">Ajouter un membre</a>
            <a href="ListeCotisation.php">Voir la liste</a>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>