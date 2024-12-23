<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pastikan pengguna sudah login
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu.']);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $complaintText = $_POST['complaint'] ?? '';

    // Validasi input
    if (empty($complaintText)) {
        echo json_encode(['success' => false, 'message' => 'Deskripsi komplain tidak boleh kosong.']);
        exit;
    }

    try {
        $pdo = getConnection();

        // Simpan komplain ke database
        $stmt = $pdo->prepare("INSERT INTO complaints (user_id, complain_text, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$userId, $complaintText]);

        echo json_encode(['success' => true, 'message' => 'Pengaduan Anda berhasil dikirim. Terima kasih!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
