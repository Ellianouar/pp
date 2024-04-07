<?php
session_start();


if (isset($_POST['logout'])) {

    $_SESSION = array();


    session_destroy();

 
    header("Location: index.php");
    exit;
}


include_once "./admin/config.php";


if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

  
    $stmt = $conn->prepare("SELECT * FROM client WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {

        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
  
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['sold'] = $row['sold']; 
      
            header('Location: index.php');
            exit;
        } else {
      
            $error = "Invalid username or password.";
        }
    } else {

        $error = "Invalid username or password.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>
