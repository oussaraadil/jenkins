<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Gestion des Cotisations</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <div class="navbar">
        <ul>
            <li><a href="Accueil.php" class="active">🏠 Accueil</a></li>
            <li><a href="SaisieMembre.php">👤 Saisie Membre</a></li>
            <li><a href="SaisieCotisation.php">💰 Saisie Cotisation</a></li>
            <li><a href="ListeCotisation.php">📋 Liste des Cotisations</a></li>
            <li><a href="RechercheCotisation.php">🔍 Recherche par Mois</a></li>
        </ul>
    </div>

    
    <div class="hero">
        <h1>Gestion des Cotisations</h1>
        <p>Bienvenue dans l'application de gestion des membres et des cotisations</p>
    </div>

    
    <div class="container-large">
      
        <div class="menu-grid">
           
            <div class="menu-item">
                <a href="SaisieMembre.php">
                    <span class="icon">👤</span>
                    <h3>Ajouter un Membre</h3>
                    <p>Enregistrer un nouveau membre dans la base de données</p>
                </a>
            </div>
            
            
            <div class="menu-item">
                <a href="SaisieCotisation.php">
                    <span class="icon">💰</span>
                    <h3>Ajouter une Cotisation</h3>
                    <p>Enregistrer un nouveau paiement de cotisation</p>
                </a>
            </div>
            
            
            <div class="menu-item">
                <a href="ListeCotisation.php">
                    <span class="icon">📋</span>
                    <h3>Liste des Cotisations</h3>
                    <p>Consulter tous les paiements effectués</p>
                </a>
            </div>
            
            
            <div class="menu-item">
                <a href="RechercheCotisation.php">
                    <span class="icon">🔍</span>
                    <h3>Recherche par Mois</h3>
                    <p>Rechercher des cotisations par mois</p>
                </a>
            </div>
        </div>

        
        <div class="info-section">
            <h3>📊 Statistiques</h3>
            <?php
          
            $serveur = "db";
            $utilisateur = "root";
            $motdepasse = "rootpass";
            $base = "EspaceMembreDB";
            
            $conn = new mysqli($serveur, $utilisateur, $motdepasse, $base);
            
            if (!$conn->connect_error) {
               
                $resultMembres = $conn->query("SELECT COUNT(*) as total FROM Membre");
                $membres = $resultMembres->fetch_assoc();
                
               
                $resultCotisations = $conn->query("SELECT COUNT(*) as total FROM Cotisation");
                $cotisations = $resultCotisations->fetch_assoc();
                
                
                $resultTotal = $conn->query("SELECT SUM(Montant) as total FROM Cotisation");
                $total = $resultTotal->fetch_assoc();
                
                echo "<p>👥 Membres: " . $membres['total'] . "</p>";
                echo "<p>📝 Cotisations: " . $cotisations['total'] . "</p>";
                echo "<p>💰 Total: " . number_format($total['total'] ?? 0, 0, ',', ' ') . " FCFA</p>";
                
                $conn->close();
            }
            ?>
        </div>
    </div>
</body>
</html>