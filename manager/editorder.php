<?php
include("../conn.php");
$orderid = "";
$manageruser = "";

//CUSTOMER DATA
$custname = "";
$printabout = "";
$email = "";

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
                    $statementgetorder = $conn->prepare("SELECT * FROM orders WHERE custid = ?");
                    $statementgetorder->execute([$orderid]);
                    $resultgetorder = $statementgetorder->fetch();
                    if (!empty($resultgetorder['custid'])) {
                        $custname = $resultgetorder['custname'];
                        $printabout = $resultgetorder['printabout'];
                        $email = $resultgetorder['email'];
                    } else {
                        echo "NO DATA CUSTOMER";
                        die();
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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="edititem" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Edit detail here
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
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
                        <p style="color: white; font-weight:500; margin: 0;">Status: Printing</p>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
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
                                    <td>Email</td>
                                    <td><?php if (!empty($email)) {
                                            echo $email;
                                        } else {
                                            echo "-";
                                        } ?></td>
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
                                    <th colspan="4">Items <a class="btn btn-primary btn-sm callAdditempopover" data-toggle="popover" tabindex="0">Add new item</a></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $increment = 1;
                                try {
                                    $statementgetitems = $conn->prepare("SELECT * FROM items WHERE custid = ?");
                                    $statementgetitems->execute([$orderid]);
                                    while ($row = $statementgetitems->fetch(PDO::FETCH_NUM)) {
                                ?>
                                        <tr>
                                            <td><?php echo $increment++ ?></td>
                                            <td><?php echo $row[1]; ?></td>
                                            <td>
                                                <button class="btn btn-primary btn-sm callpapertypepopover" data-toggle="popover" id="papertypebtn<?php echo $row[0]; ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
                                                        <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z" />
                                                    </svg>
                                                    Edit
                                                </button>
                                                <br>RM 0.00
                                            </td>
                                            <td><?php
                                                $statustext = "";
                                                $badgecolor = "";
                                                if (strtolower($row[5]) == 'printing') {
                                                    $statustext = "Printing";
                                                    $badgecolor = "primary";
                                                } else if (strtolower($row[5]) == 'complete') {
                                                    $statustext = "Complete";
                                                    $badgecolor = "success";
                                                } else if (strtolower($row[5]) == 'failed') {
                                                    $statustext = "Print failed";
                                                    $badgecolor = "danger";
                                                } else if (strtolower($row[5]) == 'cancelled') {
                                                    $statustext = "Print cancelled";
                                                    $badgecolor = "danger";
                                                }
                                                ?>
                                                <span class="badge rounded-pill bg-<?php echo $badgecolor ?>"><?php echo $statustext ?></span>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } catch (PDOException $e) {
                                    echo $e->getMessage();
                                }
                                ?>
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
                        RM 0.00
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>