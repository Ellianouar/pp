<?php
session_start();


include_once "config.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $name = $_POST['name'];
    $price = $_POST['price'];
    $ram = $_POST['ram'] ?? null; 
    $rom = $_POST['rom'] ?? null; 
    $description = $_POST['description'];

    
    $targetDir = "../image/";
    $targetFile = $targetDir . basename($_FILES["photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

  
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $_SESSION['error'] = "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        $_SESSION['error'] = "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["photo"]["size"] > 500000) {
        $_SESSION['error'] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $_SESSION['error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $_SESSION['error'] = "Sorry, your file was not uploaded.";

    } else {
        // Attempt to move uploaded file
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
            // File uploaded successfully, insert product data into database
            $photolink = $targetFile; // Save the file path in the database

            // Insert product into database
            $sql = "INSERT INTO product (name, photolink, price, ram, rom, description) 
                    VALUES ('$name', '$photolink', '$price', '$ram', '$rom', '$description')";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['success'] = "New product added successfully.";
            } else {
                $_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            $_SESSION['error'] = "Sorry, there was an error uploading your file.";
        }
    }

    // Debugging messages
    var_dump($name, $price, $ram, $rom, $description, $targetFile, $uploadOk);

    // Redirect back to admin.php
    header("Location: admin.php");
    exit;
} else {
    // If form is not submitted, redirect back to admin.php
    header("Location: admin.php");
    exit;
}
?>
