<?php
require_once '../config.php';
require_once '../functions.php';

// Get all active products
$products = getProducts();

// Get cart count if logged in
$cartCount = 0;
if (isLoggedIn()) {
    $cartCount = getCartCount(getCurrentUserId());
}
// If user is not logged in to the system, user cannot add product to cart and purchase.
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
        <link rel="stylesheet" href="../style.css">
        <link
            href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
            rel="stylesheet"
        />
        <title>My Products - CYCRIDE</title>
    </head>
    <body>
        <header>
        <a href="home.php" class="logo">CYCRIDE</a>
        
        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <nav class="navbar" id="navbar">
            <a href="dashboard.php">Dashboard</a>
            <a href="manage_products.php">Manage Products</a>
            
            <?php if (isLoggedIn()): ?>
                <span style="color: #333;">Welcome, <strong><?php echo clean(getCurrentUsername()); ?></strong></span>
                <a href="../logout_process.php" style="color: #ff4444;">Logout</a>
            <?php else: ?>
                <a href="login.php" class="login-link">Login</a>
                <a href="register.php" class="register-link">Register</a>
            <?php endif; ?>
        </nav>

        </header>
        
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
                                <?php if ($product['image_url'] && file_exists($product['image_url'])): ?>
                                    <img src="<?php echo clean($product['image_url']); ?>" 
                                         alt="<?php echo clean($product['product_name']); ?>">
                                <?php else: ?>
                                    <img src="images/placeholder.jpg" alt="No image" 
                                         style="background: #f0f0f0;">
                                <?php endif; ?>
                            </div>
                            <h2 class="product-title"><?php echo clean($product['product_name']); ?></h2>
                            <div class="price-and-cart">
                                <p style="font-weight:600;">PHP<?php echo number_format($product['price'], 0); ?></p>
                                <span class="price" style="visibility:hidden;"><?php echo $product['price']; ?></span>
                                <i class="ri-shopping-bag-line add-cart" 
                                   data-product-id="<?php echo $product['product_id']; ?>"
                                   title="Add to cart"></i>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <div id="cart-notification">Product added to cart!</div>
        
        <script src="script.js"></script>
        <script>
          document.addEventListener('DOMContentLoaded', () => {
            const hamburger = document.getElementById('hamburger');
            const nav = document.getElementById('navbar');

            hamburger.addEventListener('click', () => {
                nav.classList.toggle('active');
            });
            
            // Load cart on page load
            if (localStorage.getItem('userId')) {
                loadCart();
            }
        });
        </script>
        
    </body>
</html>
