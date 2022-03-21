<?php
$servername = "localhost";
$username = "ordersapp";
$password = "uJcYMXi:VX7KwFb";

//PRICE PER PAPER
$a4blackwhite = 0.10;
$a4color = 0.50;

try {
  $conn = new PDO("mysql:host=$servername;dbname=print", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "System failed to connect to database: " . $e->getMessage();
  die();
}
?>