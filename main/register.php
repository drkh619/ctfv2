<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  // Redirect the logged-in user to the index.php page
  header("Location: index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Read user input from the registration form
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Load existing user data from the JSON file
  $usersFile = '../data/users.json';
  $usersData = file_get_contents($usersFile);
  $users = json_decode($usersData, true);

  // Check if the username already exists
  if (isset($users[$username])) {
    $errorMessage = "Username already exists. Please choose a different username.";
  } else {
    // Hash the password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Add the new user to the user data
    $users[$username] = [
      'email' => $email,
      'password' => $hashedPassword
    ];

    // Save the updated user data to the JSON file
    file_put_contents($usersFile, json_encode($users));

    // Redirect the user to the login page
    header("Location: login.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>MyShop - Register</title>
  <link rel="stylesheet" type="text/css" href="../mat/style.css">
</head>
<body>
  <header>
    <div class="logo">
    <a href="./index.php"><h1>MyShop</h1>
    </div>
    <nav>
      <ul>
        <li><a href="./login.php">Login</a></li>
        <li><a href="#">Register</a></li>
      </ul>
    </nav>
  </header>
  <section class="register-section">
    <div class="register-container">
      <h2>Create an Account</h2>
      <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        
        <input type="submit" value="Register">
      </form>
      <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
      <?php endif; ?>
    </div>
  </section>
</body>
</html>
