<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  $loggedIn = true;
} else {
  $loggedIn = false;
}

// Check if the id parameter is set
if (isset($_GET['id'])) {
  $cartId = $_GET['id'];
} else {
  // Redirect the user to the index page if the id parameter is missing
  header('Location: index.php');
  exit;
}

$cartFile = '../data/cart.json';

// Read the cart data from the JSON file
$cartData = file_get_contents($cartFile);
$cartData = json_decode($cartData, true);

// Check if the cart ID exists in the cart data
if (!array_key_exists($cartId, $cartData)) {
  // Redirect the user to the index page if the cart ID does not exist
  header('Location: index.php');
  exit;
}

// Get the cart items for the specific cart ID
$cartItems = $cartData[$cartId];

// Calculate the total quantity and price of the cart items
$totalQuantity = 0;
$totalPrice = 0;

foreach ($cartItems as $productId => $item) {
  $productFile = '../data/prod.json';

  // Read the product data from the JSON file
  $productData = file_get_contents($productFile);
  $productData = json_decode($productData, true);

  // Check if the product ID exists in the product data
  if (array_key_exists($productId, $productData)) {
    $product = $productData[$productId];
    $itemQuantity = $item['quantity'];
    $itemPrice = $product['price'] * $itemQuantity;

    $totalQuantity += $itemQuantity;
    $totalPrice += $itemPrice;
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>MyShop - Cart</title>
  <link rel="stylesheet" type="text/css" href="../mat/style.css">
</head>
<body>
  <header>
    <div class="logo">
      <h1><a href="index.php">MyShop</a></h1>
    </div>
    <nav>
      <ul>
        <?php if ($loggedIn) { ?>
          <li><a href="cart.php?id=<?php echo $_GET['id']; ?>">Cart</a></li>
          <li><a href="logout.php">Logout</a></li>
        <?php } else { ?>
          <li><a href="login.php">Login</a></li>
          <li><a href="register.php">Register</a></li>
        <?php } ?>
      </ul>
    </nav>
  </header>
  <section class="cart">
    <h2>Cart</h2>
    <?php if ($totalQuantity > 0) { ?>
      <div class="cart-items">
        <?php foreach ($cartItems as $productId => $item) {
          // Read the product data from the JSON file
          $productData = file_get_contents($productFile);
          $productData = json_decode($productData, true);

          // Check if the product ID exists in the product data
          if (array_key_exists($productId, $productData)) {
            $product = $productData[$productId];
            $itemName = $product['name'];
            $itemQuantity = $item['quantity'];
            $itemPrice = $product['price'] * $itemQuantity;
        ?>
          <div class="cart-item">
            <span class="item-name"><?php echo $itemName; ?></span>
            <span class="item-quantity">Quantity: <?php echo $itemQuantity; ?></span>
            <span class="item-price">Price: $<?php echo $itemPrice; ?></span>
          </div>
        <?php
          }
        }
        ?>
      </div>
      <div class="cart-summary">
        <span class="total-quantity">Total Quantity: <?php echo $totalQuantity; ?></span>
        <span class="total-price">Total Price: $<?php echo $totalPrice; ?></span>
      </div>
    <?php } else { ?>
      <p>Your cart is empty.</p>
    <?php } ?>
  </section>
</body>
</html>
