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
    <div id="additempopover" class="d-none">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Item name">
            <button class="btn btn-outline-primary popover-dismiss" type="button" data-toggle="popover" data-placement="bottom" data-html="true" data-title="Search">
                Add
            </button>
        </div>
    </div>
    <div id="blackquantitypopover" class="d-none">
        <div class="input-group">
            <input type="number" class="form-control" min="0" placeholder="Quantity">
            <button class="btn btn-outline-primary popover-dismiss" type="button" data-toggle="popover" data-placement="bottom" data-html="true" data-title="Search">
                OK
            </button>
        </div>
    </div>
    <div id="colorquantitypopover" class="d-none">
        <div class="input-group">
            <input type="number" class="form-control" min="0" placeholder="Quantity">
            <button class="btn btn-outline-primary popover-dismiss" type="button" data-toggle="popover" data-placement="bottom" data-html="true" data-title="Search">
                OK
            </button>
        </div>
    </div>
    <div id="papertypepopover" class="d-none">
        <div class="input-group">
            <button class="btn btn-outline-info btn-sm">A4</button>
            <button class="btn btn-outline-info btn-sm">A3</button>
            <button class="btn btn-outline-info btn-sm">A1</button>
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
                                                <button class="btn btn-primary btn-sm callpapertypepopover" data-toggle="popover" id="papertypebtn<?php echo $row[0]; ?>" onclick="updatepapertype(<?php echo $row[0]; ?>)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
                                                        <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z" />
                                                    </svg>
                                                </button>
                                                <button class="btn btn-secondary btn-sm callblackquantitypopover" data-toggle="popover" title="Black white quantity">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-circle-half" viewBox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z" />
                                                    </svg>
                                                </button>
                                                <button class="btn btn-warning btn-sm callcolorquantitypopover" data-toggle="popover" title="Color quantity">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-palette" viewBox="0 0 16 16">
                                                        <path d="M8 5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm4 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM5.5 7a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm.5 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                                                        <path d="M16 8c0 3.15-1.866 2.585-3.567 2.07C11.42 9.763 10.465 9.473 10 10c-.603.683-.475 1.819-.351 2.92C9.826 14.495 9.996 16 8 16a8 8 0 1 1 8-8zm-8 7c.611 0 .654-.171.655-.176.078-.146.124-.464.07-1.119-.014-.168-.037-.37-.061-.591-.052-.464-.112-1.005-.118-1.462-.01-.707.083-1.61.704-2.314.369-.417.845-.578 1.272-.618.404-.038.812.026 1.16.104.343.077.702.186 1.025.284l.028.008c.346.105.658.199.953.266.653.148.904.083.991.024C14.717 9.38 15 9.161 15 8a7 7 0 1 0-7 7z" />
                                                    </svg>
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
    <script>
        //ADD ITEM POPOVER HANDLER
        const popover = new bootstrap.Popover(document.querySelector('.callAdditempopover'), {
            container: 'body',
            title: 'Add Item',
            html: true,
            placement: 'bottom',
            sanitize: false,
            content() {
                return document.querySelector('#additempopover').innerHTML;
            }
        });

        //PAPER TYPE POPOVER HANDLER

        popover2 = new bootstrap.Popover(document.querySelector('.callpapertypepopover'), {
            container: 'body',
            title: 'Paper Type',
            html: true,
            placement: 'bottom',
            sanitize: false,
            content() {
                return document.querySelector('#papertypepopover').innerHTML;
            }
        })

        //BLACK WHITE QUANTITY POPOVER HANDLER
        const popover3 = new bootstrap.Popover(document.querySelector('.callblackquantitypopover'), {
            container: 'body',
            title: 'Black White Quantity',
            html: true,
            placement: 'bottom',
            sanitize: false,
            content() {
                return document.querySelector('#blackquantitypopover').innerHTML;
            }
        })

        //COLOR QUANTITY POPOVER HANDLER
        const popover4 = new bootstrap.Popover(document.querySelector('.callcolorquantitypopover'), {
            container: 'body',
            title: 'Color Quantity',
            html: true,
            placement: 'bottom',
            sanitize: false,
            content() {
                return document.querySelector('#colorquantitypopover').innerHTML;
            }
        })

        $('body').on('click', function(e) {
            $('[data-toggle="popover"]').each(function() {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });

        function updatepapertype(id) {
           // var btnpapertype = document.getElementById("papertypebtn" + id);
           // launchpopoverpapertype(btnpapertype);
            //var currentpopover = bootstrap.Popover.getInstance(btnpapertype);
           // console.log(currentpopover);
        }
    </script>
</body>

</html>