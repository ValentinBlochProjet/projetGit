<?php

require_once dirname(__FILE__) . '/../../configs/chemins.class.php';

class ControleurAdmin {
    
public function __construct() {
        // Constructeur vide pour l'instant
    }

    /**
     * Traite l'upload d'une image et retourne le nom du fichier
     * @param array $fichier Tableau $_FILES
     * @return string|false Nom du fichier ou false en cas d'erreur
     */
    private function traiterUploadImage($fichier) {
        // Extensions autorisées
        $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'gif'];
        
        // Récupérer l'extension du fichier
        $infoFichier = pathinfo($fichier['name']);
        $extension = strtolower($infoFichier['extension']);
        
        // Vérifier l'extension
        if (!in_array($extension, $extensionsAutorisees)) {
            return false;
        }
        
        // Vérifier la taille (max 2MB)
        if ($fichier['size'] > 2097152) {
            return false;
        }
        
        // Vérifier le type MIME
        $mimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fichier['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $mimeTypes)) {
            return false;
        }
        
        // Générer un nom unique pour le fichier
        $nomFichier = uniqid() . '_' . time() . '.' . $extension;
        
        // Chemin de destination
        $cheminDestination = Chemins::IMAGES_PRODUITS . $nomFichier;
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir(Chemins::IMAGES_PRODUITS)) {
            mkdir(Chemins::IMAGES_PRODUITS, 0755, true);
        }
        
        // Déplacer le fichier
        if (move_uploaded_file($fichier['tmp_name'], $cheminDestination)) {
            return $nomFichier;
        }
        
        return false;
    }

    /**
     * Affiche la page de connexion ou le dashboard admin
     */
    public function afficherIndex() {
        // Charger l'entête admin
        require Chemins::VUES_ADMIN . 'v_entete_admin.inc.php';
        
        // Vérifier si un cookie de connexion existe
        if (isset($_COOKIE['login_admin'])) {
            $_SESSION['login_admin'] = $_COOKIE['login_admin'];
        }

        // Si l'utilisateur est connecté, afficher le dashboard
        if (isset($_SESSION['login_admin'])) {
            $this->dashboard();
        } else {
            // Sinon, afficher la page de connexion
            require Chemins::VUES_ADMIN . 'v_connexion.inc.php';
        }
        
        // Charger le pied de page admin
        require Chemins::VUES_ADMIN . 'v_pied_admin.inc.php';
    }

    /**
     * Vérifie les identifiants de connexion
     */
    public function verifierConnexion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = isset($_POST['login']) ? trim($_POST['login']) : '';
            $passe = isset($_POST['passe']) ? $_POST['passe'] : '';

            // Vérifier les identifiants
            if (GestionBoutique::isAdminOK($login, $passe)) {
                // Connexion réussie
                $_SESSION['login_admin'] = $login;

                // Message de succès
                $_SESSION['message_succes_admin'] = "Connexion administrateur réussie. Bienvenue, $login !";

                // Gestion de la connexion automatique
                if (isset($_POST['connexion_auto'])) {
                    setcookie('login_admin', $login, time() + (7 * 24 * 3600), '', '', false, true);
                }

                // Redirection vers le dashboard
                header("Location: index.php?controleur=admin&action=afficherIndex");
                exit();
            } else {
                // Identifiants incorrects
                $_SESSION['erreur_connexion'] = "Identifiants incorrects. Veuillez réessayer.";
                header("Location: index.php?controleur=admin&action=afficherIndex");
                exit();
            }
        }
    }

    /**
     * Affiche le dashboard admin
     */
    public function dashboard() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=admin&action=afficherIndex");
            exit();
        }

        // Charger l'entête admin
        require Chemins::VUES_ADMIN . 'v_entete_admin.inc.php';
        
        // Récupérer les statistiques
        $totalProduits = count(GestionBoutique::getLesProduits());
        $stats = [
            'totalProduits' => $totalProduits,
            'totalCommandes' => 12,
            'totalClients' => 128,
            'chiffreAffaires' => '2450€'
        ];

        // Récupérer les produits
        $produits = GestionBoutique::getLesProduits();

        // Charger la vue du dashboard
        require Chemins::VUES_ADMIN . 'v_dashboard.inc.php';
        
        // Charger le pied de page admin
        require Chemins::VUES_ADMIN . 'v_pied_admin.inc.php';
    }

    /**
     * Affiche la liste de tous les produits
     */
    public function afficherTousLesProduits() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=admin&action=afficherIndex");
            exit();
        }

        // Charger l'entête admin
        require Chemins::VUES_ADMIN . 'v_entete_admin.inc.php';
        
        // Récupérer tous les produits
        $produits = GestionBoutique::getLesProduits();

        // Charger la vue des produits
        require Chemins::VUES_ADMIN . 'v_gerer_produits.inc.php';
        
        // Charger le pied de page admin
        require Chemins::VUES_ADMIN . 'v_pied_admin.inc.php';
    }

    /**
     * Affiche la liste des commandes
     */
    public function afficherCommandes() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=admin&action=afficherIndex");
            exit();
        }

        // Charger l'entête admin
        require Chemins::VUES_ADMIN . 'v_entete_admin.inc.php';
        
        // Récupérer les commandes réelles
        require_once dirname(__FILE__) . '/../modeles/gestion_commande.class.php';
        $commandes = GestionCommande::getToutesLesCommandes();

        // Charger la vue des commandes
        require Chemins::VUES_ADMIN . 'v_gerer_commandes.inc.php';
        
        // Charger le pied de page admin
        require Chemins::VUES_ADMIN . 'v_pied_admin.inc.php';
    }

    /**
     * Affiche le détail d'une commande (admin)
     */
    public function afficherDetailCommandeAdmin() {
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=admin&action=afficherIndex");
            exit();
        }

        if (!isset($_GET['id'])) {
            header("Location: index.php?controleur=admin&action=afficherCommandes");
            exit();
        }

        require_once dirname(__FILE__) . '/../modeles/gestion_commande.class.php';
        $commandeId = intval($_GET['id']);
        $commande = GestionCommande::getCommandeById($commandeId);
        $produits = GestionCommande::getProduitsCommande($commandeId);

        // Entête
        require Chemins::VUES_ADMIN . 'v_entete_admin.inc.php';
        // Vue détail
        require Chemins::VUES_ADMIN . 'v_commande_detail_admin.inc.php';
        // Pied
        require Chemins::VUES_ADMIN . 'v_pied_admin.inc.php';
    }

    /**
     * Affiche la liste des utilisateurs
     */
    public function afficherUtilisateurs() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['login_admin'])) {
            header("Location: indexAdmin.php?controleur=admin&action=afficherIndex");
            exit();
        }

        // Charger l'entête admin
        require Chemins::VUES_ADMIN . 'v_entete_admin.inc.php';
        
        // Récupérer tous les utilisateurs
        require_once dirname(__FILE__) . '/../modeles/gestion_client.class.php';
        $gestionClient = new GestionClient();
        $utilisateurs = $gestionClient->getTousLesClients();
        
        $messages = [];
        
        // Charger la vue des utilisateurs
        require Chemins::VUES_ADMIN . 'v_gerer_utilisateurs.inc.php';
        
        // Charger le pied de page admin
        require Chemins::VUES_ADMIN . 'v_pied_admin.inc.php';
    }

    /**
     * Édite un utilisateur
     */
    public function editerUtilisateur() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['login_admin'])) {
            header("Location: indexAdmin.php?controleur=admin&action=afficherIndex");
            exit();
        }

        if (!isset($_GET['id'])) {
            header("Location: indexAdmin.php?controleur=admin&action=afficherUtilisateurs");
            exit();
        }

        $id = intval($_GET['id']);
        require_once dirname(__FILE__) . '/../modeles/gestion_client.class.php';
        $gestionClient = new GestionClient();
        $utilisateur = $gestionClient->getClientById($id);

        if (!$utilisateur) {
            header("Location: indexAdmin.php?controleur=admin&action=afficherUtilisateurs");
            exit();
        }

        $messages = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pseudo = isset($_POST['pseudo']) ? trim($_POST['pseudo']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
            $prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $isAdmin = isset($_POST['isAdmin']) ? 1 : 0;

            // Validation
            if (empty($pseudo) || empty($email)) {
                $messages['erreur'] = 'Le pseudo et l\'email sont obligatoires.';
            } else {
                $data = [
                    'pseudo' => $pseudo,
                    'email' => $email,
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'isAdmin' => $isAdmin
                ];

                // Ajouter le mot de passe s'il est fourni
                if (!empty($password)) {
                    if (strlen($password) < 6) {
                        $messages['erreur'] = 'Le mot de passe doit faire au moins 6 caractères.';
                    } else {
                        $data['password'] = sha1($password);
                    }
                }

                // Si pas d'erreur, mettre à jour
                if (!isset($messages['erreur'])) {
                    if ($gestionClient->editerClient($id, $data)) {
                        $messages['succes'] = 'Client modifié avec succès !';
                        // Recharger les données
                        $utilisateur = $gestionClient->getClientById($id);
                    } else {
                        $messages['erreur'] = 'Erreur lors de la modification du client.';
                    }
                }
            }
        }

        // Charger l'entête admin
        require Chemins::VUES_ADMIN . 'v_entete_admin.inc.php';
        require Chemins::VUES_ADMIN . 'v_editer_utilisateur.inc.php';
        require Chemins::VUES_ADMIN . 'v_pied_admin.inc.php';
    }

    /**
     * Bascule le statut admin d'un utilisateur
     */
    public function toggleAdmin() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['login_admin'])) {
            header("Location: indexAdmin.php?controleur=admin&action=afficherIndex");
            exit();
        }

        if (!isset($_GET['id'])) {
            header("Location: indexAdmin.php?controleur=admin&action=afficherUtilisateurs");
            exit();
        }

        $id = intval($_GET['id']);
        require_once dirname(__FILE__) . '/../modeles/gestion_client.class.php';
        $gestionClient = new GestionClient();
        $utilisateur = $gestionClient->getClientById($id);

        if ($utilisateur) {
            $newAdminStatus = ($utilisateur['isAdmin'] == 1) ? 0 : 1;
            $gestionClient->editerClient($id, ['isAdmin' => $newAdminStatus]);
        }

        header("Location: indexAdmin.php?controleur=admin&action=afficherUtilisateurs");
        exit();
    }

    /**
     * Supprime un utilisateur
     */
    public function supprimerUtilisateur() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['login_admin'])) {
            header("Location: indexAdmin.php?controleur=admin&action=afficherIndex");
            exit();
        }

        if (!isset($_GET['id'])) {
            header("Location: indexAdmin.php?controleur=admin&action=afficherUtilisateurs");
            exit();
        }

        $id = intval($_GET['id']);
        require_once dirname(__FILE__) . '/../modeles/gestion_client.class.php';
        $gestionClient = new GestionClient();

        // Ne pas supprimer le compte admin
        $utilisateur = $gestionClient->getClientById($id);
        if ($utilisateur && $utilisateur['isAdmin'] == 1) {
            $_SESSION['message_erreur'] = 'Impossible de supprimer un administrateur.';
        } else {
            if ($gestionClient->supprimerClient($id)) {
                $_SESSION['message_succes'] = 'Client supprimé avec succès !';
            } else {
                $_SESSION['message_erreur'] = 'Erreur lors de la suppression du client.';
            }
        }

        header("Location: indexAdmin.php?controleur=admin&action=afficherUtilisateurs");
        exit();
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function seDeconnecter() {
        // Supprimer les variables de session
        $_SESSION = array();
        
        // Détruire la session
        session_destroy();
        
        // Supprimer le cookie
        setcookie('login_admin', '', time() - 3600, '', '', false, true);

        // Redirection vers l'accueil
        header("Location: index.php");
        exit();
    }

    /**
     * Ajoute un nouveau produit
     */
    public function ajouterProduit() {
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=admin&action=afficherIndex");
            exit();
        }

        // Récupérer les catégories
        $categories = GestionBoutique::getLesCategories();
        
        $messages = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Valider les données
            $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';
            $prix = isset($_POST['prix']) ? floatval($_POST['prix']) : 0;
            $idCategorie = isset($_POST['idCategorie']) ? intval($_POST['idCategorie']) : 0;
            
            // Validation basique
            if (empty($nom) || empty($description) || $prix <= 0 || $idCategorie <= 0) {
                $messages['erreur'] = 'Tous les champs sont obligatoires et le prix doit être positif.';
            } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $messages['erreur'] = 'Une image est requise pour ajouter un produit.';
            } else {
                // Traiter l'upload de l'image
                $fichierImage = $_FILES['image'];
                $nomFichier = $this->traiterUploadImage($fichierImage);
                
                if ($nomFichier) {
                    // Ajouter le produit en base de données
                    if (GestionBoutique::ajouterProduitBD($nom, $description, $prix, $idCategorie, $nomFichier)) {
                        $messages['succes'] = 'Produit ajouté avec succès !';
                        // Redirection après succès
                        header("Location: index.php?controleur=admin&action=afficherTousLesProduits");
                        exit();
                    } else {
                        $messages['erreur'] = 'Erreur lors de l\'ajout du produit en base de données.';
                    }
                } else {
                    $messages['erreur'] = 'Erreur lors de l\'upload de l\'image.';
                }
            }
        }

        // Charger l'entête admin
        require Chemins::VUES_ADMIN . 'v_entete_admin.inc.php';
        require Chemins::VUES_ADMIN . 'v_ajouter_produit.inc.php';
        require Chemins::VUES_ADMIN . 'v_pied_admin.inc.php';
    }

    /**
     * Édite un produit
     */
    public function editerProduit() {
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=admin&action=afficherIndex");
            exit();
        }

        if (!isset($_GET['id'])) {
            header("Location: index.php?controleur=admin&action=afficherTousLesProduits");
            exit();
        }

        $id = intval($_GET['id']);
        $produit = GestionBoutique::getProduitById($id);

        if (!$produit) {
            header("Location: index.php?controleur=admin&action=afficherTousLesProduits");
            exit();
        }

        // Récupérer les catégories
        $categories = GestionBoutique::getLesCategories();
        
        $messages = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Valider les données
            $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';
            $prix = isset($_POST['prix']) ? floatval($_POST['prix']) : 0;
            $idCategorie = isset($_POST['idCategorie']) ? intval($_POST['idCategorie']) : 0;
            
            // Validation basique
            if (empty($nom)) {
                $messages['erreur'] = 'Le nom du produit est obligatoire.';
            } elseif (empty($description)) {
                $messages['erreur'] = 'La description du produit est obligatoire.';
            } elseif ($prix <= 0) {
                $messages['erreur'] = 'Le prix doit être positif.';
            } elseif ($idCategorie <= 0) {
                $messages['erreur'] = 'Veuillez sélectionner une catégorie.';
            } else {
                $nomImage = null;
                
                // Si une nouvelle image est uploadée
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $nomImage = $this->traiterUploadImage($_FILES['image']);
                    if (!$nomImage) {
                        $messages['erreur'] = 'Erreur lors de l\'upload de l\'image.';
                    }
                }
                
                // Si pas d'erreur d'image
                if (!isset($messages['erreur'])) {
                    // Éditer le produit en base de données
                    error_log("DEBUG: Avant editerProduitBD - id=$id, nom=$nom, description=$description, prix=$prix, idCategorie=$idCategorie, nomImage=$nomImage");
                    
                    $resultat_edit = GestionBoutique::editerProduitBD($id, $nom, $description, $prix, $idCategorie, $nomImage);
                    
                    error_log("DEBUG: Après editerProduitBD - Résultat: " . ($resultat_edit ? "true" : "false"));
                    
                    if ($resultat_edit) {
                        $messages['succes'] = 'Produit modifié avec succès !';
                        // Recharger les données actualisées
                        $produit = GestionBoutique::getProduitById($id);
                        error_log("DEBUG: Produit rechargé - nom={$produit->nom}, prix={$produit->prix}");
                        // Ne pas rediriger, afficher le message et les données mises à jour
                    } else {
                        $messages['erreur'] = 'Erreur lors de la modification du produit en base de données. Vérifiez les logs.';
                        error_log("DEBUG: Modification échouée!");
                    }
                }
            }
        }

        // Charger l'entête admin
        require Chemins::VUES_ADMIN . 'v_entete_admin.inc.php';
        require Chemins::VUES_ADMIN . 'v_editer_produit.inc.php';
        require Chemins::VUES_ADMIN . 'v_pied_admin.inc.php';
    }

    /**
     * Supprime un produit
     */
    public function supprimerProduit() {
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=admin&action=afficherIndex");
            exit();
        }

        if (!isset($_GET['id'])) {
            header("Location: index.php?controleur=admin&action=afficherTousLesProduits");
            exit();
        }

        $id = intval($_GET['id']);
        
        // Récupérer le produit pour accéder à l'image
        $produit = GestionBoutique::getProduitById($id);
        if ($produit && !empty($produit->image)) {
            // Supprimer le fichier image
            $cheminImage = Chemins::IMAGES_PRODUITS . $produit->image;
            if (file_exists($cheminImage)) {
                unlink($cheminImage);
            }
        }
        
        // Supprimer le produit de la base de données
        if (GestionBoutique::supprimerProduitBD($id)) {
            $_SESSION['message_succes'] = 'Produit supprimé avec succès !';
        } else {
            $_SESSION['message_erreur'] = 'Erreur lors de la suppression du produit.';
        }
        
        header("Location: index.php?controleur=admin&action=afficherTousLesProduits");
        exit();
    }
}

?>