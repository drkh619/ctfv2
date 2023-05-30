<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  $loggedIn = true;
} else {
  $loggedIn = false;
}

$productsFile = '../data/prod.json';
$productsData = file_get_contents($productsFile);
$products = json_decode($productsData, true);

if (isset($_GET['image'])) {
  // Get the image filename from the URL
  $imageFileName = $_GET['image'];

  // Construct the image path
  $imagePath = '../img/' . $imageFileName;
  $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);

  if ($imageExtension === 'png') {
    header('Content-Type: image/png');
  } elseif ($imageExtension === 'jpg' || $imageExtension === 'jpeg') {
    header('Content-Type: image/jpeg');
  } elseif ($imageExtension === 'gif') {
    header('Content-Type: image/gif');
  }

  // Check if the image file exists
  if (file_exists($imagePath)) {
    // Output the image file
    readfile($imagePath);
    exit;
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>MyShop - Your Online Store</title>
  <link rel="stylesheet" type="text/css" href="../mat/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
    // JavaScript code for product popup functionality
    document.addEventListener("DOMContentLoaded", function() {
      var productItems = document.getElementsByClassName("product-item");
      var popup = document.getElementById("product-popup");
      var popupImage = document.getElementById("popup-image");
      var popupTitle = document.getElementById("popup-title");
      var popupDescription = document.getElementById("popup-description");
      var viewImageButton = document.getElementById("view-image-btn");

      for (var i = 0; i < productItems.length; i++) {
        productItems[i].addEventListener("click", function() {
          var imageSrc = this.getElementsByTagName("img")[0].src;
          var title = this.getElementsByTagName("h3")[0].innerText;
          var description = this.getElementsByClassName("product-description")[0].innerText;
          var fileName = imageSrc.substring(imageSrc.lastIndexOf("/") + 1);

          popupImage.src = imageSrc;
          popupTitle.innerText = title;
          popupDescription.innerText = description;
          viewImageButton.href = window.location.pathname + "?image=" + fileName;
          popup.style.display = "block";
        });
      }

      popup.addEventListener("click", function(event) {
        if (event.target === this || event.target.classList.contains("product-popup-close")) {
          popup.style.display = "none";
        }
      });

      // Add to Cart functionality
      $("#add-to-cart-btn").on("click", function(e) {
        e.preventDefault();
        var productId = $(this).data("product-id");
        var quantity = $("#quantity").val();
        addToCart(productId, quantity);
      });

      function addToCart(productId, quantity) {
        $.ajax({
          type: "POST",
          url: "add_to_cart.php?id=<?php echo $_GET['id']; ?>",
          data: { product_id: productId, quantity: quantity },
          success: function(response) {
            if (response === "success") {
              alert("Product added to cart successfully!");
              location.reload();
            } else {
              alert("Failed to add product to cart.");
            }
          },
          error: function() {
            alert("An error occurred while processing the request.");
          }
        });
      }
    });
  </script>
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
  <section class="hero">
    <div class="hero-content">
      <h2>Welcome to MyShop!</h2>
      <p>Discover a wide range of products at great prices.</p>
      <a href="#product" class="btn-shop-now">Shop Now</a>
    </div>
  </section>
  <section class="product-list">
    <h2>Product List</h2>
    <div class="product-container" id="product">
      <?php foreach ($products as $product) { ?>
        <div class="product-item">
          <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
          <h3><?php echo $product['name']; ?></h3>
          <div class="product-description hidden">
            <p><?php echo $product['description']; ?></p>
          </div>
          <span class="product-popup-price">Price: $<?php echo $product['price']; ?></span>
          <?php if ($loggedIn) { ?>
            <button class="add-to-cart-btn" data-product-id="<?php echo $product['id']; ?>">Add to Cart</button>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
  </section>
  <!-- Product Popup -->
  <div id="product-popup" class="product-popup">
    <span class="product-popup-close">&times;</span>
    <img id="popup-image" src="" alt="Product Image">
    <h3 id="popup-title"></h3>
    <p id="popup-description"></p>
    <a id="view-image-btn" href="#" target="_blank">View Image</a>
    <?php if ($loggedIn) { ?>
      <form action="cart.php" method="post">
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" value="1" min="1">
        <button id="add-to-cart-btn" data-product-id="<?php echo $product['id']; ?>">Add to Cart</button>
      </form>
    <?php } ?>
  </div>
</body>
</html>
