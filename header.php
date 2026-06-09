<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZooTrack - Hayvan Takip Sistemi</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="style.css" rel="stylesheet">
</head>
<body>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <i class="bi bi-wildcard me-2 text-emerald fs-4"></i>
                <span class="fw-bold fs-4 text-white">Zoo<span class="text-emerald">Track</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link px-3" href="index.php"><i class="bi bi-grid-1x2-fill me-1"></i> Panel</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3" href="add-animal.php"><i class="bi bi-plus-circle-fill me-1"></i> Yeni Hayvan Ekle</a>
                        </li>
                        <li class="nav-item ms-lg-3">
                            <span class="navbar-text me-3 d-none d-lg-inline text-light opacity-75">
                                <i class="bi bi-person-badge-fill me-1 text-emerald"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-logout btn-sm px-3 py-2" href="logout.php">
                                <i class="bi bi-box-arrow-right me-1"></i> Çıkış
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link px-3 text-light opacity-75" href="login.php">Giriş Yap</a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-emerald btn-sm px-4 py-2" href="register.php">Kayıt Ol</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="main-content">
