<?php
require 'config.php';
session_start();

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

// Ambil data rows
$rows = $data['rows'] ?? [];

if (empty($rows)) {
    echo json_encode(['success' => false, 'message' => 'No data provided']);
    exit;
}

try {
    $pdo = getConnection();
    $tableName = "user_$userId";

    // Pastikan tabel ada
    $stmt = $pdo->query("SHOW TABLES LIKE '$tableName'");
    if ($stmt->rowCount() == 0) {
        echo json_encode(['success' => false, 'message' => "Table $tableName does not exist"]);
        exit;
    }

    $pdo->beginTransaction();

    foreach ($rows as $row) {
        $id = $row['id'];
        $values = $row['values'];

        // Jika ID ada, update data
        if ($id) {
            $placeholders = [];
            $params = [];
            foreach ($values as $key => $value) {
                $placeholders[] = "column_$key = ?";
                $params[] = $value;
            }
            $params[] = $id;

            $query = "UPDATE $tableName SET " . implode(', ', $placeholders) . " WHERE id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
        } else {
            // Jika ID kosong, insert data baru
            $placeholders = implode(',', array_fill(0, count($values), '?'));
            $query = "INSERT INTO $tableName (" . implode(',', array_keys($values)) . ") VALUES ($placeholders)";
            $stmt = $pdo->prepare($query);
            $stmt->execute(array_values($values));
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Data berhasil disimpan']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
