<?php
require 'config.php'; // Memastikan file config sudah di-load

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Dapatkan koneksi database
        $pdo = getConnection();

        // Query untuk mencari username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifikasi password
        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'] ?? 'user';
            $_SESSION['subscription_role'] = $user['subscription_role'] ?? null;

            // Redirect berdasarkan role
            if ($user['role'] === 'admin') {
                header('Location: admin_dashboard.html');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            $message = 'Invalid username or password.';
        }
    } catch (PDOException $e) {
        $message = 'Error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Syncron Inn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('bground.png');
            background-size: cover; /* Menyesuaikan gambar agar memenuhi seluruh layar */
            background-repeat: no-repeat; /* Mencegah pengulangan gambar */
            background-position: center; /* Menempatkan gambar di tengah */
            background-attachment: fixed; /* Membuat latar belakang tetap saat di-scroll */
        }
        .card {
            border: none;
        }
        .text-primary {
            color: #BB2124 !important;
        }
        .btn-primary {
            background-color: #BB2124;
            border-color: #BB2124;
        }
        .btn-primary:hover {
            background-color: #a91c1f;
            border-color: #a91c1f;
        }
        .text-decoration-none {
            color: #BB2124 !important;
        }
        .text-decoration-none:hover {
            color: #a91c1f !important;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg" style="width: 24rem;">
        <img src="logo2.png" alt="Logo Syncron Inn" class="img-center mb-4" style="max-width: 350px;">
            <?php if ($message): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <p class="mt-3 text-center">Don't have an account? <a href="register.php" class="text-decoration-none">Register here</a>.</p>
        </div>
    </div>
</body>
</html>
