<?php
session_start();
// check user login or not
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("db.php");
$result = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>All Product - RedStore</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
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

	<div class="small-container">
		<div class="row row-2">
			<h2>All Products</h2>
			<select>
				<option>Default Sorting</option>
				<option>Sort by Price</option>
				<option>Sort by Popularity</option>
				<option>Sort by Rating</option>
				<option>Sort by Sale</option>
			</select>
		</div>

		<div class="row">
			<?php while ($row = mysqli_fetch_assoc($result)): ?>
			<div class="col-4">
				<img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
				<h4><?= htmlspecialchars($row['name']) ?></h4>
				<div class="rating">
					<?php for ($i = 0; $i < $row['rating']; $i++): ?>
						<i class="fa fa-star"></i>
					<?php endfor; ?>
					<?php for ($i = $row['rating']; $i < 5; $i++): ?>
						<i class="fa fa-star-o"></i>
					<?php endfor; ?>
				</div>
				<p>$<?= number_format($row['price'], 2) ?></p>
				<a href="product-details.php?id=<?= $row['id'] ?>" class="buybtn">BUY</a>
			</div>
			<?php endwhile; ?>
		</div>

		<div class="page-btn">
			<span>1</span>
			<span>2</span>
			<span>3</span>
			<span>4</span>
			<span>&#8594;</span>
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

	<script>
		var MenuItems = document.getElementById("MenuItems");
		MenuItems.style.maxHeight = "0px";
		function menutoggle(){
			MenuItems.style.maxHeight = MenuItems.style.maxHeight == "0px" ? "200px" : "0px";
		}
	</script>
</body>
</html>
