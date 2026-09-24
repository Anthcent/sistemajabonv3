<?php
require_once 'config/database.php';

try {
    $sql = "
    CREATE TABLE IF NOT EXISTS `system_settings` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `company_name` varchar(100) NOT NULL DEFAULT 'Mi Negocio',
      `nit_ruc_nif` varchar(50) DEFAULT NULL,
      `address` text DEFAULT NULL,
      `phone` varchar(50) DEFAULT NULL,
      `currency_symbol` varchar(5) DEFAULT 'Bs',
      `main_currency` enum('VES','USD') DEFAULT 'VES',
      `exchange_rate_global` decimal(10,2) DEFAULT '1.00',
      `exchange_rate_special` decimal(10,2) DEFAULT '1.00',
      `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $pdo->exec($sql);
    echo "Tabla system_settings creada correctamente.<br>";

    // Insert default row if not exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM system_settings");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO system_settings (company_name) VALUES ('Mi Negocio')");
        echo "Configuración inicial insertada.<br>";
    } else {
        echo "Configuración ya existe.<br>";
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
