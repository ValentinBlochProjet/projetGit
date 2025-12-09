<?php
// Démarrer la session
session_start();

// Inclure les fichiers de configuration
require_once dirname(__FILE__) . '/configs/chemins.class.php';
require_once dirname(__FILE__) . '/configs/mysql_config.class.php';
require_once dirname(__FILE__) . '/configs/variables_globales.class.php';

// Inclure tous les modèles
require_once dirname(__FILE__) . '/application/modeles/gestion_boutique.class.php';
require_once dirname(__FILE__) . '/application/modeles/gestion_panier.class.php';
require_once dirname(__FILE__) . '/application/modeles/gestion_client.class.php';

// Inclure les contrôleurs
require_once dirname(__FILE__) . '/application/controleurs/ControleurAdmin.class.php';

// Routage
$controleur = isset($_GET['controleur']) ? strtolower($_GET['controleur']) : 'admin';
$action = isset($_GET['action']) ? $_GET['action'] : 'afficherIndex';

try {
    switch ($controleur) {
        case 'admin':
            $ctrl = new ControleurAdmin();
            
            // Vérifier que la méthode existe
            if (method_exists($ctrl, $action)) {
                $ctrl->$action();
            } else {
                echo "Erreur: Action '$action' non trouvée";
            }
            break;
        
        default:
            echo "Erreur: Contrôleur '$controleur' non trouvé";
            break;
    }
} catch (Exception $e) {
    echo "Erreur: " . htmlspecialchars($e->getMessage());
    error_log("Erreur indexAdmin: " . $e->getMessage());
}

?>
