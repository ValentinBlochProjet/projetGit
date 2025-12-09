<?php

require_once dirname(__FILE__) . '/ModelePDO.class.php';

class GestionClient extends ModelePDO {
    
    // <editor-fold defaultstate="collapsed" desc="Champs statiques">
    private static $pdoCnxBase = null;
    private static $pdoStResults = null;
    private static $requete = "";
    private static $resultat = null;
    // </editor-fold>

    /**
     * Permet de se connecter à la base de données
     */
    // <editor-fold defaultstate="collapsed" desc="Connexion BD">
    public static function seConnecter() {
        if (!isset(self::$pdoCnxBase)) {
            try {
                // Utilise la connexion de la classe mère ModelePDO
                self::$pdoCnxBase = parent::getPDO();
            } catch (Exception $e) {
                error_log('Erreur connexion BD: ' . $e->getMessage());
                return false;
            }
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Authentification (Inscription/Connexion)">
    /**
     * Inscrit un nouveau client
     * @param string $pseudo Pseudo du client
     * @param string $email Email du client
     * @param string $password Mot de passe
     * @param string $nom Nom complet
     * @param string $prenom Prénom
     * @return array ['succes' => bool, 'message' => string, 'idClient' => int ou null]
     */
    public static function inscrireClient($pseudo, $email, $password, $nom, $prenom) {
        self::seConnecter();
        
        try {
            // Vérifier que l'email n'existe pas déjà
            $requete = "SELECT COUNT(*) as nb FROM client WHERE email = :email";
            $stmt = self::$pdoCnxBase->prepare($requete);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch();
            
            if ($result->nb > 0) {
                return ['succes' => false, 'message' => 'Cet email est déjà utilisé'];
            }
            
            // Hasher le mot de passe
            $passwordHash = sha1($password);
            
            // Insérer le nouveau client
            // Try with pseudo first, fallback without if column doesn't exist
            $requete = "INSERT INTO client (pseudo, email, password, nom, prenom, date_creation, isAdmin) 
                        VALUES (:pseudo, :email, :password, :nom, :prenom, NOW(), 0)";
            $stmt = self::$pdoCnxBase->prepare($requete);
            $stmt->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':password', $passwordHash, PDO::PARAM_STR);
            $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindValue(':prenom', $prenom, PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                $idClient = self::$pdoCnxBase->lastInsertId();
                return ['succes' => true, 'message' => 'Inscription réussie! Vous pouvez maintenant vous connecter.', 'idClient' => $idClient];
            }
            
            return ['succes' => false, 'message' => 'Erreur lors de l\'inscription'];
            
        } catch (Exception $e) {
            error_log('Erreur inscription: ' . $e->getMessage());
            return ['succes' => false, 'message' => 'Erreur lors de l\'inscription: ' . $e->getMessage()];
        }
    }

    /**
     * Connecte un client
     * @param string $email Email du client
     * @param string $password Mot de passe
     * @return array ['succes' => bool, 'message' => string, 'client' => obj ou null]
     */
    public static function connecterClient($email, $password) {
        self::seConnecter();
        
        try {
            $passwordHash = sha1($password);
            
            $requete = "SELECT id, email, nom, prenom FROM client WHERE email = :email AND password = :password";
            $stmt = self::$pdoCnxBase->prepare($requete);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':password', $passwordHash, PDO::PARAM_STR);
            $stmt->execute();
            
            $client = $stmt->fetch();
            
            if ($client) {
                return ['succes' => true, 'message' => 'Connexion réussie!', 'client' => $client];
            }
            
            return ['succes' => false, 'message' => 'Email ou mot de passe incorrect'];
            
        } catch (Exception $e) {
            error_log('Erreur connexion: ' . $e->getMessage());
            return ['succes' => false, 'message' => 'Erreur lors de la connexion'];
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Accès clients (lecture/écriture)">
    /**
     * Récupère les informations d'un client
     * @param int $idClient ID du client
     * @return object Client ou null
     */
    public static function getClient($idClient) {
        self::seConnecter();
        
        try {
            $requete = "SELECT * FROM client WHERE id = :id";
            $stmt = self::$pdoCnxBase->prepare($requete);
            $stmt->bindValue(':id', $idClient, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch();
            
        } catch (Exception $e) {
            error_log('Erreur getClient: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Vérifie si un pseudo existe
     * @param string $pseudo Pseudo à vérifier
     * @return bool true si existe, false sinon
     */
    public static function pseudoExists($pseudo) {
        self::seConnecter();
        
        try {
            $requete = "SELECT COUNT(*) as nb FROM client WHERE pseudo = :pseudo";
            $stmt = self::$pdoCnxBase->prepare($requete);
            $stmt->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch();
            
            return $result->nb > 0;
        } catch (Exception $e) {
            error_log('Erreur pseudoExists: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifie si un client est connecté
     * @return bool true si connecté
     */
    public static function isConnecte() {
        return isset($_SESSION['client_connecte']) && !empty($_SESSION['client_connecte']);
    }

    /**
     * Retourne tous les clients
     * @return array Liste des clients
     */
    public static function getTousLesClients() {
        self::seConnecter();
        
        try {
            $requete = "SELECT * FROM client ORDER BY id DESC";
            $stmt = self::$pdoCnxBase->prepare($requete);
            $stmt->execute();
            
            // Retourner comme tableau associatif, pas comme objet
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Erreur getTousLesClients: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère un client par son ID
     * @param int $id ID du client
     * @return array Client ou null
     */
    public static function getClientById($id) {
        self::seConnecter();
        
        try {
            $requete = "SELECT * FROM client WHERE id = :id";
            $stmt = self::$pdoCnxBase->prepare($requete);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Erreur getClientById: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Met à jour un client
     * @param int $id ID du client
     * @param array $data Données à mettre à jour
     * @return bool true si succès
     */
    public static function editerClient($id, $data) {
        self::seConnecter();
        
        try {
            $fields = [];
            $values = [];
            
            if (isset($data['pseudo'])) {
                $fields[] = 'pseudo = :pseudo';
                $values[':pseudo'] = $data['pseudo'];
            }
            if (isset($data['email'])) {
                $fields[] = 'email = :email';
                $values[':email'] = $data['email'];
            }
            if (isset($data['nom'])) {
                $fields[] = 'nom = :nom';
                $values[':nom'] = $data['nom'];
            }
            if (isset($data['prenom'])) {
                $fields[] = 'prenom = :prenom';
                $values[':prenom'] = $data['prenom'];
            }
            if (isset($data['password'])) {
                $fields[] = 'password = :password';
                $values[':password'] = $data['password'];
            }
            if (isset($data['isAdmin'])) {
                $fields[] = 'isAdmin = :isAdmin';
                $values[':isAdmin'] = $data['isAdmin'] ? 1 : 0;
            }
            
            if (empty($fields)) {
                return false;
            }
            
            $values[':id'] = $id;
            $requete = "UPDATE client SET " . implode(', ', $fields) . " WHERE id = :id";
            
            $stmt = self::$pdoCnxBase->prepare($requete);
            return $stmt->execute($values);
            
        } catch (Exception $e) {
            error_log('Erreur editerClient: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un client
     * @param int $id ID du client
     * @return bool true si succès
     */
    public static function supprimerClient($id) {
        self::seConnecter();
        
        try {
            $requete = "DELETE FROM client WHERE id = :id";
            $stmt = self::$pdoCnxBase->prepare($requete);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log('Erreur supprimerClient: ' . $e->getMessage());
            return false;
        }
    }
    // </editor-fold>
}
?>
