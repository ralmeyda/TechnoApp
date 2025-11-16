<?php
require_once 'config.php';
require_once 'functions.php';

// Get all active products
$products = getProducts();

// Get cart count if logged in
$cartCount = 0;
if (isLoggedIn()) {
    $cartCount = getCartCount(getCurrentUserId());
}

// Block cart actions if not logged in
if (isset($_POST['action']) && ($_POST['action'] === 'add_to_cart' || $_POST['action'] === 'purchase')) {
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in to perform this action.']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <title>Products</title>
    <style>
        .nav-welcome { color: #333; padding: 0 10px; font-size: 15px; }
        .product-description { font-size: 14px; color: #555; margin: 5px 0; }
    </style>
</head>
<body>
<header>
    <a href="home.php" class="logo">Thoto & Nene Fresh Live Tilapia and Bangus</a>
    <div class="hamburger" id="hamburger">
        <span></span><span></span><span></span>
    </div>

    <nav class="navbar" id="navbar">
        <a href="home.php">Home</a>
        <a href="index.php">Products</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact Us</a>

        <?php if (isLoggedIn()): ?>
            <span class="nav-welcome">Welcome, <strong><?php echo clean(getCurrentUsername()); ?></strong></span>
            <a href="profile.php" class="profile-link">Profile</a>
            <a href="logout_process.php" style="color:#ff4444;">Logout</a>
        <?php else: ?>
            <a href="login.php" class="login-link">Login</a>
            <a href="register.php" class="register-link">Register</a>
        <?php endif; ?>
    </nav>

    <div id="cart-icon">
        <i class="ri-shopping-bag-line"></i>
        <span class="cart-item-count"><?php echo $cartCount > 0 ? $cartCount : ''; ?></span>
    </div>
</header>

<div class="cart">
    <h2 class="cart-title">Your Cart</h2>
    <div class="cart-content"></div>
    <div class="total">
        <div class="total-title">Total</div>
        <div class="total-price">PHP0</div>
    </div>
    <button class="btn-buy">Buy Now</button>
    <i class="ri-close-line" id="cart-close"></i>
</div>
<div id="cart-notification">Product added to cart!</div>

<section class="shop">
    <h1 class="section-title">Shop Products</h1>
    <div class="product-content">
        <?php if (empty($products)): ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                <i class="ri-shopping-bag-line" style="font-size: 80px; color: #ddd;"></i>
                <h2 style="color: #666; margin-top: 20px;">No products available yet</h2>
                <p style="color: #999;">Admin is adding products. Please check back later!</p>
            </div>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="product-box" data-product-id="<?php echo $product['product_id']; ?>">
                    <div class="img-box">
                        <img src="<?php echo clean($product['image_url'] ?: 'images/placeholder.jpg'); ?>" 
                             alt="<?php echo clean($product['product_name']); ?>">
                    </div>
                    <h2 class="product-title"><?php echo clean($product['product_name']); ?></h2>
                    <p class="product-description"><?php echo nl2br(clean($product['description'])); ?></p>
                    <div class="price-and-cart">
                        <p style="font-weight:600;">PHP<?php echo number_format($product['price'], 0); ?></p>
                        <span class="price" style="display:none;"><?php echo $product['price']; ?></span>
                        <button class="add-cart" title="Add to cart">
                            <i class="ri-shopping-bag-line"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<script src="script.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.getElementById('hamburger');
    const nav = document.getElementById('navbar');

    hamburger.addEventListener('click', () => nav.classList.toggle('active'));
});
</script>
</body>
</html>
