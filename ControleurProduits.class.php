<?php

class ControleurProduits {
    
     public function afficher() {
         
         VariablesGlobales::$libelleCategorie = $_REQUEST['categorie'];
        
         VariablesGlobales::$lesProduits = GestionBoutique::getLesproduitsBycategorie($_REQUEST['categorie']);
         require Chemins::VUES . 'v_produits.inc.php'; 
     }

     // Afficher tous les produits (sans filtrer par catégorie)
     public function afficherTous() {
         VariablesGlobales::$libelleCategorie = 'Tous les produits';
         VariablesGlobales::$lesProduits = GestionBoutique::getLesProduits();
         require Chemins::VUES . 'v_produits.inc.php';
     }
}

