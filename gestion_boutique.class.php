<?php

//Inclusion de la classe MysqlConfig
// à partir de l'emplacement actuel (dossier "modeles")
//require_once '../../configs/mysql_config.class.php';

require_once dirname(__FILE__) . '/ModelePDO.class.php';

class GestionBoutique extends ModelePDO {
// <editor-fold defaultstate="collapsed" desc="Champs statiques">
// <editor-fold defaultstate="collapsed" desc="région Champs statiques"> 

    /**
     * Objet de la classe PDO
     * @var PDO
     */
    private static $pdoCnxBase = null;

    /**
     * Objet de la classe PDOStatement
     * @var PDOStatement
     */
    private static $pdoStResults = null;
    private static $requete = "";
    private static $resultat = null;
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="Méthodes de connexion"> 

    /**
     * Permet de se connecter à la base de données
     */
    public static function seConnecter() {
        if (!isset(self::$pdoCnxBase)) { //S'il n'y a pas encore eu de connexion
            try {
                // Utilise la connexion fournie par la classe mère ModelePDO
                self::$pdoCnxBase = parent::getPDO();
            } catch (Exception $e) {
                echo 'Erreur : ' . $e->getMessage() . '<br />';
                echo 'Code : ' . $e->getCode();
            }
        }
    }

    public static function seDeconnecter() {
        self::$pdoCnxBase = null;
// si on n'appelle pas la méthode, la déconnexion a lieu en fin de script
    }
// </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Catégories"> 
    /**
     * Retourne la liste des Catégories
     * @return type Tableau d'objets 
     */
        public static function getLesCategories() {
        self::seConnecter();
        
        self::$requete = "SELECT * FROM Categorie";
        
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetchAll();
        
        self::$pdoStResults->closeCursor();
        
        return self::$resultat;
    }
    // </editor-fold>
           
    // <editor-fold defaultstate="collapsed" desc="Produits (tous et par catégorie)"> 
    public static function getLesProduits() {
        self::seConnecter();
        
        self::$requete = "SELECT P.*, C.libelle AS categorie FROM Produit P
                      JOIN Categorie C ON P.idCategorie = C.id";
        
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetchAll();
        
        self::$pdoStResults->closeCursor();
        
        return self::$resultat;
    }
    public static function getLesProduitsByCategorie($libelleCategorie) {
        self::seConnecter();
        
        // IMPORTANT: sélectionner explicitement les colonnes pour éviter les collisions d'alias (id de Categorie vs id de Produit)
        self::$requete = "SELECT 
                                P.id AS id,
                                P.nom,
                                P.description,
                                P.prix,
                                P.image,
                                P.idCategorie,
                                C.libelle AS categorie
                           FROM Produit P 
                           INNER JOIN Categorie C ON P.idCategorie = C.id
                           WHERE C.libelle = :libCateg";
        
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->bindValue('libCateg', $libelleCategorie);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetchAll();
        
        self::$pdoStResults->closeCursor();
        
        return self::$resultat;
    }
    
    public static function getProduitById($idProduit) {
            try {
                self::seConnecter();
                
                self::$requete = "SELECT P.id, P.nom, P.description, P.prix, P.image, P.idCategorie, 
                                 C.libelle as categorie 
                                 FROM Produit P 
                                 LEFT JOIN Categorie C ON P.idCategorie = C.id 
                                 WHERE P.id = :idProd";
                
                self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
                self::$pdoStResults->bindValue(':idProd', $idProduit, PDO::PARAM_INT);
                self::$pdoStResults->execute();
                self::$resultat = self::$pdoStResults->fetch();
                
                self::$pdoStResults->closeCursor();
                
                return self::$resultat;
            } catch (Exception $e) {
                error_log("Erreur getProduitById($idProduit): " . $e->getMessage());
                return false;
            }
}
    // </editor-fold>
    public static function getNbProduits() {
        self::seConnecter();
 
        self::$requete = "SELECT Count(*) AS nbProduits FROM Produit";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetch();
 
        self::$pdoStResults->closeCursor();
 
        return self::$resultat->nbProduits;
}

Private static function getLesTuplesbytables($table){
    self::$requete = "SELECT * FROM $table";
            
            self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
            self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
            self::$pdoStResults->execute();
            self::$resultat = self::$pdoStResults->fetchALL();
            
            self::$pdoStResults->closeCursor();
            
            return self::$resultat;

}

    // <editor-fold defaultstate="collapsed" desc="CRUD Produits"> 
    /**
     * Ajoute un nouveau produit dans la base de données
     * @param string $nom Nom du produit
     * @param string $description Description du produit
     * @param float $prix Prix du produit
     * @param int $idCategorie ID de la catégorie
     * @param string $nomImage Nom de l'image
     * @return bool true si succès, false sinon
     */
    public static function ajouterProduitBD($nom, $description, $prix, $idCategorie, $nomImage) {
        self::seConnecter();
        
        try {
            self::$requete = "INSERT INTO Produit (nom, description, prix, image, idCategorie) 
                              VALUES (:nom, :description, :prix, :image, :idCategorie)";
            
            self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
            self::$pdoStResults->bindValue(':nom', $nom);
            self::$pdoStResults->bindValue(':description', $description);
            self::$pdoStResults->bindValue(':prix', $prix);
            self::$pdoStResults->bindValue(':image', $nomImage);
            self::$pdoStResults->bindValue(':idCategorie', $idCategorie);
            
            return self::$pdoStResults->execute();
        } catch (Exception $e) {
            echo 'Erreur lors de l\'ajout : ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Édite un produit dans la base de données
     * @param int $id ID du produit
     * @param string $nom Nom du produit
     * @param string $description Description du produit
     * @param float $prix Prix du produit
     * @param int $idCategorie ID de la catégorie
     * @param string $nomImage Nom de l'image (optionnel)
     * @return bool true si succès, false sinon
     */
    public static function editerProduitBD($id, $nom, $description, $prix, $idCategorie, $nomImage = null) {
        self::seConnecter();
        
        try {
            error_log("DEBUG editerProduitBD: id=$id, nom=$nom, prix=$prix, idCategorie=$idCategorie, nomImage=$nomImage");
            
            if ($nomImage) {
                // Avec nouvelle image
                self::$requete = "UPDATE Produit SET nom=:nom, description=:description, prix=:prix, image=:image, idCategorie=:idCategorie WHERE id=:id";
            } else {
                // Sans image
                self::$requete = "UPDATE Produit SET nom=:nom, description=:description, prix=:prix, idCategorie=:idCategorie WHERE id=:id";
            }
            
            self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
            
            self::$pdoStResults->bindValue(':id', (int)$id, PDO::PARAM_INT);
            self::$pdoStResults->bindValue(':nom', $nom, PDO::PARAM_STR);
            self::$pdoStResults->bindValue(':description', $description, PDO::PARAM_STR);
            self::$pdoStResults->bindValue(':prix', (float)$prix, PDO::PARAM_STR);
            self::$pdoStResults->bindValue(':idCategorie', (int)$idCategorie, PDO::PARAM_INT);
            
            if ($nomImage) {
                self::$pdoStResults->bindValue(':image', $nomImage, PDO::PARAM_STR);
            }
            
            $resultat = self::$pdoStResults->execute();
            error_log("DEBUG editerProduitBD result: " . ($resultat ? "true" : "false"));
            self::$pdoStResults->closeCursor();
            
            return $resultat;
        } catch (Exception $e) {
            error_log('Erreur lors de la modification du produit : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un produit de la base de données
     * @param int $id ID du produit
     * @return bool true si succès, false sinon
     */
    public static function supprimerProduitBD($id) {
        self::seConnecter();
        
        try {
            self::$requete = "DELETE FROM Produit WHERE id=:id";
            self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
            self::$pdoStResults->bindValue(':id', $id);
            
            return self::$pdoStResults->execute();
        } catch (Exception $e) {
            echo 'Erreur lors de la suppression : ' . $e->getMessage();
            return false;
        }
    }
    // </editor-fold>

    /**
     * Vérifie si l'utilisateur est un administrateur présent dans la base
     * @param string $login Login de l'utilisateur
     * @param string $passe Passe de l'utilisateur
     * @return bool Booléen
     */
    public static function isAdminOK($login, $passe) {
        self::seConnecter();
        self::$requete = "SELECT * FROM Utilisateur where login=:login and passe=:passe";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        if (self::$pdoStResults === false) {
            throw new Exception('Préparation de la requête a échoué');
        }
        self::$pdoStResults->bindValue(':login', $login, PDO::PARAM_STR);
        self::$pdoStResults->bindValue(':passe', sha1($passe));
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetch();
        self::$pdoStResults->closeCursor();
        if ((self::$resultat != null) and (self::$resultat->isAdmin))
            return true;
        else
            return false;
    }    
    

// Fin classe


}

?>

<?php
//var_dump(GestionBoutique::isAdminOK("grandChef","passeGrandChef"));
//var_dump(GestionBoutique::isAdminOK("petitChef","passePetitChef"));
////Ex 1
//$leproduit = GestionBoutique::getproduitById(1);
//
//echo "produit retourné : <br/>";
//echo "------------------<br/>";
//echo "id :$leproduit->id<br/>";
//echo "nom :$leproduit->nom<br/>";
//echo "description :$leproduit->description<br/>";
//echo "Fichier de l'image :$leproduit->image<br/>";

////////////////////////////////////////////////////////////////
//Ex2
//
//$lesCategories = GestionBoutique::getlesCategories();
////var_dump($lesCategories);
//echo "il y a ". count($lesCategories)."catégories dans la base:</br>";
//echo "---------------------------------------------</br>";
//Foreach ($lesCategories as $uneCategorie)
//{
//    echo "$uneCategorie->libelle (catégorie $uneCategorie->id)</br>";
//}
//--------------------------------------------------------------------
