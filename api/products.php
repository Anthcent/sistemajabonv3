<?php
require_once '../config/database.php';
require_once '../includes/logger.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$id = $_GET['id'] ?? $_POST['id'] ?? null;

switch ($action) {
    case 'list':
        try {
            $search = $_GET['search'] ?? '';
            $sql = "SELECT p.*, c.name as category_name 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id";
            
            if ($search) {
                $sql .= " WHERE p.name LIKE ? OR p.sku LIKE ? OR p.barcode LIKE ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(["%$search%", "%$search%", "%$search%"]);
            } else {
                $sql .= " ORDER BY p.name ASC";
                $stmt = $pdo->query($sql);
            }
            
            echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'save':
        try {
            $name = $_POST['name'] ?? '';
            $sku = $_POST['sku'] ?? '';
            $barcode = $_POST['barcode'] ?? '';
            $brand = $_POST['brand'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $price = $_POST['price'] ?? 0;
            $cost_price = $_POST['cost_price'] ?? 0;
            $display_unit = $_POST['display_unit'] ?? 'U';
            $min_stock = $_POST['min_stock'] ?? 5;
            $is_liquid = $_POST['is_liquid'] ?? 0;
            $is_raw_material = $_POST['is_raw_material'] ?? 0;
            
            // Fix: Default to null to distinguish between "not sent" (edit) and "0" (explicit)
            $stock_quantity = null;
            if (isset($_POST['stock_quantity'])) $stock_quantity = $_POST['stock_quantity'];
            elseif (isset($_POST['stock'])) $stock_quantity = $_POST['stock'];
            
            $use_special_rate = (isset($_POST['use_special_rate']) && $_POST['use_special_rate'] == 1) ? 1 : 0;

            if (empty($name)) throw new Exception("Nombre obligatorio");

            // Fetch Settings for Rate
            $settingsStmt = $pdo->query("SELECT exchange_rate_global, exchange_rate_special FROM system_settings LIMIT 1");
            $settings = $settingsStmt->fetch(PDO::FETCH_ASSOC);
            $globalRate = $settings['exchange_rate_global'] ?? 1;
            $specialRate = $settings['exchange_rate_special'] ?? 1;

            // Handle Image
            $image_path = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename);
                $image_path = 'uploads/' . $filename;
                
                // Backup Trigger: Update timestamp
                try {
                    $pdo->exec("UPDATE system_settings SET last_image_change = NOW()");
                } catch (Exception $e) {}
            }

            if ($id) {
                // Fetch Old Data for Comparison
                $oldStmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $oldStmt->execute([$id]);
                $oldData = $oldStmt->fetch(PDO::FETCH_ASSOC);

                // Update
                $sql = "UPDATE products SET name=?, sku=?, barcode=?, brand=?, category_id=?, price=?, cost_price=?, display_unit=?, min_stock=?, is_liquid=?, is_raw_material=?, use_special_rate=?";
                $params = [$name, $sku, $barcode, $brand, $category_id, $price, $cost_price, $display_unit, $min_stock, $is_liquid, $is_raw_material, $use_special_rate];
                
                if ($image_path) {
                    $sql .= ", image_path=?";
                    $params[] = $image_path;
                }
                
                $sql .= " WHERE id=?";
                $params[] = $id;

                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);

                // Log Changes
                if ($oldData) {
                     $changes = [];
                     // Check Price
                     if ($oldData['price'] != $price) {
                         $changes['price'] = ['old' => $oldData['price'], 'new' => $price];
                     }

                     // Check other scalar fields
                     $fields = ['name', 'brand', 'sku', 'barcode'];
                     foreach($fields as $f) {
                         if(($oldData[$f]??'') != $$f) $changes[$f] = ['old' => $oldData[$f], 'new' => $$f];
                     }

                     if (!empty($changes)) {
                         log_movement($pdo, 'PRODUCT_UPDATE', "Producto modificado: $name", $changes);
                         
                         // Log Price Details specifically
                         if (isset($changes['price'])) {
                             $rateUsed = ($use_special_rate) ? $specialRate : $globalRate;
                             $changes['price']['rate'] = $rateUsed;
                             $changes['price']['old_bs'] = $changes['price']['old'] * $rateUsed;
                             $changes['price']['new_bs'] = $changes['price']['new'] * $rateUsed;
                             log_movement($pdo, 'PRICE_UPDATE', "Cambio de precio: $name", $changes['price']);
                         }
                     }
                }
                
                // Fix: Only update stock if explicitly provided (not null)
                // During standard edit, frontend doesn't send stock, so strict check prevents reset to 0
                if ($oldData && $stock_quantity !== null && $oldData['stock_quantity'] != $stock_quantity) {
                     $pdo->prepare("UPDATE products SET stock_quantity = ? WHERE id = ?")->execute([$stock_quantity, $id]);
                     log_movement($pdo, 'STOCK_UPDATE', "Ajuste manual stock: $name", ['amount' => $stock_quantity - $oldData['stock_quantity']]);
                }

                $msg = 'Producto actualizado correctamente';
            } else {
                // Insert: Default stock to 0 if not provided
                $finalStock = $stock_quantity ?? 0;
                
                $sql = "INSERT INTO products (name, sku, barcode, brand, category_id, price, cost_price, display_unit, min_stock, is_liquid, is_raw_material, stock_quantity, image_path, use_special_rate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$name, $sku, $barcode, $brand, $category_id, $price, $cost_price, $display_unit, $min_stock, $is_liquid, $is_raw_material, $finalStock, $image_path, $use_special_rate]);
                $id = $pdo->lastInsertId();
                
                log_movement($pdo, 'PRODUCT_CREATE', "Nuevo producto creado: $name");
                
                $msg = 'Producto creado correctamente';
            }

            echo json_encode(['status' => 'success', 'message' => $msg, 'id' => $id]);

        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'delete':
        if ($id) {
             $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
             $stmt->execute([$id]);
             log_movement($pdo, 'PRODUCT_DELETE', "Producto eliminado: ID $id");
             echo json_encode(['status' => 'success', 'message' => 'Producto eliminado']);
        }
        break;

    case 'update_stock':
        $amount = $_POST['amount'] ?? 0;
        if ($id && $amount != 0) {
            $stmt = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity + ? WHERE id = ?");
            $stmt->execute([$amount, $id]);
            
            // Get Name
            $pName = $pdo->query("SELECT name FROM products WHERE id = $id")->fetchColumn();
            
            // Log with Rate context? Stock update doesn't usually use rate directly unless value is tracked, 
            // but log details usually just need qty.
            log_movement($pdo, 'STOCK_UPDATE', "Ajuste de stock: $pName", ['amount' => $amount]);
            
            echo json_encode(['status' => 'success', 'message' => 'Stock actualizado']);
        }
        break;

    case 'toggle_special_rate':
         $use = $_POST['use_special_rate'] ?? 0;
         if ($id) {
             $stmt = $pdo->prepare("UPDATE products SET use_special_rate = ? WHERE id = ?");
             $stmt->execute([$use, $id]);
             echo json_encode(['status' => 'success']);
         }
         break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}
?>
