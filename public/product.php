<?php
session_start();
$error = $_GET['error'] ?? '';
include 'service/database.php';

if (!isset($_SESSION['is_login'])) {
    header("Location: login.php");
    exit();
}

$machine_id = $_SESSION['machine_id'] ?? 1;
$products = mysqli_query($db,
    "SELECT * FROM `products`
     WHERE `machine_id` = '$machine_id' AND `stock` > 0
     ORDER BY `id` ASC"
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/Animate.css">
    <title>Sanitary On The Go - Product Page</title>
</head>
<body class="product-page animate-page-in">

<div id="transition-overlay"></div>

<div class="product-page-wrapper">

    <!-- Heading -->
    <div class="product-heading">
        <h1>Pick Your Product</h1>
    </div>

    <!-- Alert stok habis -->
    <?php if ($error === 'stok_habis'): ?>
    <div class="alert-error">⚠️ Maaf, stok produk ini sudah habis!</div>
    <?php endif; ?>

    <!-- Product Cards -->
    <section class="product-grid">

        <?php while ($product = mysqli_fetch_assoc($products)): ?>
        <div class="product-item">
            <form action="add_to_cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                <input type="hidden" name="name"       value="<?php echo htmlspecialchars($product['name']); ?>">
                <input type="hidden" name="size"       value="<?php echo htmlspecialchars($product['size']); ?>">
                <input type="hidden" name="price"      value="<?php echo htmlspecialchars($product['price']); ?>">
                <input type="hidden" name="image"      value="<?php echo htmlspecialchars($product['image']); ?>">

                <button type="submit" name="buy" class="btn-buy">
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>"
                             alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="product-info">
                            <span class="product-name"><?php echo htmlspecialchars($product['name']); ?></span>
                            <p class="product-size"><?php echo htmlspecialchars($product['size']); ?></p>
                            <div class="product-price-wrap">
                                <p class="product-price">
                                    Rp <?php echo number_format($product['price'], 0, ',', '.'); ?>
                                </p>
                            </div>
                            <p class="product-stock <?php echo $product['stock'] <= 3 ? 'stock-low' : 'stock-ok'; ?>">
                                <?php echo $product['stock'] <= 3
                                    ? '⚡ Sisa ' . $product['stock']
                                    : '✓ Stok: ' . $product['stock']; ?>
                            </p>
                        </div>
                    </div>
                </button>
            </form>
        </div>
        <?php endwhile; ?>

    </section>


</div><!-- /.product-page-wrapper -->

<script src="js/timeout_product.js"></script>

</body>
</html>