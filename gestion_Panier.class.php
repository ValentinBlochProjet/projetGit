<?php

class GestionPanier {
    
    /**
     * Ajoute un article au panier
     * @param int $idProduit ID du produit
     * @param int $quantite Quantité à ajouter
     */
    public static function ajouterAuPanier($idProduit, $quantite = 1) {
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }
        
        $quantite = intval($quantite);
        if ($quantite <= 0) $quantite = 1;
        
        if (isset($_SESSION['panier'][$idProduit])) {
            $_SESSION['panier'][$idProduit] += $quantite;
        } else {
            $_SESSION['panier'][$idProduit] = $quantite;
        }
    }
    
    /**
     * Supprime un article du panier
     * @param int $idProduit ID du produit à supprimer
     */
    public static function supprimerDuPanier($idProduit) {
        if (isset($_SESSION['panier'][$idProduit])) {
            unset($_SESSION['panier'][$idProduit]);
        }
    }
    
    /**
     * Met à jour la quantité d'un article
     * @param int $idProduit ID du produit
     * @param int $quantite Nouvelle quantité
     */
    public static function mettreAJourQuantite($idProduit, $quantite) {
        $quantite = intval($quantite);
        if ($quantite <= 0) {
            self::supprimerDuPanier($idProduit);
        } else {
            $_SESSION['panier'][$idProduit] = $quantite;
        }
    }
    
    /**
     * Vide complètement le panier
     */
    public static function viderPanier() {
        $_SESSION['panier'] = [];
    }
    
    /**
     * Retourne le contenu du panier avec les détails des produits
     * @return array Tableau des produits du panier avec détails
     */
    public static function getPanier() {
        if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
            return [];
        }
        
        $panier = [];
        foreach ($_SESSION['panier'] as $idProduit => $quantite) {
            $produit = GestionBoutique::getProduitById($idProduit);
            if ($produit) {
                $panier[] = [
                    'id' => $idProduit,
                    'nom' => $produit->nom,
                    'prix' => $produit->prix,
                    'image' => $produit->image,
                    'quantite' => $quantite,
                    'total' => $produit->prix * $quantite
                ];
            }
        }
        return $panier;
    }
    
    /**
     * Retourne le nombre d'articles dans le panier
     * @return int Nombre total d'articles
     */
    public static function getNombreArticles() {
        if (!isset($_SESSION['panier'])) {
            return 0;
        }
        
        $total = 0;
        foreach ($_SESSION['panier'] as $quantite) {
            $total += $quantite;
        }
        return $total;
    }
    
    /**
     * Retourne le total du panier
     * @return float Total du panier
     */
    public static function getTotalPanier() {
        $panier = self::getPanier();
        $total = 0;
        foreach ($panier as $article) {
            $total += $article['total'];
        }
        return round($total, 2);
    }
}
?>