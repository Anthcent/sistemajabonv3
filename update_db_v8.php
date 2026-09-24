<?php
require_once 'config/database.php';

try {
    echo "Actualizando base de datos para control de cambios de tasa...\n";

    // Add rate_change_count
    try {
        $pdo->exec("ALTER TABLE system_settings ADD COLUMN rate_change_count INT DEFAULT 0");
        echo "Columna rate_change_count agregada.\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), "Duplicate column") !== false) {
            echo "Columna rate_change_count ya existe.\n";
        }
    }

    // Add first_rate_change_at
    try {
        $pdo->exec("ALTER TABLE system_settings ADD COLUMN first_rate_change_at DATETIME DEFAULT NULL");
        echo "Columna first_rate_change_at agregada.\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), "Duplicate column") !== false) {
            echo "Columna first_rate_change_at ya existe.\n";
        }
    }

    echo "Actualización completada.\n";

} catch (PDOException $e) {
    echo "Error de Base de Datos: " . $e->getMessage() . "\n";
}
?>
