<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  // Redirect the user to the login page or display an error message
  header('Location: login.php');
  exit;
}

// Check if the cart ID is provided
if (!isset($_GET['id'])) {
  // Redirect the user back to the product page or display an error message
  header('Location: index.php');
  exit;
}

// Get the cart ID from the URL
$cartID = $_GET['id'];

// Load the cart data from the JSON file
$cartFile = '../data/cart.json';
$cartData = file_get_contents($cartFile);
$carts = json_decode($cartData, true);

// Check if the cart ID exists in the JSON data
if (!isset($carts[$cartID])) {
  // Redirect the user back to the product page or display an error message
  header('Location: index.php');
  exit;
}

// Get the cart for the user
$cart = $carts[$cartID];

// Load the product data from the JSON file
$productsFile = '../data/prod.json';
$productsData = file_get_contents($productsFile);
$products = json_decode($productsData, true);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Your Cart</title>
  <link rel="stylesheet" type="text/css" href="../mat/style.css">
</head>
<body>
  <h1>Your Cart</h1>
  <table>
    <tr>
      <th>Product</th>
      <th>Quantity</th>
      <th>Price</th>
      <th>Total</th>
    </tr>
    <?php foreach ($cart as $productID => $item) { ?>
      <?php if (isset($products[$productID])) { ?>
        <?php $product = $products[$productID]; ?>
        <tr>
          <td><?php echo $product['name']; ?></td>
          <td><?php echo $item['quantity']; ?></td>
          <td>$<?php echo $product['price']; ?></td>
          <td>$<?php echo $product['price'] * $item['quantity']; ?></td>
        </tr>
      <?php } ?>
    <?php } ?>
  </table>
  <!-- Add any additional elements or functionalities here -->
</body>
</html>
