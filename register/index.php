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
    <title>Registration | Spardha'19</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css">
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<body>";
}
else {
    echo '<body onload="createCaptcha();">';
}
?>
    <div class="container"
        style="padding: 1em 2em 3em 2em; margin-top: 50px; max-width: 55em; border-radius: 5.2px; box-shadow: 0px 3px 10px -2px rgba(0, 0, 0, 0.2);">
        <h3 style="text-align:center;color:#7ed321"> COLLEGE REGISTRATION </h3>
        <hr>
        <?php
            echo '<div class="col-sm-12">';
            include("db.php");
            if (!isset($_SESSION['flag'])) {
                $_SESSION['flag'] = 0;
            }
            if (!isset($_SESSION['OTPck'])) {
                $_SESSION['OTPck'] = 0;
            }
            if (!isset($_SESSION['email'])) {
                $_SESSION['email'] = "";
            }
            if (!isset($_SESSION['email_reg'])) {
                $_SESSION['email_reg'] = 0;
            }
            if ($_SESSION['flag'] == 0 && $_SERVER["REQUEST_METHOD"] == "POST") {
                $_SESSION['flag'] = 1;
                $_SESSION['name'] = mysqli_real_escape_string ($conn, clean($_POST["name"]));
                $_SESSION['designation'] = mysqli_real_escape_string ($conn, clean($_POST["designation"]));
                $_SESSION['institute_name'] = mysqli_real_escape_string ($conn, clean($_POST["institute"]));
                $_SESSION['acad_year'] = mysqli_real_escape_string ($conn, clean($_POST["year"]));
                $_SESSION['email'] = mysqli_real_escape_string ($conn, clean($_POST["email"]));
                $phone = mysqli_real_escape_string ($conn, clean($_POST["phone"]));
                if (strlen($phone) == 11) {
                    $phone = substr($phone, 1);
                }
                else if (strlen($phone) == 13) {
                    $phone = substr($phone, 3);
                }
                $_SESSION['phone'] = $phone;
                $checkbox1 = $_POST["opt"];  
                $chk = "";
                $chkF = "";
                foreach($checkbox1 as $chk1)  
                {
                    if (substr($chk1,0,2) == "F-") {
                        $chkF .= $chk1 . ", ";
                    }
                    else {
                        $chk .= $chk1.", "; 
                    }
                }
                $chk = rtrim($chk, ", ");
                $chkF = rtrim($chkF, ", ");
                $institute_name = $_SESSION['institute_name'];
                $email = $_SESSION['email'];
                $_SESSION['opted_events'] = mysqli_real_escape_string ($conn, clean($chk));
                $_SESSION['girls_events'] = mysqli_real_escape_string ($conn, clean($chkF));
                $query = "SELECT * FROM `registration2019` WHERE (`institute_name`='$institute_name')";
                $result1 = mysqli_query ($conn, $query);
                $query = "SELECT * FROM `registration2019` WHERE (`email`='$email')";
                $result2 = mysqli_query ($conn, $query);
                if ($result1 && $result2) {
                    $rows1 = mysqli_num_rows ($result1);
                    $rows2 = mysqli_num_rows ($result2);
                    if ($rows1) {
                        $_SESSION['flag'] = 3;
                    }
                    if ($rows2) {
                        $_SESSION['email_reg'] = 1;
                    }
                }
                else {
                    echo "Could not connect: ". mysqli_error($conn);
                    session_destroy();
                }
                include("otp.php");
            }
            else if ($_SESSION['flag'] == 1 && $_SERVER["REQUEST_METHOD"] == "POST") {
                $otp_user = mysqli_real_escape_string ($conn, clean($_POST["OTPTextBox"]));
                if ($otp_user == $_SESSION['otp']) {
                    $_SESSION['flag'] = 2;
                    $name = $_SESSION['name'];
                    $designation = $_SESSION['designation'];
                    $institute_name = $_SESSION['institute_name'];
                    $acad_year = $_SESSION['acad_year'];
                    $email = $_SESSION['email'];
                    $phone = $_SESSION['phone'];
                    $opted_events = $_SESSION['opted_events'];
                    $girls_events = $_SESSION['girls_events'];
                    $flag = $_SESSION['flag'];
                    if ($_SESSION['email_reg'] == 0)
                        $query = "INSERT INTO `registration2019` (`name`, `designation`, `institute_name`, `acad_year`, `email`, `phone`, `opted_events`, `girls_events`) VALUES ('$name', '$designation', '$institute_name', '$acad_year', '$email', '$phone', '$opted_events', '$girls_events')";
                    else {
                        $query1 = "SELECT * FROM `registration2019` WHERE (`email`='$email')";
                        $result1 = mysqli_query ($conn, $query1);
                        $row = mysqli_fetch_row($result1);
                        $query = "UPDATE `registration2019` SET `id`='$row[0]', `name`='$name', `designation`='$designation', `institute_name`='$institute_name', `acad_year`='$acad_year', `email`='$email', `phone`='$phone', `opted_events`='$opted_events', `girls_events`='$girls_events' WHERE `email`='$email'";
                    }
                    $result = mysqli_query ($conn, $query);
                    if ($result) {
                        include("success.php");
                    }
                    else {
                        echo "Could not register: " . mysqli_error($conn);
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
                                <label class="control-label heading-color" for="name">Name:&nbsp;<span
                                        style="color: red">*</span></label>
                                <div class="inputWithIcon">
                                    <input type="text" class="form-control" id="name" name="name" onfocus='onInput("name")' oninput='onInput("name")' onblur='onLeave("name")'
                                        placeholder="Enter your Name" required><i class="fa fa-user fa-lg form-control-feedback"></i>
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
                                        placeholder="Write your Designation" required>
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
                                        list="instituteList" placeholder="Enter your Institute & City" required><i
                                        class="fa fa-building fa-lg form-control-feedback"></i>
                                    <span id="institute-icon"></span>
                                </div>
                                <div class="error" id="institute-error">&nbsp;</div>
                                <datalist id="instituteList"></datalist>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label heading-color" for="year">Academic Year:&nbsp;<span
                                        style="color: red">*</span></label>
                                <div class="inputWithIcon">
                                    <input type="text" class="form-control" id="year" name="year" onfocus='onInput("year")' oninput='onInput("year")' onblur='onLeave("year")'
                                        placeholder="Enter your Academic Year" required><i
                                        class="fa fa-graduation-cap fa-lg form-control-feedback"></i>
                                    <span id="year-icon"></span>
                                </div>
                                <div class="error" id="year-error">&nbsp;</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label heading-color" for="email">Email Address:&nbsp;<span
                                        style="color: red">*</span></label>
                                <div class="inputWithIcon">
                                    <input type="email" class="form-control" id="email" name="email" onfocus='onInput("email")' oninput='onInput("email")' onblur='onLeave("email")'
                                        placeholder="Enter Your Email Address" required><i
                                        class="fa fa-envelope fa-lg form-control-feedback"></i>
                                    <span id="email-icon"></span>
                                </div>
                                <div class="error" id="email-error">&nbsp;</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label heading-color" for="phone">Phone Number:&nbsp;<span
                                        style="color: red">*</span></label>
                                <div class="inputWithIcon">
                                    <input type="text" class="form-control" id="phone" name="phone" onfocus='onInput("phone")' oninput='onInput("phone")' onblur='onLeave("phone")'
                                        placeholder="Enter Your Phone No." required><i class="fa fa-phone fa-lg form-control-feedback"></i>
                                    <span id="phone-icon"></span>
                                </div>
                                <div class="error" id="phone-error">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" style="margin-top: 30px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title text-center" style="color:#59ba00;">
                            <a data-toggle="collapse" href="#collapse1" class="" style="color:#59ba00;">
                                Select Events for Participation&nbsp;<span style="color: red">*</span></a>
                        </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse in">
                        <div class="panel-body expanded-panel">
                            <div class="col-sm-3">
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Aquatics"><span>Aquatics</span></label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Athletics"><span>Athletics</span></label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Badminton"><span>Badminton</span></label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Basketball"><span>Basketball</span></label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Boxing"><span>Boxing</span></label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Carrom"><span>Carrom</span></label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Chess"><span>Chess</span></label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Cricket"><span>Cricket</span></label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Football"><span>Football</span></label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Handball"><span>Handball</span></label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Hockey"><span>Hockey</span></label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Kabaddi"><span>Kabaddi</span></label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Kho-kho"><span>Kho-Kho</span></label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Powerlifting"><span>Powerlifting</span></label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Squash"><span>Squash</span></label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Taekwondo"><span>Taekwondo</span></label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Tennis"><span>Tennis</span></label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Table-tennis"><span>Table Tennis</span></label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Volleyball"><span>Volleyball</span></label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" class="event" name="opt[]"
                                            value="Weightlifting"><span>Weightlifting</span></label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <label><input type="checkbox" class="event" name="opt[]"
                                                value="F-Athletics"><span>Athletics (GIRLS)</span></label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" class="event" name="opt[]"
                                                value="F-Badminton"><span>Badminton (GIRLS)</span></label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" class="event" name="opt[]"
                                                value="F-Basketball"><span>Basketball (GIRLS)</span></label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" class="event" name="opt[]"
                                                value="F-Boxing"><span>Boxing (GIRLS)</span></label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" class="event" name="opt[]"
                                                value="F-Carrom"><span>Carrom (GIRLS)</span></label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" class="event" name="opt[]"
                                                value="F-Kabaddi"><span>Kabaddi (GIRLS)</span></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <label><input type="checkbox" class="event" name="opt[]"
                                                value="F-Kho-kho"><span>Kho-Kho (GIRLS)</span></label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" class="event" name="opt[]"
                                                value="F-Taekwondo"><span>Taekwondo (GIRLS)</span></label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" class="event" name="opt[]"
                                                value="F-Tennis"><span>Tennis (GIRLS)</span></label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" class="event" name="opt[]"
                                                value="F-Table-tennis"><span>Table Tennis (GIRLS)</span></label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" class="event" name="opt[]"
                                                value="F-Volleyball"><span>Volleyball (GIRLS)</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms"> <span style="color: red">*</span> By submitting this form, you agree to abide by
                        the <a target="_blank">"Rules of Spardha 2019."</a></label>
                        <div class="row">
                            <div class="col-sm-12" style="text-align: center;">
                                <div id="captcha" style="padding-left: 50px;"></div>
                                <label>Captcha:&nbsp;<span style="color: red">*</span></label>
                                <input type="text" placeholder="Enter the Captcha" id="captchaTextBox" name="captchaTextBox">
                            </div>
                        </div>
                    <div id="finalerror1" class="alert alert-danger" style="margin-top: 20px; display: none;"></div>
                    <div id="finalsuccess1" class="alert alert-success" style="margin-top: 20px; display: none;"></div>
                    <hr>
                    <button id="submit1" type="submit1" name="submit1" class="btn btn-default btn-success btn-block"> &nbsp;
                        <i class="fa fa-paper-plane"></i> Register
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
                else if ($_SESSION['OTPck'] == 1)
                    echo '<div id="finalsuccess2" class="alert alert-success" style="margin-top: 20px; display: none;">
                    OTP has been re-sent to your email address: <b>' . $_SESSION['email'] .
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
                    <input type="text" placeholder="Enter the OTP" id="OTPTextBox" name="OTPTextBox">
                    <button id="submit2" type="submit2" name="submit2" class="btn btn-default btn-success"> &nbsp;
                        <i class="fa fa-check"></i> Verify
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
                const url2 = 'institutelist.json';
                $.getJSON(url2, function (data) {
                    $.each(data, function (key, entry) {
                        dropdown.append($('<option></option>').attr('value', entry.abbreviation)
                            .text(entry.name));
                    })
                });
            });
        </script>
        <script src="form.js"></script>
        <?php
        if ($_SESSION['flag'] == 1)
            echo '<script type="text/javascript"> disableAll2(); </script>';
        }
        if ($_SESSION['OTPck'] >= 0)
            echo '<script type="text/javascript">
            hideError(1);
            hideError(2);
            hideSuccess(1);
            $("#finalsuccess2").fadeIn();
            </script>';
        else
            echo '<script type="text/javascript">
            hideError(1);
            hideSuccess(1);
            hideSuccess(2);
            $("#finalerror2").fadeIn();
            </script>';
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