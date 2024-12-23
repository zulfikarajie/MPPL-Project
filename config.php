<?php
$host = 'localhost';
$db_name = 'syncroninn_db';
$db_user = 'root';
$db_pass = '';

// Koneksi ke database
function getConnection() {
    global $host, $db_name, $db_user, $db_pass;
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db_name", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// Validasi nama tabel
function validateTableName($tableName) {
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $tableName)) {
        throw new Exception('Invalid table name.');
    }
    return $tableName;
}

// Validasi nama kolom
function validateColumnName($columnName) {
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $columnName)) {
        throw new Exception('Invalid column name.');
    }
    return $columnName;
}
?>
