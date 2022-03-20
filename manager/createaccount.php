<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../bootstrap-5.1.3-dist/css/bootstrap.min.css">
    <script src="../../bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../style.css">
    <title>Create Account</title>
</head>

<body>
    <div class="container" style="max-width: 500px;">
        <br>
        <div class="loginframe">
            <form action="createaccountprocess.php" method="post">
                <h1 class="display-6" style="color: white;">Create account</h1>
                <p style="margin: 0; color: white;">Manager</p><br>
                <p style="margin: 0; color: white;">Name</p>
                <input type="text" name="name" class="form-control" required><br>
                <p style="margin: 0; color: white;">Username</p>
                <input type="text" name="manageruser" class="form-control" required><br>
                <p style="margin: 0; color: white;">Password</p>
                <input type="password" name="managerpass" class="form-control" required><br>
                <p style="margin: 0; color: white;">E-Mail</p>
                <input type="password" name="email" class="form-control" required><br>
                <p style="margin: 0; text-align:right;">
                    <button class="btn btn-primary">Create account</button>
                </p>
            </form>
        </div>
    </div>
</body>

</html>