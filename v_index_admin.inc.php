<!-- v_index_admin.inc.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - La Boutique du Roi</title>
    <link href="<?php echo Chemins::STYLES . 'style.css'; ?>" rel="stylesheet" type="text/css">
</head>
<body>
    <h1>Bienvenue sur l'administration de La Boutique du Roi !</h1>

    <nav>
        <ul>
<!--            <li><a href="index.php?controleur=admin&action=afficherProduits">Voir les produits</a></li>-->
            <li><a href="index.php?controleur=admin&action=seDeconnecter">Se déconnecter</a></li>
            <li><a href="index.php?controleur=admin&action=afficherTousLesProduits">Voir tous les produits</a></li>

        </ul>
    </nav>
    
    <h2>Gestion des produits</h2>
    <p>Dans cette section, vous pouvez gérer les produits, les commandes, etc.</p>
    
</body>
</html>
