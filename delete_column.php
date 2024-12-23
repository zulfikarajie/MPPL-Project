<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
include 'config.php';
$userId = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
$tableName = validateTableName('user_' . intval($userId));
$columnName = validateColumnName($data['columnName']);
try {
    $pdo = getConnection();
    $pdo->exec("ALTER TABLE $tableName DROP COLUMN `$columnName`");
    echo json_encode(['success' => true, 'message' => 'Column deleted successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
