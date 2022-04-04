<?php
include("../conn.php");
$itemid = "";
$manageruser = "";

if (isset($_COOKIE['managerusercookie'])) {
    $manageruser = $_COOKIE['managerusercookie'];
    try {
        $statementcheckmanager = $conn->prepare("SELECT * FROM manageruser WHERE manageruser = ?");
        $statementcheckmanager->execute([$manageruser]);
        $result = $statementcheckmanager->fetch();
        if (!empty($result['manageruser'])) {
            if (isset($_GET['item'])) {
                $itemid = $_GET['item'];
                try {
                    $statementgetorder = $conn->prepare("SELECT * FROM items WHERE itemid = ?");
                    $statementgetorder->execute([$itemid]);
                    $resultgetorder = $statementgetorder->fetch();
                    if (!empty($resultgetorder['itemid'])) {
                        $itemname = $resultgetorder['itemname'];
                        $papertype = $resultgetorder['papertype'];
                        
                    } else {
                        echo "NO DATA CUSTOMER";
                        die();
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage(); //IF ORDER NOT FOUND OR ERROR
                }
            } else {
                die("NO DATA"); //IF GET URL ORDER NOT SET
            }
        } else {
            die(header('location: login?error=4')); //USERNAME NOT FOUND OR DELETED
        }
    } catch (PDOException $e) {
        header('location: login?error=2'); //FAILED EXCEPTION
        echo $e->getMessage();
        die();
    } catch (Exception $e) {
        echo $e->getMessage();
        die();
    }
} else {
    echo "FAILED COOKIE";
    die(header('location: login')); //IF COOKIE NOT EXIST
}
?>

Item Name <br>
<input type="text" name="itemname" class="form-control">
Paper Type <br>
<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="paperselector" data-bs-toggle="dropdown" aria-expanded="false">
        A4 (Default)
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
        <li><a class="dropdown-item" id="paperchoose" href="#">A4</a></li>
    </ul>
</div><br>
Color Quantity <br>
<input type="number" name="colorquantity" class="form-control"><br>
Black Quantity <br>
<input type="number" name="blackquantity" class="form-control"><br>