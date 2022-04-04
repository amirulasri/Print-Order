<?php
include("../conn.php");
if (isset($_COOKIE['managerusercookie'])) {
    $manageruser = $_COOKIE['managerusercookie'];
    if (isset($_POST['phoneno']) && isset($_POST['printabout'])) {
        if (!empty($_POST['phoneno']) && !empty($_POST['printabout'])) {
            $phoneno = $_POST['phoneno'];
            $printabout = $_POST['printabout'];
            $date = date('d-M-y');
            try {
                $statementgetcustid = $conn->prepare("SELECT id FROM customerlogin WHERE phoneno = ?");
                $statementgetcustid->execute([$phoneno]);
                $row = $statementgetcustid->fetch(PDO::FETCH_NUM);
                $id = $row[0];
                if(!empty($id)){
                    $addprintorder = "INSERT INTO orders VALUES (NULL, '$printabout', '$date', '$id')";
                    $conn->exec($addprintorder);
                    $lastid = $conn->lastInsertId();
                    header('location: editorder?order=' . $lastid);
                }else{
                    header('location: printorder?erroraddorder');
                }

            } catch (PDOException $e) {
                //header('location: printorder');
                echo $e->getMessage();
            }
        }
    }
} else {
    echo "FAILED COOKIE";
    die(header('location: login'));
}
