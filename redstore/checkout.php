<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$cart_res = mysqli_query($conn, "SELECT * FROM cart_items WHERE user_id = $user_id");
$cart = [];
$total = 0;
while ($row = mysqli_fetch_assoc($cart_res)) {
    $cart[] = $row;
    $total += $row['price'] * $row['quantity'];
}

$error = "";
$success = "";
$order_completed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    $delivery_address = mysqli_real_escape_string($conn, $_POST['delivery_address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $postal_code = mysqli_real_escape_string($conn, $_POST['postal_code']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);

    if (empty($cart)) {
        $error = "Your cart is empty.";
    } else {
        mysqli_begin_transaction($conn);

        try {
            $order_sql = "INSERT INTO orders (user_id, total_amount, delivery_address, city, state, postal_code, country, contact_number)
                VALUES ($user_id, $total, '$delivery_address', '$city', '$state', '$postal_code', '$country', '$contact_number')";
            mysqli_query($conn, $order_sql);
            $order_id = mysqli_insert_id($conn);

            foreach ($cart as $item) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];

                $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price)
                    VALUES ($order_id, $product_id, $quantity, $price)";
                mysqli_query($conn, $item_sql);
            }

            mysqli_query($conn, "DELETE FROM cart_items WHERE user_id = $user_id");

            mysqli_commit($conn);

            $success = "Order placed successfully! Redirecting to your cart...";
            $order_completed = true;
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = "Failed to place order: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - RedStore</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
      /* Checkout specific overrides */
      .checkout-container {
          max-width: 700px;
          margin: 40px auto 120px;
          background: #fff;
          padding: 40px 50px;
          border-radius: 10px;
          box-shadow: 0 0 25px rgba(0,0,0,0.1);
      }
      h2 {
          text-align: center;
          margin-bottom: 30px;
          font-weight: 600;
          color: #555;
      }
      .form-group {
          margin-bottom: 20px;
      }
      label {
          display: block;
          margin-bottom: 6px;
          font-weight: 500;
          color: #555;
      }
      input[type=text],
      input[type=tel] {
          width: 100%;
          padding: 12px 15px;
          border: 1px solid #ddd;
          border-radius: 6px;
          font-size: 15px;
          transition: 0.3s;
      }
      input[type=text]:focus,
      input[type=tel]:focus {
          border-color: #ff523b;
          outline: none;
      }
      .card-details {
          display: flex;
          gap: 15px;
          align-items: flex-end;
          flex-wrap: wrap;
      }
      .card-details .form-group {
          flex: 1 1 40%;
          min-width: 180px;
          margin-bottom: 0;
      }
      .card-details .cvv-group {
          flex: 1 1 20%;
          min-width: 100px;
      }
      #verifyCardBtn {
          flex: 1 1 150px;
          padding: 12px 15px;
          background-color: #007bff;
          color: white;
          border: none;
          border-radius: 30px;
          font-size: 16px;
          cursor: pointer;
          transition: background-color 0.3s ease;
          margin-left: 10px;
          height: 44px;
          align-self: center;
          white-space: nowrap;
      }
      #verifyCardBtn:hover:not(:disabled) {
          background-color: #0056b3;
      }
      #verifyCardBtn:disabled {
          background-color: #999;
          cursor: not-allowed;
      }
      #placeOrderBtn {
          width: 100%;
          padding: 14px;
          background-color: #ff523b;
          color: white;
          border: none;
          border-radius: 30px;
          font-size: 18px;
          cursor: pointer;
          transition: background-color 0.3s ease;
          margin-top: 30px;
      }
      #placeOrderBtn:hover:not(:disabled) {
          background-color: #563434;
      }
      #placeOrderBtn:disabled {
          opacity: 0.6;
          cursor: not-allowed;
      }
      .message {
          text-align: center;
          margin: 20px 0;
          font-weight: 600;
          font-size: 16px;
      }
      .error {
          color: #d9534f;
      }
      .success {
          color: #28a745;
      }
      @media (max-width: 600px) {
          .card-details {
              flex-direction: column;
              gap: 10px;
          }
          #verifyCardBtn {
              margin-left: 0;
              width: 100%;
          }
          .card-details .form-group,
          .card-details .cvv-group {
              min-width: 100%;
          }
      }
    </style>
</head>
<body>
<!-- HEADER + NAVBAR from your style.css -->
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
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
            <a href="cart.php"><img src="images/cart.png" width="30px" height="30px"></a>
            <img src="images/menu.png" class="menu-icon" onclick="menutoggle()">
        </div>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="small-container checkout-container">
    <h2>Shipping & Payment Details</h2>

    <?php if ($error): ?>
        <p class="message error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p class="message success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <?php if ($order_completed): ?>
        <script>
            setTimeout(() => {
                window.location.href = 'cart.php';
            }, 3000);
        </script>
    <?php else: ?>
    <form id="checkoutForm" method="POST" action="">
        <!-- Shipping address -->
        <div class="form-group">
            <label for="delivery_address">Address Line 1</label>
            <input type="text" name="delivery_address" id="delivery_address" required>
        </div>

        <div class="form-group">
            <label for="city">City / Town</label>
            <input type="text" name="city" id="city" required>
        </div>

        <div class="form-group">
            <label for="state">State / Province / Region</label>
            <input type="text" name="state" id="state" required>
        </div>

        <div class="form-group">
            <label for="postal_code">Postal / ZIP Code</label>
            <input type="text" name="postal_code" id="postal_code" required>
        </div>

        <div class="form-group">
            <label for="country">Country</label>
            <input type="text" name="country" id="country" required>
        </div>

        <div class="form-group">
            <label for="contact_number">Contact Number</label>
            <input type="tel" name="contact_number" id="contact_number" required>
        </div>

        <!-- Payment card details -->
        <h3 style="margin-top:40px; font-weight:600; color:#555;">Payment Information</h3>

        <div class="card-details">
            <div class="form-group">
                <label for="card_number">Card Number</label>
                <input type="text" id="card_number" maxlength="19" placeholder="1234 5678 9012 3456" required pattern="\d{13,19}">
            </div>

            <div class="form-group">
                <label for="expiry_date">Expiry Date (MM/YY)</label>
                <input type="text" id="expiry_date" maxlength="5" placeholder="MM/YY" required pattern="(0[1-9]|1[0-2])\/\d{2}">
            </div>

            <div class="form-group cvv-group">
                <label for="cvv">CVV</label>
                <input type="password" id="cvv" maxlength="4" placeholder="123" required pattern="\d{3,4}">
            </div>

            <button type="button" id="verifyCardBtn">Verify Card</button>
        </div>

        <button type="submit" name="place_order" id="placeOrderBtn" disabled>Place Order and Pay</button>
    </form>

    <p id="verificationMessage" class="message"></p>

    <script>
        const verifyBtn = document.getElementById('verifyCardBtn');
        const placeOrderBtn = document.getElementById('placeOrderBtn');
        const verificationMessage = document.getElementById('verificationMessage');

        verifyBtn.addEventListener('click', () => {
            const cardNumberRaw = document.getElementById('card_number').value.trim();
            const cardNumber = cardNumberRaw.replace(/\s+/g, '');
            const expiryDate = document.getElementById('expiry_date').value.trim();
            const cvv = document.getElementById('cvv').value.trim();

            if (!cardNumber.match(/^\d{13,19}$/)) {
                verificationMessage.textContent = 'Invalid card number format.';
                verificationMessage.className = 'message error';
                return;
            }
            if (!expiryDate.match(/^(0[1-9]|1[0-2])\/\d{2}$/)) {
                verificationMessage.textContent = 'Invalid expiry date format.';
                verificationMessage.className = 'message error';
                return;
            }
            if (!cvv.match(/^\d{3,4}$/)) {
                verificationMessage.textContent = 'Invalid CVV.';
                verificationMessage.className = 'message error';
                return;
            }

            verificationMessage.textContent = 'Verifying card...';
            verificationMessage.className = 'message';

            setTimeout(() => {
                verificationMessage.textContent = 'Card verified successfully!';
                verificationMessage.className = 'message success';
                placeOrderBtn.disabled = false;
                verifyBtn.disabled = true;
                verifyBtn.style.cursor = 'not-allowed';
            }, 1500);
        });

        function menutoggle(){
            var MenuItems = document.getElementById("MenuItems");
            if(MenuItems.style.maxHeight == "0px" || MenuItems.style.maxHeight==""){
                MenuItems.style.maxHeight = "200px";
            } else {
                MenuItems.style.maxHeight = "0px";
            }
        }
    </script>
    <?php endif; ?>
</div>

</body>
</html>
