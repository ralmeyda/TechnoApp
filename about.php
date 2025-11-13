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
            <i class="ri-shopping-bag-line"></i>
            <span class="cart-item-count"></span>
        </div>
        </header>
        <div class="hero-image" style="border-bottom:3px solid rgb(255, 40, 40);">
        </div>
        <h1 style="text-align:center; font-weight:600px; padding-top:35px;"> About Us  </h1>
        <section class="container">
            <p style="margin-right:260px; padding:25px; padding-left:15%;"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean vitae gravida arcu, id maximus enim. Nulla facilisi. Duis finibus in quam sit amet dapibus. Maecenas consequat luctus risus in commodo. Vivamus ac magna a erat tempor elementum vitae eu magna. Nulla hendrerit, enim et ullamcorper volutpat, lacus nunc dignissim urna, quis congue ipsum augue non risus. Ut sit amet velit ac tortor euismod aliquet.

Aliquam volutpat lacinia ex, sit amet suscipit velit elementum eget. Phasellus congue lacinia sem, sit amet bibendum nunc iaculis quis. Sed ut orci accumsan, consectetur erat et, blandit nisl. Maecenas pretium lectus nec nulla commodo pretium. Phasellus tincidunt sed nunc quis facilisis. Donec posuere pretium molestie. Interdum et malesuada fames ac ante ipsum primis in faucibus. Praesent pharetra malesuada turpis eu tristique. Suspendisse ultricies vitae magna vel tempor. Praesent condimentum elit id neque ultrices fermentum. Cras sodales ligula id neque finibus ullamcorper.</p>
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
