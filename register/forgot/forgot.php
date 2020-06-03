<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    session_destroy();
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Forgot | Spardha'19</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="forgot.css">
</head>

<body style='background-color: transparent'>
    <div class="container forgot-box">
        <h3 style="text-align: center; color: #6db549; font-family: 'League Spartan'; font-size: 30px;"> FORGOT PASSWORD </h3>
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
            if (!isset($_SESSION['OTPck'])) {
                $_SESSION['OTPck'] = 0;
            }
            if (!isset($_SESSION['email'])) {
                $_SESSION['email'] = "";
            }
            if ($_SESSION['flag'] == 0 && $_SERVER["REQUEST_METHOD"] == "POST") {
                $_SESSION['flag1'] = 0;
                $_SESSION['username'] = strtolower (mysqli_real_escape_string ($conn, $_POST["username"]));
                $_SESSION['institute_name'] = strtoupper (mysqli_real_escape_string ($conn, clean($_POST["institute"])));
                $_SESSION['password1'] = mysqli_real_escape_string ($conn, $_POST["password1"]);
                $_SESSION['password2'] = mysqli_real_escape_string ($conn, $_POST["password2"]);
                $is_email = 0;
                if (strpos($_SESSION['username'], '@') !== false) {
                    $is_email = 1;
                }
                $username = $_SESSION['username'];
                $institute_name = $_SESSION['institute_name'];
                
                if ($is_email) {
                    $query = "SELECT * FROM `users` WHERE (`email`='$username' AND `institute_name`='$institute_name' AND `status`='1')";
                    $result = mysqli_query ($conn, $query);
                    $query = "SELECT * FROM `users` WHERE (`email`='$username' AND `institute_name`='$institute_name' AND `status`='-1')";
                    $result0 = mysqli_query ($conn, $query);
                }
                else {
                    $query = "SELECT * FROM `users` WHERE (`username`='$username' AND `institute_name`='$institute_name' AND `status`='1')";
                    $result = mysqli_query ($conn, $query);
                    $query = "SELECT * FROM `users` WHERE (`username`='$username' AND `institute_name`='$institute_name' AND `status`='-1')";
                    $result0 = mysqli_query ($conn, $query);
                }
                $rows = mysqli_num_rows ($result);
                $row = mysqli_fetch_row($result);
                $rows0 = mysqli_num_rows ($result0);
                if ($result && $result0) {
                    if ($rows) {
                        $_SESSION['flag'] = 1;
                        $_SESSION['email'] = $row[1];
                        $_SESSION['username'] = $row[2];
                        $_SESSION['name'] = $row[4];
                        include("forgototp.php");
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
            else if (($_SESSION['flag'] == 1) && $_SERVER["REQUEST_METHOD"] == "POST") {
                $otp_user = mysqli_real_escape_string ($conn, clean($_POST["OTPTextBox"]));
                if ($otp_user == $_SESSION['otp']) {
                    $_SESSION['flag'] = 2;
                    $password = $_SESSION['password1'];
                    $email = $_SESSION['email'];
                    $query = "UPDATE `users` SET `password` = '$password' WHERE `email` = '$email' AND `status`='1'";
                    $result = mysqli_query ($conn, $query);
                    if ($result) {
                        include("forgotsuccess.php");
                    }
                    else {
                        echo "Could not process: " . mysqli_error($conn);
                        session_destroy();
                    }
                }
                else {
                    $_SESSION['OTPck'] = -1;
                }
            }
            echo '</div>';
            if ($_SESSION['flag'] == 0 || $_SESSION['flag'] == 1) {
        ?>
        <div class="col-sm-12">
            <div class="alert alert-success">
                Click <a href="../../register.php?page=register#content" target="_parent">here</a> to <b>sign up</b>.<br>
                Click <a href="../../register.php?page=login#content" target="_parent">here</a> to <b>login</b>.
            </div>
            <div class="alert alert-info">
                Enter the details below and confirm using OTP Verification.
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
                                <label class="control-label heading-color" for="institute">Institute Name:&nbsp;<span
                                        style="color: red">*</span></label>
                                <div class="inputWithIcon">
                                    <input type="text" class="form-control" id="institute" name="institute" onfocus='onInput("institute")' oninput='onInput("institute")' onblur='onLeave("institute")'
                                    value="<?php echo (isset($_SESSION['institute_name']) ? $_SESSION['institute_name'] : ''); ?>" list="instituteList" placeholder="Select your Institute & City" required><i
                                        class="fa fa-building fa-lg form-control-feedback"></i>
                                    <span id="institute-icon"></span>
                                </div>
                                <div class="error" id="institute-error"></div>
                                <datalist id="instituteList"></datalist>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label heading-color" for="password1">New Password:&nbsp;<span
                                        style="color: red">*</span></label>
                                <div class="inputWithIcon">
                                    <input type="password" class="form-control" id="password1" name="password1" onfocus='onInput("password1")' oninput='onInput("password1")' onblur='onLeave("password1")'
                                    value="<?php echo (isset($_SESSION['password1']) ? $_SESSION['password1'] : ''); ?>" placeholder="Enter the New Password" required><i class="fa fa-key fa-lg form-control-feedback"></i>
                                    <span id="password1-icon"></span>
                                </div>
                                <div class="error" id="password1-error"></div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label heading-color" for="password2">Password Confirmation:&nbsp;<span
                                        style="color: red">*</span></label>
                                <div class="inputWithIcon">
                                    <input type="password" class="form-control" id="password2" name="password2" onfocus='onInput("password2")' oninput='onInput("password2")' onblur='onLeave("password2")'
                                    value="<?php echo (isset($_SESSION['password2']) ? $_SESSION['password2'] : ''); ?>" placeholder="Confirm your Password" required><i class="fa fa-key fa-lg form-control-feedback"></i>
                                    <span id="password2-icon"></span>
                                </div>
                                <div class="error" id="password2-error"></div>
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
                    <div id="finalerror1" class="alert alert-danger" style="margin-top: 20px; display: none;"></div>
                    <div id="finalsuccess1" class="alert alert-success" style="margin-top: 20px; display: none;"></div>
                    <hr>
                    <button id="submit1" type="submit1" name="submit1" class="btn btn-default btn-success btn-block" style="font-size: 16px; font-family: 'League Spartan'; padding-top: 10px;"> &nbsp;
                        <i class="fa fa-paper-plane"></i> SUBMIT
                    </button>
                </div>
            </div>
        </form>
        <form class="form-horizontal" role="form" method="post" action="" onsubmit="return verifyOTP(this);"
            novalidate>
            <div id="OTPdiv" class="col-sm-12" style="padding-top: 25px; text-align: center; display: none;">
                <?php
                if ($_SESSION['OTPck'] == 0)
                    echo '<div id="finalsuccess2" class="alert alert-success" style="margin-top: 20px; display: none;">
                    OTP has been sent to your email address: <b>' . $_SESSION['email'] .
                    '</b><br> Please enter the OTP below to verify.
                    <br><b>NOTE: </b>Check your spam folder if you didn\'t receive the email.
                    </div>';
                else
                    echo '<div id="finalsuccess2" class="alert alert-success" style="margin-top: 20px; display: none;"></div>';
                if ($_SESSION['OTPck'] == -1)
                    echo '<div id="finalerror2" class="alert alert-danger" style="margin-top: 20px; display: none;">
                    Invalid OTP! Please try again.
                    </div>';
                else
                    echo '<div id="finalerror2" class="alert alert-danger" style="margin-top: 20px; display: none;"></div>';
                ?>
                <div class="form-group">
                    <label>OTP:&nbsp;<span style="color: red">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter the OTP" id="OTPTextBox" name="OTPTextBox" style="width: auto;">
                    <button id="submit2" type="submit2" name="submit2" class="btn btn-default btn-success" style="font-size: 16px; font-family: 'League Spartan'; padding-top: 10px; padding-right: 23px;"> &nbsp;
                        <i class="fa fa-check"></i> VERIFY
                    </button>
                </div>
            </div>
        </form>
        <script>
            $("document").ready(function () {
                let dropdown = $('#instituteList');
                dropdown.empty();
                dropdown.append('<option selected="true" disabled>Select your institute</option>');
                dropdown.prop('selectedIndex', 0);
                const url2 = '../institutelist.json';
                $.getJSON(url2, function (data) {
                    $.each(data, function (key, entry) {
                        dropdown.append($('<option></option>').attr('value', entry.abbreviation)
                            .text(entry.name));
                    })
                });
            });
        </script>
        <script src="forgot.js"></script>
        <?php
        if ($_SESSION['flag'] == 1)
            echo '<script type="text/javascript"> disableAll2(); </script>';
        if ($_SESSION['OTPck'] >= 0)
            echo '<script type="text/javascript">
            $("#finalerror1").fadeOut();
            $("#finalerror2").fadeOut();
            $("#finalsuccess1").fadeOut();
            $("#finalsuccess2").fadeIn();
            </script>';
        else
            echo '<script type="text/javascript">
            $("#finalerror1").fadeOut();
            $("#finalsuccess1").fadeOut();
            $("#finalsuccess2").fadeOut();
            $("#finalerror2").fadeIn();
            </script>';
        }
        mysqli_close ($conn);
        function clean($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        ?>
    </div>
</body>

</html>