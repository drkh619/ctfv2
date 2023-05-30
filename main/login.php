<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  // Redirect the logged-in user to the index.php page
  header("Location: index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Read user input from the login form
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Load user data from the JSON file
  $usersFile = '../data/users.json';
  $usersData = file_get_contents($usersFile);
  $users = json_decode($usersData, true);

  // Check if the provided username exists in the user data
  if (isset($users[$username])) {
    $storedPassword = $users[$username]['password'];

    // Check if the provided password matches the stored password
    if (password_verify($password, $storedPassword)) {
      // Authentication successful

      // Set the session variable to track the logged-in state
      $_SESSION['loggedin'] = true;

      // Redirect the user to the index.php page
      header("Location: index.php");
      exit();
    }
  }

  // Authentication failed, show error message
  $errorMessage = "Invalid username or password.";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login - MyShop</title>
  <link rel="stylesheet" type="text/css" href="../mat/style.css">
</head>
<body>
  <header>
    <div class="logo">
      <a href="index.php"><h1>MyShop</h1></a>
    </div>
    <nav>
      <ul>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
      </ul>
    </nav>
  </header>

  <section class="login-section">
    <div class="login-container">
      <h2>Login to Your Account</h2>
      <?php if (isset($errorMessage)) { ?>
        <p class="error"><?php echo $errorMessage; ?></p>
      <?php } ?>
      <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <input type="submit" value="Login">
      </form>
    </div>
  </section>
</body>
</html>
