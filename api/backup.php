<?php
require_once '../config/database.php';
require_once '../includes/logger.php';
require_once '../includes/BackupManager.php';
session_start();

header('Content-Type: application/json');

// Auth Check (Except auto_check implies weak auth or none? Usually auto_check should be protected or benign)
// Let's assume auto_check is benign and can be called by logged in users.
// Manual triggers need Admin auth.

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$manager = new BackupManager($pdo, __DIR__ . '/../');

try {
    switch ($action) {
        case 'manual':
            // Verify Admin
            if (!isset($_SESSION['admin_unlocked']) || !$_SESSION['admin_unlocked']) {
                // If checking 'check_pin' logic, maybe we need pin here? 
                // Or just rely on session.
                 throw new Exception("Acceso denegado. Se requiere Modo Admin.");
            }
            
            $res = $manager->runBackup('MANUAL');
            echo json_encode($res);
            break;

        case 'auto_check':
            // Can be called by footer. No strict admin needed, just valid session maybe?
            // To prevent abuse, maybe just check if any backup is needed.
            $res = $manager->checkAutoTrigger();
            echo json_encode($res);
            break;

        case 'save_config':
            if (!isset($_SESSION['admin_unlocked']) || !$_SESSION['admin_unlocked']) {
                 throw new Exception("Acceso denegado.");
            }
            
            $path = $_POST['backup_path'] ?? 'backups/';
            $freq = intval($_POST['backup_frequency'] ?? 24);
            
            $sql = "UPDATE system_settings SET backup_path = ?, backup_frequency = ?";
            $pdo->prepare($sql)->execute([$path, $freq]);
            
            echo json_encode(['status' => 'success']);
            break;
            
        case 'list':
             $files = $manager->getBackupList();
             // Format dates for JSON
             foreach($files as &$f) {
                 $f['date_formatted'] = date("d/m/Y H:i A", $f['date']);
                 $f['size_formatted'] = round($f['size'] / 1024 / 1024, 2) . ' MB';
             }
             echo json_encode(['status' => 'success', 'data' => $files]);
             break;

        case 'delete':
             if (!isset($_SESSION['admin_unlocked']) || !$_SESSION['admin_unlocked']) throw new Exception("Acceso Denegado");
             $filename = $_POST['filename'] ?? '';
             if($manager->deleteBackup($filename)) {
                 echo json_encode(['status' => 'success']);
             } else {
                 throw new Exception("No se pudo eliminar");
             }
             break;

        case 'download':
             if (!isset($_SESSION['admin_unlocked']) || !$_SESSION['admin_unlocked']) die("Acceso Denegado");
             $filename = $_GET['filename'] ?? '';
             
             // Security traversal check
             if (strpos($filename, '..') !== false || strpos($filename, '/') !== false) die("Nombre de archivo inválido");
             
             $settings = $manager->getSettings();
             $targetDir = realpath(__DIR__ . '/../') . '/' . trim($settings['backup_path'], '/\\');
             $path = $targetDir . '/' . $filename;
             
             if (file_exists($path)) {
                 header('Content-Description: File Transfer');
                 header('Content-Type: application/octet-stream');
                 header('Content-Disposition: attachment; filename="'.basename($path).'"');
                 header('Expires: 0');
                 header('Cache-Control: must-revalidate');
                 header('Pragma: public');
                 header('Content-Length: ' . filesize($path));
                 readfile($path);
                 exit;
             }
             die("Archivo no encontrado");
             break;

        case 'restore':
             if (!isset($_SESSION['admin_unlocked']) || !$_SESSION['admin_unlocked']) throw new Exception("Acceso Denegado");
             
             // From Server File
             if (isset($_POST['filename'])) {
                 $filename = $_POST['filename'];
                  // Traversal check
                 if (strpos($filename, '..') !== false || strpos($filename, '/') !== false) throw new Exception("Nombre inválido");
                 
                 $settings = $manager->getSettings();
                 $targetDir = realpath(__DIR__ . '/../') . '/' . trim($settings['backup_path'], '/\\');
                 $path = $targetDir . '/' . $filename;
                 
                 if (strpos($filename, '.sql') !== false) {
                     $manager->restoreDatabase($path);
                 } elseif (strpos($filename, '.zip') !== false) {
                     $manager->restoreImages($path);
                 } else {
                     throw new Exception("Tipo de archivo no soportado");
                 }
                 echo json_encode(['status' => 'success']);
             
             // From Upload
             } elseif (isset($_FILES['backup_file'])) {
                 $file = $_FILES['backup_file'];
                 if ($file['error'] !== UPLOAD_ERR_OK) throw new Exception("Error en subida");
                 
                 $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                 if ($ext === 'sql') {
                     $manager->restoreDatabase($file['tmp_name']);
                 } elseif ($ext === 'zip') {
                     $manager->restoreImages($file['tmp_name']);
                 } else {
                     throw new Exception("Solo se permiten archivos .sql o .zip");
                 }
                 echo json_encode(['status' => 'success']);
             } else {
                 throw new Exception("No se especificó archivo");
             }
             break;

        default:
            throw new Exception("Acción inválida");
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
