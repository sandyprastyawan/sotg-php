<?php
session_start();

if(isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/Animate.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <title>Sanitary On The Go - Dashboard</title>
</head>
<body class="animate-page-in" style="height:100vh;overflow:hidden;margin:0;padding:0;">

    <!-- Tombol Logout pojok kanan atas -->
    <div class="dashboard-topbar">
        <form method="post">
            <button type="submit" name="logout" class="btn-logout-top">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Logout
            </button>
        </form>
    </div>

    <section class="dashboard-section">
        <div class="dashboard-container">

            <!-- Kiri: Teks & Tombol -->
            <div class="dashboard-content">
                <span class="dashboard-subtitle">Your needs, whenever you need.</span>
                <h1 class="dashboard-title">Sanitary On The Go!</h1>
                </p>
                <div class="dashboard-actions">
                    <a href="product.php" class="btn-shop">Order Sekarang!</a>
                </div>
            </div>

            <!-- Kanan: Gambar Produk -->
            <div class="dashboard-image-container">
                <div class="dashboard-glass-frame">
                    <img src="Produk.png" class="dashboard-product-img" alt="Sanitary Products">
                </div>
            </div>

        </div>
    </section>

</body>
</html>