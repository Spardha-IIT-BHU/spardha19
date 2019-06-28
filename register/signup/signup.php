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
    <title>Sign Up | Spardha'19</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="signup.css">
    <script>
    var code;
        function createCaptcha() {
            document.getElementById('captcha').innerHTML = "";
            var charsArray =
                "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@!#$%^&*";
            var lengthOtp = 6;
            var captcha = [];
            for (var i = 0; i < lengthOtp; i++) {
                //below code will not allow Repetition of Characters
                var index = Math.floor(Math.random() * charsArray.length + 1); //get the next character from the array
                if (captcha.indexOf(charsArray[index]) == -1)
                    captcha.push(charsArray[index]);
                else i--;
            }
            var canv = document.createElement("canvas");
            canv.id = "captcha";
            canv.width = 120;
            canv.height = 50;
            var ctx = canv.getContext("2d");
            ctx.font = "25px Georgia";
            ctx.strokeText(captcha.join(""), 0, 30);
            //storing captcha so that can validate you can save it somewhere else according to your specific requirements
            code = captcha.join("");
            document.getElementById("captcha").appendChild(canv); // adds the canvas to the body element
        }
    </script>
</head>
<?php
if (!isset($_SESSION['flag'])) {
    $_SESSION['flag'] = 0;
}
if ($_SESSION['flag'] == 2) {
    echo "<body style='background-color: transparent'>";
}
else {
    echo '<body style="background-color: transparent" onload="createCaptcha();">';
}
?>
    <div class="container signup-box">
        <h3 style="text-align: center; color: #6db549; font-family: 'League Spartan'; font-size: 30px;"> SIGN UP </h3>
        <hr>
        <?php
            echo '<div class="col-sm-12">';
            include("../db.php");
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
                $_SESSION['email'] = strtolower (mysqli_real_escape_string ($conn, clean($_POST["email"])));
                $_SESSION['username'] = strtolower (mysqli_real_escape_string ($conn, $_POST["username"]));
                $_SESSION['password1'] = mysqli_real_escape_string ($conn, $_POST["password1"]);
                $_SESSION['password2'] = mysqli_real_escape_string ($conn, $_POST["password2"]);
                $_SESSION['name'] = mysqli_real_escape_string ($conn, clean($_POST["name"]));
                $_SESSION['designation'] = mysqli_real_escape_string ($conn, clean($_POST["designation"]));
                $_SESSION['institute_name'] = strtoupper (mysqli_real_escape_string ($conn, clean($_POST["institute"])));
                $phone = mysqli_real_escape_string ($conn, clean($_POST["phone"]));
                if (strlen($phone) == 11) {
                    $phone = substr($phone, 1);
                }
                else if (strlen($phone) == 13) {
                    $phone = substr($phone, 3);
                }
                $_SESSION['phone'] = $phone;
                $institute_name = $_SESSION['institute_name'];
                $email = $_SESSION['email'];
                $username = $_SESSION['username'];
                $query = "SELECT * FROM `users` WHERE (`email`='$email' AND `status`='-1')";
                $result0 = mysqli_query ($conn, $query);
                $query = "SELECT * FROM `users` WHERE (`email`='$email' AND `status`='1')";
                $result1 = mysqli_query ($conn, $query);
                $query = "SELECT * FROM `users` WHERE (`username`='$username' AND (`status`='1' OR `status`='-1'))";
                $result2 = mysqli_query ($conn, $query);
                $query = "SELECT * FROM `users` WHERE (`institute_name`='$institute_name' AND `status`='1')";
                $result3 = mysqli_query ($conn, $query);
                $rows0 = mysqli_num_rows ($result0);
                $rows1 = mysqli_num_rows ($result1);
                $rows2 = mysqli_num_rows ($result2);
                $rows3 = mysqli_num_rows ($result3);
                // If account is deleted (status = 0), then the user can create another account, with same username or same email
                // But if the account is disabled (status = -1), then the user can't create account with same email. Another user is also not allowed create account with same username.
                if ($result0 && $result1 && $result2 && $result3) {
                    if ($rows0) {
                        $_SESSION['flag1'] = -1; // Account is disabled
                    }
                    else if ($rows1) {
                        $_SESSION['flag1'] = 1;
                    }
                    else if ($rows2) {
                        $_SESSION['flag1'] = 2;
                    }
                    else if ($rows3) {
                        $_SESSION['flag1'] = 3;
                    }
                    else {
                        $_SESSION['flag'] = 1;
                        include("signupotp.php");
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
                    $email = $_SESSION['email'];
                    $username = $_SESSION['username'];
                    $password = $_SESSION['password1'];
                    $name = $_SESSION['name'];
                    $designation = $_SESSION['designation'];
                    $institute_name = $_SESSION['institute_name'];
                    $phone = $_SESSION['phone'];
                    $flag = $_SESSION['flag'];
                    $query = "INSERT INTO `users` (`email`, `username`, `password`, `name`, `designation`, `institute_name`, `phone_number`, `status`) VALUES ('$email', '$username', '$password', '$name', '$designation', '$institute_name', '$phone', '1')";
                    $result = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `events` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `aquatics` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `athletics` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `badminton` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `basketball` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `boxing` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `carrom` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `chess` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `cricket` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `football` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `handball` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `hockey` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `kabaddi` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `kho-kho` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `powerlifting` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `tennis` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `table-tennis` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `squash` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `taekwondo` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `volleyball` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `weightlifting` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `f-aquatics` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `f-athletics` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `f-badminton` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `f-basketball` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `f-boxing` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `f-carrom` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `f-kabaddi` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `f-kho-kho` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `f-tennis` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `f-table-tennis` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `f-taekwondo` (`email`, `institute_name`) VALUES ('$email', '$institute_name')";
                    $result1 = mysqli_query ($conn, $query);
                    $query = "INSERT INTO `f-volleyball` (`email`, `institute_name`) VALUES ('$email', '$institute_name');";
                    $result1 = mysqli_query ($conn, $query);
                    if ($result && $result1) {
                        include("signupsuccess.php");
                    }
                    else {
                        echo "Could not sign up: " . mysqli_error($conn);
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
                <b>Already Signed Up?</b> Click <a href="../../register.php?page=login#content" target="_parent">here</a> to login.
            </div>
            <div class="alert alert-info">
                <strong>NOTE:</strong> Individual registrations are not entertained. Only one registration is allowed
                per college.
            </div>
        </div>
        <div class="col-sm-12 text-right">
            <span style="color: red">*&nbsp;Mandatory</span>
        </div>
        <form class="form-horizontal" role="form" method="post" action="" onsubmit="return validate(this);"
            novalidate>
            <div class="col-sm-12" style="margin-top: 10px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title text-center" style="color:#59ba00;">
                            <a style="color:#59ba00;"><i class="fa fa-user fa-lg"></i> Details </a>
                        </h4>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label heading-color" for="email">Email Address:&nbsp;<span
                                        style="color: red">*</span></label>
                                <div class="inputWithIcon">
                                    <input type="email" class="form-control" id="email" name="email" onfocus='onInput("email")' oninput='onInput("email")' onblur='onLeave("email")'
                                    value="<?php echo (isset($_SESSION['email']) ? $_SESSION['email'] : ''); ?>" placeholder="Enter Your Email Address" required><i
                                        class="fa fa-envelope fa-lg form-control-feedback"></i>
                                    <span id="email-icon"></span>
                                </div>
                                <div class="error" id="email-error">&nbsp;</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label heading-color" for="username">Username:&nbsp;<span
                                        style="color: red">*</span></label>
                                <div class="inputWithIcon">
                                    <input type="text" class="form-control" id="username" name="username" onfocus='onInput("username")' oninput='onInput("username")' onblur='onLeave("username")'
                                    value="<?php echo (isset($_SESSION['username']) ? $_SESSION['username'] : ''); ?>" placeholder="Enter your username" required>
                                        <i class="fa fa-at fa-lg form-control-feedback"></i>
                                    <span id="username-icon"></span>
                                </div>
                                <div class="error" id="username-error">&nbsp;</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label heading-color" for="password1">Password:&nbsp;<span
                                        style="color: red">*</span></label>
                                <div class="inputWithIcon">
                                    <input type="password" class="form-control" id="password1" name="password1" onfocus='onInput("password1")' oninput='onInput("password1")' onblur='onLeave("password1")'
                                    value="<?php echo (isset($_SESSION['password1']) ? $_SESSION['password1'] : ''); ?>" placeholder="Enter the Password" required><i class="fa fa-key fa-lg form-control-feedback"></i>
                                    <span id="password1-icon"></span>
                                </div>
                                <div class="error" id="password1-error">&nbsp;</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label heading-color" for="password2">Password Confirmation:&nbsp;<span
                                        style="color: red">*</span></label>
                                <div class="inputWithIcon">
                                    <input type="password" class="form-control" id="password2" name="password2" onfocus='onInput("password2")' oninput='onInput("password2")' onblur='onLeave("password2")'
                                    value="<?php echo (isset($_SESSION['password2']) ? $_SESSION['password2'] : ''); ?>" placeholder="Confirm your Password" required><i class="fa fa-key fa-lg form-control-feedback"></i>
                                    <span id="password2-icon"></span>
                                </div>
                                <div class="error" id="password2-error">&nbsp;</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label heading-color" for="name">Name:&nbsp;<span
                                        style="color: red">*</span></label>
                                <div class="inputWithIcon">
                                    <input type="text" class="form-control" id="name" name="name" onfocus='onInput("name")' oninput='onInput("name")' onblur='onLeave("name")'
                                    value="<?php echo (isset($_SESSION['name']) ? $_SESSION['name'] : ''); ?>" placeholder="Enter your Name" required><i class="fa fa-user fa-lg form-control-feedback"></i>
                                    <span id="name-icon"></span>
                                </div>
                                <div class="error" id="name-error">&nbsp;</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label heading-color" for="designation">Designation:&nbsp;<span
                                        style="color: red">*</span></label>
                                <div class="inputWithIcon">
                                    <input type="text" class="form-control" id="designation" name="designation" onfocus='onInput("designation")' oninput='onInput("designation")' onblur='onLeave("designation")'
                                    value="<?php echo (isset($_SESSION['designation']) ? $_SESSION['designation'] : ''); ?>" placeholder="Write your Designation" required>
                                        <i class="fa fa-briefcase fa-lg form-control-feedback"></i>
                                    <span id="designation-icon"></span>
                                </div>
                                <div class="error" id="designation-error">&nbsp;</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label heading-color" for="institute">Institute Name:&nbsp;<span
                                        style="color: red">*</span></label>
                                <div class="inputWithIcon">
                                    <input type="text" class="form-control" id="institute" name="institute" onfocus='onInput("institute")' oninput='onInput("institute")' onblur='onLeave("institute")'
                                    value="<?php echo (isset($_SESSION['institute_name']) ? $_SESSION['institute_name'] : ''); ?>" list="instituteList" placeholder="Select your Institute & City" required><i
                                        class="fa fa-building fa-lg form-control-feedback"></i>
                                    <span id="institute-icon"></span>
                                </div>
                                <div class="error" id="institute-error">&nbsp;</div>
                                <datalist id="instituteList"></datalist>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label heading-color" for="phone">Phone Number:&nbsp;<span
                                        style="color: red">*</span></label>
                                <div class="inputWithIcon">
                                    <input type="text" class="form-control" id="phone" name="phone" onfocus='onInput("phone")' oninput='onInput("phone")' onblur='onLeave("phone")'
                                    value="<?php echo (isset($_SESSION['phone']) ? $_SESSION['phone'] : ''); ?>" placeholder="Enter Your Phone No." required><i class="fa fa-phone fa-lg form-control-feedback"></i>
                                    <span id="phone-icon"></span>
                                </div>
                                <div class="error" id="phone-error">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <input type="checkbox" id="terms" name="terms" <?php echo isset($_POST['terms'])?'checked="checked"':'';?> required>
                    <label for="terms"> <span style="color: red">*</span> By submitting this form, you agree to abide by
                        the <a target="_blank">"Rules of Spardha 2019."</a></label>
                        <div class="row">
                            <div class="col-sm-12" style="text-align: center;">
                                <div id="captcha" style="padding-left: 50px;"></div>
                                <label>Captcha:&nbsp;<span style="color: red">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter the Captcha" id="captchaTextBox" name="captchaTextBox" style="width: auto;">
                            </div>
                        </div>
                    <?php
                    if ($_SESSION['flag1'] == -1) echo '<div class="alert alert-danger" style="margin-top: 20px;">Sorry, your account has been disabled. Contact us for any support on <a href="tel:+917238856930">+91&#8209;7238856930</a>.!</div>';
                    if ($_SESSION['flag1'] == 1) echo '<div class="alert alert-danger" style="margin-top: 20px;">Email <b>' . $email . '</b> is already registered. Please login <a href="../../register.php?page=login#content" target="_parent">here</a>.</div>';
                    if ($_SESSION['flag1'] == 2) echo '<div class="alert alert-danger" style="margin-top: 20px;">Username <b>' . $username . '</b> is already taken. Please choose another username.</div>';
                    if ($_SESSION['flag1'] == 3) {
                        $institute_name = $_SESSION['institute_name'];
                        $query = "SELECT * FROM `users` WHERE (`institute_name`='$institute_name' AND `status`='1')";
                        $result = mysqli_query ($conn, $query);
                        $rows = mysqli_num_rows ($result);
                        $row = mysqli_fetch_row($result);
                        if ($result) {
                            if ($rows) {
                                $contactemail = $row[1];
                                $contactname = $row[4];
                                $contactdesignation = $row[5];
                                $contactphone = $row[7];
                                echo '<div class="alert alert-danger" style="margin-top: 20px;"> <b>'.
                                $contactname.'</b> has already registered from your institute and we allow only one registration per institute.<br><br>
                                Please contact him/her for your institute registration:<br>
                                <b>Designation: </b>' . $contactdesignation . '<br>
                                <b>Email Address: </b>' . $contactemail . '<br>
                                <b>Phone Number: </b>' . $contactphone . '<br><br>
                                <b>In case of any difficulty or to resolve any disputes, kindly contact us on <a href="tel:+917238856930">+91&#8209;7238856930</a>.</b>
                                </div>';
                            }
                            else {
                                echo '<div class="alert alert-danger" style="margin-top: 20px;">
                                Some error occured!
                                </div>';
                            }
                        }
                        else if (mysqli_connect_errno()) {
                            echo "Could not connect: ". mysqli_connect_error($conn);
                            session_destroy();
                        }
                    }
                    ?>
                    <div id="finalerror1" class="alert alert-danger" style="margin-top: 20px; display: none;"></div>
                    <div id="finalsuccess1" class="alert alert-success" style="margin-top: 20px; display: none;"></div>
                    <hr>
                    <button id="submit1" type="submit1" name="submit1" class="btn btn-default btn-success btn-block" style="font-size: 16px; font-family: 'League Spartan'; padding-top: 10px;"> &nbsp;
                        <i class="fa fa-paper-plane"></i> SIGN UP
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
                    '</b><br> Please enter the OTP below to verify and confirm your registration.
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
        <script src="signup.js"></script>
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