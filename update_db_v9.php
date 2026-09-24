<?php
require_once 'config/database.php';

try {
    echo "Actualizando base de datos para sistema de respaldos...\n";

    // backup_path
    try {
        $pdo->exec("ALTER TABLE system_settings ADD COLUMN backup_path VARCHAR(255) DEFAULT 'backups/'");
        echo "Columna backup_path agregada.\n";
    } catch (Exception $e) {}

    // backup_frequency (hours)
    try {
        $pdo->exec("ALTER TABLE system_settings ADD COLUMN backup_frequency INT DEFAULT 24");
        echo "Columna backup_frequency agregada.\n";
    } catch (Exception $e) {}

    // last_db_backup
    try {
        $pdo->exec("ALTER TABLE system_settings ADD COLUMN last_db_backup DATETIME DEFAULT NULL");
        echo "Columna last_db_backup agregada.\n";
    } catch (Exception $e) {}

    // last_full_backup
    try {
        $pdo->exec("ALTER TABLE system_settings ADD COLUMN last_full_backup DATETIME DEFAULT NULL");
        echo "Columna last_full_backup agregada.\n";
    } catch (Exception $e) {}

    // last_image_change
    try {
        $pdo->exec("ALTER TABLE system_settings ADD COLUMN last_image_change DATETIME DEFAULT CURRENT_TIMESTAMP");
        echo "Columna last_image_change agregada.\n";
    } catch (Exception $e) {}

    echo "Migración completada.\n";

} catch (PDOException $e) {
    echo "Error de Base de Datos: " . $e->getMessage() . "\n";
}
?>
