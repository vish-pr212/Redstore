<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch orders of this user
$orders_res = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Orders - RedStore</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .orders-section {
            background: radial-gradient(#fff, #ffd6d6);
            padding: 40px 0;
        }

        .orders-container {
            max-width: 1080px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }

        .orders-container h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .order {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }

        .order:last-child {
            border-bottom: none;
        }

        .order-header {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .order-items {
            margin-left: 20px;
        }

        .order-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .order-item img {
            width: 60px;
            margin-right: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .order-item-details {
            flex-grow: 1;
        }

        .order-item-details p {
            margin: 0;
            font-size: 14px;
        }

        .order-total {
            text-align: right;
            font-weight: bold;
            margin-top: 10px;
        }

        .no-orders {
            text-align: center;
            padding: 50px 0;
            color: #555;
        }
    </style>
</head>
<body>

<!-- Header with Navbar -->
<div class="header">
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <a href="index.php"><img src="images/logo.png" width="125px"></a>
            </div>
            <nav>
                <ul id="MenuItems">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="product.php">Product</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="orders.php">My Orders</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
            <a href="cart.php"><img src="images/cart.png" width="30px" height="30px"></a>
            <img src="images/menu.png" class="menu-icon" onclick="menutoggle()">
        </div>
    </div>
</div>

<!-- Orders Section -->
<div class="orders-section">
    <div class="orders-container">
        <h2>Your Past Orders</h2>

        <?php if (mysqli_num_rows($orders_res) == 0): ?>
            <p class="no-orders">You have no past orders.</p>
        <?php else: ?>
            <?php while ($order = mysqli_fetch_assoc($orders_res)): ?>
                <div class="order">
                    <div class="order-header">
                        Order #<?= $order['id'] ?> — <?= date("F j, Y, g:i a", strtotime($order['order_date'])) ?>
                    </div>
                    <div class="order-items">
                        <?php
                        $order_id = $order['id'];
                        $items_res = mysqli_query($conn, 
                            "SELECT oi.*, p.name, p.image 
                             FROM order_items oi
                             JOIN products p ON oi.product_id = p.id
                             WHERE oi.order_id = $order_id"
                        );
                        ?>
                        <?php while ($item = mysqli_fetch_assoc($items_res)): ?>
                            <div class="order-item">
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                <div class="order-item-details">
                                    <p><strong><?= htmlspecialchars($item['name']) ?></strong></p>
                                    <p>Quantity: <?= $item['quantity'] ?></p>
                                    <p>Price: $<?= number_format($item['price'], 2) ?></p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <div class="order-total">
                        Total: $<?= number_format($order['total_amount'], 2) ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
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

<!-- Menu Toggle Script -->
<script>
  var MenuItems = document.getElementById("MenuItems");
  MenuItems.style.maxHeight = "0px";
  function menutoggle(){
    MenuItems.style.maxHeight = MenuItems.style.maxHeight == "0px" ? "200px" : "0px";
  }
</script>

</body>
</html>
