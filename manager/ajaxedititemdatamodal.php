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
                        $progressbar = $resultgetorder['progressbar'];
                        $statusprint = $resultgetorder['statusprint'];
                        $blackquantity = $resultgetorder['blackwhitequantity'];
                        $colorquantity = $resultgetorder['colorquantity'];
                        $orderid = $resultgetorder['orderid'];
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

<form action="updateitemprocess" method="post" id="edititemform">
    File name
    <input type="text" class="form-control" value="<?php echo $itemname ?>" name="itemname" required><br>
    Paper Type B-Black White C-Color
    <select name="paperid" id="" class="form-control" required>
        <?php
        try {
            $statementgetitems = $conn->prepare("SELECT * FROM papertype");
            $statementgetitems->execute();
            while ($row = $statementgetitems->fetch(PDO::FETCH_NUM)) {
        ?>
                <option value="<?php echo $row[0] ?>" <?php if($papertype == $row[1]){echo "selected";} ?>><?php echo $row[1] ?> B-RM<?php echo $row[3] ?> C-RM<?php echo $row[2] ?></option>
        <?php
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        ?>
    </select><br>
    Black white quantity
    <input type="number" name="blackquantity" min="0" value="<?php echo $blackquantity ?>" class="form-control" required><br>
    Color quantity
    <input type="number" name="colorquantity" min="0" value="<?php echo $colorquantity ?>" class="form-control" required><br>
    Status
    <select class="form-control" name="statusprint" id="" required>
        <option value="printing" <?php if($statusprint == 'printing'){echo "selected";} ?>>Printing</option>
        <option value="complete" <?php if($statusprint == 'complete'){echo "selected";} ?>>Complete</option>
        <option value="failed" <?php if($statusprint == 'failed'){echo "selected";} ?>>Failed</option>
        <option value="cancelled" <?php if($statusprint == 'cancelled'){echo "selected";} ?>>Cancelled</option>
    </select><br>
    Progress
    <input type="range" min="0" max="1" step="0.01" value="<?php echo $progressbar ?>" name="progress" class="form-range" required>
    <input type="hidden" name="itemid" value="<?php echo $itemid ?>">
    <input type="hidden" name="orderid" value="<?php echo $orderid ?>">
</form>