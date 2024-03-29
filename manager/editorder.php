<?php
include("../conn.php");
session_start();
$orderid = "";
$manageruser = "";

//CUSTOMER DATA
$custname = "";
$printabout = "";
$phoneno = "";

if (isset($_COOKIE['managerusercookie'])) {
    $manageruser = $_COOKIE['managerusercookie'];
    try {
        $statementcheckmanager = $conn->prepare("SELECT * FROM manageruser WHERE manageruser = ?");
        $statementcheckmanager->execute([$manageruser]);
        $result = $statementcheckmanager->fetch();
        if (!empty($result['manageruser'])) {
            if (isset($_GET['order'])) {
                $orderid = $_GET['order'];
                try {
                    $statementgetorder = $conn->prepare("SELECT * FROM orders INNER JOIN customerlogin ON orders.customerid = customerlogin.id WHERE orderid = ?");
                    $statementgetorder->execute([$orderid]);
                    $resultgetorder = $statementgetorder->fetch();
                    if (!empty($resultgetorder['orderid'])) {
                        $custname = $resultgetorder['fullname'];
                        $printabout = $resultgetorder['printabout'];
                        $phoneno = $resultgetorder['phoneno'];
                    } else {
                        echo "NO DATA CUSTOMER";
                        die();
                    }

                    //GET TOTALPRICE AND PROGRESS
                    $totalitemprice = 0;
                    $progressbar = 0;
                    $countitem = 0;
                    $statementgetitemprice = $conn->prepare("SELECT price, progressbar FROM items WHERE orderid = ?");
                    $statementgetitemprice->execute([$orderid]);
                    while ($row = $statementgetitemprice->fetch(PDO::FETCH_NUM)) {
                        $countitem++;
                        $totalitemprice += $row[0];
                        $progressbar += $row[1];
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage(); //IF ORDER NOT FOUND OR ERROR
                }
            } else {
                die(header('location: printorder')); //IF GET URL ORDER NOT SET
            }
        } else {
            header('location: login?error=4'); //USERNAME NOT FOUND OR DELETED
        }
    } catch (PDOException $e) {
        header('location: login?error=2'); //FAILED EXCEPTION
        echo $e->getMessage();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} else {
    echo "FAILED COOKIE";
    die(header('location: login')); //IF COOKIE NOT EXIST
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../jquery.js"></script>
    <link rel="stylesheet" href="../../bootstrap-5.1.3-dist/css/bootstrap.min.css">
    <script src="../../bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../style.css">
    <title>Edit Order</title>
</head>

<body>
    <!-- Modal Edit Item -->
    <div class="modal fade" id="edititemmodal" tabindex="-1" aria-labelledby="edititem" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="edititemdatamodal">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="deleteitembutton" onclick="">Delete Item</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" value="Save changes" class="btn btn-primary" form="edititemform">
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Add Item -->
    <div class="modal fade" id="additemmodal" tabindex="-1" aria-labelledby="edititem" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="additemprocess" method="post" id="additemform">
                        File name
                        <input type="text" class="form-control" name="itemname" required><br>
                        Paper Type B-Black White C-Color
                        <select name="paperid" id="" class="form-control" required>
                            <?php
                            try {
                                $statementgetitems = $conn->prepare("SELECT * FROM papertype");
                                $statementgetitems->execute();
                                while ($row = $statementgetitems->fetch(PDO::FETCH_NUM)) {
                            ?>
                                    <option value="<?php echo $row[0] ?>"><?php echo $row[1] ?> B-RM<?php echo $row[3] ?> C-RM<?php echo $row[2] ?></option>
                            <?php
                                }
                            } catch (PDOException $e) {
                                echo $e->getMessage();
                            }
                            ?>
                        </select><br>
                        Black white quantity
                        <input type="number" name="blackquantity" min="0" value="0" class="form-control" required><br>
                        Color quantity
                        <input type="number" name="colorquantity" min="0" value="0" class="form-control" required><br>
                        Status
                        <select class="form-control" name="statusprint" id="" required>
                            <option value="printing">Printing</option>
                            <option value="complete">Complete</option>
                            <option value="failed">Failed</option>
                            <option value="cancelled">Cancelled</option>
                        </select><br>
                        Progress
                        <input type="range" min="0" max="1" step="0.01" value="0" name="progress" class="form-range" required>
                        <input type="hidden" name="orderid" value="<?php echo $orderid ?>">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" value="Save" class="btn btn-primary" form="additemform">
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <br>
        <div class="receiptframe">
            <h1 class="display-6" style="color: white;">
                <svg xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                    <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                    <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                </svg>
                Edit Order - ID: <?php echo $orderid ?>
            </h1>
            <div class="row">
                <div class="col-sm-12">
                    <br>
                    <div class="inframe">
                        <p style="color: white; font-weight:500; margin: 0;">Status: <?php
                                                                                        if ($countitem != 0) {
                                                                                            echo ($progressbar / $countitem) * 100;
                                                                                        } else {
                                                                                            echo 0;
                                                                                        }
                                                                                        ?>% complete</p>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: <?php
                                                                                                                                                if ($countitem != 0) {
                                                                                                                                                    echo ($progressbar / $countitem) * 100;
                                                                                                                                                } else {
                                                                                                                                                    echo 0;
                                                                                                                                                }
                                                                                                                                                ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5">
                    <br>
                    <div class="inframe">
                        <table class="table table-dark">
                            <thead>
                                <tr>
                                    <th colspan="2">Customer Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Name</td>
                                    <td><?php echo $custname ?></td>
                                </tr>
                                <tr>
                                    <td>Phone No</td>
                                    <td><?php if (!empty($phoneno)) {
                                            echo $phoneno;
                                        } else {
                                            echo "-";
                                        } ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-7">
                    <br>
                    <div class="inframe">
                        <table class="table table-dark">
                            <thead>
                                <tr>
                                    <th>Share URL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroupPrepend">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link" viewBox="0 0 16 16">
                                                    <path d="M6.354 5.5H4a3 3 0 0 0 0 6h3a3 3 0 0 0 2.83-4H9c-.086 0-.17.01-.25.031A2 2 0 0 1 7 10.5H4a2 2 0 1 1 0-4h1.535c.218-.376.495-.714.82-1z" />
                                                    <path d="M9 5.5a3 3 0 0 0-2.83 4h1.098A2 2 0 0 1 9 6.5h3a2 2 0 1 1 0 4h-1.535a4.02 4.02 0 0 1-.82 1H12a3 3 0 1 0 0-6H9z" />
                                                </svg>
                                            </span>
                                            <input type="text" class="form-control" value="https://amirulasri.tplinkdns.com/printorderapp/orderview/<?php echo $orderid ?>" readonly>
                                            <span class="input-group-text" id="inputGroupPrepend">
                                                <button class="btn btn-primary">Copy</button>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <br>
                    <div class="inframe">
                        <table class="table table-dark">
                            <thead>
                                <tr>
                                    <th colspan="4">Items <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#additemmodal">
                                            Add Item
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="ordertabledata">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-4">
                    <div class="pricedisplay">
                        Total <br>
                        RM <?php echo number_format((float)$totalitemprice, 2, '.', '') ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="pricedisplay">
                        Pay <br>
                        <input type="number" name="payamount" id="" class="form-control">
                        <button type="submit" class="btn btn-light">Pay</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onload = function() {
            getordertabledata(<?php echo $orderid ?>);
        };

        function getordertabledata(orderid) {
            if (orderid.length == 0) {
                document.getElementById("ordertabledata").innerHTML = "";
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("ordertabledata").innerHTML = this.responseText;
                    }
                };
                xmlhttp.open("GET", "ajaxordertable.php?order=" + orderid, true);
                xmlhttp.send();
            }
        }

        function getitemdatatomodal(itemid, orderid) {
            if (itemid.length == 0) {
                document.getElementById("edititemdatamodal").innerHTML = "";
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("edititemdatamodal").innerHTML = this.responseText;
                    }
                };
                xmlhttp.open("GET", "ajaxedititemdatamodal.php?item=" + itemid, true);
                xmlhttp.send();
            }
            deletebutton = document.getElementById("deleteitembutton");
            deletebutton.setAttribute("onclick", "window.location='deleteitemprocess?item=" + itemid + "&order=" + orderid + "'");
        }
    </script>
</body>

</html>