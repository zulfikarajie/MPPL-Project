<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe - Syncron Inn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
            background-image: url('bground3.png');
            background-size: cover; /* Menyesuaikan gambar agar memenuhi seluruh layar */
            background-repeat: no-repeat; /* Mencegah pengulangan gambar */
            background-position: center; /* Menempatkan gambar di tengah */
            background-attachment: fixed; /* Membuat latar belakang tetap saat di-scroll */
        }  
        main {
            flex: 1;
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
        .card-subscription {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-subscription:hover {
            transform: scale(1.05);
            transition: all 0.3s;
        }
        .btn-subscribe {
            background-color: #BB2124;
            color: #fff;
        }
        .btn-subscribe:hover {
            background-color: #f8d7da;
            color: #BB2124;
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="#">
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

<!-- Main Content -->
<main class="container mt-5">
    <h2 class="text-center mb-4">Subscribe to Unlock Premium Features</h2>
    <div class="row justify-content-center">
        <!-- Trial Plan -->
        <div class="col-md-4">
            <div class="card card-subscription text-center">
                <div class="card-body">
                    <h3 class="card-title">Trial Plan</h3>
                    <p class="card-text">Free</p>
                    <ul class="list-unstyled">
                        <li>✔️ Basic Features</li>
                        <li>✔️ Free for 3 days</li>
                    </ul>
                    <button class="btn btn-subscribe" onclick="subscribePlan('Trial')">Choose Plan</button>
                </div>
            </div>
        </div>
        <!-- Monthly Plan -->
        <div class="col-md-4">
            <div class="card card-subscription text-center">
                <div class="card-body">
                    <h3 class="card-title">Monthly Plan</h3>
                    <p class="card-text">Rp98.000 / Month</p>
                    <ul class="list-unstyled">
                        <li>✔️ Max 500 Tables / Month</li>
                        <li>✔️ Data Export (Monthly Basis)</li>
                    </ul>
                    <button class="btn btn-subscribe" onclick="subscribePlan('Monthly')">Choose Plan</button>
                </div>
            </div>
        </div>
        <!-- Yearly Plan -->
        <div class="col-md-4">
            <div class="card card-subscription text-center">
                <div class="card-body">
                    <h3 class="card-title">Yearly Plan</h3>
                    <p class="card-text">Rp980.000 / Year</p>
                    <ul class="list-unstyled">
                        <li>✔️ Unlimited Tables / Year</li>
                        <li>✔️ Data Export (Monthly & Yearly)</li>
                    </ul>
                    <button class="btn btn-subscribe" onclick="subscribePlan('Yearly')">Choose Plan</button>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<footer>
    <p>&copy; <?= date('Y'); ?> Syncron Inn. All Rights Reserved.</p>
</footer>

<script>
    function subscribePlan(plan) {
        alert(`You selected the ${plan} plan!`);
    }
</script>
</body>
</html>
