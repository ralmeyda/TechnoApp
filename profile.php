<!DOCTYPE html>
<html>
<head>
    <title>Profile - CYCRIDE</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
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
            <a href="profile.php" class="profile-link">Profile</a>
        </nav>

        <div id="cart-icon">
            <i class="ri-shopping-bag-line"></i>
            <span class="cart-item-count"></span>
        </div>
    </header>

    <div class="form-container">
        <h2>User Profile</h2>
        <div id="userProfile">
            <!-- User details will be displayed here -->
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const userProfileDiv = document.getElementById("userProfile");
            const userData = JSON.parse(localStorage.getItem("userData"));

            if (userData) {
                userProfileDiv.innerHTML = `
                    <p><strong>First Name:</strong> ${userData.firstName}</p>
                    <p><strong>Last Name:</strong> ${userData.lastName}</p>
                    <p><strong>Phone Number:</strong> ${userData.phone}</p>
                    <p><strong>Email Address:</strong> ${userData.email}</p>
                    <p><strong>Username:</strong> ${userData.username}</p>
                `;
            } else {
                userProfileDiv.innerHTML = "<p>No user data found. Please <a href='register.html'>register</a>.</p>";
            }

            const hamburger = document.getElementById('hamburger');
            const nav = document.getElementById('navbar');
            hamburger.addEventListener('click', () => {
                nav.classList.toggle('active');
            });
        });
    </script>
</body>
</html>