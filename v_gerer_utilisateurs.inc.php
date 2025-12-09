<?php
// Sécurité : vérification session
if (!isset($_SESSION['login_admin'])) {
    header("Location: indexAdmin.php?controleur=admin&action=afficherIndex");
    exit();
}
?>

<div class="admin-container">
    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <h2>Administration</h2>
        <ul>
            <li><a href="indexAdmin.php?controleur=admin&action=afficherIndex">📊 Tableau de bord</a></li>
            <li><a href="indexAdmin.php?controleur=admin&action=afficherTousLesProduits">📦 Produits</a></li>
            <li><a href="indexAdmin.php?controleur=admin&action=afficherCommandes">🛒 Commandes</a></li>
            <li><a href="indexAdmin.php?controleur=admin&action=afficherUtilisateurs" class="active">👥 Clients</a></li>
            <li><a href="indexAdmin.php?controleur=admin&action=seDeconnecter">🚪 Déconnexion</a></li>
        </ul>
    </aside>

    <!-- MAIN -->
    <main class="admin-main">
        <div class="admin-header">
            <h1>Gestion des Clients</h1>
            <div class="user-info">
                <p>Bienvenue, <strong><?php echo htmlspecialchars($_SESSION['login_admin'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
                <a href="indexAdmin.php?controleur=admin&action=seDeconnecter" class="btn-logout">Déconnexion</a>
            </div>
        </div>

        <!-- MESSAGES -->
        <?php if (isset($messages)): ?>
            <?php if (isset($messages['succes'])): ?>
                <div style="padding: 15px; background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; border-radius: 4px; margin-bottom: 20px;">
                    ✓ <?php echo htmlspecialchars($messages['succes']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($messages['erreur'])): ?>
                <div style="padding: 15px; background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 4px; margin-bottom: 20px;">
                    ✗ <?php echo htmlspecialchars($messages['erreur']); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- TABLEAU DES CLIENTS -->
        <div class="admin-table-container">
            <div class="table-header">
                <h2>Liste des Clients</h2>
            </div>

            <?php if (isset($utilisateurs) && is_array($utilisateurs) && count($utilisateurs) > 0): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Date d'inscription</th>
                            <th>Admin</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($utilisateurs as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['nom'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($user['prenom'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($user['email'] ?? ''); ?></td>
                                <td><?php echo isset($user['date_creation']) ? htmlspecialchars($user['date_creation']) : 'N/A'; ?></td>
                                <td>
                                    <?php if (isset($user['isAdmin']) && $user['isAdmin'] == 1): ?>
                                        <span style="background: #28a745; padding: 4px 8px; border-radius: 3px; color: white;">✓ Oui</span>
                                    <?php else: ?>
                                        <span style="background: #dc3545; padding: 4px 8px; border-radius: 3px; color: white;">Non</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="indexAdmin.php?controleur=admin&action=editerUtilisateur&id=<?php echo urlencode($user['id'] ?? $user['id_client']); ?>" class="btn-admin btn-admin-info">✏️ Éditer</a>
                                    <a href="indexAdmin.php?controleur=admin&action=toggleAdmin&id=<?php echo urlencode($user['id'] ?? $user['id_client']); ?>" class="btn-admin" style="background-color: <?php echo (isset($user['isAdmin']) && $user['isAdmin'] == 1) ? '#6c757d' : '#ffc107'; ?>;">
                                        <?php echo (isset($user['isAdmin']) && $user['isAdmin'] == 1) ? '👤 Retirer Admin' : '👨‍💼 Rendre Admin'; ?>
                                    </a>
                                    <a href="indexAdmin.php?controleur=admin&action=supprimerUtilisateur&id=<?php echo urlencode($user['id'] ?? $user['id_client']); ?>" class="btn-admin btn-admin-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client?');">🗑️ Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="padding: 20px; background-color: #e2e3e5; text-align: center; border-radius: 4px;">
                    <p>Aucun client trouvé.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>
