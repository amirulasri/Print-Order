<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap-5.1.3-dist/css/bootstrap.min.css">
    <script src="../bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <title>Order</title>
</head>

<body>
    <div class="container">
        <br>
        <div class="receiptframe">
            <h1 class="display-4" style="color: white;">
                <svg xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                    <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                    <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                </svg>
                Print
            </h1>
            <div class="row">
                <div class="col-sm-12">
                    <br>
                    <div class="inframe">
                        <p style="color: white; font-weight:500; margin: 0;">Status: Printing</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
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
                                    <td>Test</td>
                                </tr>
                                <tr>
                                    <td>Phone No</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="pricedisplay">
                        Total <br>
                        RM 0.00
                    </div>
                </div>
                <div class="col-sm-8">
                    <br>
                    <div class="inframe">
                        <table class="table table-dark">
                            <thead>
                                <tr>
                                    <th colspan="3">Items</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Nama penuh file itu.pdf</td>
                                    <td><span class="badge rounded-pill bg-primary">Printing</span></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Nama penuh file itu.pdf</td>
                                    <td><span class="badge rounded-pill bg-success">Complete</span></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Nama penuh file itu.pdf</td>
                                    <td><span class="badge rounded-pill bg-danger">Failed</span></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Nama penuh file itu.pdf</td>
                                    <td><span class="badge rounded-pill bg-danger">Failed</span></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Nama penuh file itu.pdf</td>
                                    <td><span class="badge rounded-pill bg-danger">Failed</span></td>
                                </tr>
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
            <br>
            <p style="text-align:right; margin: 0;">
                <button class="btn btn-success btn-lg">Paid</button>
            </p>
        </div>
    </div>
</body>

</html>