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
            <li><a href="index.php?controleur=admin&action=afficherTousLesProduits" class="active">📦 Produits</a></li>
            <li><a href="index.php?controleur=admin&action=afficherCommandes">🛒 Commandes</a></li>
            <li><a href="index.php?controleur=admin&action=afficherUtilisateurs">👥 Utilisateurs</a></li>
            <li><a href="index.php?controleur=admin&action=seDeconnecter">🚪 Déconnexion</a></li>
        </ul>
    </aside>

    <!-- MAIN -->
    <main class="admin-main">
        <div class="admin-header">
            <h1>Ajouter un Produit</h1>
            <div class="user-info">
                <p>Bienvenue, <strong><?php echo htmlspecialchars($_SESSION['login_admin'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
                <a href="index.php?controleur=admin&action=seDeconnecter" class="btn-logout">Déconnexion</a>
            </div>
        </div>

        <!-- FORMULAIRE D'AJOUT -->
        <div class="admin-form-container">
            <div class="form-header">
                <h2>Nouveau Produit</h2>
                <a href="index.php?controleur=admin&action=afficherTousLesProduits" class="btn-admin btn-admin-secondary">← Retour à la liste</a>
            </div>

            <!-- Messages -->
            <?php if (isset($messages['succes'])): ?>
                <div class="alert alert-success">
                    ✓ <?php echo htmlspecialchars($messages['succes'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($messages['erreur'])): ?>
                <div class="alert alert-danger">
                    ✕ <?php echo htmlspecialchars($messages['erreur'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire -->
            <form method="POST" action="index.php?controleur=admin&action=ajouterProduit" enctype="multipart/form-data" class="admin-form">
                <div class="form-group">
                    <label for="nom">Nom du produit *</label>
                    <input type="text" id="nom" name="nom" required maxlength="50" placeholder="Ex: Miles Davis - Live At Montreux">
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" required rows="5" maxlength="500" placeholder="Décrivez le produit..."></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="prix">Prix (€) *</label>
                        <input type="number" id="prix" name="prix" required step="0.01" min="0" placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label for="idCategorie">Catégorie *</label>
                        <select id="idCategorie" name="idCategorie" required>
                            <option value="">-- Sélectionnez une catégorie --</option>
                            <?php if (isset($categories) && !empty($categories)): ?>
                                <?php foreach ($categories as $categorie): ?>
                                    <option value="<?php echo (int)$categorie->id; ?>">
                                        <?php echo htmlspecialchars($categorie->libelle, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="image">Image du produit *</label>
                    <div class="file-input-container" id="fileDropZone">
                        <input type="file" id="image" name="image" required accept="image/*" style="display: none;">
                        <div class="file-upload-btn">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            <p class="btn-text">Cliquez ou glissez une image ici</p>
                            <p class="btn-subtext">JPG, PNG, GIF (Max: 2MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Aperçu de l'image -->
                <div class="form-group">
                    <div id="imagePreview" class="image-preview" style="display: none;">
                        <h3>Aperçu de l'image:</h3>
                        <img id="previewImg" src="" alt="Aperçu">
                    </div>
                </div>

                <!-- Boutons -->
                <div class="form-actions">
                    <button type="submit" class="btn-admin btn-admin-success">✓ Ajouter le produit</button>
                    <a href="index.php?controleur=admin&action=afficherTousLesProduits" class="btn-admin btn-admin-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </main>
</div>

<style>
/* Styles pour le formulaire */
.admin-form-container {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    max-width: 800px;
}

.form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 15px;
}

.form-header h2 {
    margin: 0;
    color: #333;
}

.admin-form {
    margin-top: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group input[type="file"],
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    font-family: Arial, sans-serif;
}

.form-group input[type="file"] {
    padding: 5px;
}

.form-group textarea {
    resize: vertical;
    font-family: Arial, sans-serif;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: #4CAF50;
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

/* Styles Upload Zone */
.file-input-container {
    cursor: pointer;
    position: relative;
}

.file-upload-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    border: 3px dashed #4CAF50;
    border-radius: 8px;
    background-color: #f9fff9;
    transition: all 0.3s ease;
    text-align: center;
    min-height: 150px;
}

.file-upload-btn svg {
    color: #4CAF50;
    margin-bottom: 10px;
    transition: transform 0.2s ease;
}

.file-upload-btn .btn-text {
    font-size: 16px;
    font-weight: 600;
    color: #4CAF50;
    margin: 0;
    padding: 0;
}

.file-upload-btn .btn-subtext {
    font-size: 12px;
    color: #999;
    margin: 5px 0 0 0;
    padding: 0;
}

/* Hover state */
.file-input-container:hover .file-upload-btn {
    border-color: #45a049;
    background-color: #f0fff0;
}

.file-input-container:hover .file-upload-btn svg {
    transform: scale(1.1);
}

/* Drag over state */
.file-input-container.drag-over .file-upload-btn {
    border-color: #2196F3;
    background-color: #e3f2fd;
    box-shadow: 0 0 10px rgba(33, 150, 243, 0.2);
}

.file-input-container.drag-over .file-upload-btn svg {
    color: #2196F3;
}

.file-input-container.drag-over .file-upload-btn .btn-text {
    color: #2196F3;
}

/* File selected state */
.file-input-container.file-selected .file-upload-btn {
    border-color: #4CAF50;
    background-color: #e8f5e9;
}

.image-preview {
    margin-top: 20px;
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 4px;
    text-align: center;
}

.image-preview img {
    max-width: 300px;
    max-height: 300px;
    margin-top: 10px;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #f0f0f0;
}

.form-actions .btn-admin {
    flex: 1;
    padding: 12px;
    text-align: center;
}

small {
    display: block;
    color: #666;
    font-size: 12px;
    margin-top: 5px;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
    border-left: 4px solid;
}

.alert-success {
    background-color: #d4edda;
    border-color: #28a745;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #dc3545;
    color: #721c24;
}

@media (max-width: 600px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .file-upload-btn {
        padding: 30px 15px;
        min-height: 120px;
    }
    
    .file-upload-btn svg {
        width: 36px;
        height: 36px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileDropZone = document.getElementById('fileDropZone');
    const fileInput = document.getElementById('image');
    
    if (!fileDropZone || !fileInput) return;

    // Fonction pour déclencher le sélecteur de fichiers
    fileDropZone.addEventListener('click', function() {
        fileInput.click();
    });

    // Événement quand un fichier est sélectionné
    fileInput.addEventListener('change', function() {
        handleFile(this.files[0]);
        fileDropZone.classList.add('file-selected');
    });

    // Drag & Drop
    fileDropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        fileDropZone.classList.add('drag-over');
    });

    fileDropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        fileDropZone.classList.remove('drag-over');
    });

    fileDropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        fileDropZone.classList.remove('drag-over');
        fileDropZone.classList.add('file-selected');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFile(files[0]);
        }
    });

    // Traiter le fichier (afficher aperçu)
    function handleFile(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                let preview = document.getElementById('imagePreview');
                
                if (!preview) {
                    // Créer le conteneur d'aperçu s'il n'existe pas
                    preview = document.createElement('div');
                    preview.id = 'imagePreview';
                    preview.className = 'image-preview';
                    preview.innerHTML = '<h3>Aperçu de l\'image:</h3><img id="previewImg" src="" alt="Aperçu">';
                    fileDropZone.parentNode.insertBefore(preview, fileDropZone.nextSibling);
                }
                
                document.getElementById('previewImg').src = e.target.result;
                preview.style.display = 'block';
            };
            
            reader.readAsDataURL(file);
        }
    }
});
</script>
