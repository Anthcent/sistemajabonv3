<?php
require_once '../config/database.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$range = $_GET['range'] ?? 'all';

// Date filtering logic
if ($range === 'today') {
    $dateWhere = "DATE(s.created_at) = CURDATE()";
    $plainDateWhere = "DATE(created_at) = CURDATE()";
} elseif ($range === 'yesterday') {
    $dateWhere = "DATE(s.created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
    $plainDateWhere = "DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
} elseif ($range === '7days') {
    $dateWhere = "s.created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    $plainDateWhere = "created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
} else {
    $dateWhere = "1=1";
    $plainDateWhere = "1=1";
}

switch ($action) {
    case 'dashboard':
        try {
            // 1. Top 5 Products
            $topProductsStmt = $pdo->prepare("
                SELECT p.name, SUM(si.quantity) as total_qty
                FROM sale_items si
                JOIN products p ON si.product_id = p.id
                JOIN sales s ON si.sale_id = s.id
                WHERE $dateWhere
                GROUP BY si.product_id
                ORDER BY total_qty DESC
                LIMIT 5
            ");
            $topProductsStmt->execute();
            $topProducts = $topProductsStmt->fetchAll(PDO::FETCH_ASSOC);

            // 2. Sales by Category
            $categorySalesStmt = $pdo->prepare("
                SELECT c.name, SUM(si.subtotal) as total_amount
                FROM sale_items si
                JOIN products p ON si.product_id = p.id
                JOIN categories c ON p.category_id = c.id
                JOIN sales s ON si.sale_id = s.id
                WHERE $dateWhere
                GROUP BY c.id
                ORDER BY total_amount DESC
            ");
            $categorySalesStmt->execute();
            $categorySales = $categorySalesStmt->fetchAll(PDO::FETCH_ASSOC);

            // 3. Payment Methods
            $paymentMethodsStmt = $pdo->prepare("
                SELECT payment_method, COUNT(*) as count, SUM(total_amount) as total
                FROM sales
                WHERE $plainDateWhere
                GROUP BY payment_method
            ");
            $paymentMethodsStmt->execute();
            $paymentMethods = $paymentMethodsStmt->fetchAll(PDO::FETCH_ASSOC);

            // 4. Sales Trend (Dynamic based on range)
            $trendLimit = $range === 'all' ? 30 : 7;
            $trendStmt = $pdo->prepare("
                SELECT DATE(created_at) as date, SUM(total_amount) as total
                FROM sales
                WHERE $plainDateWhere
                GROUP BY DATE(created_at)
                ORDER BY date ASC
                LIMIT $trendLimit
            ");
            $trendStmt->execute();
            $trend = $trendStmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'status' => 'success',
                'data' => [
                    'topProducts' => $topProducts,
                    'categorySales' => $categorySales,
                    'paymentMethods' => $paymentMethods,
                    'trend' => $trend
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Action not specified']);
}
