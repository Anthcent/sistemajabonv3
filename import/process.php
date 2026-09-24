<?php
// Silenciar errores HTML para evitar romper el JSON
error_reporting(E_ALL); 
ini_set('display_errors', 0); 

// Iniciar buffer para atrapar cualquier salida inesperada
ob_start();

header('Content-Type: application/json');

// Usar la configuración principal del sistema
require_once '../config/database.php'; 

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    if (!isset($_FILES['zipFile']) || $_FILES['zipFile']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error al subir el archivo');
    }

    $zipFile = $_FILES['zipFile']['tmp_name'];
    $extractPath = __DIR__ . '/temp_' . uniqid();
    
    // 1. Extraer ZIP
    $zip = new ZipArchive;
    if ($zip->open($zipFile) === TRUE) {
        $zip->extractTo($extractPath);
        $zip->close();
    } else {
        throw new Exception('No se pudo abrir el archivo ZIP');
    }

    // 2. Leer JSON
    $jsonFile = $extractPath . '/products.json';
    if (!file_exists($jsonFile)) {
        throw new Exception('El archivo ZIP no contiene products.json');
    }

    $jsonData = file_get_contents($jsonFile);
    $products = json_decode($jsonData, true);

    if (!$products) {
        throw new Exception('Error al leer los datos de productos');
    }

    // 3. Procesar Productos
    $pdo->beginTransaction();
    $importedCount = 0;
    
    // Directorio final de imágenes
    $mainUploadsDir = '../uploads/';
    if (!is_dir($mainUploadsDir)) {
        mkdir($mainUploadsDir, 0777, true);
    }

    $sql = "INSERT INTO products 
            (name, category_id, sku, barcode, brand, is_liquid, display_unit, price, cost_price, stock_quantity, min_stock, image_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    foreach ($products as $prod) {
        $name = $prod['name'];
        $sku = $prod['sku'] ?? null;
        
        // 1. Verificación de Duplicados
        // Comprobamos si ya existe por SKU (si tiene) o por Nombre
        $duplicateCheckSq = "SELECT id FROM products WHERE name = :name";
        $params = [':name' => $name];
        
        if (!empty($sku)) {
            $duplicateCheckSq .= " OR sku = :sku";
            $params[':sku'] = $sku;
        }
        
        $checkStmt = $pdo->prepare($duplicateCheckSq);
        $checkStmt->execute($params);
        
        if ($checkStmt->fetch()) {
            // Ya existe -> Saltar
            $skippedCount++;
            continue; 
        }

        // 2. Manejar Imagen (Solo si NO es duplicado)
        $finalImagePath = null;
        if (!empty($prod['image_path'])) {
            // El path en json es 'uploads/filename.jpg', en el zip está en carpeta 'uploads'
            $sourceImg = $extractPath . '/' . $prod['image_path'];
            $fileName = basename($prod['image_path']);
            
            // Evitar colisiones de nombre
            $newFileName = 'imported_' . uniqid() . '_' . $fileName;
            $destImg = $mainUploadsDir . $newFileName;

            if (file_exists($sourceImg)) {
                if (rename($sourceImg, $destImg)) {
                    $finalImagePath = 'uploads/' . $newFileName;
                }
            }
        }

        // 3. Insertar
        // Mapear campos (asegurar que coinciden o usar valores por defecto)
        $stmt->execute([
            $prod['name'],
            $prod['category_id'],
            $prod['sku'] ?? null,
            $prod['barcode'] ?? null,
            $prod['brand'] ?? null,
            $prod['is_liquid'] ?? 1,
            $prod['display_unit'] ?? 'Litro',
            $prod['price'],
            $prod['cost_price'] ?? 0,
            $prod['stock_quantity'] ?? 0,
            $prod['min_stock'] ?? 10,
            $finalImagePath
        ]);
        
        $importedCount++;
    }

    $pdo->commit();

    // 4. Limpieza
    // Función recursiva para borrar temp
    function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
    deleteDir($extractPath);

    // Limpiar buffer antes de enviar respuesta
    ob_end_clean(); 
    echo json_encode([
        'success' => true,
        'count' => $importedCount,
        'skipped' => $skippedCount,
        'message' => 'Proceso completado'
    ]);

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // Limpiar buffer
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
