<?php
if (!function_exists('log_movement')) {
    function log_movement($pdo, $type, $description, $details = null) {
        try {
            $stmt = $pdo->prepare("INSERT INTO movement_history (type, description, details) VALUES (?, ?, ?)");
            $stmt->execute([
                $type, 
                $description, 
                $details ? json_encode($details) : null
            ]);
        } catch (Exception $e) {
            // Silently fail logging to not disrupt main flow, or log to file
            error_log("Failed to log movement: " . $e->getMessage());
        }
    }
}
?>
