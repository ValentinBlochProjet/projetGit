<?php
// Sécurité : vérification session
if (!isset($_SESSION['login_admin'])) {
    header("Location: index.php?controleur=admin&action=afficherIndex");
    exit();
}

// Récupérer les produits si non fourni
if (!isset($produits) || !is_array($produits)) {
    $produits = GestionBoutique::getLesProduits();
}
?>

<div class="admin-container">
    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <h2>Administration</h2>
        <ul>
            <li><a href="index.php?controleur=admin&action=afficherIndex">📊 Tableau de bord</a></li>
            <li><a href="index.php?controleur=admin&action=afficherTousLesProduits" class="active">📦 Produits</a></li>
            <li><a href="index.php?controleur=admin&action=afficherCommandes">🛒 Commandes</a></li>
            <li><a href="index.php?controleur=admin&action=afficherUtilisateurs">👥 Utilisateurs</a></li>
            <li><a href="index.php?controleur=admin&action=seDeconnecter">🚪 Déconnexion</a></li>
        </ul>
    </aside>

    <!-- MAIN -->
    <main class="admin-main">
        <div class="admin-header">
            <h1>Gestion des Produits</h1>
            <div class="user-info">
                <p>Bienvenue, <strong><?php echo htmlspecialchars($_SESSION['login_admin'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
                <a href="index.php?controleur=admin&action=seDeconnecter" class="btn-logout">Déconnexion</a>
            </div>
        </div>

        <!-- TABLEAU DES PRODUITS -->
        <div class="admin-table-container">
            <div class="table-header">
                <h2>Liste des Produits</h2>
                <a href="index.php?controleur=admin&action=ajouterProduit" class="btn-admin btn-admin-primary">+ Ajouter un produit</a>
            </div>

            <?php if (!empty($produits)): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Prix</th>
                            <th>Catégorie</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produits as $produit): ?>
                            <tr>
                                <td class="image-cell">
                                    <?php if (isset($produit->image) && !empty($produit->image)): ?>
                                        <img src="<?php echo Chemins::IMAGES_PRODUITS . htmlspecialchars($produit->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                             alt="<?php echo htmlspecialchars($produit->nom, ENT_QUOTES, 'UTF-8'); ?>" 
                                             class="product-thumbnail">
                                    <?php else: ?>
                                        <span class="no-image">Pas d'image</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo isset($produit->id) ? (int)$produit->id : '-'; ?></td>
                                <td><strong><?php echo isset($produit->nom) ? htmlspecialchars($produit->nom, ENT_QUOTES, 'UTF-8') : '-'; ?></strong></td>
                                <td><?php echo isset($produit->description) ? htmlspecialchars(substr($produit->description, 0, 80), ENT_QUOTES, 'UTF-8') . (strlen($produit->description) > 80 ? '...' : '') : '-'; ?></td>
                                <td><span class="prix"><?php echo isset($produit->prix) ? number_format((float)$produit->prix, 2, ',', ' ') . ' €' : '0 €'; ?></span></td>
                                <td><?php echo isset($produit->categorie) ? htmlspecialchars($produit->categorie, ENT_QUOTES, 'UTF-8') : '-'; ?></td>
                                <td>
                                    <a href="index.php?controleur=admin&action=editerProduit&id=<?php echo isset($produit->id) ? (int)$produit->id : '#'; ?>" class="btn-admin btn-admin-info">✏️ Modifier</a>
                                    <a href="index.php?controleur=admin&action=supprimerProduit&id=<?php echo isset($produit->id) ? (int)$produit->id : '#'; ?>" class="btn-admin btn-admin-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">🗑️ Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>Aucun produit disponible.</p>
                    <a href="index.php?controleur=admin&action=ajouterProduit" class="btn-admin btn-admin-primary">+ Ajouter un produit</a>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>
