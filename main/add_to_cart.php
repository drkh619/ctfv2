<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  // Redirect the user to the login page or display an error message
  header('Location: login.php');
  exit;
}

// Check if the product ID and quantity are provided
if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
  // Redirect the user back to the product page or display an error message
  header('Location: index.php');
  exit;
}

// Get the product ID and quantity from the request
$productID = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Validate the input if needed

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

// Get the current cart for the user
$cart = $carts[$cartID];

// Check if the product is already in the cart
if (isset($cart[$productID])) {
  // Update the quantity if the product already exists
  $cart[$productID]['quantity'] += $quantity;
} else {
  // Add the product to the cart with the specified quantity
  $cart[$productID] = array(
    'quantity' => $quantity
  );
}

// Update the cart data in the JSON file
$carts[$cartID] = $cart;
$updatedCartData = json_encode($carts, JSON_PRETTY_PRINT);
file_put_contents($cartFile, $updatedCartData);

// Redirect the user to the cart page or display a success message
header('Location: cart.php?id=' . $cartID);
exit;
?>
