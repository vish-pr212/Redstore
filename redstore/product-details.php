<?php
session_start();
// check user login or not
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("db.php");

if (!isset($_GET['id'])) {
    die("Product ID is missing.");
}
$id = (int)$_GET['id']; // cast to int for safety
$result = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("Product not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($product['name']) ?> - RedStore</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<div class="container">
  <div class="navbar">
    <div class="logo">
      <img src="images/logo.png" width="125px" alt="Logo">
    </div>
    <nav>
      <ul id="MenuItems">
        <li><a href="index.php">Home</a></li>
        <li><a href="product.php">Product</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="orders.php">My Orders</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['user_name']) ?>)</a></li>
        <?php else: ?>
          <li><a href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </nav>
    <a href="cart.php"><img src="images/cart.png" width="30px" height="30px" alt="Cart"></a>
    <img src="images/menu.png" class="menu-icon" onclick="menutoggle()">
  </div>
</div>

<!-- Single product details -->
<div class="small-container single-product">
  <div class="row">
    <div class="col-2">
      <img src="<?= htmlspecialchars($product['gallery1']) ?>" width="100%" id="ProductImg" alt="<?= htmlspecialchars($product['name']) ?>">
      <div class="small-img-row">
        <div class="small-img-col"><img src="<?= htmlspecialchars($product['gallery1']) ?>" width="100%" class="small-img" alt="Gallery 1"></div>
        <div class="small-img-col"><img src="<?= htmlspecialchars($product['gallery2']) ?>" width="100%" class="small-img" alt="Gallery 2"></div>
        <div class="small-img-col"><img src="<?= htmlspecialchars($product['gallery3']) ?>" width="100%" class="small-img" alt="Gallery 3"></div>
        <div class="small-img-col"><img src="<?= htmlspecialchars($product['gallery4']) ?>" width="100%" class="small-img" alt="Gallery 4"></div>
      </div>
    </div>
    <div class="col-2">
      
      <h1><?= htmlspecialchars($product['name']) ?></h1>
      <h4>$<?= number_format($product['price'], 2) ?></h4>
      <select>
        <option>Select Size</option>
        <option>XXL</option>
        <option>XL</option>
        <option>Large</option>
        <option>Medium</option>
        <option>Small</option>
      </select>

      <form action="add-to-cart.php" method="POST" style="margin-top: 20px;">
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" value="1" min="1" required>
        <!-- hide the other product related details for cart page -->
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
        <input type="hidden" name="price" value="<?= $product['price'] ?>">
        <input type="hidden" name="image" value="<?= htmlspecialchars($product['image']) ?>">

        <button type="submit" class="btn">Add To Cart</button>
      </form>

      <h3>Product Details <i class="fa fa-indent"></i></h3>
      <br>
      <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
    </div>
  </div>
</div>

<!-- Footer and related products -->
<div class="small-container">
  <div class="row row-2">
    <h2>Other Products</h2>
    <p>View More</p>
  </div>
</div>

<!-- Static related products -->
<div class="small-container">
  <div class="row">
    <div class="col-4">
      <img src="images/product-9.jpg" alt="Red Printed T-Shirt">
      <h4>Red Printed T-Shirt</h4>
      <div class="rating"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i></div>
      <p>$50.00</p>
      <a href="#" class="buybtn">BUY</a>
    </div>
    <!-- Add more related products here -->
  </div>
</div>

<!-- Footer -->
<div class="footer">
  <div class="container">
    <div class="row">
      <div class="footer-col-1">
        <h3>Download Our App</h3>
        <p>Download App for Android and ios mobile phone.</p>
        <div class="app-logo">
          <img src="images/play-store.png" alt="Play Store">
          <img src="images/app-store.png" alt="App Store">
        </div>
      </div>
      <div class="footer-col-2">
        <img src="images/logo-white.png" alt="Logo">
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

<!-- JS for menu toggle -->
<script>
  var MenuItems = document.getElementById("MenuItems");
  MenuItems.style.maxHeight = "0px";
  function menutoggle() {
    MenuItems.style.maxHeight = MenuItems.style.maxHeight === "0px" ? "200px" : "0px";
  }
</script>

<!-- JS for product gallery -->
<script>
  var ProductImg = document.getElementById("ProductImg");
  var SmallImg = document.getElementsByClassName("small-img");
  SmallImg[0].onclick = function() { ProductImg.src = SmallImg[0].src; }
  SmallImg[1].onclick = function() { ProductImg.src = SmallImg[1].src; }
  SmallImg[2].onclick = function() { ProductImg.src = SmallImg[2].src; }
  SmallImg[3].onclick = function() { ProductImg.src = SmallImg[3].src; }
</script>

</body>
</html>
