<div class="admin-container">
    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <h2>Admin</h2>
        <ul>
            <li><a href="index.php?controleur=admin&action=afficherIndex" class="active">📊 Tableau de bord</a></li>
            <li><a href="index.php?controleur=admin&action=afficherTousLesProduits">📦 Produits</a></li>
            <li><a href="index.php?controleur=admin&action=afficherCommandes">🛒 Commandes</a></li>
            <li><a href="index.php?controleur=admin&action=afficherUtilisateurs">👥 Utilisateurs</a></li>
            <li><a href="index.php?controleur=admin&action=seDeconnecter">🚪 Déconnexion</a></li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="admin-main">
        <div class="admin-header">
            <h1>Tableau de bord - Tous les Produits</h1>
            <div class="user-info">
                <p>Bienvenue, <strong><?php echo isset($_SESSION['login_admin']) ? $_SESSION['login_admin'] : 'Admin'; ?></strong></p>
                <a href="index.php?controleur=admin&action=seDeconnecter" class="btn-logout">Déconnexion</a>
            </div>
        </div>

        <!-- CARDS STATISTIQUES -->
        <div class="admin-cards">
            <div class="admin-card">
                <h3>Produits</h3>
                <div class="numero"><?php echo isset($stats['totalProduits']) ? $stats['totalProduits'] : 0; ?></div>
                <p>Produits en stock</p>
            </div>

            <div class="admin-card">
                <h3>Commandes</h3>
                <div class="numero"><?php echo isset($stats['totalCommandes']) ? $stats['totalCommandes'] : 0; ?></div>
                <p>Commandes en attente</p>
            </div>

            <div class="admin-card">
                <h3>Clients</h3>
                <div class="numero"><?php echo isset($stats['totalClients']) ? $stats['totalClients'] : 0; ?></div>
                <p>Clients enregistrés</p>
            </div>

            <div class="admin-card">
                <h3>Chiffre d'affaires</h3>
                <div class="numero"><?php echo isset($stats['chiffreAffaires']) ? $stats['chiffreAffaires'] : '0€'; ?></div>
                <p>Ce mois-ci</p>
            </div>
        </div>

        <!-- TABLEAU DES PRODUITS -->
        <div class="admin-table-container">
            <div class="table-header">
                <h2>Tous les Produits</h2>
                <a href="index.php?controleur=admin&action=ajouterProduit" class="btn-admin btn-admin-primary">+ Ajouter un produit</a>
            </div>

            <?php if (!empty($produits)): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
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
                                <td><?php echo isset($produit->id) ? $produit->id : '-'; ?></td>
                                <td><strong><?php echo isset($produit->nom) ? $produit->nom : '-'; ?></strong></td>
                                <td><?php echo isset($produit->description) ? substr($produit->description, 0, 50) . '...' : '-'; ?></td>
                                <td><span class="prix"><?php echo isset($produit->prix) ? $produit->prix : '0'; ?> €</span></td>
                                <td><?php echo isset($produit->categorie) ? $produit->categorie : '-'; ?></td>
                                <td>
                                    <a href="index.php?controleur=admin&action=editerProduit&id=<?php echo isset($produit->id) ? $produit->id : '#'; ?>" class="btn-admin btn-admin-info">✏️ Modifier</a>
                                    <a href="index.php?controleur=admin&action=supprimerProduit&id=<?php echo isset($produit->id) ? $produit->id : '#'; ?>" class="btn-admin btn-admin-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">🗑️ Supprimer</a>
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