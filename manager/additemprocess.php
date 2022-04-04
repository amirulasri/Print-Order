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
                $papertypename = $row[1];
                $colorprice = $row[2];
                $blackprice = $row[3];

                //CUSTOMER ID
                $statementgetuserdata = $conn->prepare("SELECT id FROM customer WHERE username = ?");
                $statementgetuserdata->execute([$manageruser]);
                $rowuserdata = $statementgetuserdata->fetch(PDO::FETCH_NUM);
                $userid = $rowuserdata[0];
                

                if(!empty($userid)){
                    $additem = "INSERT INTO orders VALUES (NULL, '$printabout', '$date', '$id')";
                    $conn->exec($additem);
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