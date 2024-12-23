<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan Komplain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background-color: #BB2124;
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #ffffff !important;
        }
        .navbar-custom .nav-link:hover {
            color: #f8d7da !important;
        }
        body {
            background-image: url('bground2.png');
            background-size: cover; /* Menyesuaikan gambar agar memenuhi seluruh layar */
            background-repeat: no-repeat; /* Mencegah pengulangan gambar */
            background-position: center; /* Menempatkan gambar di tengah */
            background-attachment: fixed; /* Membuat latar belakang tetap saat di-scroll */
        }
        .card {
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #BB2124;
            border-color: #BB2124;
        }
        .btn-primary:hover {
            background-color: #a91c1f;
            border-color: #a91c1f;
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
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="admin_dashboard.php">
            <img src="logo1.png" alt="Logo" class="img-fluid" style="max-width: 15%; height: auto;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg" style="width: 30rem;">
            <h2 class="text-center text-danger mb-4">Complain Form</h2>
            <form id="complaint-form">
                <div class="mb-3">
                    <label for="complaint" class="form-label">What's Problem?</label>
                    <textarea id="complaint" name="complaint" class="form-control" rows="5" placeholder="Write your complains here" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit Complain</button>
            </form>
            <div id="response-message" class="mt-3"></div>
        </div>
    </div>

    <script>
        document.getElementById('complaint-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('handle_complaint.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const messageBox = document.getElementById('response-message');
                if (data.success) {
                    messageBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    this.reset(); // Reset form setelah sukses
                } else {
                    messageBox.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                }
            })
            .catch(err => {
                document.getElementById('response-message').innerHTML = `
                    <div class="alert alert-danger">Terjadi kesalahan saat mengirim pengaduan.</div>
                `;
                console.error(err);
            });
        });
    </script>
</body>
<footer>
    <p>&copy; <?= date('Y'); ?> Syncron Inn. All Rights Reserved.</p>
</footer>
</html>
