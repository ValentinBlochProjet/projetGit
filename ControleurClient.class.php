<?php

require_once dirname(__FILE__) . '/../../configs/chemins.class.php';

class ControleurClient {
    
    /**
     * Affiche la page d'accueil avec les produits
     */
    public function afficherAccueil() {
        $categories = GestionBoutique::getLesCategories();
        $produits = GestionBoutique::getLesProduits();
        
        require Chemins::VUES_PERMANENTES . 'v_entete.inc.php';
        require Chemins::VUES . 'v_accueil.inc.php';
        require Chemins::VUES_PERMANENTES . 'v_pied.inc.php';
    }
    
    /**
     * Affiche les produits d'une catégorie
     */
    public function afficherProduits() {
        if (!isset($_GET['categorie'])) {
            header("Location: index.php");
            exit();
        }
        
        $categorie = htmlspecialchars($_GET['categorie']);
        VariablesGlobales::$lesProduits = GestionBoutique::getLesProduitsByCategorie($categorie);
        VariablesGlobales::$libelleCategorie = $categorie;
        
        require Chemins::VUES_PERMANENTES . 'v_entete.inc.php';
        require Chemins::VUES . 'v_produits.inc.php';
        require Chemins::VUES_PERMANENTES . 'v_pied.inc.php';
    }
    
    /**
     * Ajoute un produit au panier
     */
    public function ajouterAuPanier() {
        if (!isset($_GET['id'])) {
            $_SESSION['message_erreur'] = "Aucun produit sélectionné.";
            header("Location: index.php");
            exit();
        }
        
        $idProduit = intval($_GET['id']);
        $quantite = isset($_GET['quantite']) ? intval($_GET['quantite']) : 1;
        
        // Vérifier que le produit existe
        $produit = GestionBoutique::getProduitById($idProduit);
        if (!$produit) {
            $_SESSION['message_erreur'] = "Produit introuvable.";
            header("Location: index.php");
            exit();
        }
        
        // Ajouter au panier
        GestionPanier::ajouterAuPanier($idProduit, $quantite);
        
        // Message de confirmation
        $_SESSION['message_succes'] = "✓ {$produit->nom} ajouté au panier (x{$quantite})";
        
        // Redirection vers le panier
        header("Location: index.php?controleur=client&action=afficherPanier");
        exit();
    }
    
    /**
     * Affiche le panier
     */
    public function afficherPanier() {
        $panier = GestionPanier::getPanier();
        $total = GestionPanier::getTotalPanier();
        $nombreArticles = GestionPanier::getNombreArticles();
        
        require Chemins::VUES_PERMANENTES . 'v_entete.inc.php';
        require Chemins::VUES . 'v_panier.inc.php';
        require Chemins::VUES_PERMANENTES . 'v_pied.inc.php';
    }
    
    /**
     * Supprime un produit du panier
     */
    public function supprimerDuPanier() {
        if (!isset($_GET['id'])) {
            header("Location: index.php?controleur=client&action=afficherPanier");
            exit();
        }
        
        $idProduit = intval($_GET['id']);
        GestionPanier::supprimerDuPanier($idProduit);
        
        header("Location: index.php?controleur=client&action=afficherPanier");
        exit();
    }
    
    /**
     * Met à jour la quantité d'un article
     */
    public function mettreAJourQuantite() {
        if (!isset($_POST['id']) || !isset($_POST['quantite'])) {
            header("Location: index.php?controleur=client&action=afficherPanier");
            exit();
        }
        
        $idProduit = intval($_POST['id']);
        $quantite = intval($_POST['quantite']);
        
        GestionPanier::mettreAJourQuantite($idProduit, $quantite);
        
        header("Location: index.php?controleur=client&action=afficherPanier");
        exit();
    }
    
    /**
     * Vide le panier
     */
    public function viderPanier() {
        GestionPanier::viderPanier();
        header("Location: index.php?controleur=client&action=afficherPanier");
        exit();
    }
    
    /**
     * Valide et crée la commande
     */
    public function validerCommande() {
        // Vérifier que l'utilisateur est connecté (accepte plusieurs clés de session)
        $clientId = null;
        if (isset($_SESSION['id_client']) && is_numeric($_SESSION['id_client'])) {
            $clientId = intval($_SESSION['id_client']);
        } elseif (isset($_SESSION['client_connecte']) && is_numeric($_SESSION['client_connecte'])) {
            // Compatibilité avec l'existant: certaines pages utilisent 'client_connecte'
            $clientId = intval($_SESSION['client_connecte']);
        }

        if (!$clientId) {
            $_SESSION['message_erreur'] = "Vous devez être connecté pour passer commande.";
            header("Location: index.php?controleur=auth&action=afficherConnexion");
            exit();
        }
        
        $panier = GestionPanier::getPanier();
        
        if (empty($panier)) {
            $_SESSION['message_erreur'] = "Votre panier est vide.";
            header("Location: index.php?controleur=client&action=afficherPanier");
            exit();
        }
        
        $total = GestionPanier::getTotalPanier();
        
        // Adresse de livraison (optionnel)
        $adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : null;
        
        // Créer la commande
        require_once dirname(__FILE__) . '/../modeles/gestion_commande.class.php';
        $commandeId = GestionCommande::creerCommande($clientId, $panier, $total, $adresse);
        
        if ($commandeId) {
            // Vider le panier
            GestionPanier::viderPanier();
            
            $_SESSION['message_succes'] = "Commande n°$commandeId créée avec succès !";
            header("Location: index.php?controleur=client&action=afficherMesCommandes");
        } else {
            $_SESSION['message_erreur'] = "Erreur lors de la création de la commande.";
            header("Location: index.php?controleur=client&action=afficherPanier");
        }
        exit();
    }
    
    /**
     * Affiche les commandes du client
     */
    public function afficherMesCommandes() {
        $clientId = null;
        if (isset($_SESSION['id_client']) && is_numeric($_SESSION['id_client'])) {
            $clientId = intval($_SESSION['id_client']);
        } elseif (isset($_SESSION['client_connecte']) && is_numeric($_SESSION['client_connecte'])) {
            $clientId = intval($_SESSION['client_connecte']);
        }

        if (!$clientId) {
            header("Location: index.php?controleur=auth&action=afficherConnexion");
            exit();
        }
        
        require_once dirname(__FILE__) . '/../modeles/gestion_commande.class.php';
        $commandes = GestionCommande::getCommandesClient($clientId);
        
        require Chemins::VUES_PERMANENTES . 'v_entete.inc.php';
        require Chemins::VUES . 'v_mes_commandes.inc.php';
        require Chemins::VUES_PERMANENTES . 'v_pied.inc.php';
    }
    
    /**
     * Affiche le détail d'une commande
     */
    public function afficherDetailCommande() {
        $clientId = null;
        if (isset($_SESSION['id_client']) && is_numeric($_SESSION['id_client'])) {
            $clientId = intval($_SESSION['id_client']);
        } elseif (isset($_SESSION['client_connecte']) && is_numeric($_SESSION['client_connecte'])) {
            $clientId = intval($_SESSION['client_connecte']);
        }

        if (!$clientId || !isset($_GET['id'])) {
            header("Location: index.php");
            exit();
        }
        
        require_once dirname(__FILE__) . '/../modeles/gestion_commande.class.php';
        $commandeId = intval($_GET['id']);
        $commande = GestionCommande::getCommandeById($commandeId);
        
        // Vérifier que c'est bien la commande du client connecté
        if (!$commande || $commande->client_id != $clientId) {
            header("Location: index.php");
            exit();
        }
        
        $produits = GestionCommande::getProduitsCommande($commandeId);
        
        require Chemins::VUES_PERMANENTES . 'v_entete.inc.php';
        require Chemins::VUES . 'v_detail_commande.inc.php';
        require Chemins::VUES_PERMANENTES . 'v_pied.inc.php';
    }
}
?>
