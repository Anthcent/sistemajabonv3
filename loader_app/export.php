<?php
require_once 'config/database.php';

// 1. Obtener Datos
$stmt = $pdo->query("SELECT * FROM loader_products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$jsonData = json_encode($products, JSON_PRETTY_PRINT);

// 2. Crear ZIP
$zipName = 'backup_loader_' . date('Y-m-d_H-i-s') . '.zip';
$zip = new ZipArchive();

if ($zip->open($zipName, ZipArchive::CREATE) === TRUE) {
    // Agregar JSON
    $zip->addFromString('products.json', $jsonData);

    // Agregar Imágenes
    $uploadDir = __DIR__ . '/uploads/';
    if (is_dir($uploadDir)) {
        $files = scandir($uploadDir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $filePath = $uploadDir . $file;
                $zip->addFile($filePath, 'uploads/' . $file);
            }
        }
    }

    $zip->close();

    // 3. Forzar Descarga
    if (file_exists($zipName)) {
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="'.basename($zipName).'"');
        header('Content-Length: ' . filesize($zipName));
        readfile($zipName);
        
        // Limpiar
        unlink($zipName);
        exit;
    } else {
        die("Error al crear el archivo ZIP");
    }
} else {
    die("No se pudo iniciar la creación del ZIP");
}
?>
