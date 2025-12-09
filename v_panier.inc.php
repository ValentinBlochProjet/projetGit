<?php
// Vérifier si le panier existe
if (!isset($panier)) {
    $panier = [];
}
?>

<div class="panier-container">
    <div class="panier-header">
        <h1>🛒 Mon Panier</h1>
        <?php if (!empty($panier)): ?>
            <span class="badge-count"><?php echo $nombreArticles; ?> article(s)</span>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['message_succes'])): ?>
        <div class="alert alert-success">
            ✓ <?php echo htmlspecialchars($_SESSION['message_succes']); unset($_SESSION['message_succes']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['message_erreur'])): ?>
        <div class="alert alert-danger">
            ✗ <?php echo htmlspecialchars($_SESSION['message_erreur']); unset($_SESSION['message_erreur']); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($panier)): ?>
        <div class="panier-vide">
            <div class="empty-icon">🛒</div>
            <h2>Votre panier est vide</h2>
            <p>Découvrez nos produits et ajoutez-les à votre panier</p>
            <a href="index.php" class="btn btn-primary">Découvrir nos produits</a>
        </div>
    <?php else: ?>
        <div class="panier-content">
            <!-- Liste des produits -->
            <div class="panier-items">
                <?php foreach ($panier as $article): ?>
                    <div class="panier-item">
                        <div class="item-image">
                            <?php if (!empty($article['image'])): ?>
                                <img src="<?php echo Chemins::IMAGES_PRODUITS . htmlspecialchars($article['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($article['nom']); ?>">
                            <?php else: ?>
                                <div class="no-image">📦</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="item-details">
                            <h3 class="item-name"><?php echo htmlspecialchars($article['nom']); ?></h3>
                            <p class="item-price-unit"><?php echo number_format($article['prix'], 2, ',', ' '); ?> € / unité</p>
                        </div>
                        
                        <div class="item-quantity">
                            <form method="POST" action="index.php?controleur=client&action=mettreAJourQuantite" class="quantity-form">
                                <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                                <button type="submit" name="quantite" value="<?php echo $article['quantite'] - 1; ?>" class="btn-qty">−</button>
                                <span class="qty-value"><?php echo $article['quantite']; ?></span>
                                <button type="submit" name="quantite" value="<?php echo $article['quantite'] + 1; ?>" class="btn-qty">+</button>
                            </form>
                        </div>
                        
                        <div class="item-total">
                            <p class="total-price"><?php echo number_format($article['total'], 2, ',', ' '); ?> €</p>
                        </div>
                        
                        <div class="item-actions">
                            <a href="index.php?controleur=client&action=supprimerDuPanier&id=<?php echo $article['id']; ?>" 
                               class="btn-delete" 
                               onclick="return confirm('Supprimer cet article du panier ?')"
                               title="Supprimer">
                                🗑️
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Résumé et actions -->
            <div class="panier-summary">
                <div class="summary-card">
                    <h2>Récapitulatif</h2>
                    
                    <div class="summary-line">
                        <span>Sous-total</span>
                        <span class="summary-value"><?php echo number_format($total, 2, ',', ' '); ?> €</span>
                    </div>
                    
                    <div class="summary-line">
                        <span>Livraison</span>
                        <span class="summary-value">Gratuite</span>
                    </div>
                    
                    <div class="summary-line summary-total">
                        <span>Total</span>
                        <span class="summary-value-total"><?php echo number_format($total, 2, ',', ' '); ?> €</span>
                    </div>

                    <?php if (isset($_SESSION['id_client']) || isset($_SESSION['client_connecte'])): ?>
                        <form method="POST" action="index.php?controleur=client&action=validerCommande" class="checkout-form">
                            <div class="form-group">
                                <label for="adresse">Adresse de livraison (optionnel)</label>
                                <textarea id="adresse" name="adresse" rows="3" placeholder="Entrez votre adresse..." class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">
                                ✓ Valider la commande
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="login-notice">
                            <p>⚠️ Vous devez être connecté pour passer commande</p>
                            <a href="index.php?controleur=auth&action=afficherConnexion" class="btn btn-primary btn-block">
                                Se connecter
                            </a>
                        </div>
                    <?php endif; ?>

                    <a href="index.php?controleur=client&action=viderPanier" 
                       class="btn btn-outline-danger btn-block"
                       onclick="return confirm('Vider complètement le panier ?')">
                        🗑️ Vider le panier
                    </a>
                    
                    <a href="index.php" class="btn btn-outline-secondary btn-block">
                        ← Continuer mes achats
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    .panier-container {
        max-width: 1400px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .panier-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 3px solid #8b5cf6;
    }

    .panier-header h1 {
        margin: 0;
        font-size: 2.5em;
        color: #1a1a2e;
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
    }

    .badge-count {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1em;
    }

    /* Alerts */
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .alert-success {
        background-color: #d4edda;
        border-left: 4px solid #28a745;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-left: 4px solid #dc3545;
        color: #721c24;
    }

    /* Panier vide */
    .panier-vide {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .empty-icon {
        font-size: 120px;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    .panier-vide h2 {
        font-size: 2em;
        color: #1a1a2e;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .panier-vide p {
        color: #666;
        font-size: 1.1em;
        margin-bottom: 30px;
    }

    /* Panier content */
    .panier-content {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 30px;
    }

    /* Items */
    .panier-items {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .panier-item {
        display: grid;
        grid-template-columns: 120px 1fr auto auto auto;
        gap: 20px;
        align-items: center;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .panier-item:hover {
        box-shadow: 0 4px 20px rgba(139, 92, 246, 0.15);
        transform: translateY(-2px);
    }

    .item-image img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
    }

    .no-image {
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f0f0;
        border-radius: 8px;
        font-size: 48px;
    }

    .item-details {
        flex: 1;
    }

    .item-name {
        font-size: 1.3em;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0 0 8px 0;
        font-family: 'Poppins', sans-serif;
    }

    .item-price-unit {
        color: #666;
        font-size: 1em;
        margin: 0;
    }

    /* Quantity controls */
    .item-quantity {
        min-width: 140px;
    }

    .quantity-form {
        display: flex;
        align-items: center;
        gap: 5px;
        background: #f8f9fa;
        padding: 8px;
        border-radius: 50px;
    }

    .btn-qty {
        width: 36px;
        height: 36px;
        border: none;
        background: white;
        color: #8b5cf6;
        font-size: 20px;
        font-weight: bold;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-qty:hover {
        background: #8b5cf6;
        color: white;
        transform: scale(1.1);
    }

    .qty-value {
        min-width: 40px;
        text-align: center;
        font-weight: 700;
        font-size: 1.1em;
        color: #1a1a2e;
    }

    .item-total {
        min-width: 120px;
        text-align: right;
    }

    .total-price {
        font-size: 1.5em;
        font-weight: 900;
        color: #8b5cf6;
        margin: 0;
        font-family: 'Poppins', sans-serif;
    }

    .item-actions .btn-delete {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fee;
        color: #dc3545;
        border-radius: 8px;
        text-decoration: none;
        font-size: 20px;
        transition: all 0.3s ease;
    }

    .item-actions .btn-delete:hover {
        background: #dc3545;
        color: white;
        transform: scale(1.1);
    }

    /* Summary */
    .summary-card {
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        position: sticky;
        top: 20px;
    }

    .summary-card h2 {
        font-size: 1.8em;
        font-weight: 800;
        color: #1a1a2e;
        margin: 0 0 25px 0;
        font-family: 'Poppins', sans-serif;
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 1.1em;
    }

    .summary-total {
        border-top: 2px solid #1a1a2e;
        border-bottom: none;
        padding: 20px 0;
        margin-top: 10px;
        font-size: 1.2em;
        font-weight: 700;
    }

    .summary-value-total {
        color: #8b5cf6;
        font-size: 1.8em;
        font-weight: 900;
        font-family: 'Poppins', sans-serif;
    }

    /* Forms */
    .checkout-form {
        margin: 25px 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #1a1a2e;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1em;
        font-family: 'Inter', sans-serif;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #8b5cf6;
    }

    /* Buttons */
    .btn {
        padding: 14px 28px;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1.1em;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
    }

    .btn-block {
        width: 100%;
        display: block;
        margin-bottom: 12px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #7c3aed, #6d28d9);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }

    .btn-outline-danger {
        background: white;
        color: #dc3545;
        border: 2px solid #dc3545;
    }

    .btn-outline-danger:hover {
        background: #dc3545;
        color: white;
    }

    .btn-outline-secondary {
        background: white;
        color: #666;
        border: 2px solid #ddd;
    }

    .btn-outline-secondary:hover {
        background: #f8f9fa;
        border-color: #8b5cf6;
        color: #8b5cf6;
    }

    .login-notice {
        background: #fff3cd;
        border: 2px solid #ffc107;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
        text-align: center;
    }

    .login-notice p {
        margin: 0 0 15px 0;
        font-weight: 600;
        color: #856404;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .panier-content {
            grid-template-columns: 1fr;
        }

        .summary-card {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .panier-item {
            grid-template-columns: 80px 1fr;
            grid-template-rows: auto auto auto;
            gap: 15px;
        }

        .item-image {
            grid-row: 1 / 3;
        }

        .item-details {
            grid-column: 2;
        }

        .item-quantity {
            grid-column: 1 / 3;
            justify-self: center;
        }

        .item-total {
            grid-column: 1 / 3;
            text-align: center;
        }

        .item-actions {
            grid-column: 1 / 3;
            text-align: center;
        }

        .item-actions .btn-delete {
            margin: 0 auto;
        }
    }
</style>
