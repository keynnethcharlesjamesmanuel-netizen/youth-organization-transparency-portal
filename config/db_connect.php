<?php
/**
 * Database Connection Configuration
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'youth_org_portal');

try {
    // Create PDO connection
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
        DB_USER,
        DB_PASS
    );
    
    // Set error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set charset to utf8
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

/**
 * Sanitize input to prevent SQL injection
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Log activity
 */
function log_activity($pdo, $admin_id, $action, $table_name = null, $record_id = null, $changes = null) {
    try {
        $stmt = $pdo->prepare('INSERT INTO activity_logs (admin_id, action, table_name, record_id, changes_description) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$admin_id, $action, $table_name, $record_id, $changes]);
    } catch (PDOException $e) {
        error_log('Activity logging failed: ' . $e->getMessage());
    }
}
?>