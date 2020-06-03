<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $_SESSION['username'] = '';
    $_SESSION['password'] = '';
    $_SESSION['email'] = '';
    $_SESSION['flag'] = 0;
    $_SESSION['flag1'] = 0;
}
if (!isset($_SESSION['valid_login'])) {
    $_SESSION['valid_login'] = 0;
}
if (!isset($_SESSION['is_logout'])) {
    $_SESSION['is_logout'] = 0;
}
if (!isset($_SESSION['is_delete'])) {
    $_SESSION['is_delete'] = 0;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login | Spardha'19</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="login.css">
</head>

<body style='background-color: transparent'>
    <div class="container login-box">
        <h3 style="text-align: center; color: #6db549; font-family: 'League Spartan'; font-size: 30px;"> LOGIN </h3>
        <hr>
        <?php
            echo '<div class="col-sm-12">';
            include("../db.php");
            if (!isset($_SESSION['flag'])) {
                $_SESSION['flag'] = 0;
            }
            if (!isset($_SESSION['flag1'])) {
                $_SESSION['flag1'] = 0;
            }
            if ($_SESSION['flag'] == 0 && $_SERVER["REQUEST_METHOD"] == "POST") {
                $_SESSION['flag1'] = 0;
                $_SESSION['username'] = strtolower (mysqli_real_escape_string ($conn, $_POST["username"]));
                $_SESSION['password'] = mysqli_real_escape_string ($conn, $_POST["password"]);
                $is_email = 0;
                if (strpos($_SESSION['username'], '@') !== false) {
                    $is_email = 1;
                }
                $username = $_SESSION['username'];
                $password = $_SESSION['password'];
                
                if ($is_email) {
                    $query = "SELECT * FROM `users` WHERE (`email`='$username' AND `password`='$password' AND `status`='1')";
                    $result = mysqli_query ($conn, $query);
                    $query = "SELECT * FROM `users` WHERE (`email`='$username' AND `password`='$password' AND `status`='-1')";
                    $result0 = mysqli_query ($conn, $query);
                }
                else {
                    $query = "SELECT * FROM `users` WHERE (`username`='$username' AND `password`='$password' AND `status`='1')";
                    $result = mysqli_query ($conn, $query);
                    $query = "SELECT * FROM `users` WHERE (`username`='$username' AND `password`='$password' AND `status`='-1')";
                    $result0 = mysqli_query ($conn, $query);
                }
                $rows = mysqli_num_rows ($result);
                $row = mysqli_fetch_row($result);
                $rows0 = mysqli_num_rows ($result0);
                if ($result && $result0) {
                    if ($rows) {
                        $_SESSION['valid_login'] = 1;
                        $_SESSION['email'] = $row[1];
                        $_SESSION['username'] = $row[2];
                        echo '<script>window.top.location="../../dashboard";</script>';
                    }
                    else if ($rows0) {
                        $_SESSION['flag1'] = -1;
                    }
                    else {
                        $_SESSION['flag1'] = 1;
                    }
                }
                else if (mysqli_connect_errno()) {
                    echo "Could not connect: ". mysqli_connect_error($conn);
                    session_destroy();
                }
            }
            echo '</div>';
            if ($_SESSION['flag'] == 0 || $_SESSION['flag'] == 1) {
        ?>
        <div class="col-sm-12">
            <?php
            if ($_SESSION['is_logout'] == 1) {
                echo '<div class="alert alert-success">You have successfully logged out!</div>';
                $_SESSION['is_logout'] = 0;
            }
            if ($_SESSION['is_delete'] == 1) {
                echo '<div class="alert alert-success">Your profile has been successfully deleted!</div>';
                $_SESSION['is_delete'] = 0;
            }
            ?>
            <div class="alert alert-success">
                <b>Account not yet created?</b> Click <a href="../../register.php?page=register#content" target="_parent">here</a> to create one.<br>
                <b>Forgot Password?</b> Click <a href="../../register.php?page=forgot#content" target="_parent">here</a>
            </div>
        </div>
        <form class="form-horizontal" role="form" method="post" action="" onsubmit="return validate(this);"
            novalidate>
            <div class="col-sm-12" style="margin-top: 10px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title text-center" style="color:#59ba00;">
                            <a style="color:#59ba00;"><i class="fa fa-user fa-lg"></i>&nbsp; Login Details </a>
                        </h4>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label heading-color" for="username">Username/Email:&nbsp;<span
                                        style="color: red">*</span></label>
                                <div class="inputWithIcon">
                                    <input type="text" class="form-control" id="username" name="username" onfocus='onInput("username")' oninput='onInput("username")' onblur='onLeave("username")'
                                    value="<?php echo (isset($_SESSION['username']) ? $_SESSION['username'] : ''); ?>" placeholder="Enter your username or email" required>
                                        <i class="fa fa-at fa-lg form-control-feedback"></i>
                                    <span id="username-icon"></span>
                                </div>
                                <div class="error" id="username-error"></div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label heading-color" for="password">Password:&nbsp;<span
                                        style="color: red">*</span></label>
                                <div class="inputWithIcon">
                                    <input type="password" class="form-control" id="password" name="password" onfocus='onInput("password")' oninput='onInput("password")' onblur='onLeave("password")'
                                    value="<?php echo (isset($_SESSION['password']) ? $_SESSION['password'] : ''); ?>" placeholder="Enter the Password to login" required><i class="fa fa-key fa-lg form-control-feedback"></i>
                                    <span id="password-icon"></span>
                                </div>
                                <div class="error" id="password-error"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <?php
                    if ($_SESSION['flag1'] == -1) echo '<div class="alert alert-danger" style="margin-top: 20px;">Sorry, your account has been disabled. Contact us for any support on <a href="tel:+917238856930">+91&#8209;7238856930</a>.</div>';
                    if ($_SESSION['flag1'] == 1) echo '<div class="alert alert-danger" style="margin-top: 20px;">Invalid Credentials! Please check and try again.</div>';
                    ?>
                    <div id="finalerror" class="alert alert-danger" style="margin-top: 20px; display: none;"></div>
                    <hr>
                    <button id="login" type="login" name="login" class="btn btn-default btn-success btn-block" style="font-size: 16px; font-family: 'League Spartan'; padding-top: 10px;"> &nbsp;
                        <i class="fa fa-paper-plane"></i> LOGIN
                    </button>
                </div>
            </div>
        </form>
        <script src="login.js"></script>
        <?php
        if ($_SESSION['flag'] == 1)
            echo '<script type="text/javascript"> disableAll(); </script>';
        }
        mysqli_close ($conn);
        ?>
    </div>
</body>

</html>