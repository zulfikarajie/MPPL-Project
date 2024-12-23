<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

include 'config.php';

$userId = $_SESSION['user_id'];
$tableName = 'user_' . intval($userId);

// Mendapatkan nama kolom baru dari permintaan
$data = json_decode(file_get_contents('php://input'), true);
$newColumnName = $data['columnName'] ?? '';

if (empty($newColumnName)) {
    echo json_encode(['success' => false, 'message' => 'Column name cannot be empty.']);
    exit;
}

try {
    $pdo = getConnection();

    // Tambahkan kolom baru ke tabel
    $query = "ALTER TABLE `$tableName` ADD `$newColumnName` VARCHAR(255)";
    $pdo->exec($query);

    echo json_encode(['success' => true, 'message' => 'Column added successfully.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
