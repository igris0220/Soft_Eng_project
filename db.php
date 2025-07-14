
<?php

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "ecommerce_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// $conn = new mysqli('localhost', 'root', 'root', 'finquest');

if ($conn->connect_error){
    die ("connection failed:" .$conn->connect_error);
}
// echo "connected successfully!";
?>
