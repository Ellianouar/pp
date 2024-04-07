<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $servername = $_POST['servername'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $dbname = $_POST['dbname'];

    // Create connection
    $conn = new mysqli($servername, $username, $password);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create the database if it doesn't exist
    $query = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($query) === TRUE) {
        echo "Database '$dbname' created successfully.<br>";
    } else {
        echo "Error creating database: " . $conn->error;
    }

    // Select the database
    $conn->select_db($dbname);

    // SQL queries to create tables
    $tables = array(
        'product' => "CREATE TABLE IF NOT EXISTS product (
                        product_id INT AUTO_INCREMENT PRIMARY KEY,
                        name VARCHAR(255) NOT NULL,
                        photolink VARCHAR(255),
                        price DECIMAL(10, 2),
                        ram VARCHAR(50),
                        rom VARCHAR(50),
                        description TEXT
                    )",
        'client' => "CREATE TABLE IF NOT EXISTS client (
                        client_id INT AUTO_INCREMENT PRIMARY KEY,
                        username VARCHAR(255) NOT NULL,
                        password VARCHAR(255) NOT NULL,
                        fullname VARCHAR(255) NOT NULL,
                        address VARCHAR(255),
                        city VARCHAR(100),
                        sold DECIMAL(10, 2)
                    )",
        'order' => "CREATE TABLE IF NOT EXISTS orders (
                        order_id INT AUTO_INCREMENT PRIMARY KEY,
                        client_id INT,
                        date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (client_id) REFERENCES client(client_id)
                    )",
        'ordersproducts' => "CREATE TABLE IF NOT EXISTS ordersproducts (
                                id INT AUTO_INCREMENT PRIMARY KEY,
                                productid INT,
                                quantity INT,
                                FOREIGN KEY (productid) REFERENCES product(product_id)
                            )"
    );

    // Execute SQL queries to create tables
    foreach ($tables as $table => $sql) {
        if ($conn->query($sql) === TRUE) {
            echo "Table '$table' created successfully.<br>";
        } else {
            echo "Error creating table '$table': " . $conn->error;
        }
    }
 // Update config.php file
$configContent = '<?php' . PHP_EOL;
$configContent .= '$servername = "' . $servername . '";' . PHP_EOL;
$configContent .= '$username = "' . $username . '";' . PHP_EOL;
$configContent .= '$password = "' . $password . '";' . PHP_EOL;
$configContent .= '$dbname = "' . $dbname . '";' . PHP_EOL;
$configContent .= '$conn = new mysqli($servername, $username, $password, $dbname);' . PHP_EOL;
$configContent .= 'if ($conn->connect_error) {' . PHP_EOL;
$configContent .= '    die("Connection failed: " . $conn->connect_error);' . PHP_EOL;
$configContent .= '}' . PHP_EOL;
$configContent .= '?>';

    // Write updated content to config.php
    file_put_contents('config.php', $configContent);
  
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Database</title>
</head>
<body>
    <h2>Create Database</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="servername">Server Name:</label>
        <input type="text" id="servername" name="servername" required><br><br>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="dbname">Database Name:</label>
        <input type="text" id="dbname" name="dbname" required><br><br>
        <button type="submit">Create Database</button>
    </form>
</body>
</html>
