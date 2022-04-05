<?php
include("../conn.php");
if (isset($_COOKIE['managerusercookie'])) {
    $manageruser = $_COOKIE['managerusercookie'];
    if (isset($_POST['itemname']) && isset($_POST['paperid']) && isset($_POST['blackquantity']) && isset($_POST['colorquantity']) && isset($_POST['statusprint']) && isset($_POST['progress']) && isset($_POST['orderid'])) {
        //GET ALL DATA
        $itemname = $_POST['itemname'];
        $paperid = $_POST['paperid'];
        $blackquantity = $_POST['blackquantity'];
        $colorquantity = $_POST['colorquantity'];
        $statusprint = $_POST['statusprint'];
        $progress = $_POST['progress'];
        $orderid = $_POST['orderid'];

        //COLOR PROGRESS
        $progressbarcolor = "";
        if ($statusprint == 'printing') {
            $progressbarcolor = "primary";
        } else if ($statusprint == 'complete') {
            $progressbarcolor = "success";
        } else if ($statusprint == 'failed') {
            $progressbarcolor = "danger";
        } else if ($statusprint == 'cancelled') {
            $progressbarcolor = "danger";
        }

        $colorprice = 0;
        $blackprice = 0;
        $totalblackprice = 0;
        $totalcolorprice = 0;

        try {
            //FIND PRICE BASED ON PAPER TYPE
            $statementgetpaperprice = $conn->prepare("SELECT * FROM papertype WHERE paperid = ?");
            $statementgetpaperprice->execute([$paperid]);
            $row = $statementgetpaperprice->fetch(PDO::FETCH_NUM);
            $papertypename = $row[1];
            $colorprice = $row[2];
            $blackprice = $row[3];

            //CALCULATE FOR EACH PAGES
            $totalblackprice = $blackprice * $blackquantity;
            $totalcolorprice = $colorprice * $colorquantity;

            $totalprice = $totalblackprice + $totalcolorprice;

            //CUSTOMER ID
            $statementgetuserdata = $conn->prepare("SELECT id FROM customerlogin WHERE username = ?");
            $statementgetuserdata->execute([$manageruser]);
            $rowuserdata = $statementgetuserdata->fetch(PDO::FETCH_NUM);
            $userid = $rowuserdata[0];

            if (!empty($userid)) {
                $additem = "INSERT INTO items VALUES (NULL, '$itemname', '$blackquantity', '$colorquantity', '$papertypename', '$statusprint', '$progress', '$progressbarcolor', '$totalprice', '$orderid', '$userid')";
                $conn->exec($additem);
                header('location: editorder?order=' . $orderid);
            } else {
                header('location: printorder?erroraddorder1');
            }
        } catch (PDOException $e) {
            //header('location: printorder');
            //header('location: printorder?erroraddorder2');
            echo $e->getMessage();
        }
    } else {
        echo "Error ISSET";
    }
} else {
    echo "Error COOKIE";
}
