<?php
include 'service/database.php';
session_start();

$login_message = "";

if(isset($_SESSION['is_login'])) {
    if($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hash_password = hash('sha256', $password);

    $sql    = "SELECT * FROM `user` WHERE `username`='$username' AND `password`='$hash_password'";
    $result = $db->query($sql);

    if($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $_SESSION['user_id']    = $data['id'];
        $_SESSION['username']   = $data['username'];
        $_SESSION['role']       = $data['role']       ?? 'user';
        $_SESSION['machine_id'] = $data['machine_id'] ?? 1;
        $_SESSION['is_login']   = true;

        if($_SESSION['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: dashboard.php");
        }
        exit();
    } else {
        $login_message = "Login failed: Invalid username or password.";
        $message_type  = "error";
    }
    $db->close();
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
    <title>Sanitary On The Go - Login</title>
</head>
<body class="animate-page-in">

    <div class="auth-wrapper">
        <div class="auth-card">

            <!-- Kiri: Gambar Produk -->
            <div class="auth-image-side">
                <div class="product-img-wrap">
                    <img src="Produk3.png" alt="Charm Safe Night Sanitary Pad">
                </div>
            </div>

            <!-- Kanan: Form Login -->
            <div class="auth-form-side">
                <a href="index.php" class="btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                    Kembali
                </a>
                <h2 class="auth-title">Hello!</h2>
                <p class="auth-subtitle">Login to your dashboard</p>

                <form action="login.php" method="post">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="form-group mt-top">
                        <button type="submit" name="login" class="btn-submit">Login</button>
                    </div>
                </form>

                <div class="auth-divider">
                    <span class="divider-line"></span>
                    <a href="register.php" class="divider-link">Don't have an account? Register</a>
                    <span class="divider-line"></span>
                </div>
            </div>

        </div>
    </div>

    <?php if (isset($message_type)): ?>
    <div id="notif" class="notif-wrapper notif-hidden">
        <?php if ($message_type === "error"): ?>
            <div class="notif-box notif-error">
                <img src="cross.png" class="notif-icon" alt="">
                <div>
                    <div class="notif-heading">Login Failed</div>
                    <div class="notif-body"><?php echo $login_message; ?></div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const notif = document.getElementById("notif");
        if (notif) {
            setTimeout(() => notif.classList.add("notif-show"), 100);
            setTimeout(() => {
                notif.classList.remove("notif-show");
                notif.classList.add("notif-hide");
            }, 3000);
            setTimeout(() => notif.remove(), 3700);
        }
    });
    </script>

</body>
</html>
