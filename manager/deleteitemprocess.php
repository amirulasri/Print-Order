<?php
include("../conn.php");
if (isset($_COOKIE['managerusercookie'])) {
    $manageruser = $_COOKIE['managerusercookie'];
    if (isset($_GET['item']) && isset($_GET['order'])) {
        try {
            $itemid = $_GET['item'];
            $orderid = $_GET['order'];
            $deleteitem = "DELETE FROM items WHERE itemid = '$itemid' AND orderid = '$orderid'";
            $conn->exec($deleteitem);
            header('location: editorder?order='.$orderid);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
