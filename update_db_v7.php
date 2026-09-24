<?php
require_once 'config/database.php';

try {
    // Check if column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM system_settings LIKE 'admin_pin'");
    if ($stmt->rowCount() == 0) {
        $sql = "ALTER TABLE `system_settings` ADD COLUMN `admin_pin` varchar(255) DEFAULT '1234' AFTER `exchange_rate_special`";
        $pdo->exec($sql);
        echo "Columna admin_pin agregada con pin por defecto '1234'.<br>";
    } else {
        echo "Columna admin_pin ya existe.<br>";
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
