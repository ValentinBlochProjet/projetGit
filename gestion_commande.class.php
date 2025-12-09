<?php

require_once dirname(__FILE__) . '/ModelePDO.class.php';

class GestionCommande extends ModelePDO {
    private static $pdoCnxBase = null;
    
    private static function seConnecter() {
        if (self::$pdoCnxBase === null) {
            self::$pdoCnxBase = parent::getPDO();
            // S'assurer que le schéma des commandes existe
            self::assurerSchema();
        }
    }

    /**
     * Crée les tables commande et commande_produit si elles n'existent pas
     */
    private static function assurerSchema() {
        try {
            // Table commande
            $sqlCommande = "CREATE TABLE IF NOT EXISTS commande (
                id INT AUTO_INCREMENT PRIMARY KEY,
                client_id INT NOT NULL,
                montant_total DECIMAL(10,2) NOT NULL,
                date_commande DATETIME NOT NULL,
                statut ENUM('en_attente','validee','en_preparation','expediee','livree','annulee') NOT NULL DEFAULT 'en_attente',
                adresse_livraison TEXT NULL,
                INDEX (client_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            self::$pdoCnxBase->exec($sqlCommande);

            // Table commande_produit
            $sqlCommandeProduit = "CREATE TABLE IF NOT EXISTS commande_produit (
                id INT AUTO_INCREMENT PRIMARY KEY,
                commande_id INT NOT NULL,
                produit_id INT NOT NULL,
                quantite INT NOT NULL,
                prix_unitaire DECIMAL(10,2) NOT NULL,
                total_ligne DECIMAL(10,2) NOT NULL,
                INDEX (commande_id),
                INDEX (produit_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            self::$pdoCnxBase->exec($sqlCommandeProduit);
        } catch (Exception $e) {
            error_log('Erreur assurerSchema commandes: ' . $e->getMessage());
        }
    }
    
    /**
     * Crée une nouvelle commande
     * @param int $clientId ID du client
     * @param array $panier Contenu du panier
     * @param float $montantTotal Montant total
     * @param string $adresseLivraison Adresse de livraison
     * @return int|false ID de la commande créée ou false
     */
    public static function creerCommande($clientId, $panier, $montantTotal, $adresseLivraison = null) {
        self::seConnecter();
        
        try {
            // Démarrer une transaction
            self::$pdoCnxBase->beginTransaction();
            
            // Insérer la commande
            $sql = "INSERT INTO commande (client_id, montant_total, date_commande, statut, adresse_livraison) 
                    VALUES (:client_id, :montant_total, NOW(), 'en_attente', :adresse)";
            $stmt = self::$pdoCnxBase->prepare($sql);
            $stmt->bindValue(':client_id', $clientId, PDO::PARAM_INT);
            $stmt->bindValue(':montant_total', $montantTotal, PDO::PARAM_STR);
            $stmt->bindValue(':adresse', $adresseLivraison, PDO::PARAM_STR);
            $stmt->execute();
            
            $commandeId = self::$pdoCnxBase->lastInsertId();
            
            // Insérer les produits de la commande
            $sqlProduit = "INSERT INTO commande_produit (commande_id, produit_id, quantite, prix_unitaire, total_ligne) 
                           VALUES (:commande_id, :produit_id, :quantite, :prix_unitaire, :total_ligne)";
            $stmtProduit = self::$pdoCnxBase->prepare($sqlProduit);
            
            foreach ($panier as $article) {
                if (!isset($article['id']) || !isset($article['quantite']) || !isset($article['prix']) || !isset($article['total'])) {
                    throw new Exception('Article panier invalide: ' . json_encode($article));
                }
                $stmtProduit->bindValue(':commande_id', $commandeId, PDO::PARAM_INT);
                $stmtProduit->bindValue(':produit_id', $article['id'], PDO::PARAM_INT);
                $stmtProduit->bindValue(':quantite', $article['quantite'], PDO::PARAM_INT);
                $stmtProduit->bindValue(':prix_unitaire', $article['prix'], PDO::PARAM_STR);
                $stmtProduit->bindValue(':total_ligne', $article['total'], PDO::PARAM_STR);
                $stmtProduit->execute();
            }
            
            // Valider la transaction
            self::$pdoCnxBase->commit();
            
            return $commandeId;
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            if (self::$pdoCnxBase->inTransaction()) {
                self::$pdoCnxBase->rollBack();
            }
            error_log('Erreur création commande: ' . $e->getMessage());
            error_log('DEBUG clientId=' . $clientId . ' montant=' . $montantTotal . ' adresse=' . ($adresseLivraison ?? '')); 
            error_log('DEBUG panier=' . json_encode($panier));
            return false;
        }
    }
    
    /**
     * Récupère toutes les commandes
     * @return array Liste des commandes
     */
    public static function getToutesLesCommandes() {
        self::seConnecter();
        
        try {
            $sql = "SELECT c.*, cl.pseudo, cl.email, cl.nom, cl.prenom
                    FROM commande c
                    INNER JOIN client cl ON c.client_id = cl.id
                    ORDER BY c.date_commande DESC";
            $stmt = self::$pdoCnxBase->query($sql);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log('Erreur getToutesLesCommandes: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupère les commandes d'un client
     * @param int $clientId ID du client
     * @return array Liste des commandes du client
     */
    public static function getCommandesClient($clientId) {
        self::seConnecter();
        
        try {
            $sql = "SELECT * FROM commande WHERE client_id = :client_id ORDER BY date_commande DESC";
            $stmt = self::$pdoCnxBase->prepare($sql);
            $stmt->bindValue(':client_id', $clientId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log('Erreur getCommandesClient: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupère une commande par son ID
     * @param int $commandeId ID de la commande
     * @return object|false Commande ou false
     */
    public static function getCommandeById($commandeId) {
        self::seConnecter();
        
        try {
            $sql = "SELECT c.*, cl.pseudo, cl.email, cl.nom, cl.prenom
                    FROM commande c
                    INNER JOIN client cl ON c.client_id = cl.id
                    WHERE c.id = :id";
            $stmt = self::$pdoCnxBase->prepare($sql);
            $stmt->bindValue(':id', $commandeId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log('Erreur getCommandeById: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère les produits d'une commande
     * @param int $commandeId ID de la commande
     * @return array Liste des produits de la commande
     */
    public static function getProduitsCommande($commandeId) {
        self::seConnecter();
        
        try {
            $sql = "SELECT cp.*, p.nom, p.image
                    FROM commande_produit cp
                    INNER JOIN produit p ON cp.produit_id = p.id
                    WHERE cp.commande_id = :commande_id";
            $stmt = self::$pdoCnxBase->prepare($sql);
            $stmt->bindValue(':commande_id', $commandeId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log('Erreur getProduitsCommande: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Met à jour le statut d'une commande
     * @param int $commandeId ID de la commande
     * @param string $statut Nouveau statut
     * @return bool Succès ou échec
     */
    public static function changerStatut($commandeId, $statut) {
        self::seConnecter();
        
        $statutsValides = ['en_attente', 'validee', 'en_preparation', 'expediee', 'livree', 'annulee'];
        if (!in_array($statut, $statutsValides)) {
            return false;
        }
        
        try {
            $sql = "UPDATE commande SET statut = :statut WHERE id = :id";
            $stmt = self::$pdoCnxBase->prepare($sql);
            $stmt->bindValue(':statut', $statut, PDO::PARAM_STR);
            $stmt->bindValue(':id', $commandeId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log('Erreur changerStatut: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Supprime une commande
     * @param int $commandeId ID de la commande
     * @return bool Succès ou échec
     */
    public static function supprimerCommande($commandeId) {
        self::seConnecter();
        
        try {
            // Les produits seront supprimés automatiquement grâce à ON DELETE CASCADE
            $sql = "DELETE FROM commande WHERE id = :id";
            $stmt = self::$pdoCnxBase->prepare($sql);
            $stmt->bindValue(':id', $commandeId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log('Erreur supprimerCommande: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère les statistiques des commandes
     * @return array Statistiques
     */
    public static function getStatistiques() {
        self::seConnecter();
        
        try {
            $sql = "SELECT 
                        COUNT(*) as total_commandes,
                        SUM(CASE WHEN statut = 'en_attente' THEN 1 ELSE 0 END) as en_attente,
                        SUM(CASE WHEN statut = 'validee' THEN 1 ELSE 0 END) as validees,
                        SUM(CASE WHEN statut = 'expediee' THEN 1 ELSE 0 END) as expediees,
                        SUM(CASE WHEN statut = 'livree' THEN 1 ELSE 0 END) as livrees,
                        SUM(montant_total) as chiffre_affaires
                    FROM commande";
            $stmt = self::$pdoCnxBase->query($sql);
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log('Erreur getStatistiques: ' . $e->getMessage());
            return null;
        }
    }
}
?>
