<?php
// Page de détail d'une commande
if (!isset($commande) || !isset($produits)) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail Commande #<?php echo $commande->id; ?></title>
</head>
<body>

<div class="detail-container">
    <div class="detail-header">
        <a href="index.php?controleur=client&action=afficherMesCommandes" class="btn-retour">← Mes commandes</a>
        <h1>Commande N°<?php echo htmlspecialchars($commande->id); ?></h1>
    </div>

    <div class="detail-grid">
        <!-- Informations de la commande -->
        <div class="info-card">
            <h2>📋 Informations</h2>
            <div class="info-list">
                <div class="info-row">
                    <span class="info-label">Date de commande:</span>
                    <span class="info-value">
                        <?php 
                        $date = new DateTime($commande->date_commande);
                        echo $date->format('d/m/Y à H:i'); 
                        ?>
                    </span>
                </div>
                <div class="info-row">
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
                <div class="info-row">
                    <span class="info-label">Client:</span>
                    <span class="info-value"><?php echo htmlspecialchars($commande->prenom . ' ' . $commande->nom); ?></span>
                </div>
                <?php if (!empty($commande->adresse_livraison)): ?>
                <div class="info-row">
                    <span class="info-label">Adresse de livraison:</span>
                    <span class="info-value"><?php echo nl2br(htmlspecialchars($commande->adresse_livraison)); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Produits commandés -->
        <div class="produits-card">
            <h2>📦 Produits commandés</h2>
            <div class="produits-list">
                <?php foreach ($produits as $produit): ?>
                    <div class="produit-item">
                        <div class="produit-image">
                            <?php if (!empty($produit->image)): ?>
                                <img src="<?php echo Chemins::IMAGES_PRODUITS . htmlspecialchars($produit->image); ?>" 
                                     alt="<?php echo htmlspecialchars($produit->nom_produit); ?>">
                            <?php else: ?>
                                <div class="no-image">📦</div>
                            <?php endif; ?>
                        </div>
                        <div class="produit-info">
                            <h3><?php echo htmlspecialchars($produit->nom_produit); ?></h3>
                            <div class="produit-details">
                                <span class="prix-unitaire"><?php echo number_format($produit->prix_unitaire, 2, ',', ' '); ?> € × <?php echo $produit->quantite; ?></span>
                            </div>
                        </div>
                        <div class="produit-total">
                            <?php echo number_format($produit->total_ligne, 2, ',', ' '); ?> €
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Total -->
    <div class="total-card">
        <div class="total-content">
            <span class="total-label">Total de la commande</span>
            <span class="total-montant"><?php echo number_format($commande->montant_total, 2, ',', ' '); ?> €</span>
        </div>
    </div>
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

    .detail-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .detail-header {
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 30px;
    }

    .btn-retour {
        padding: 12px 25px;
        background: #8b5cf6;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .btn-retour:hover {
        background: #7c3aed;
        transform: translateX(-5px);
    }

    .detail-header h1 {
        font-size: 2.5em;
        font-weight: 800;
        color: #1a1a2e;
        font-family: 'Poppins', sans-serif;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 400px 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }

    .info-card, .produits-card {
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .info-card h2, .produits-card h2 {
        font-size: 1.5em;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 25px;
        font-family: 'Poppins', sans-serif;
    }

    .info-list {
        display: grid;
        gap: 20px;
    }

    .info-row {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-label {
        font-size: 0.9em;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 1.1em;
        font-weight: 600;
        color: #1a1a2e;
    }

    .statut-badge {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 1em;
    }

    .statut-en_attente { background: #fef3c7; color: #92400e; }
    .statut-validee { background: #d1fae5; color: #065f46; }
    .statut-en_preparation { background: #dbeafe; color: #1e40af; }
    .statut-expediee { background: #e0e7ff; color: #3730a3; }
    .statut-livree { background: #d1fae5; color: #065f46; }
    .statut-annulee { background: #fee2e2; color: #991b1b; }

    .produits-list {
        display: grid;
        gap: 20px;
    }

    .produit-item {
        display: grid;
        grid-template-columns: 100px 1fr auto;
        gap: 20px;
        padding: 20px;
        background: #f9fafb;
        border-radius: 12px;
        align-items: center;
    }

    .produit-image {
        width: 100px;
        height: 100px;
        border-radius: 8px;
        overflow: hidden;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .produit-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-image {
        font-size: 2.5em;
    }

    .produit-info h3 {
        font-size: 1.2em;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 8px;
    }

    .produit-details {
        color: #6b7280;
        font-weight: 600;
    }

    .prix-unitaire {
        font-size: 0.95em;
    }

    .produit-total {
        font-size: 1.5em;
        font-weight: 800;
        color: #8b5cf6;
        font-family: 'Poppins', sans-serif;
    }

    .total-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .total-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .total-label {
        font-size: 1.5em;
        font-weight: 700;
        color: white;
        font-family: 'Poppins', sans-serif;
    }

    .total-montant {
        font-size: 3em;
        font-weight: 900;
        color: white;
        font-family: 'Poppins', sans-serif;
    }

    @media (max-width: 1024px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .detail-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .detail-header h1 {
            font-size: 1.8em;
        }

        .produit-item {
            grid-template-columns: 80px 1fr;
            gap: 15px;
        }

        .produit-total {
            grid-column: 2;
            text-align: right;
            margin-top: 10px;
        }

        .total-content {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .total-montant {
            font-size: 2.5em;
        }
    }
</style>

</body>
</html>
