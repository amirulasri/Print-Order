<?php
include("../conn.php");
if (isset($_COOKIE['managerusercookie'])) {
    $manageruser = $_COOKIE['managerusercookie'];
    if (isset($_POST['custname']) && isset($_POST['printabout'])) {
        if (!empty($_POST['custname']) && !empty($_POST['printabout'])) {
            $custname = $_POST['custname'];
            $printabout = $_POST['printabout'];
            try {
                $addprintorder = "INSERT INTO orders VALUES (NULL, '$custname', '$printabout', '', '$manageruser')";
                $conn->exec($addprintorder);
                header('location: login');
            } catch (PDOException $e) {
                header('location: login?error=1');
                echo $e->getMessage();
            }
        }
    }
} else {
    echo "FAILED COOKIE";
    die(header('location: login'));
}