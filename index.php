<?php

//echo sha1("Leroy");
session_start(); // Pour éviter erreurs SESSIONS

ob_start(); // Pour éviter erreur COOKIES

require_once 'configs/chemins.class.php';
require_once Chemins::CONFIGS . 'mysql_config.class.php';
require_once Chemins::MODELES . 'gestion_boutique.class.php';
require_once Chemins::MODELES . 'gestion_panier.class.php';
require_once Chemins::MODELES . 'gestion_client.class.php';
require_once Chemins::MODELES . 'gestion_commande.class.php';
require_once Chemins::CONFIGS . 'variables_globales.class.php';

// Vérifier si c'est une action admin
$isAdmin = (isset($_REQUEST['controleur']) && strtolower($_REQUEST['controleur']) === 'admin');

// Vérifier si c'est une page d'authentification
$isAuth = (isset($_REQUEST['controleur']) && strtolower($_REQUEST['controleur']) === 'auth');

// N'afficher l'entête que si ce n'est pas une page admin
if (!$isAdmin) {
    require Chemins::VUES_PERMANENTES . 'v_entete.inc.php';
}

require_once Chemins::CONTROLEURS . 'ControleurCategories.class.php';
$controleurCategories = new ControleurCategories();
// Afficher les catégories seulement si ce n'est pas admin ET pas auth
if (!$isAdmin && !$isAuth) {
    $controleurCategories->afficher();
}

if (!isset($_REQUEST['controleur'])) {
    require_once(Chemins::VUES . "v_accueil.inc.php");
} else {

    $classeControleur = 'Controleur' . $_REQUEST['controleur']; //ex : ControleurProduits
    $fichierControleur = $classeControleur . ".class.php"; //ex : ControleurProduits.class.php
    require_once(Chemins::CONTROLEURS . $fichierControleur);

    $action = $_REQUEST['action']; //exemple : afficher

    $objetControleur = new $classeControleur(); //ex : $objetControleur = new ControleurProduits();
    $objetControleur->$action(); //ex : $objetControleur->afficher();
    //version avec classe statique
    // $classeStatiqueControleur = 'Controleur' . $_REQUEST['controleur'];
    // $classeStatiqueControleur::$action();
}

// N'afficher le pied de page que si ce n'est pas une page admin
if (!$isAdmin) {
    // Résumé du panier et pied de page (afficher pour toutes les pages sauf panier lui-même)
    $action = isset($_REQUEST['action']) ? strtolower($_REQUEST['action']) : '';
    if ($action !== 'afficherpanier') {
        // Préparer les données du résumé panier pour la vue (respect MVC)
        $nombreArticles = GestionPanier::getNombreArticles();
        $totalPanier = GestionPanier::getTotalPanier();
        require Chemins::VUES_PERMANENTES . 'v_resume_panier.inc.php';
    }
    require Chemins::VUES_PERMANENTES . 'v_pied.inc.php';
}

?>