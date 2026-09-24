<?php
require_once '../config/database.php';

// Debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        try {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;
            
            // Filters
            $search = $_GET['search'] ?? '';
            $type = $_GET['type'] ?? '';
            $dateFrom = $_GET['date_from'] ?? '';
            $dateTo = $_GET['date_to'] ?? '';

            $sql = "SELECT * FROM movement_history WHERE 1=1";
            $params = [];

            if (!empty($search)) {
                $sql .= " AND (description LIKE ? OR type LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }

            if (!empty($type) && $type !== 'all') {
                $sql .= " AND type = ?";
                $params[] = $type;
            }

            if (!empty($dateFrom)) {
                $sql .= " AND DATE(created_at) >= ?";
                $params[] = $dateFrom;
            }

            if (!empty($dateTo)) {
                $sql .= " AND DATE(created_at) <= ?";
                $params[] = $dateTo;
            }

            $sql .= " ORDER BY created_at DESC LIMIT ?";
            
            $stmt = $pdo->prepare($sql);
            
            // Bind 1-based index
            foreach ($params as $i => $val) {
                $stmt->bindValue($i + 1, $val);
            }
            $stmt->bindValue(count($params) + 1, $limit, PDO::PARAM_INT);
            
            $stmt->execute();
            echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;
        
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}
?>
