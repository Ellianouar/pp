<?php
session_start();


include_once "admin/config.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $sold = 0;


    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: register.php");
        exit;
    }


    $stmt = $conn->prepare("SELECT * FROM client WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Username already exists.";
        header("Location: register.php");
        exit;
    }


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $sql = "INSERT INTO client (username, password, fullname, address, city, sold) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $username, $hashed_password, $fullname, $address, $city, $sold);
    if ($stmt->execute()) {
        $_SESSION['success'] = "User registered successfully.";
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
        header("Location: register.php");
        exit;
    }
}
?>
