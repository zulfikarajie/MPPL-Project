<?php
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

include 'config.php';

// Ambil ID pengguna dari sesi
$userId = intval($_SESSION['user_id']);
$tableName = validateTableName('user_' . $userId);

// Validasi data input
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['selected']) || !is_array($input['selected'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

// Data ID yang dipilih
$selectedIds = array_map('intval', $input['selected']); // Pastikan semuanya angka

// Pastikan ada ID untuk dihapus
if (empty($selectedIds)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No rows selected']);
    exit;
}

// Buat query penghapusan
try {
    $pdo = getConnection();

    // Siapkan query untuk menghapus berdasarkan ID
    $placeholders = rtrim(str_repeat('?,', count($selectedIds)), ',');
    $stmt = $pdo->prepare("DELETE FROM $tableName WHERE id_pelanggan IN ($placeholders)");
    
    // Eksekusi query
    $stmt->execute($selectedIds);

    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Selected rows deleted successfully']);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
