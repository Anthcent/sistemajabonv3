<?php
require_once 'config/database.php';

try {
    echo "Agregando columna is_favorite a la tabla products...\n";

    // Add is_favorite
    try {
        $pdo->exec("ALTER TABLE products ADD COLUMN is_favorite TINYINT(1) DEFAULT 0");
        echo "Columna is_favorite agregada.\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), "Duplicate column") !== false) {
            echo "Columna is_favorite ya existe.\n";
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
