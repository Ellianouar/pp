<?php
session_start();

// Include database connection
include_once "admin/config.php";

// Check if user is logged in
$logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Set username if logged in
$username = $logged_in ? $_SESSION['username'] : "";

// Initialize sold variable
$sold = "";

// Fetch sold amount from the database if user is logged in
if ($logged_in) {
    $stmt = $conn->prepare("SELECT sold FROM client WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $sold = $row['sold'];
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecom pfe</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>Ecom pfe</h1>
    <?php if ($logged_in): ?>
        <div class="user-info">
            <span>Welcome, <?php echo $_SESSION['username']; ?></span>
            <span>Sold: <?php echo $sold; ?></span>
            <form method="post" action="login_user.php">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
    <?php endif; ?>
</header>

<nav>
    <a href="#">Home</a>
    <a href="#">Category</a>
    <a href="#">About</a>
    <a href="#">Contact</a>
    <?php if (!$logged_in): ?>
        <a href="login_user.php">Login</a>
        <a href="register.php">Register</a>
    <?php endif; ?>
</nav>

<section id="products">
    <!-- Product section content goes here -->
</section>

<footer>
    &copy; 2024 Ecom pfe. All rights reserved.
</footer>
</body>
</html>
