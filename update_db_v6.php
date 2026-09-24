<?php
require_once 'config/database.php';

try {
    // Add use_special_rate column to products if not exists
    $stmt = $pdo->query("SHOW COLUMNS FROM products LIKE 'use_special_rate'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE products ADD COLUMN `use_special_rate` tinyint(1) DEFAULT 0 AFTER `is_raw_material`");
        echo "Columna use_special_rate agregada a products.<br>";
    } else {
        echo "Columna use_special_rate ya existe.<br>";
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
