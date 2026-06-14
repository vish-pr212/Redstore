<?php
include("db.php");

// Handle DELETE
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM order_items WHERE product_id = $delete_id");
    mysqli_query($conn, "DELETE FROM products WHERE id = $delete_id");
    header("Location: admin-products.php");
    exit;
}

// Handle EDIT
$edit_mode = false;
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_id = $_GET['edit'];
    $edit_product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id = $edit_id"));
}

// Handle FORM SUBMIT (Add or Update)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $rating = $_POST['rating'];
    $image = $_POST['image'];
    $desc = $_POST['description'];
    $g1 = $_POST['gallery1'];
    $g2 = $_POST['gallery2'];
    $g3 = $_POST['gallery3'];
    $g4 = $_POST['gallery4'];

    if (isset($_POST['product_id'])) {
        // Update
        $update_id = $_POST['product_id'];
        $query = "UPDATE products SET name='$name', price='$price', rating='$rating',
                  image='$image', description='$desc', gallery1='$g1', gallery2='$g2',
                  gallery3='$g3', gallery4='$g4' WHERE id=$update_id";
    } else {
        // Insert
        $query = "INSERT INTO products (name, price, rating, image, description, gallery1, gallery2, gallery3, gallery4)
                  VALUES ('$name', '$price', '$rating', '$image', '$desc', '$g1', '$g2', '$g3', '$g4')";
    }
    mysqli_query($conn, $query);
    header("Location: admin-products.php");
    exit;
}

// Get all products
$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - RedStore</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-header {
            background: radial-gradient(#fff, #ffd6d6);
            padding: 20px;
            text-align: center;
        }
        .admin-header h1 {
            color: #ff523b;
        }
        .admin-container {
            max-width: 1080px;
            margin: 20px auto;
        }
        .form-section, .table-section {
            background: #fff;
            border: 1px solid #ccc;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 40px;
        }
        .form-section h2 {
            margin-bottom: 20px;
            color: #ff523b;
        }
        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ff523b;
            border-radius: 6px;
        }
        textarea {
            resize: vertical;
            height: 80px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #ff523b;
            color: white;
        }
        table img {
            width: 50px;
            height: auto;
        }
        .action-btns a {
            padding: 5px 10px;
            border-radius: 6px;
            margin-right: 5px;
            font-size: 13px;
            text-decoration: none;
        }
        .edit-btn {
            background-color: #ffa600;
            color: #fff;
        }
        .delete-btn {
            background-color: #e60023;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="admin-header">
    <h1>RedStore Admin Panel</h1>
    <p>Manage your product listings here</p>
</div>

<div class="admin-container">
    <!-- Add / Edit Product Form -->
    <div class="form-section">
        <h2><?= $edit_mode ? 'Edit Product' : 'Add New Product' ?></h2>
        <form method="POST">
            <?php if ($edit_mode): ?>
                <input type="hidden" name="product_id" value="<?= $edit_product['id'] ?>">
            <?php endif; ?>
            <input type="text" name="name" placeholder="Product Name" value="<?= $edit_mode ? $edit_product['name'] : '' ?>" required>
            <input type="text" name="price" placeholder="Price" value="<?= $edit_mode ? $edit_product['price'] : '' ?>" required>
            <input type="number" name="rating" placeholder="Rating (0-5)" min="0" max="5" value="<?= $edit_mode ? $edit_product['rating'] : '' ?>" required>
            <input type="text" name="image" placeholder="Main Image Path" value="<?= $edit_mode ? $edit_product['image'] : '' ?>" required>
            <input type="text" name="gallery1" placeholder="Gallery Image 1" value="<?= $edit_mode ? $edit_product['gallery1'] : '' ?>">
            <input type="text" name="gallery2" placeholder="Gallery Image 2" value="<?= $edit_mode ? $edit_product['gallery2'] : '' ?>">
            <input type="text" name="gallery3" placeholder="Gallery Image 3" value="<?= $edit_mode ? $edit_product['gallery3'] : '' ?>">
            <input type="text" name="gallery4" placeholder="Gallery Image 4" value="<?= $edit_mode ? $edit_product['gallery4'] : '' ?>">
            <textarea name="description" placeholder="Description"><?= $edit_mode ? $edit_product['description'] : '' ?></textarea>
            <button type="submit" class="btn"><?= $edit_mode ? 'Update Product' : 'Add Product' ?></button>
        </form>
    </div>

    <!-- Product Table -->
    <div class="table-section">
        <h2>All Products</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Preview</th>
                <th>Name</th>
                <th>Price</th>
                <th>Rating</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><img src="<?= $row['image'] ?>" alt="img"></td>
                <td><?= $row['name'] ?></td>
                <td>$<?= number_format($row['price'], 2) ?></td>
                <td><?= $row['rating'] ?> ★</td>
                <td class="action-btns">
                    <a href="admin-products.php?edit=<?= $row['id'] ?>" class="edit-btn">Edit</a>
                    <a href="admin-products.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this product?')" class="delete-btn">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

</body>
</html>
