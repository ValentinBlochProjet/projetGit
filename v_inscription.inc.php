<div class="container" style="max-width: 500px; margin: 40px auto; padding: 0 20px;">
    <h1 style="text-align: center; color: #333; margin-bottom: 30px;">Créer un compte</h1>
    
    <?php if (isset($messages['succes'])): ?>
        <div style="padding: 15px; background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; border-radius: 4px; margin-bottom: 20px;">
            ✓ <?php echo htmlspecialchars($messages['succes']); ?>
            <p style="margin-top: 10px; font-size: 14px;">Redirection vers la connexion...</p>
        </div>
    <?php elseif (isset($messages['erreur'])): ?>
        <div style="padding: 15px; background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 4px; margin-bottom: 20px;">
            ✗ <?php echo htmlspecialchars($messages['erreur']); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" style="background-color: #f9f9f9; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" id="formInscription">
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Pseudo: *</label>
            <input type="text" name="pseudo" id="pseudo" required minlength="3" maxlength="20" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 14px;">
            <small id="pseudoStatus" style="display: block; margin-top: 5px; color: #666;"></small>
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Prénom:</label>
            <input type="text" name="prenom" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 14px;">
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Nom:</label>
            <input type="text" name="nom" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 14px;">
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Email:</label>
            <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 14px;">
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Mot de passe (min. 6 caractères):</label>
            <input type="password" name="password" required minlength="6" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 14px;">
        </div>
        
        <div style="margin-bottom: 30px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Confirmer le mot de passe:</label>
            <input type="password" name="password_confirm" required minlength="6" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 14px;">
        </div>
        
        <button type="submit" id="btnInscription" style="width: 100%; padding: 12px; background-color: #28a745; color: white; border: none; border-radius: 4px; font-weight: bold; font-size: 16px; cursor: pointer; margin-bottom: 15px;">
            S'inscrire
        </button>
        
        <p style="text-align: center; color: #666;">
            Vous avez un compte? <a href="index.php?controleur=auth&action=afficherConnexion" style="color: #007bff; text-decoration: none; font-weight: bold;">Se connecter</a>
        </p>
    </form>
</div>

<style>
    .container {
        font-family: Arial, sans-serif;
    }
    
    input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
    }
    
    button:hover:not(:disabled) {
        background-color: #218838;
        transition: background-color 0.3s;
    }
    
    button:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }
    
    #pseudoStatus.disponible {
        color: #28a745;
        font-weight: bold;
    }
    
    #pseudoStatus.indisponible {
        color: #dc3545;
        font-weight: bold;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let pseudoValide = false;
    
    // Vérifier le pseudo en temps réel
    $('#pseudo').on('keyup', function() {
        const pseudo = $(this).val();
        const statusDiv = $('#pseudoStatus');
        const btnInscription = $('#btnInscription');
        
        if (pseudo.length < 3) {
            statusDiv.text('Le pseudo doit faire au moins 3 caractères').removeClass('disponible indisponible');
            pseudoValide = false;
            btnInscription.prop('disabled', true);
            return;
        }
        
        if (!/^[a-zA-Z0-9_-]+$/.test(pseudo)) {
            statusDiv.text('Caractères invalides (lettres, chiffres, - et _ seulement)').removeClass('disponible indisponible').addClass('indisponible');
            pseudoValide = false;
            btnInscription.prop('disabled', true);
            return;
        }
        
        // Vérifier auprès du serveur
        $.ajax({
            url: 'api.php?action=verifierPseudo&pseudo=' + encodeURIComponent(pseudo),
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.existe) {
                    statusDiv.text('❌ ' + response.message).removeClass('disponible').addClass('indisponible');
                    pseudoValide = false;
                    btnInscription.prop('disabled', true);
                } else {
                    statusDiv.text('✓ ' + response.message).removeClass('indisponible').addClass('disponible');
                    pseudoValide = true;
                    btnInscription.prop('disabled', false);
                }
            }
        });
    });
    
    // Valider avant submission
    $('#formInscription').on('submit', function(e) {
        if (!pseudoValide) {
            e.preventDefault();
            alert('Veuillez choisir un pseudo valide et disponible');
        }
    });
});
</script>

