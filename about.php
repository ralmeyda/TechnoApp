<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <link
            href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
            rel="stylesheet"
        />
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
            <a href="home.php">Home</a>
            <a href="index.php">Products</a>
            <a href="about.php">About Us</a>
            <a href="contact.php">Contact Us</a>
            <a href="login.php" class="login-link">Login</a>
            <a href="register.php" class="register-link">Register</a>
            <a href="profile.php" class="profile-link" style="display: none;">Profile</a>
        </nav>

        <div id="cart-icon">
            <i class="ri-shopping-bag-line"></i>
            <span class="cart-item-count"></span>
        </div>
        </header>
        <div class="hero-image" style="border-bottom:3px solid rgb(255, 40, 40);">
        </div>
        <h1 style="text-align:center; font-weight:600px; padding-top:35px;"> About Us  </h1>
        <section class="container">
            <p style="margin-right:260px; padding:25px; padding-left:15%;"> Welcome to CYCRIDE, your ultimate destination for premium bicycles and exceptional service.
At CYCRIDE, we’re passionate about cycling and committed to delivering the highest quality products and support to riders of all levels. We specialize in premium bike brands known for their performance, innovation, and reliability—whether you're a casual rider, a competitive racer, or a trail enthusiast.

But we don't just sell bikes—we build lasting relationships. Our experienced team is dedicated to providing expert advice, personalized fittings, and top-notch maintenance services to ensure every ride is smooth, safe, and unforgettable.

Ride with confidence. Ride with CYCRIDE.</p>
        </section>
        <div class="cart">
            <h2 class="cart-title">Your Cart</h2>
            <div class="cart-content">
            </div>
            <div class="total">
                <div class="total-title">Total</div>
                <div class="total-price">0</div>
            </div>
            <i class="ri-close-line" id="cart-close"></i>
        </div>
        <script src="script.js"></script>
        <script>
          document.addEventListener('DOMContentLoaded', () => {
            const hamburger = document.getElementById('hamburger');
            const nav = document.getElementById('navbar');

            hamburger.addEventListener('click', () => {
                nav.classList.toggle('active');
            });
        });
        </script>
    </body>

</html>
