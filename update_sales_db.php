<?php
require_once 'config/database.php';

try {
    echo "Agregando columnas de tasa de cambio a la tabla sales...\n";

    // Add exchange_rate_global
    try {
        $pdo->exec("ALTER TABLE sales ADD COLUMN exchange_rate_global DECIMAL(10,2) DEFAULT 0.00 AFTER amount_tendered");
        echo "Columna exchange_rate_global agregada.\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), "Duplicate column") !== false) {
            echo "Columna exchange_rate_global ya existe.\n";
        } else {
            throw $e;
        }
    }

    // Add exchange_rate_special
    try {
        $pdo->exec("ALTER TABLE sales ADD COLUMN exchange_rate_special DECIMAL(10,2) DEFAULT 0.00 AFTER exchange_rate_global");
        echo "Columna exchange_rate_special agregada.\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), "Duplicate column") !== false) {
            echo "Columna exchange_rate_special ya existe.\n";
        } else {
            throw $e;
        }
    }

    echo "Actualización de base de datos completada.\n";

} catch (PDOException $e) {
    echo "Error de Base de Datos: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Error General: " . $e->getMessage() . "\n";
}
?>
