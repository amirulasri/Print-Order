<?php
include("../conn.php");
$name = "";
$manageruser = "";
$managerpass = "";
$email = "";

if (isset($_POST['name']) && isset($_POST['manageruser']) && isset($_POST['managerpass']) && isset($_POST['email'])) {
  if (!empty($_POST['name']) && !empty($_POST['manageruser']) && !empty($_POST['managerpass']) && !empty($_POST['email'])) {
    $name = $_POST['name'];
    $manageruser = $_POST['manageruser'];
    $managerpass = $_POST['managerpass'];
    $email = $_POST['email'];

    $encryptedpass = password_hash($managerpass, PASSWORD_ARGON2ID);
    try {
      $addaccountsql = "INSERT INTO manageruser VALUES ('$manageruser', '$encryptedpass', '$name', '$email')";
      $conn->exec($addaccountsql);
      header('location: login');
    } catch (PDOException $e) {
      header('location: login?error=1');
      echo $e->getMessage();
    }
  }
}
