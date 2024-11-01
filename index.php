<?php 
include 'Includes/dbcon.php';
session_start();

if (isset($_POST['login'])) {
    $userType = $_POST['userType'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Use plain text for testing

    // Determine which database connection to use
    $dbName = $userType == "Administrator" ? "sas_six" : "sas_seven"; // Update according to your actual DB names
    $dbConnection = $conn[$dbName];

    // Prepare query based on user type
    if ($userType == "Administrator") {
        $query = $dbConnection->prepare("SELECT * FROM tbladmin WHERE emailAddress = ?");
    } else if ($userType == "ClassTeacher") {
        $query = $dbConnection->prepare("SELECT * FROM tblclassteacher WHERE emailAddress = ?");
    } else {
        echo "<div class='alert alert-danger' role='alert'>Invalid User Role!</div>";
        exit;
    }

    // Execute query
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $rows = $result->fetch_assoc();

        // Use password hashing in production
        if ($password === $rows['password']) { // Consider using password_verify() here
            $_SESSION['userId'] = $rows['Id'];
            $_SESSION['firstName'] = $rows['firstName'];
            $_SESSION['lastName'] = $rows['lastName'];
            $_SESSION['emailAddress'] = $rows['emailAddress'];

            // Redirect based on user type
            $redirectLocation = $userType == "Administrator" ? "Admin/index.php" : "ClassTeacher/index.php";
            echo "<script type='text/javascript'>window.location = ('$redirectLocation')</script>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Invalid Username/Password!</div>";
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'>Invalid Username/Password!</div>";
    }

    $query->close(); // Close the prepared statement
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="img/logo/attnlg.jpg" rel="icon">
    <title>Code Camp BD - Login</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-login" style="background-image: url('img/logo/loral1.jpe00g');">
    <div class="container-login">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <h5 align="center">STUDENT ATTENDANCE SYSTEM</h5>
                                    <div class="text-center">
                                        <img src="img/logo/attnlg.jpg" style="width:100px;height:100px">
                                        <br><br>
                                        <h1 class="h4 text-gray-900 mb-4">Login Panel</h1>
                                    </div>
                                    <form class="user" method="POST" action="">
                                        <div class="form-group">
                                            <select required name="userType" class="form-control mb-3">
                                                <option value="">--Select User Roles--</option>
                                                <option value="Administrator">Administrator</option>
                                                <option value="ClassTeacher">ClassTeacher</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" required name="username" id="exampleInputEmail" placeholder="Enter Email Address">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" required class="form-control" id="exampleInputPassword" placeholder="Enter Password">
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-success btn-block" value="Login" name="login" />
                                        </div>
                                    </form>
                                    <div class="text-center"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
</body>
</html>