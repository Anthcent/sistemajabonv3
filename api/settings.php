<?php
require_once '../config/database.php';
session_start();
require_once '../includes/logger.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? 'get';

switch ($action) {
    case 'get':
        try {
            $stmt = $pdo->query("SELECT * FROM system_settings LIMIT 1");
            $settings = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$settings) {
                // If somehow empty, create default
                $pdo->exec("INSERT INTO system_settings (company_name) VALUES ('Mi Negocio')");
                $stmt = $pdo->query("SELECT * FROM system_settings LIMIT 1");
                $settings = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            echo json_encode(['status' => 'success', 'data' => $settings]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'check_pin':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die(json_encode(['status' => 'error', 'message' => 'Invalid method']));
        
        $input_pin = $_POST['pin'] ?? '';
        
        try {
            $stmt = $pdo->query("SELECT admin_pin FROM system_settings LIMIT 1");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Default PIN if not set
            $stored_pin = $row['admin_pin'] ?? '1234';
            
            if ($input_pin === $stored_pin) {
                $_SESSION['admin_unlocked'] = true;
                
                // Reset limit counter on unlock
                $pdo->exec("UPDATE system_settings SET rate_change_count = 0, first_rate_change_at = NULL");
                
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'PIN Incorrecto']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'quick_update_rate':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die(json_encode(['status' => 'error', 'message' => 'Invalid method']));
        
        $new_rate = $_POST['rate'] ?? 0;
        if ($new_rate <= 0) die(json_encode(['status' => 'error', 'message' => 'Tasa inválida']));

        try {
            $checkStmt = $pdo->query("SELECT * FROM system_settings LIMIT 1");
            $settings = $checkStmt->fetch(PDO::FETCH_ASSOC);
            $id = $settings['id'];

            // Logic:
            // 1. If Unlocked -> Allow & Reset
            // 2. If Locked -> Check Limit (Max 3 per 24h)

            $is_unlocked = isset($_SESSION['admin_unlocked']) && $_SESSION['admin_unlocked'];
            
            if ($is_unlocked) {
                 // Update, Reset Counter
                 $sql = "UPDATE system_settings SET exchange_rate_global = ?, rate_change_count = 0, first_rate_change_at = NULL WHERE id = ?";
                 $pdo->prepare($sql)->execute([$new_rate, $id]);
                 $msg = 'Tasa actualizada';
            } else {
                // Locked Logic
                $count = $settings['rate_change_count'] ?? 0;
                $first_at = $settings['first_rate_change_at'];
                
                // Check Time Reset (24h)
                if ($first_at) {
                    $firstDate = new DateTime($first_at);
                    $now = new DateTime();
                    $diff = $now->diff($firstDate);
                    // if more than 24 hours (or days > 0)
                    if ($diff->days > 0 || $diff->h >= 24) {
                        $count = 0; // Reset
                        $first_at = null;
                        // Clear in DB
                        $pdo->exec("UPDATE system_settings SET rate_change_count = 0, first_rate_change_at = NULL");
                    }
                }

                if ($count >= 3) {
                    echo json_encode(['status' => 'error', 'message' => 'Límite de 3 cambios diarios alcanzado. Desbloquea para continuar.']);
                    exit;
                }

                // Increment
                $new_count = $count + 1;
                $update_first = ($new_count === 1) ? ", first_rate_change_at = NOW()" : "";
                
                $sql = "UPDATE system_settings SET exchange_rate_global = ?, rate_change_count = ? $update_first WHERE id = ?";
                $pdo->prepare($sql)->execute([$new_rate, $new_count, $id]);
                
                $remaining = 3 - $new_count;
                $msg = "Tasa actualizada. Te quedan $remaining cambios hoy.";
            }

            // Log It
            log_movement($pdo, 'SETTINGS_UPDATE', "Cambio rápido de tasa: $new_rate", [
                'exchange_rate_global' => ['old' => $settings['exchange_rate_global'], 'new' => $new_rate],
                'method' => 'quick_nav'
            ]);

            echo json_encode(['status' => 'success', 'message' => $msg, 'new_rate' => $new_rate]);

        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'save':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die(json_encode(['status' => 'error', 'message' => 'Invalid method']));

        try {
            $company_name = $_POST['company_name'] ?? 'Mi Negocio';
            $nit_ruc_nif = $_POST['nit_ruc_nif'] ?? '';
            $address = $_POST['address'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $main_currency = $_POST['main_currency'] ?? 'VES';
            $currency_symbol = ($main_currency === 'USD') ? '$' : 'Bs';
            
            $exchange_rate_global = $_POST['exchange_rate_global'] ?? 1;

            $exchange_rate_special = $_POST['exchange_rate_special'] ?? 1;
            $admin_pin = $_POST['admin_pin'] ?? null;
            $manager_pin = $_POST['manager_pin'] ?? null;

            $manager_pin = $_POST['manager_pin'] ?? null;

            // Restore targetId logic
            $checkStmt = $pdo->query("SELECT * FROM system_settings LIMIT 1");
            $check = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$check) {
                 $pdo->exec("INSERT INTO system_settings (company_name) VALUES ('Mi Negocio')");
                 $checkStmt = $pdo->query("SELECT * FROM system_settings LIMIT 1");
                 $check = $checkStmt->fetch(PDO::FETCH_ASSOC);
            }
            $targetId = $check['id'] ?? 1;

             $sql = "UPDATE system_settings SET 
                    company_name = ?, 
                    nit_ruc_nif = ?, 
                    address = ?, 
                    phone = ?,
                    currency_symbol = ?, 
                    main_currency = ?, 
                    exchange_rate_global = ?, 
                    exchange_rate_special = ?,
                    admin_pin = COALESCE(?, admin_pin), 
                    manager_pin = COALESCE(?, manager_pin),
                    backup_path = ?,
                    backup_frequency = ?
                    WHERE id = ?";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $company_name, 
                $nit_ruc_nif, 
                $address, 
                $phone, 
                $currency_symbol, 
                $main_currency, 
                $exchange_rate_global, 
                $exchange_rate_special,
                $admin_pin,
                $manager_pin,
                $_POST['backup_path'] ?? 'backups/',
                $_POST['backup_frequency'] ?? 24,
                $targetId
            ]);

            // Logger Logic
            $changes = [];
            // Compare old row with new values
            if ($check && is_array($check)) {
                 $fields = [
                     'company_name' => $company_name,
                     'exchange_rate_global' => $exchange_rate_global,
                     'exchange_rate_special' => $exchange_rate_special,
                     'main_currency' => $main_currency
                 ];
                 foreach ($fields as $key => $newVal) {
                     $oldVal = $check[$key] ?? '';
                     if ($oldVal != $newVal) {
                         $changes[$key] = ['old' => $oldVal, 'new' => $newVal];
                     }
                 }
            }
            
            if (!empty($changes)) {
                log_movement($pdo, 'SETTINGS_UPDATE', "Configuración actualizada", $changes);
            } else {
                 // Even if no specific tracked changes (e.g. only address changed), log it
                 log_movement($pdo, 'SETTINGS_UPDATE', "Configuración actualizada (General)");
            }

            echo json_encode(['status' => 'success', 'message' => 'Configuración guardada']);

        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}
?>
