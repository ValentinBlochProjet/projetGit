<?php
// Diagnostic script pour vérifier la structure de la table client

require_once dirname(__FILE__) . '/configs/mysql_config.class.php';

try {
    $pdo = new PDO(
        'mysql:host=' . MysqlConfig::SERVEUR . ';dbname=' . MysqlConfig::BASE,
        MysqlConfig::UTILISATEUR,
        MysqlConfig::MOT_DE_PASSE
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Structure de la table client:</h2>";
    
    $stmt = $pdo->query("DESCRIBE client");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($columns)) {
        echo "<p style='color: red;'>Table client n'existe pas!</p>";
    } else {
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
        echo "<tr style='background-color: #f0f0f0;'><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Default</th></tr>";
        
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($col['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Default'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<h3>Nombre d'enregistrements:</h3>";
        $count = $pdo->query("SELECT COUNT(*) as total FROM client")->fetch();
        echo "<p>" . $count['total'] . " clients en base</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Erreur: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
