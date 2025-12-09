<div class="container-connexion-admin">
    <div class="card-connexion">
        <div class="header-connexion">
            <h1>Espace Administration</h1>
            <p>Connectez-vous pour accéder au panneau d'administration</p>
        </div>

        <?php if (isset($_SESSION['message_succes_admin'])): ?>
            <div class="alert-success-admin">✓ <?php echo htmlspecialchars($_SESSION['message_succes_admin']); unset($_SESSION['message_succes_admin']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['erreur_connexion'])): ?>
            <div class="alert-error-admin">✗ <?php echo htmlspecialchars($_SESSION['erreur_connexion']); unset($_SESSION['erreur_connexion']); ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?controleur=admin&action=verifierConnexion" class="form-connexion">
            <div class="form-group">
                <label for="login">Identifiant :</label>
                <input type="text" id="login" name="login" required placeholder="Votre identifiant">
            </div>

            <div class="form-group">
                <label for="passe">Mot de passe :</label>
                <input type="password" id="passe" name="passe" required placeholder="Votre mot de passe">
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" name="connexion_auto" id="connexion_auto">
                <label for="connexion_auto" class="checkbox-label">Connexion automatique</label>
            </div>

            <button type="submit" class="btn-connexion">Se connecter</button>
        </form>

        <div class="footer-connexion">
            <a href="index.php">← Retour à l'accueil</a>
        </div>
    </div>
</div>

<style>
    .alert-success-admin {
        background: #d1fae5;
        border: 2px solid #10b981;
        color: #065f46;
        padding: 12px 16px;
        border-radius: 8px;
        margin: 10px 0 20px;
        font-weight: 600;
    }
    .alert-error-admin {
        background: #fee2e2;
        border: 2px solid #ef4444;
        color: #991b1b;
        padding: 12px 16px;
        border-radius: 8px;
        margin: 10px 0 20px;
        font-weight: 600;
    }
</style>

