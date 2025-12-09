<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once 'configs/chemins.class.php';
require_once Chemins::CONFIGS . 'mysql_config.class.php';
require_once Chemins::MODELES . 'gestion_client.class.php';

header('Content-Type: application/json');

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch ($action) {
        case 'verifierPseudo':
            if (isset($_GET['pseudo'])) {
                $pseudo = trim($_GET['pseudo']);
                
                if (strlen($pseudo) < 3) {
                    echo json_encode(['existe' => false, 'message' => 'Le pseudo doit faire au moins 3 caractères']);
                } elseif (strlen($pseudo) > 20) {
                    echo json_encode(['existe' => false, 'message' => 'Le pseudo ne doit pas dépasser 20 caractères']);
                } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $pseudo)) {
                    echo json_encode(['existe' => false, 'message' => 'Le pseudo ne doit contenir que des lettres, chiffres, tirets et underscores']);
                } else {
                    $existe = GestionClient::pseudoExists($pseudo);
                    echo json_encode([
                        'existe' => $existe,
                        'message' => $existe ? 'Ce pseudo est déjà utilisé' : 'Pseudo disponible ✓'
                    ]);
                }
            }
            break;
            
        default:
            echo json_encode(['erreur' => 'Action inconnue']);
    }
} else {
    echo json_encode(['erreur' => 'Action non spécifiée']);
}
?>
