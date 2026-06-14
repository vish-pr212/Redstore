<?php
session_start();
include("db.php");
// check user login or not
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$cart = [];

// Fetch cart items
$res = mysqli_query($conn, "SELECT * FROM cart_items WHERE user_id = $user_id");
while ($row = mysqli_fetch_assoc($res)) {
    $cart[] = $row;
}

// Handle quantity update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $product_id = $_POST['product_id'];
    $quantity = max(1, (int)$_POST['quantity']);
    mysqli_query($conn, "UPDATE cart_items SET quantity = $quantity WHERE user_id = $user_id AND product_id = $product_id");
    header("Location: cart.php");
    exit;
}

// Handle item removal
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM cart_items WHERE user_id = $user_id AND product_id = $product_id");
    header("Location: cart.php");
    exit;
}

// Calculate total
$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart - RedStore</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
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
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <a href="cart.php"><img src="images/cart.png" width="30px" height="30px"></a>
        <img src="images/menu.png" class="menu-icon" onclick="menutoggle()">
    </div>
</div>

<!-- Cart Items -->
<div class="small-container cart-page">
    <h2>Your Shopping Cart</h2>

    <table>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>

        <?php if (count($cart) == 0): ?>
            <tr>
                <td colspan="3"><p style="padding: 20px;">Your cart is empty.</p></td>
            </tr>
        <?php endif; ?>

        <?php foreach ($cart as $item): 
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        ?>
        <tr>
            <td>
                <div class="cart-info">
                    <img src="<?= $item['image'] ?>">
                    <div>
                        <p><?= $item['product_name'] ?></p>
                        <small>Price: $<?= number_format($item['price'], 2) ?></small><br>
                        <a href="cart.php?remove=<?= $item['product_id'] ?>" onclick="return confirm('Remove this item?')">Remove</a>
                    </div>
                </div>
            </td>
            <td>
                <form action="cart.php" method="POST" style="display: flex;">
                    <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1">
                    <button type="submit" name="update" class="btn" style="margin-left: 10px; padding: 5px 15px; font-size: 13px;">Update</button>
                </form>
            </td>
            <td>$<?= number_format($subtotal, 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <?php if (count($cart) > 0): ?>
    <div class="total-price">
        <table>
            <tr>
                <td>Total</td>
                <td>$<?= number_format($total, 2) ?></td>
            </tr>
        </table>
    </div>
    <a href="product.php" class="checkbtn">Product</a>
    <a href="checkout.php" class="checkbtn">Checkout</a>
    <?php endif; ?>
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
