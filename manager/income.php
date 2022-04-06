<?php
include('../conn.php');
include('aplhaids.php');
session_start();
$manageruser = "";
if (!$_COOKIE['managerusercookie']) {
    echo "FAILED COOKIE";
    die(header('location: login'));
} else {
    $manageruser = $_COOKIE['managerusercookie'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../bootstrap-5.1.3-dist/css/bootstrap.min.css">
    <script src="../../bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../style.css">
    <title>Order</title>
</head>

<body>
    <div class="container">
        <br>
        <div class="receiptframe">
            <h1 class="display-6" style="color: white;">
                <svg xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                    <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                    <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                </svg>
                Net Worth
            </h1>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="inframe">
                        <p style="color: white;"><strong>Net Worth</strong></p>
                        <table class="table table-dark">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Print About</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $statementgetnettotal = $conn->prepare("SELECT customerlogin.fullname, orders.printabout, income.totalprice FROM customerlogin INNER JOIN orders ON customerlogin.id = orders.customerid INNER JOIN income ON orders.orderid = income.orderid WHERE income.manageruser = ?");
                                    $statementgetnettotal->execute([$manageruser]);
                                    while ($row = $statementgetnettotal->fetch()) {
                                ?>
                                        <tr>
                                            <td><?php echo $row['username'] ?></td>
                                            <td><?php echo $row['printabout'] ?></td>
                                            <td><?php
                                                $statementgetpage = $conn->prepare("SELECT * FROM items WHERE orderid = ?");
                                                $statementgetpage->execute([$row[0]]);
                                                $totalpage = 0;
                                                while ($rowpage = $statementgetpage->fetch(PDO::FETCH_NUM)) {
                                                    $totalpage += (intval($rowpage[2]) + intval($rowpage[3]));
                                                }
                                                echo $totalpage; ?></td>
                                            <td>
                                                <button class="btn btn-primary" onclick="window.location='editorder?order=<?php echo $row[0] ?>'">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                                                    </svg>
                                                </button>
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
        </div>
    </div>
</body>

</html>