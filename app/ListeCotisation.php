<?php

$conn = new mysqli("db", "root", "rootpass", "EspaceMembreDB");

if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}


$sql = "SELECT c.*, m.Nom, m.Prenom, m.Matricule as MembreMatricule 
        FROM Cotisation c 
        JOIN Membre m ON c.Matricule = m.Matricule 
        ORDER BY c.DateCotis DESC";
$resultat = $conn->query($sql);

$message = "";
if(isset($_GET['message'])) {
    if($_GET['message'] == "success") {
        $message = '<div class="success">✅ Opération réussie!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Cotisations</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table th, table td {
            text-align: center;
            vertical-align: middle;
        }
        .matricule-badge {
            background: #667eea;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
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

    <div class="container-large">
        <h2>📋 Liste des Cotisations</h2>
        
        <?php echo $message; ?>
        
        <div style="margin-bottom: 20px;">
            <input type="text" id="searchInput" placeholder="🔍 Rechercher..." style="padding: 10px; width: 100%; max-width: 300px; border: 2px solid #e0e0e0; border-radius: 5px;">
        </div>
        
        <div class="links" style="margin-bottom: 20px;">
            <a href="Accueil.php">Accueil</a>
            <a href="SaisieCotisation.php">➕ Nouvelle cotisation</a>
            <a href="RechercheCotisation.php">🔍 Recherche par mois</a>
        </div>
        
        <?php if($resultat->num_rows > 0): ?>
            <table id="cotisationTable">
                <thead>
                    <tr>
                        <th>N° Cotis</th>
                        <th>Matricule</th>
                        <th>Membre</th>
                        <th>Date</th>
                        <th>Mois</th>
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
                        <td><strong>#<?php echo $row['NumCotis']; ?></strong></td>
                        <td><span class="matricule-badge"><?php echo $row['MembreMatricule']; ?></span></td>
                        <td><?php echo $row['Nom'] . " " . $row['Prenom']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['DateCotis'])); ?></td>
                        <td><?php echo $row['Mois']; ?></td>
                        <td>
                            <span class="badge badge-<?php echo strtolower($row['Motif']); ?>">
                                <?php echo $row['Motif']; ?>
                            </span>
                        </td>
                        <td><strong><?php echo number_format($row['Montant'], 0, ',', ' ') . ' FCFA'; ?></strong></td>
                        <td>
                            <a href="modifierPaiement.php?id=<?php echo $row['NumCotis']; ?>" class="btn-small btn-modify" style="background:#28a745; color:white; padding:5px 10px; border-radius:3px; text-decoration:none; margin:2px;">✏️ Modifier</a>
                            <a href="SupprimerCotisation.php?id=<?php echo $row['NumCotis']; ?>" class="btn-small btn-delete" style="background:#dc3545; color:white; padding:5px 10px; border-radius:3px; text-decoration:none; margin:2px;" onclick="return confirm('Supprimer cette cotisation?')">🗑️ Supprimer</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <tr class="total-row">
                        <td colspan="6" style="text-align: right;"><strong>TOTAL GÉNÉRAL:</strong></td>
                        <td colspan="2"><strong><?php echo number_format($total, 0, ',', ' ') . ' FCFA'; ?></strong></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-message">
                <p>😕 Aucune cotisation trouvée</p>
                <a href="SaisieCotisation.php">➕ Ajouter une cotisation</a>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        var searchText = this.value.toLowerCase();
        var table = document.getElementById('cotisationTable');
        if(!table) return;
        var rows = table.getElementsByTagName('tr');
        
        for(var i = 1; i < rows.length - 1; i++) {
            var row = rows[i];
            var cells = row.getElementsByTagName('td');
            var found = false;
            
            for(var j = 0; j < cells.length - 1; j++) {
                if(cells[j] && cells[j].innerText.toLowerCase().indexOf(searchText) > -1) {
                    found = true;
                    break;
                }
            }
            row.style.display = found ? '' : 'none';
        }
    });
    </script>
</body>
</html>
<?php $conn->close(); ?>