<?php
include 'service/database.php';
session_start();

$register_message = "";
$message_type     = "";

if(isset($_SESSION['is_login'])) {
    header("Location: dashboard.php");
    exit();
}

$mesin_list = mysqli_query($db, "SELECT * FROM `machines` WHERE `is_active` = 1");

if(isset($_POST['register'])) {
    $username   = $_POST['username'];
    $password   = $_POST['password'];
    $machine_id = (int)$_POST['machine_id'];
    $role       = $_POST['role'];

    if (!in_array($role, ['user', 'admin'])) { $role = 'user'; }

    $hash_password = hash('sha256', $password);
    $sql = "INSERT INTO `user` (`username`, `password`, `role`, `machine_id`)
            VALUES ('$username', '$hash_password', '$role', '$machine_id')";

    try {
        if($db->query($sql)) {
            $register_message = "You can login now with your account";
            $message_type     = "success";
        } else {
            $register_message = "Registration failed. Please try again.";
            $message_type     = "error";
        }
    } catch(mysqli_sql_exception $e) {
        $register_message = "Username already exists. Please choose a different username.";
        $message_type     = "error";
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
    <title>Sanitary On The Go - Register</title>
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

            <!-- Kanan: Form Register -->
            <div class="auth-form-side">
                <a href="index.php" class="btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                    Kembali
                </a>
                <h2 class="auth-title">Hello!</h2>
                <p class="auth-subtitle">Create your account</p>

                <form action="register.php" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="form-group">
                        <label>Pilih Mesin</label>
                        <select name="machine_id" required>
                            <option value="">-- Pilih Mesin --</option>
                            <?php while ($mesin = mysqli_fetch_assoc($mesin_list)): ?>
                            <option value="<?php echo $mesin['id']; ?>">
                                <?php echo $mesin['name']; ?> - <?php echo $mesin['location']; ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group mt-top">
                        <button type="submit" name="register" class="btn-submit">Create an Account</button>
                    </div>
                </form>

                <div class="auth-divider">
                    <span class="divider-line"></span>
                    <a href="login.php" class="divider-link">Already have an account? Login</a>
                    <span class="divider-line"></span>
                </div>
            </div>

        </div>
    </div>

    <?php if (isset($message_type)): ?>
    <div id="notif" class="notif-wrapper notif-hidden">
        <?php if ($message_type === "success"): ?>
            <div class="notif-box notif-success">
                <img src="check.png" class="notif-icon" alt="">
                <div>
                    <div class="notif-heading">Account has been created</div>
                    <div class="notif-body"><?php echo $register_message; ?></div>
                </div>
            </div>
        <?php elseif ($message_type === "error"): ?>
            <div class="notif-box notif-error">
                <img src="cross.png" class="notif-icon" alt="">
                <div>
                    <div class="notif-heading">Registration Failed</div>
                    <div class="notif-body"><?php echo $register_message; ?></div>
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
