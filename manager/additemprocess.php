<?php
include("../conn.php");
if (isset($_COOKIE['managerusercookie'])) {
    $manageruser = $_COOKIE['managerusercookie'];
    if (isset($_POST['itemname']) && isset($_POST['papertype']) && isset($_POST['blackquantity']) && isset($_POST['colorquantity'])) {
        if (!empty($_POST['itemname']) && !empty($_POST['papertype']) && !empty($_POST['blackquantity']) && !empty($_POST['colorquantity'])) {
            //GET ALL DATA
            $itemname = $_POST['itemname'];
            $paperid = $_POST['paperid'];
            $blackquantity = $_POST['blackquantity'];
            $colorquantity = $_POST['colorquantity'];

            try {
                //FIND PRICE BASED ON PAPER TYPE
                $statementgetpaperprice = $conn->prepare("SELECT * FROM papertype WHERE paperid = ?");
                $statementgetpaperprice->execute([$paperid]);
                $row = $statementgetpaperprice->fetch(PDO::FETCH_NUM);
                $colorprice = $row[2];
                $blackprice = $row[3];
                

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
}