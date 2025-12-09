<div class="products-page">
    <div class="page-header">
        <h1><?php echo htmlspecialchars(VariablesGlobales::$libelleCategorie ?? 'Produits', ENT_QUOTES, 'UTF-8'); ?></h1>
        <p class="product-count"><?php echo count(VariablesGlobales::$lesProduits); ?> produit(s) disponible(s)</p>
    </div>

    <?php if (empty(VariablesGlobales::$lesProduits)): ?>
        <div class="empty-state">
            <div class="empty-icon">📦</div>
            <h2>Aucun produit</h2>
            <p>Cette catégorie ne contient pas encore de produits.</p>
            <a href="index.php" class="btn-home">← Retour à l'accueil</a>
        </div>
    <?php else: ?>
        <div class="products-grid">
            <?php foreach(VariablesGlobales::$lesProduits as $unProduit): 
                $cheminImage = '';
                if (!empty($unProduit->image)) {
                    $cheminCentralise = Chemins::IMAGES_PRODUITS . $unProduit->image;
                    if (file_exists($cheminCentralise)) {
                        $cheminImage = $cheminCentralise;
                    } else {
                        // Utiliser la catégorie du produit si disponible, sinon le libellé courant
                        $catImage = !empty($unProduit->categorie) ? $unProduit->categorie : (VariablesGlobales::$libelleCategorie ?? '');
                        $cheminParCategorie = Chemins::IMAGES_PRODUITS . $catImage . "/" . $unProduit->image;
                        $cheminImage = $cheminParCategorie;
                    }
                }
            ?>
            <div class="product-card">
                <div class="product-image">
                    <?php if (!empty($cheminImage)): ?>
                        <img src="<?php echo htmlspecialchars($cheminImage); ?>" alt="<?php echo htmlspecialchars($unProduit->nom); ?>">
                    <?php else: ?>
                        <div class="no-image">📦</div>
                    <?php endif; ?>
                </div>
                
                <div class="product-body">
                    <span class="product-category"><?php echo htmlspecialchars(VariablesGlobales::$libelleCategorie); ?></span>
                    <h3 class="product-name"><?php echo htmlspecialchars($unProduit->nom); ?></h3>
                    <p class="product-desc"><?php echo htmlspecialchars($unProduit->description ?? ''); ?></p>
                    
                    <div class="product-price">
                        <?php echo number_format((float)$unProduit->prix, 2, ',', ' '); ?> €
                    </div>
                    
                    <form method="get" action="index.php" class="add-form">
                        <input type="hidden" name="controleur" value="client">
                        <input type="hidden" name="action" value="ajouterAuPanier">
                        <input type="hidden" name="id" value="<?php echo $unProduit->id; ?>">
                        
                        <div class="qty-wrapper">
                            <label for="qty<?php echo $unProduit->id; ?>">Quantité :</label>
                            <input type="number" id="qty<?php echo $unProduit->id; ?>" name="quantite" value="1" min="1" max="99" class="qty-input">
                        </div>
                        
                        <button type="submit" class="btn-add">
                            <span>🛒</span>
                            Ajouter au panier
                        </button>
                    </form>
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

    .products-page {
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px 20px;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .page-header {
        text-align: center;
        margin-bottom: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px 20px;
        border-radius: 12px;
        color: white;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .page-header h1 {
        font-size: 2.8em;
        font-weight: 900;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .product-count {
        font-size: 1.2em;
        opacity: 0.95;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 100px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .empty-icon {
        font-size: 5em;
        margin-bottom: 20px;
    }

    .empty-state h2 {
        font-size: 2em;
        color: #333;
        margin-bottom: 15px;
    }

    .empty-state p {
        font-size: 1.1em;
        color: #666;
        margin-bottom: 30px;
    }

    .btn-home {
        display: inline-block;
        padding: 15px 35px;
        background: #8b5cf6;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1.1em;
        transition: all 0.3s ease;
    }

    .btn-home:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
    }

    .product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.2);
    }

    .product-image {
        width: 100%;
        height: 250px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.05);
    }

    .no-image {
        font-size: 4em;
    }

    .product-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        flex: 1;
    }

    .product-category {
        display: inline-block;
        padding: 6px 12px;
        background: #e0e7ff;
        color: #5b21b6;
        border-radius: 20px;
        font-size: 0.75em;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        align-self: flex-start;
    }

    .product-name {
        font-size: 1.4em;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.3;
    }

    .product-desc {
        font-size: 0.95em;
        color: #6b7280;
        line-height: 1.5;
        flex: 1;
    }

    .product-price {
        font-size: 2em;
        font-weight: 900;
        color: #8b5cf6;
        margin: 10px 0;
    }

    .add-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
        padding-top: 15px;
        border-top: 2px solid #f3f4f6;
    }

    .qty-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .qty-wrapper label {
        font-weight: 600;
        color: #374151;
        font-size: 0.95em;
    }

    .qty-input {
        width: 70px;
        padding: 10px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1.1em;
        font-weight: 700;
        text-align: center;
        transition: all 0.2s ease;
    }

    .qty-input:focus {
        outline: none;
        border-color: #8b5cf6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .btn-add {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1.1em;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-add:active {
        transform: translateY(0);
    }

    .btn-add span {
        font-size: 1.2em;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .page-header h1 {
            font-size: 2em;
        }

        .product-name {
            font-size: 1.2em;
        }

        .product-price {
            font-size: 1.6em;
        }
    }

    @media (max-width: 480px) {
        .products-grid {
            grid-template-columns: 1fr;
        }

        .page-header {
            padding: 30px 15px;
        }

        .page-header h1 {
            font-size: 1.8em;
        }
    }
</style>
