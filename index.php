<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'config.php';

$userId = $_SESSION['user_id'];
$tableName = validateTableName('user_' . intval($userId));

// Ambil data tabel
function getUserTable($tableName)
{
    $pdo = getConnection();
    try {
        $stmt = $pdo->query("SELECT * FROM $tableName");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

// Ambil kolom tabel
function getTableColumns($tableName)
{
    $pdo = getConnection();
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM $tableName");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

$tableData = getUserTable($tableName);
$tableColumns = getTableColumns($tableName);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syncron Inn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    <style>
        body {
            background-image: url('bground2.png');
            background-size: cover; /* Menyesuaikan gambar agar memenuhi seluruh layar */
            background-repeat: no-repeat; /* Mencegah pengulangan gambar */
            background-position: center; /* Menempatkan gambar di tengah */
            background-attachment: fixed; /* Membuat latar belakang tetap saat di-scroll */
        }  

        .navbar {
            background-color: #BB2124;
        }

        .navbar-brand,
        .nav-link {
            color: #ffffff !important;
        }

        .table-container {
            margin: 2rem auto;
            background: #ffffff;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #BB2124;
            border-color: #BB2124;
        }

        footer {
            background-color: #BB2124;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        footer p {
            margin: 0;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="logo1.png" alt="Logo" class="img-fluid" style="max-width: 15%; height: auto;">
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="subscribe.php">Subscribe</a></li>
                    <li class="nav-item"><a class="nav-link" href="complain.php">Complain</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="table-container">
            <h2 class="text-center mb-4">Data Management</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <!-- Kolom tambahan untuk Checkbox -->
                        <th class="text-center align-middle">Select to Delete</th>
                        <?php if (!empty($tableColumns)): ?>
                            <?php foreach ($tableColumns as $column): ?>
                                <?php if ($column['Field'] === 'id_pelanggan'): ?>
                                    <!-- Header id_pelanggan read-only -->
                                    <th class="text-center align-middle">
                                        <input type="text" class="form-control d-inline-block w-75 mb-2"
                                            value="<?= htmlspecialchars($column['Field']) ?>" readonly>
                                    </th>
                                <?php else: ?>
                                    <!-- Header kolom lain -->
                                    <th class="text-center align-middle">
                                        <input type="text" class="form-control d-inline-block w-75 mb-2"
                                            value="<?= htmlspecialchars($column['Field']) ?>"
                                            onchange="editColumnName('<?= $column['Field'] ?>', this.value)">
                                        <button class="btn btn-sm btn-danger d-inline-block mt-1"
                                            onclick="deleteColumn('<?= $column['Field'] ?>')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </th>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <th class="text-center" colspan="100%">No columns found</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <?php if (!empty($tableData)): ?>
                        <?php foreach ($tableData as $row): ?>
                            <tr>
                                <!-- Kolom Checkbox -->
                                <td class="text-center">
                                    <input type="checkbox" class="row-checkbox"
                                        value="<?= htmlspecialchars($row['id_pelanggan']) ?>">
                                </td>
                                <?php foreach ($row as $key => $cell): ?>
                                    <?php if ($key === 'id_pelanggan'): ?>
                                        <!-- Body id_pelanggan read-only -->
                                        <td class="text-center align-middle">
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($cell) ?>" readonly>
                                        </td>
                                    <?php else: ?>
                                        <!-- Body kolom lain -->
                                        <td>
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($cell) ?>">
                                        </td>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td class="text-center" colspan="100%">No data available</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="text-center">
                <button class="btn btn-primary" onclick="addRowToDatabase()">+ Add Row</button>
                <button class="btn btn-outline-danger" onclick="deleteSelectedRows()">- Delete Selected Row</button>
                <button class="btn btn-warning" onclick="addColumn()">+ Add Column</button>
                <button class="btn btn-success" onclick="submitChanges()"> Submit Changes</button>
                <button class="btn btn-info" onclick="exportTableToPDF()">Export to PDF</button>
            </div>
        </div>

    </div>

    <footer>
        <p>&copy; <?= date('Y'); ?> Syncron Inn. All Rights Reserved.</p>
    </footer>

    <script>
        function exportTableToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Ambil elemen tabel
            const table = document.querySelector('.table');
            const headers = [];
            const data = [];

            // Ambil header tabel, lewati kolom "Select to Delete"
            const headerCells = table.querySelectorAll('thead th');
            headerCells.forEach((header, index) => {
                // Lewati kolom pertama (checkbox)
                if (index === 0) return;

                // Ambil nilai dari input jika ada
                const input = header.querySelector('input');
                headers.push(input ? input.value.trim() : header.textContent.trim());
            });

            // Ambil data tabel
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const rowData = [];
                const cells = row.querySelectorAll('td');

                cells.forEach((cell, index) => {
                    // Lewati kolom pertama (checkbox)
                    if (index === 0) return;

                    const input = cell.querySelector('input');
                    // Ambil nilai input jika ada, jika tidak, ambil teks dalam cell
                    rowData.push(input ? input.value.trim() : cell.textContent.trim());
                });

                data.push(rowData);
            });

            // Gunakan autoTable untuk membuat tabel di PDF
            doc.autoTable({
                head: [headers],
                body: data,
            });

            // Unduh PDF
            doc.save('table.pdf');
        }



        function addColumn() {
            const columnName = prompt("Enter the new column name:");
            if (!columnName) {
                alert("Column name cannot be empty!");
                return;
            }

            fetch('add_column.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ columnName })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Column added successfully!');
                        location.reload();
                    } else {
                        alert(`Error: ${data.message}`);
                    }
                })
                .catch(err => console.error('Error:', err));
        }

        function submitChanges() {
            // Ambil semua baris tabel
            const rows = document.querySelectorAll('#table-body tr');
            const updates = [];

            rows.forEach(row => {
                const id = row.querySelector('.row-checkbox')?.value; // Ambil ID dari checkbox
                if (!id) return;

                const cells = row.querySelectorAll('td:not(:first-child) input'); // Ambil semua input (kecuali checkbox)
                const rowUpdate = { id }; // Mulai objek untuk baris ini
                cells.forEach((cell, index) => {
                    rowUpdate[`col_${index}`] = cell.value; // Simpan data cell
                });

                updates.push(rowUpdate); // Tambahkan ke array updates
            });

            if (updates.length === 0) {
                alert('No changes to submit!');
                return;
            }

            // Kirim data ke server
            fetch('submit_changes.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ updates })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Changes submitted successfully!');
                        location.reload();
                    } else {
                        alert(`Error: ${data.message}`);
                    }
                })
                .catch(err => console.error('Error:', err));
        }

        function deleteSelectedRows() {
            const selected = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(checkbox => checkbox.value);

            if (selected.length === 0) {
                alert('No rows selected!');
                return;
            }

            if (!confirm('Are you sure you want to delete the selected rows?')) return;

            fetch('delete_selected_row.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ selected })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Selected rows deleted successfully!');
                        location.reload();
                    } else {
                        alert(`Error: ${data.message}`);
                    }
                })
                .catch(err => console.error('Error:', err));
        }

        function editColumnName(oldName, newName) {
            if (!newName || newName.trim() === '') {
                alert('Column name cannot be empty!');
                return;
            }

            fetch('edit_column_name.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ oldName, newName })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Column name updated successfully!');
                        location.reload();
                    } else {
                        alert(`Error: ${data.message}`);
                    }
                })
                .catch(err => console.error('Error:', err));
        }

        function addRowToDatabase() {
            fetch('add_row.php', { method: 'POST', headers: { 'Content-Type': 'application/json' } })
                .then(response => response.json())
                .then(data => { alert(data.message); location.reload(); })
                .catch(err => console.error(err));
        }

        function deleteColumn(columnName) {
            if (!confirm(`Delete column ${columnName}?`)) return;

            fetch('delete_column.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ columnName })
            })
                .then(response => response.json())
                .then(data => { alert(data.message); location.reload(); })
                .catch(err => console.error(err));
        }
    </script>
</body>

</html>