<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

include 'config.php';

$userId = $_SESSION['user_id'];
$tableName = 'user_' . intval($userId);

// Mengambil data JSON dari permintaan POST
$data = json_decode(file_get_contents('php://input'), true);
$oldName = $data['oldName'] ?? '';
$newName = $data['newName'] ?? '';

if (empty($oldName) || empty($newName)) {
    echo json_encode(['success' => false, 'message' => 'Invalid column names provided.']);
    exit;
}

try {
    $pdo = getConnection();

    // Periksa apakah nama kolom baru sudah digunakan
    $stmt = $pdo->query("SHOW COLUMNS FROM `$tableName` LIKE '$newName'");
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'The new column name is already in use.']);
        exit;
    }

    // Ubah nama kolom
    $query = "ALTER TABLE `$tableName` CHANGE `$oldName` `$newName` VARCHAR(255)";
    $pdo->exec($query);

    echo json_encode(['success' => true, 'message' => 'Column name updated successfully.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
