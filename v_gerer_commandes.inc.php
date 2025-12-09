<?php
// Sécurité : vérification session
if (!isset($_SESSION['login_admin'])) {
    header("Location: index.php?controleur=admin&action=afficherIndex");
    exit();
}
?>

<div class="admin-container">
    <!-- SIDEBAR -->
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

    <!-- MAIN -->
    <main class="admin-main">
        <div class="admin-header">
            <h1>Gestion des Commandes</h1>
            <div class="user-info">
                <p>Bienvenue, <strong><?php echo htmlspecialchars($_SESSION['login_admin'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
                <a href="index.php?controleur=admin&action=seDeconnecter" class="btn-logout">Déconnexion</a>
            </div>
        </div>

        <!-- TABLEAU DES COMMANDES -->
        <div class="admin-table-container">
            <h2>Liste des Commandes</h2>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($commandes)): ?>
                        <?php foreach ($commandes as $cmd): ?>
                            <tr>
                                <td>#<?php echo (int)$cmd->id; ?></td>
                                <td><?php echo htmlspecialchars(($cmd->prenom ?? '') . ' ' . ($cmd->nom ?? ''), ENT_QUOTES, 'UTF-8'); ?><br><small><?php echo htmlspecialchars($cmd->email ?? '', ENT_QUOTES, 'UTF-8'); ?></small></td>
                                <td><span class="prix"><?php echo number_format((float)$cmd->montant_total, 2, ',', ' '); ?> €</span></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($cmd->date_commande)); ?></td>
                                <td>
                                    <span class="statut statut-<?php echo htmlspecialchars($cmd->statut); ?>"><?php echo htmlspecialchars($cmd->statut); ?></span>
                                </td>
                                <td>
                                    <a class="btn-admin btn-admin-info" href="index.php?controleur=admin&action=afficherDetailCommandeAdmin&id=<?php echo (int)$cmd->id; ?>">👁️ Voir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center; padding:20px;">Aucune commande pour le moment.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
