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
        <a href="home.php" class="logo">Thoto & Nene Fresh Live Tilapia and Bangus</a>
        
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
        </div>
        </header>
        <div class="hero-image" style="border-bottom:3px solid rgb(255, 40, 40);">
        </div>
        <h1 style="text-align:center; font-weight:600px; padding-top:35px;"> Products that we offer </h1>
        <section class="brand-logos">
            <h2>Tilapia </h2>
            <h2> Bangus</h2>
            <h2> and many more...</h2>
        </section>
        <div class="buy-now-wrapper">
        <a href="index.php" class="buy-now-btn">Buy Now</a>
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
