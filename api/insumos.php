<?php
require_once '../config/database.php';
require_once '../includes/logger.php';
session_start();

header('Content-Type: application/json');

// Security Check
if (!isset($_SESSION['insumos_unlocked']) || $_SESSION['insumos_unlocked'] !== true) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Acceso no autorizado']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'search':
        try {
            $query = $_GET['q'] ?? '';
            
            $sql = "SELECT id, name, sku, stock_quantity, display_unit, image_path, is_liquid FROM products WHERE is_raw_material = 1";

            if (!empty($query)) {
                $sql .= " AND (name LIKE ? OR sku LIKE ? OR barcode LIKE ?) LIMIT 50";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(["%$query%", "%$query%", "%$query%"]);
            } else {
                $sql .= " ORDER BY name ASC LIMIT 100";
                $stmt = $pdo->query($sql);
            }
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['status' => 'success', 'data' => $results]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;
    case 'history':
        try {
            $sql = "SELECT * FROM movement_history WHERE type = 'INSUMO_OUT' ORDER BY created_at DESC LIMIT 100";
            $stmt = $pdo->query($sql);
            $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Pre-process details if necessary, but frontend can handle JSON parsing too.
             // Let's decode here to keep frontend cleaner if we want, or send raw. 
             // Sending raw is fine, JS can JSON.parse.
            
            echo json_encode(['status' => 'success', 'data' => $logs]);
        } catch (Exception $e) {
             echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'decrement':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Método inválido']);
            exit;
        }

        try {
            $product_id = $_POST['product_id'] ?? null;
            $quantity = floatval($_POST['quantity'] ?? 0);
            $reason = $_POST['reason'] ?? 'Consumo Interno';
            $pin_used = $_POST['auth_pin'] ?? ''; // Optional extra check if we wanted

            if (!$product_id || $quantity <= 0) {
                throw new Exception("Datos inválidos");
            }

            // 1. Get current stock
            $stmt = $pdo->prepare("SELECT name, stock_quantity, display_unit FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$product) throw new Exception("Producto no encontrado");

            // 2. Update Stock
            $newStock = $product['stock_quantity'] - $quantity;
            $updateSql = "UPDATE products SET stock_quantity = ? WHERE id = ?";
            $pdo->prepare($updateSql)->execute([$newStock, $product_id]);

            // 3. Log Movement
            // We use the logger helper
            $logDetails = [
                'previous_stock' => $product['stock_quantity'],
                'decremented' => -$quantity,
                'new_stock' => $newStock,
                'reason' => $reason,
                'type' => 'BAJA_GERENCIAL'
            ];
            
            log_movement($pdo, 'INSUMO_OUT', "Baja por Insumo: {$product['name']} ($reason)", $logDetails);

            echo json_encode(['status' => 'success', 'message' => 'Stock actualizado correctamente', 'new_stock' => $newStock]);

        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
}
?>
