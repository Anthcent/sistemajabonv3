<?php
require_once 'config/database.php';

try {
    $pdo->exec("ALTER TABLE sales ADD COLUMN payment_reference VARCHAR(255) DEFAULT NULL AFTER payment_method");
    echo "Column added successfully";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "Column already exists";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?>
