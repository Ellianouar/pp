<?php
$servername = "localhost";
$username = "admin";
$password = ")Q4Jv-!MCl19yp!G";
$dbname = "ezfzegzrgre";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>