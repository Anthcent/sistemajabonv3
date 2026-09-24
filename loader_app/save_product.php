<?php
header('Content-Type: application/json');
require_once 'config/database.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    // Validar datos obligatorios
    $name = $_POST['name'] ?? '';
    $category_id = $_POST['category_id'] ?? null;
    
    if (empty($name) || empty($category_id)) {
        throw new Exception('Nombre y Categoría son obligatorios');
    }

    // Datos opcionales / numéricos
    $price = !empty($_POST['price']) ? $_POST['price'] : 0;
    $stock = !empty($_POST['stock_quantity']) ? $_POST['stock_quantity'] : 0;
    $cost = !empty($_POST['cost_price']) ? $_POST['cost_price'] : 0;
    $min_stock = !empty($_POST['min_stock']) ? $_POST['min_stock'] : 10;
    
    $sku = $_POST['sku'] ?? '';
    $barcode = $_POST['barcode'] ?? '';
    $brand = $_POST['brand'] ?? '';
    $display_unit = $_POST['display_unit'] ?? 'Litro';
    $is_liquid = isset($_POST['is_liquid']) ? $_POST['is_liquid'] : 1;

    // Manejar Imagen
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'prod_' . time() . '_' . uniqid() . '.' . $extension;
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
             // Guardar solo ruta relativa
            $imagePath = 'uploads/' . $filename;
        } else {
            throw new Exception('Error al guardar la imagen');
        }
    }

    // Insertar en BD
    $sql = "INSERT INTO loader_products 
            (name, category_id, sku, barcode, brand, is_liquid, display_unit, price, cost_price, stock_quantity, min_stock, image_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $name, 
        $category_id, 
        $sku, 
        $barcode, 
        $brand, 
        $is_liquid, 
        $display_unit, 
        $price, 
        $cost, 
        $stock, 
        $min_stock, 
        $imagePath
    ]);

    echo json_encode([
        'success' => true, 
        'message' => 'Producto guardado correctamente',
        'id' => $pdo->lastInsertId()
    ]);

} catch (Exception $e) {
    // Asegurar respuesta JSON incluso en error
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
?>
