<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Check if the user is logged in
  if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect the user to the login page or display an error message
    header('Location: login.php');
    exit;
  }

  // Check if the cart ID is provided
  if (!isset($_POST['cart_id'])) {
    // Redirect the user back to the product page or display an error message
    header('Location: index.php');
    exit;
  }

  // Get the cart ID from the POST data
  $cartID = $_POST['cart_id'];

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

  // Get the product ID from the POST data
  if (!isset($_POST['product_id'])) {
    // Redirect the user back to the product page or display an error message
    header('Location: index.php');
    exit;
  }
  $productID = $_POST['product_id'];

  // Update the cart with the new product
  $carts[$cartID][$productID] = 1; // You can set the quantity as needed

  // Save the updated cart data to the JSON file
  $cartData = json_encode($carts, JSON_PRETTY_PRINT);
  file_put_contents($cartFile, $cartData);

  // Redirect the user to the cart page
  header('Location: cart.php?id=' . $cartID);
  exit;
}
?>
