<?php
// Sécurité : vérification session
if (!isset($_SESSION['login_admin'])) {
    header("Location: index.php?controleur=admin&action=afficherIndex");
    exit();
}
?>

<div class="admin-container">
    <aside class="admin-sidebar">
        <h2>Administration</h2>
        <ul>
            <li><a href="index.php?controleur=admin&action=afficherIndex">📊 Tableau de bord</a></li>
            <li><a href="index.php?controleur=admin&action=afficherTousLesProduits">📦 Produits</a></li>
            <li><a href="index.php?controleur=admin&action=afficherCommandes" class="active">🛒 Commandes</a></li>
            <li><a href="index.php?controleur=admin&action=afficherUtilisateurs">👥 Utilisateurs</a></li>
            <li><a href="index.php?controleur=admin&action=seDeconnecter">🚪 Déconnexion</a></li>
        </ul>
    </aside>

    <main class="admin-main">
        <div class="admin-header">
            <h1>Détail de la commande #<?php echo (int)$commande->id; ?></h1>
            <div class="user-info">
                <p>Client: <strong><?php echo htmlspecialchars(($commande->prenom ?? '') . ' ' . ($commande->nom ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong></p>
                <p>Email: <?php echo htmlspecialchars($commande->email ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>

        <div class="card">
            <h2>Informations</h2>
            <p><strong>Date:</strong> <?php echo date('d/m/Y H:i', strtotime($commande->date_commande)); ?></p>
            <p><strong>Statut:</strong> <?php echo htmlspecialchars($commande->statut); ?></p>
            <p><strong>Montant total:</strong> <?php echo number_format((float)$commande->montant_total, 2, ',', ' '); ?> €</p>
            <?php if (!empty($commande->adresse_livraison)): ?>
                <p><strong>Adresse:</strong> <?php echo nl2br(htmlspecialchars($commande->adresse_livraison)); ?></p>
            <?php endif; ?>
            <p><a class="btn-admin" href="index.php?controleur=admin&action=afficherCommandes">← Retour à la liste</a></p>
        </div>

        <div class="card">
            <h2>Produits</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Total ligne</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produits as $p): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p->nom ?? ('#' . (int)$p->produit_id)), ENT_QUOTES, 'UTF-8'; ?></td>
                            <td><?php echo (int)$p->quantite; ?></td>
                            <td><?php echo number_format((float)$p->prix_unitaire, 2, ',', ' '); ?> €</td>
                            <td><?php echo number_format((float)$p->total_ligne, 2, ',', ' '); ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
