<?php
/**
 * Configuración de Base de Datos - Producción InfinityFree
 */

$host = 'localhost';
$dbname = 'jabones_pos_db';
$username = 'root';
$password = '';
$port = '3306';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Error simple
    die("Error de conexión (DB): " . $e->getMessage());
}
?>