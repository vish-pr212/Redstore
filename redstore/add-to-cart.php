<?php
session_start();
include("db.php");
// check user login or not
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $price = $_POST['price'];
    $image = mysqli_real_escape_string($conn, $_POST['image']);
    $quantity = $_POST['quantity'];

    // Check if item already exists
    $check = mysqli_query($conn, "SELECT * FROM cart_items WHERE user_id = $user_id AND product_id = $product_id");
    if (mysqli_num_rows($check) > 0) {
        // Update quantity
        mysqli_query($conn, "UPDATE cart_items SET quantity = quantity + $quantity WHERE user_id = $user_id AND product_id = $product_id");
    } else {
        // Insert new item
        mysqli_query($conn, "INSERT INTO cart_items (user_id, product_id, product_name, price, image, quantity) VALUES ($user_id, $product_id, '$product_name', $price, '$image', $quantity)");
    }

    header("Location: cart.php");
    exit;
}
?>
