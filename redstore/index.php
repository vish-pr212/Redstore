<?php
session_start();
include("db.php");

// Fetch 4 product for featured section
$featured_products = mysqli_query($conn, "SELECT * FROM products ORDER BY id ASC LIMIT 4");
// Fetch 8 product for featured section
$latest_products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC LIMIT 8");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>RedStore</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
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
						<!-- -show user name and logout button if user log in -->
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

			<div class="row">
				<div class="col-2">
					<h1>Give Your Workout<br>A New Style!</h1>
					<p>Success isn't always about greatness. It's about consistency.<br>Consistent hard work gains success. Greatness will come.</p>
					<a href="product.php" class="btn">Explore Now &#8594;</a>
				</div>
				<div class="col-2">
					<img src="images/image1.png">
				</div>
			</div>
		</div>
	</div>

	<!-- featured categories static html -->
	<div class="categories">
		<div class="small-container">
			<div class="row">
				<div class="col-3"><img src="images/category-1.jpg"></div>
				<div class="col-3"><img src="images/category-2.jpg"></div>
				<div class="col-3"><img src="images/category-3.jpg"></div>
			</div>
		</div>
	</div>

	<!-- featured products -->
	<div class="small-container">
		<h2 class="title">Featured Products</h2>
		<div class="row">
			<?php while ($product = mysqli_fetch_assoc($featured_products)): ?>
				<div class="col-4">
					<img src="<?= $product['image'] ?>">
					<h4><?= $product['name'] ?></h4>
					<div class="rating">
						<?php for ($i = 1; $i <= 5; $i++): ?>
							<i class="fa <?= $i <= $product['rating'] ? 'fa-star' : 'fa-star-o' ?>"></i>
						<?php endfor; ?>
					</div>
					<p>$<?= number_format($product['price'], 2) ?></p>
					<a href="product-details.php?id=<?= $product['id'] ?>" class="buybtn">BUY</a>
				</div>
			<?php endwhile; ?>
		</div>

		<h2 class="title">Latest Products</h2>
		<div class="row">
			<?php while ($product = mysqli_fetch_assoc($latest_products)): ?>
				<div class="col-4">
					<img src="<?= $product['image'] ?>">
					<h4><?= $product['name'] ?></h4>
					<div class="rating">
						<?php for ($i = 1; $i <= 5; $i++): ?>
							<i class="fa <?= $i <= $product['rating'] ? 'fa-star' : 'fa-star-o' ?>"></i>
						<?php endfor; ?>
					</div>
					<p>$<?= number_format($product['price'], 2) ?></p>
					<a href="product-details.php?id=<?= $product['id'] ?>" class="buybtn">BUY</a>
				</div>
			<?php endwhile; ?>
		</div>
	</div>

	<!-- offer section -->
	<div class="offer">
		<div class="small-container">
			<div class="row">
				<div class="col-2">
					<img src="images/exclusive.png" class="offer-img">
				</div>
				<div class="col-2">
					<p>Exclusively Available on RedStore</p>
					<h1>Smart Band 4</h1>
					<small>The Mi Smart Band 4 features a 39.9% larger (than Mi Band 3) AMOLED color full-touch display with adjustable brightness.</small><br>
					<a href="product.php" class="btn">Explore Now &#8594;</a>
				</div>
			</div>
		</div>
	</div>

	<!-- footer -->
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

	<!-- JS for toggle menu -->
	<script>
		var MenuItems = document.getElementById("MenuItems");
		MenuItems.style.maxHeight = "0px";
		function menutoggle(){
			MenuItems.style.maxHeight = MenuItems.style.maxHeight === "0px" ? "200px" : "0px";
		}
	</script>
</body>
</html>