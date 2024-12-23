<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

include 'config.php';

$userId = intval($_SESSION['user_id']);
$tableName = validateTableName('user_' . $userId);

// Ambil input JSON
$input = json_decode(file_get_contents('php://input'), true);

// Validasi input
if (!isset($input['updates']) || !is_array($input['updates'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

$updates = $input['updates'];

try {
    $pdo = getConnection();

    // Ambil daftar kolom untuk tabel ini
    $stmt = $pdo->query("SHOW COLUMNS FROM $tableName");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $columnNames = array_column($columns, 'Field'); // Daftar nama kolom

    // Proses setiap update
    foreach ($updates as $update) {
        // Periksa apakah ID valid
        if (!isset($update['id']) || !is_numeric($update['id']) || intval($update['id']) <= 0) {
            continue; // Abaikan data jika ID tidak valid
        }
    
        $id = intval($update['id']); // Ambil nilai ID yang valid
        $updateFields = [];
        $updateValues = [];
    
        foreach ($update as $key => $value) {
            if ($key === 'id') continue; // Abaikan ID dari kolom yang di-update
            $columnIndex = str_replace('col_', '', $key);
    
            if (isset($columnNames[$columnIndex])) {
                $updateFields[] = "{$columnNames[$columnIndex]} = ?";
                $updateValues[] = $value;
            }
        }
    
        if (count($updateFields) > 0) {
            $updateValues[] = $id; // Tambahkan ID sebagai syarat WHERE
            $updateQuery = "UPDATE $tableName SET " . implode(', ', $updateFields) . " WHERE id_pelanggan = ?";
            $stmt = $pdo->prepare($updateQuery);
            $stmt->execute($updateValues);
        }
    }
        

    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Changes submitted successfully']);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
