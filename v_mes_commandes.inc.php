<?php
// Page de liste des commandes du client
if (!isset($commandes)) {
    $commandes = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes</title>
</head>
<body>

<div class="commandes-container">
    <div class="commandes-header">
        <h1>📦 Mes Commandes</h1>
        <a href="index.php" class="btn-retour">← Retour à l'accueil</a>
    </div>

    <?php if (empty($commandes)): ?>
        <div class="no-commandes">
            <div class="no-commandes-icon">📋</div>
            <h2>Aucune commande</h2>
            <p>Vous n'avez pas encore passé de commande.</p>
            <a href="index.php" class="btn-shopping">Découvrir nos produits</a>
        </div>
    <?php else: ?>
        <div class="commandes-list">
            <?php foreach ($commandes as $commande): ?>
                <div class="commande-card">
                    <div class="commande-header-card">
                        <div class="commande-numero">
                            <span class="label">Commande N°</span>
                            <span class="numero"><?php echo htmlspecialchars($commande->id); ?></span>
                        </div>
                        <div class="commande-date">
                            <?php 
                            $date = new DateTime($commande->date_commande);
                            echo $date->format('d/m/Y à H:i'); 
                            ?>
                        </div>
                    </div>
                    
                    <div class="commande-body">
                        <div class="commande-info">
                            <div class="info-item">
                                <span class="info-label">Montant total:</span>
                                <span class="info-value montant"><?php echo number_format($commande->montant_total, 2, ',', ' '); ?> €</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Statut:</span>
                                <span class="statut-badge statut-<?php echo htmlspecialchars($commande->statut); ?>">
                                    <?php 
                                    $statuts = [
                                        'en_attente' => '⏳ En attente',
                                        'validee' => '✓ Validée',
                                        'en_preparation' => '📦 En préparation',
                                        'expediee' => '🚚 Expédiée',
                                        'livree' => '✓ Livrée',
                                        'annulee' => '✗ Annulée'
                                    ];
                                    echo $statuts[$commande->statut] ?? $commande->statut;
                                    ?>
                                </span>
                            </div>
                            <?php if (!empty($commande->adresse_livraison)): ?>
                            <div class="info-item">
                                <span class="info-label">Adresse:</span>
                                <span class="info-value"><?php echo nl2br(htmlspecialchars($commande->adresse_livraison)); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="commande-footer">
                        <a href="index.php?controleur=client&action=afficherDetailCommande&id=<?php echo $commande->id; ?>" class="btn-detail">
                            Voir le détail →
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 20px;
    }

    .commandes-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .commandes-header {
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .commandes-header h1 {
        font-size: 2.5em;
        font-weight: 800;
        color: #1a1a2e;
        font-family: 'Poppins', sans-serif;
    }

    .btn-retour {
        padding: 12px 25px;
        background: #8b5cf6;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-retour:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
    }

    .no-commandes {
        background: white;
        padding: 80px 40px;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .no-commandes-icon {
        font-size: 5em;
        margin-bottom: 20px;
    }

    .no-commandes h2 {
        font-size: 2em;
        color: #1a1a2e;
        margin-bottom: 15px;
        font-family: 'Poppins', sans-serif;
    }

    .no-commandes p {
        font-size: 1.1em;
        color: #6b7280;
        margin-bottom: 30px;
    }

    .btn-shopping {
        display: inline-block;
        padding: 15px 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1.1em;
        transition: all 0.3s ease;
    }

    .btn-shopping:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .commandes-list {
        display: grid;
        gap: 20px;
    }

    .commande-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .commande-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .commande-header-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .commande-numero {
        display: flex;
        flex-direction: column;
    }

    .commande-numero .label {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.85em;
        font-weight: 500;
        margin-bottom: 5px;
    }

    .commande-numero .numero {
        color: white;
        font-size: 1.8em;
        font-weight: 800;
        font-family: 'Poppins', sans-serif;
    }

    .commande-date {
        color: white;
        font-size: 1em;
        font-weight: 600;
    }

    .commande-body {
        padding: 30px;
    }

    .commande-info {
        display: grid;
        gap: 15px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #6b7280;
    }

    .info-value {
        font-weight: 600;
        color: #1a1a2e;
    }

    .info-value.montant {
        font-size: 1.5em;
        color: #8b5cf6;
        font-family: 'Poppins', sans-serif;
    }

    .statut-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.9em;
    }

    .statut-en_attente { background: #fef3c7; color: #92400e; }
    .statut-validee { background: #d1fae5; color: #065f46; }
    .statut-en_preparation { background: #dbeafe; color: #1e40af; }
    .statut-expediee { background: #e0e7ff; color: #3730a3; }
    .statut-livree { background: #d1fae5; color: #065f46; }
    .statut-annulee { background: #fee2e2; color: #991b1b; }

    .commande-footer {
        padding: 20px 30px;
        background: #f9fafb;
        text-align: right;
    }

    .btn-detail {
        display: inline-block;
        padding: 12px 30px;
        background: #8b5cf6;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .btn-detail:hover {
        background: #7c3aed;
        transform: translateX(5px);
    }

    @media (max-width: 768px) {
        .commandes-header {
            flex-direction: column;
            gap: 20px;
            text-align: center;
        }

        .commandes-header h1 {
            font-size: 1.8em;
        }

        .commande-header-card {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .info-item {
            flex-direction: column;
            gap: 8px;
            text-align: center;
        }
    }
</style>

</body>
</html>
