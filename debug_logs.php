<?php
require_once 'config/database.php';
try {
    echo "Checking movement_history table...\n";
    $stmt = $pdo->query("SELECT type, COUNT(*) as count FROM movement_history GROUP BY type");
    $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($types);
    
    echo "\nLatest INSUMO_OUT logs:\n";
    $stmt = $pdo->query("SELECT * FROM movement_history WHERE type = 'INSUMO_OUT' ORDER BY created_at DESC LIMIT 5");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($logs);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
