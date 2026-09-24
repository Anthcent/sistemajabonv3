<?php
require_once 'config/database.php';

try {
    // Add manager_pin column if it doesn't exist
    $sql = "ALTER TABLE system_settings ADD COLUMN manager_pin VARCHAR(20) DEFAULT '4321'";
    $pdo->exec($sql);
    echo "Columna manager_pin agregada con exito.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "La columna manager_pin ya existe.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
?>
