<?php

require_once dirname(__FILE__) . '/../../configs/chemins.class.php';

class ControleurAuth {
    
    /**
     * Affiche la page d'inscription
     */
    public function afficherInscription() {
        $messages = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pseudo = isset($_POST['pseudo']) ? trim($_POST['pseudo']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';
            $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
            $prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
            
            // Validation
            if (empty($pseudo) || strlen($pseudo) < 3) {
                $messages['erreur'] = 'Le pseudo doit faire au moins 3 caractères';
            } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $pseudo)) {
                $messages['erreur'] = 'Le pseudo ne doit contenir que des lettres, chiffres, tirets et underscores';
            } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $messages['erreur'] = 'Email invalide';
            } elseif (empty($password) || strlen($password) < 6) {
                $messages['erreur'] = 'Le mot de passe doit faire au moins 6 caractères';
            } elseif ($password !== $password_confirm) {
                $messages['erreur'] = 'Les mots de passe ne correspondent pas';
            } elseif (empty($nom) || empty($prenom)) {
                $messages['erreur'] = 'Nom et prénom sont obligatoires';
            } else {
                $result = GestionClient::inscrireClient($pseudo, $email, $password, $nom, $prenom);
                if ($result['succes']) {
                    $messages['succes'] = $result['message'];
                    // Redirection vers connexion après 2 secondes
                    header("refresh:2;url=index.php?controleur=auth&action=afficherConnexion");
                } else {
                    $messages['erreur'] = $result['message'];
                }
            }
        }
        
        require Chemins::VUES_PERMANENTES . 'v_entete.inc.php';
        require Chemins::VUES . 'v_inscription.inc.php';
        require Chemins::VUES_PERMANENTES . 'v_pied.inc.php';
    }
    
    /**
     * Affiche la page de connexion
     */
    public function afficherConnexion() {
        $messages = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            
            if (empty($email) || empty($password)) {
                $messages['erreur'] = 'Email et mot de passe requis';
            } else {
                $result = GestionClient::connecterClient($email, $password);
                if ($result['succes']) {
                    // Normaliser la clé de session pour l'ID client
                    $_SESSION['client_connecte'] = $result['client']->id;
                    $_SESSION['id_client'] = $result['client']->id;
                    $_SESSION['client_email'] = $result['client']->email;
                    $_SESSION['client_nom'] = $result['client']->nom;
                    $_SESSION['client_prenom'] = $result['client']->prenom;
                    
                    header("Location: index.php");
                    exit();
                } else {
                    $messages['erreur'] = $result['message'];
                }
            }
        }
        
        require Chemins::VUES_PERMANENTES . 'v_entete.inc.php';
        require Chemins::VUES . 'v_connexion.inc.php';
        require Chemins::VUES_PERMANENTES . 'v_pied.inc.php';
    }
    
    /**
     * Déconnecte le client
     */
    public function deconnecter() {
        unset($_SESSION['client_connecte']);
        unset($_SESSION['id_client']);
        unset($_SESSION['client_email']);
        unset($_SESSION['client_nom']);
        unset($_SESSION['client_prenom']);
        
        header("Location: index.php");
        exit();
    }
}
?>
