<?php
require_once '../config/database.php';
require_once '../includes/logger.php';

// Prevent unwanted output (warnings/notices) from corrupting JSON
ini_set('display_errors', 0);
ob_start();

header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// If no action but POST and has body, assume save (common pitfall)
if ($action === '' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = 'save';
}

switch ($action) {
    case 'list':
        try {
            $stmt = $pdo->query("SELECT * FROM sales ORDER BY created_at DESC");
            echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'get_details':
        $sale_id = $_GET['id'] ?? null;
        if(!$sale_id) {
            echo json_encode(['status' => 'error', 'message' => 'ID missing']);
            exit;
        }
        try {
            $stmt = $pdo->prepare("
                SELECT si.*, p.name as product_name, p.sku, p.use_special_rate, p.is_liquid, s.payment_reference
                FROM sale_items si 
                JOIN sales s ON si.sale_id = s.id
                LEFT JOIN products p ON si.product_id = p.id 
                WHERE si.sale_id = ?
            ");
            $stmt->execute([$sale_id]);
            echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
             echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'save': // Process Sale
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['cart'])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
            exit;
        }

        $payment_method = $data['payment_method'] ?? 'cash';
        $amount_tendered = $data['amount_tendered'] ?? 0;

        try {
            $pdo->beginTransaction();

            // Validate Stock First
            foreach ($data['cart'] as $item) {
                // Fetch fresh stock
                $stmt = $pdo->prepare("SELECT stock_quantity, name FROM products WHERE id = ?");
                $stmt->execute([$item['id']]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$product) throw new Exception("Producto no encontrado: " . $item['name']);
                if ($product['stock_quantity'] < $item['quantity'] ?? $item['quantity_to_sell']) {
                    throw new Exception("Stock insuficiente para: " . $product['name']);
                }
            }

            // Fetch Current Exchange Rates (Snapshot)
            $settings_stmt = $pdo->query("SELECT exchange_rate_global, exchange_rate_special FROM system_settings LIMIT 1");
            $settings = $settings_stmt->fetch(PDO::FETCH_ASSOC);
            $exchange_rate_global = $settings['exchange_rate_global'] ?? 0;
            $exchange_rate_special = $settings['exchange_rate_special'] ?? 0;
            
            // Calculate Total & Prepare Items
            $total_sale_amount = 0;
            $items_to_save = [];

            foreach ($data['cart'] as $item) {
                $product_id = $item['id'];
                $quantity_sold = $item['quantity'] ?? $item['quantity_to_sell']; // Handle different payload formats (cart usually has qty)
                $subtotal = $item['subtotal'];
                
                // Deduct Stock
                $update = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
                $update->execute([$quantity_sold, $product_id]);

                $total_sale_amount += $subtotal;
                $items_to_save[] = [
                    'product_id' => $product_id,
                    'quantity' => $quantity_sold,
                    'subtotal' => $subtotal
                ];
            }

            $payment_reference = $data['payment_reference'] ?? null;

            // Create Sale Record with Rates
            $stmt = $pdo->prepare("INSERT INTO sales (total_amount, payment_method, amount_tendered, exchange_rate_global, exchange_rate_special, payment_reference) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$total_sale_amount, $payment_method, $amount_tendered, $exchange_rate_global, $exchange_rate_special, $payment_reference]);
            $sale_id = $pdo->lastInsertId();

            // Save Items
            $insert_item = $pdo->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, subtotal) VALUES (?, ?, ?, ?)");
            foreach ($items_to_save as $item) {
                $insert_item->execute([$sale_id, $item['product_id'], $item['quantity'], $item['subtotal']]);
            }

            $pdo->commit();
            
            // Log Sale with Bs Details
            // Bs Total for the WHOLE sale depends on rate. 
            // Since items might use special rate mixed with global, calculating an exact "Sale Total in Bs" is complex if not stored.
            // But usually we just use Global Rate for the generic "Total en Bs" display unless we sum per-item.
            // For the log, let's just log the Global Equivalent.
            $bsTotal = $total_sale_amount * $exchange_rate_global;
            
            log_movement($pdo, 'SALE', "Venta registrada (ID: #$sale_id)", [
                'amount' => $total_sale_amount, 
                'method' => $payment_method,
                'rate' => $exchange_rate_global,
                'bs_amount' => $bsTotal
            ]);
            
            ob_clean(); // Discard any warnings generated during processing
            echo json_encode(['status' => 'success', 'sale_id' => $sale_id]);

        } catch (Exception $e) {
            if($pdo->inTransaction()) $pdo->rollBack();
            ob_clean(); // Discard any partial output
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;
}
?>
