<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/Animate.css">
    <title>Sanitary On The Go - Cart</title>
</head>
<body class="animate-page-in">
    <?php include 'layout/header_user.html' ?>

    <section class="cart-section">
        <div class="cart-panel">

            <!-- Header -->
            <div class="cart-header">
                <h2>Shopping Cart (2)</h2>
                <button class="cart-close-btn" aria-label="Close">
                    <svg width="45" height="45" viewBox="0 0 45 45" fill="none">
                        <circle cx="22.5" cy="22.5" r="22.5" fill="white"/>
                        <path d="M28.75 16.25L16.25 28.75" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16.25 16.25L28.75 28.75" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <!-- Item 1 -->
            <div class="cart-item">
                <img src="https://iili.io/3FqLBsI.png" alt="" class="cart-item-img">
                <div class="cart-item-info">
                    <h3>Fresh Indian Orange</h3>
                    <p><span class="cart-qty">1 kg x</span><span class="cart-item-price"> 12.00</span></p>
                </div>
                <button class="cart-remove-btn" aria-label="Remove">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                        <path d="M12 23C18.0748 23 23 18.0748 23 12C23 5.92525 18.0748 1 12 1C5.92525 1 1 5.92525 1 12C1 18.0748 5.92525 23 12 23Z" stroke="#CCCCCC" stroke-miterlimit="10"/>
                        <path d="M16 8L8 16" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16 16L8 8" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <!-- Item 2 -->
            <div class="cart-item">
                <img src="https://iili.io/3FqLBsI.png" alt="" class="cart-item-img">
                <div class="cart-item-info">
                    <h3>Fresh Indian Orange</h3>
                    <p><span class="cart-qty">1 kg x</span><span class="cart-item-price"> 12.00</span></p>
                </div>
                <button class="cart-remove-btn" aria-label="Remove">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                        <path d="M12 23C18.0748 23 23 18.0748 23 12C23 5.92525 18.0748 1 12 1C5.92525 1 1 5.92525 1 12C1 18.0748 5.92525 23 12 23Z" stroke="#CCCCCC" stroke-miterlimit="10"/>
                        <path d="M16 8L8 16" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16 16L8 8" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <!-- Footer -->
            <div class="cart-footer">
                <div class="cart-total-row">
                    <span>2 Product</span>
                    <span class="cart-total-price">$26.00</span>
                </div>
                <button class="btn-checkout">Checkout</button>
                <button class="btn-go-cart">Go To Cart</button>
            </div>

        </div>
    </section>
</body>
</html>
