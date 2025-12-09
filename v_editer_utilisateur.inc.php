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
            <h1>Gestion des Clients - Édition</h1>
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

        <div class="admin-form-container">
            <div style="margin-bottom: 20px;">
                <a href="indexAdmin.php?controleur=admin&action=afficherUtilisateurs" class="btn-back">← Retour à la liste</a>
            </div>

            <?php if (isset($utilisateur) && is_array($utilisateur)): ?>
                <form method="POST" class="admin-form">
                    <div class="form-group">
                        <label for="pseudo">Pseudo:</label>
                        <input type="text" id="pseudo" name="pseudo" value="<?php echo htmlspecialchars($utilisateur['pseudo'] ?? ''); ?>" required>
                        <small>Identifiant unique de l'utilisateur (3-20 caractères)</small>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($utilisateur['email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="nom">Nom:</label>
                        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($utilisateur['nom'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="prenom">Prénom:</label>
                        <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($utilisateur['prenom'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Nouveau mot de passe (laisser vide pour ne pas modifier):</label>
                        <input type="password" id="password" name="password" minlength="6" placeholder="Min. 6 caractères">
                    </div>

                    <div class="form-group">
                        <label for="isAdmin">
                            <input type="checkbox" id="isAdmin" name="isAdmin" <?php echo (isset($utilisateur['isAdmin']) && $utilisateur['isAdmin'] == 1) ? 'checked' : ''; ?>>
                            Cet utilisateur est un administrateur
                        </label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-admin btn-admin-success">💾 Sauvegarder</button>
                        <a href="indexAdmin.php?controleur=admin&action=afficherUtilisateurs" class="btn-admin btn-admin-secondary">❌ Annuler</a>
                    </div>
                </form>
            <?php else: ?>
                <div style="padding: 20px; background-color: #e2e3e5; text-align: center; border-radius: 4px;">
                    <p>Utilisateur non trouvé.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<style>
    .admin-form-container {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        max-width: 600px;
    }

    .admin-form {
        display: flex;
        flex-direction: column;
    }

    .form-group {
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 8px;
        font-weight: bold;
        color: #333;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="password"] {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        font-family: Arial, sans-serif;
    }

    .form-group input[type="text"]:focus,
    .form-group input[type="email"]:focus,
    .form-group input[type="password"]:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
    }

    .form-group input[type="checkbox"] {
        margin-right: 8px;
        width: auto;
    }

    .form-group small {
        color: #666;
        font-size: 12px;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .btn-back {
        display: inline-block;
        padding: 10px 20px;
        background-color: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-weight: bold;
        transition: all 0.3s;
    }

    .btn-back:hover {
        background-color: #5a6268;
    }

    .btn-admin-success {
        background-color: #28a745 !important;
    }

    .btn-admin-success:hover {
        background-color: #218838 !important;
    }

    .btn-admin-secondary {
        background-color: #6c757d !important;
    }

    .btn-admin-secondary:hover {
        background-color: #5a6268 !important;
    }
</style>
