<?php
// RechercheCotisation.php
$serveur = "db";
$utilisateur = "root";
$motdepasse = "rootpass";
$base = "EspaceMembreDB";

$conn = new mysqli($serveur, $utilisateur, $motdepasse, $base);

if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

$mois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", 
              "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

$resultat = null;
$moisRecherche = "";
$total = 0;

if (isset($_POST['mois']) && !empty($_POST['mois'])) {
    $moisRecherche = $_POST['mois'];
    
    // CORRECTION: On sélectionne aussi le matricule du membre
    $sql = "SELECT c.*, m.Nom, m.Prenom, m.Matricule as MembreMatricule 
            FROM Cotisation c 
            JOIN Membre m ON c.Matricule = m.Matricule 
            WHERE c.Mois = '$moisRecherche'
            ORDER BY c.DateCotis DESC";
    
    $resultat = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche par Mois</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .matricule-badge {
            background: #667eea;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <ul>
            <li><a href="Accueil.php">🏠 Accueil</a></li>
            <li><a href="SaisieMembre.php">👤 Saisie Membre</a></li>
            <li><a href="SaisieCotisation.php">💰 Saisie Cotisation</a></li>
            <li><a href="ListeCotisation.php">📋 Liste des Cotisations</a></li>
            <li><a href="RechercheCotisation.php" class="active">🔍 Recherche par Mois</a></li>
        </ul>
    </div>

    <div class="container-large">
        <h2>🔍 Recherche des Cotisations par Mois</h2>
        
        <div style="max-width: 400px; margin: 20px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1);">
            <form method="post">
                <div class="form-group">
                    <label for="mois">Sélectionnez un mois:</label>
                    <select name="mois" id="mois" required style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
                        <option value="">-- Choisir un mois --</option>
                        <?php foreach($mois as $m): ?>
                        <option value="<?php echo $m; ?>" <?php echo ($m == $moisRecherche) ? 'selected' : ''; ?>>
                            <?php echo $m; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" style="width: 100%; padding: 12px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">Rechercher</button>
            </form>
        </div>
        
        <?php if($resultat): ?>
            <?php if($resultat->num_rows > 0): ?>
                <h3 style="text-align: center; margin: 30px 0;">Résultats pour le mois de <span style="color: #667eea;"><?php echo $moisRecherche; ?></span></h3>
                
                <table>
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Matricule</th>
                            <th>Membre</th>
                            <th>Date</th>
                            <th>Motif</th>
                            <th>Montant</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        while($row = $resultat->fetch_assoc()): 
                            $total += $row['Montant'];
                        ?>
                        <tr>
                            <td><strong><?php echo $row['NumCotis']; ?></strong></td>
                            <td><span class="matricule-badge"><?php echo $row['MembreMatricule']; ?></span></td>
                            <td><?php echo $row['Nom'] . " " . $row['Prenom']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['DateCotis'])); ?></td>
                            <td>
                                <span class="badge badge-<?php echo strtolower($row['Motif']); ?>">
                                    <?php echo $row['Motif']; ?>
                                </span>
                            </td>
                            <td><strong><?php echo number_format($row['Montant'], 0, ',', ' ') . ' FCFA'; ?></strong></td>
                            <td>
                                <a href="modifierPaiement.php?id=<?php echo $row['NumCotis']; ?>" style="background:#28a745; color:white; padding:5px 10px; border-radius:3px; text-decoration:none; margin:2px; display:inline-block;">✏️ Modifier</a>
                                <a href="SupprimerCotisation.php?id=<?php echo $row['NumCotis']; ?>" style="background:#dc3545; color:white; padding:5px 10px; border-radius:3px; text-decoration:none; margin:2px; display:inline-block;" onclick="return confirm('Supprimer cette cotisation?')">🗑️ Supprimer</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <tr style="background: #e8f5e8; font-weight: bold;">
                            <td colspan="5" style="text-align: right;">Total pour <?php echo $moisRecherche; ?> :</td>
                            <td colspan="2"><?php echo number_format($total, 0, ',', ' ') . ' FCFA'; ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="text-align: center; padding: 50px; color: #999;">
                    <p>😕 Aucune cotisation trouvée pour le mois de <strong><?php echo $moisRecherche; ?></strong></p>
                    <a href="SaisieCotisation.php" style="display: inline-block; margin-top: 20px; padding: 12px 30px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; text-decoration: none; border-radius: 5px;">➕ Ajouter une cotisation</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="links" style="text-align: center; margin-top: 30px;">
            <a href="Accueil.php" style="display: inline-block; margin: 0 10px; padding: 10px 20px; background: white; color: #667eea; text-decoration: none; border-radius: 5px; border: 2px solid #667eea;">🏠 Accueil</a>
            <a href="SaisieCotisation.php" style="display: inline-block; margin: 0 10px; padding: 10px 20px; background: white; color: #667eea; text-decoration: none; border-radius: 5px; border: 2px solid #667eea;">💰 Nouvelle cotisation</a>
            <a href="ListeCotisation.php" style="display: inline-block; margin: 0 10px; padding: 10px 20px; background: white; color: #667eea; text-decoration: none; border-radius: 5px; border: 2px solid #667eea;">📋 Voir toutes les cotisations</a>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>