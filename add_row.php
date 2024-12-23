<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
include 'config.php';
$userId = $_SESSION['user_id'];
$tableName = validateTableName('user_' . intval($userId));
try {
    $pdo = getConnection();
    $stmt = $pdo->query("SHOW COLUMNS FROM $tableName");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $placeholders = implode(',', array_fill(0, count($columns), 'DEFAULT'));
    $pdo->exec("INSERT INTO $tableName VALUES ($placeholders)");
    echo json_encode(['success' => true, 'message' => 'Row added successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
