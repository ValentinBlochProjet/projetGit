<div style="display: flex; justify-content: space-between; align-items: center; margin: 20px auto; max-width: 95%;">
    <h1 style="margin: 0;">Tous les Produits</h1>
    <a href="indexAdmin.php?controleur=admin&action=ajouterProduit" class="btn-add-product">➕ Ajouter un produit</a>
</div>

<?php 
// Afficher les messages de session
if (isset($_SESSION['message_succes'])): ?>
    <div class="alert-success" style="padding: 15px; margin-bottom: 20px; max-width: 95%; margin-left: auto; margin-right: auto; background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; border-radius: 4px;">
        <?php echo htmlspecialchars($_SESSION['message_succes']); 
        unset($_SESSION['message_succes']); ?>
    </div>
<?php endif; 

if (isset($_SESSION['message_erreur'])): ?>
    <div class="alert-error" style="padding: 15px; margin-bottom: 20px; max-width: 95%; margin-left: auto; margin-right: auto; background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 4px;">
        <?php echo htmlspecialchars($_SESSION['message_erreur']); 
        unset($_SESSION['message_erreur']); ?>
    </div>
<?php endif; ?>

<?php if (!empty($produits)): ?>
    <table>
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
                    <td><?php echo $produit->id; ?></td>
                    <td><?php echo $produit->nom; ?></td>
                    <td><?php echo $produit->description; ?></td>
                    <td><?php echo $produit->prix; ?> €</td>
                   <td><?php echo $produit->categorie; // Utilise 'categorie' au lieu de 'libelle' ?></td>
                    <td>
                        <a href="indexAdmin.php?controleur=admin&action=editerProduit&id=<?php echo $produit->id; ?>" class="btn-edit">✏️ Modifier</a>
                        <a href="indexAdmin.php?controleur=admin&action=supprimerProduit&id=<?php echo $produit->id; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')" class="btn-delete">🗑️ Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun produit disponible.</p>
<?php endif; ?>
<!-- Style CSS -->
<style>
    /* General styles */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f7fc;
    }

    h1 {
        text-align: center;
        margin-top: 20px;
        font-size: 2em;
        color: #333;
    }

    /* Table styles */
    .table-produits {
        width: 90%;
        margin: 30px auto;
        border-collapse: collapse;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .table-produits th, .table-produits td {
        padding: 15px;
        text-align: left;
        border: 1px solid #ddd;
    }

    .table-produits th {
        background-color: #007bff;
        color: white;
        font-weight: bold;
    }

    .table-produits tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .table-produits tr:hover {
        background-color: #f1f1f1;
    }

    /* Price formatting */
    .table-produits td {
        color: #333;
    }

    .table-produits td:nth-child(4) {
        color: green;
        font-weight: bold;
    }

    /* Add product button */
    .btn-add-product {
        display: inline-block;
        padding: 12px 24px;
        background-color: #28a745;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: bold;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .btn-add-product:hover {
        background-color: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    }

    /* Action buttons */
    .btn-edit, .btn-delete {
        display: inline-block;
        padding: 8px 16px;
        margin: 2px;
        text-decoration: none;
        border-radius: 4px;
        font-weight: bold;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .btn-edit {
        background-color: #007bff;
        color: white;
    }

    .btn-edit:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
    }

    .btn-delete {
        background-color: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background-color: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
    }

    /* Table improvements */
    table {
        width: 95%;
        margin: 30px auto;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    table th {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        padding: 15px;
        text-align: left;
    }

    table td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
    }

    table tr:hover {
        background-color: #f8f9fa;
    }

    table tr:last-child td {
        border-bottom: none;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        table {
            width: 100%;
            margin: 10px 0;
        }

        table th, table td {
            padding: 10px;
            font-size: 14px;
        }
        
        .btn-edit, .btn-delete {
            display: block;
            margin: 5px 0;
            text-align: center;
        }
    }
</style>
