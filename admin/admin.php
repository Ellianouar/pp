<?php
session_start();



if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}



$inactive = 1800; 
if (isset($_SESSION['timeout'])) {
    $session_life = time() - $_SESSION['timeout'];
    if ($session_life > $inactive) {
     
        session_destroy();
        header('Location: login.php');
        exit;
    }
}
$_SESSION['timeout'] = time();


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
  
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
        }

        header .user-info {
            text-align: right;
        }

        header a {
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid #fff;
            border-radius: 5px;
        }

        header a:hover {
            background-color: #fff;
            color: #333;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .container {
            padding: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Admin Page</h1>
        <div class="user-info">
            <span>Welcome, <?php echo $_SESSION['username']; ?>!</span>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
    </header>
	   <a href="./creatdb.php" > <button type="submit">Click hna pour creation basedonne</button></a>
    <div class="container">
        <div class="container">
        <h2>Add New Product</h2>
		        <?php
       
        if (isset($_SESSION['error'])) {
            echo '<p class="error">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="add_product.php" method="post" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required><br><br>
            <label for="price">Price:</label>
            <input type="text" id="price" name="price" required><br><br>
            <label for="ram">RAM:</label>
            <input type="text" id="ram" name="ram"><br><br>
            <label for="rom">ROM:</label>
            <input type="text" id="rom" name="rom"><br><br>
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"></textarea><br><br>
            <label for="photo">Product Photo:</label>
            <input type="file" id="photo" name="photo" accept="image/*"><br><br>
            <button type="submit">Add Product</button>
        </form>
    </div>
    </div>
    <footer>
        &copy; 2024 Ecom pfe. All rights reserved.
    </footer>
</body>
</html>
