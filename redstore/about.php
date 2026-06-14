<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - RedStore</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="navbar">
                <div class="logo">
                    <img src="images/logo.png" width="125px">
                </div>
                <nav>
                    <ul id="MenuItems">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="product.php">Product</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="orders.php">My Orders</a></li>
                        <li>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['user_name']) ?>)</a>
                            <?php else: ?>
                                <a href="login.php">Login</a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </nav>
                <a href="cart.php"><img src="images/cart.png" width="30px" height="30px"></a>
                <img src="images/menu.png" class="menu-icon" onclick="menutoggle()">
            </div>
        </div>
    </div>

    <div class="small-container" style="margin-top: 50px; margin-bottom: 100px;">
        <h2 class="title">About RedStore</h2>
        <div class="row">
            <!-- if wa want image we can add here 
            <div class="col-2">
                <img src="images/exclusive.png" alt="About Image" style="max-width: 100%;">
            </div> -->
            <div class="col-2">
                <p style="font-size: 16px; line-height: 28px; color: #555;">
                    Welcome to RedStore!<br><br>
                    RedStore is your go-to online destination for stylish and affordable clothing and accessories. Founded with the vision of bringing fashion to everyone, we offer a wide range of quality products designed to suit your lifestyle. Whether you're hitting the gym, heading to class, or chilling on the weekend, we’ve got the gear to keep you looking fresh.
                    <br><br>
                    Our mission is to make shopping easy, fun, and hassle-free. That’s why we continuously update our collections and ensure a smooth checkout experience. We’re committed to providing excellent customer service and building a community of fashion lovers.
                    <br><br>
                    Thank you for choosing RedStore. Let’s shop smart, live bold, and dress better—together!
                </p>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="footer-col-1">
                    <h3>Download Our App</h3>
                    <p>Download App for Android and iOS mobile phone.</p>
                    <div class="app-logo">
                        <img src="images/play-store.png">
                        <img src="images/app-store.png">
                    </div>
                </div>
                <div class="footer-col-2">
                    <img src="images/logo-white.png">
                    <p>Our Purpose Is To Sustainably Make The Pleasure and Benefits of Sports Accessible to the Many.</p>
                </div>
                <div class="footer-col-3">
                    <h3>Useful Links</h3>
                    <ul>
                        <li>Coupons</li>
                        <li>Blog Post</li>
                        <li>Return Policy</li>
                        <li>Join Affiliate</li>
                    </ul>
                </div>
                <div class="footer-col-4">
                    <h3>Follow Us</h3>
                    <ul>
                        <li>Facebook</li>
                        <li>Twitter</li>
                        <li>Instagram</li>
                        <li>YouTube</li>
                    </ul>
                </div>
            </div>
            <hr>
            <p class="copyright">Copyright 2025</p>
        </div>
    </div>

    <script>
        var MenuItems = document.getElementById("MenuItems");
        MenuItems.style.maxHeight = "0px";
        function menutoggle(){
            MenuItems.style.maxHeight = MenuItems.style.maxHeight === "0px" ? "200px" : "0px";
        }
    </script>
</body>
</html>