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
    <style>
        .form-horizontal .form-group {
            padding-left: 15px;
            padding-right: 15px;
            padding-top: 10px;
        }

        .btn-block {
            display: block;
            width: 70%;
            margin: auto;
        }

        .panel-default {
            background-color: #f9f9f9;
        }

        .panel-heading {
            background-color: #f2f2f2 !important;
        }

        .inputWithIcon input[type="text"] {
            padding-left: 40px;
            padding-right: 30px;
        }

        .inputWithIcon input[type="email"] {
            padding-left: 40px;
            padding-right: 30px;
        }

        .inputWithIcon {
            position: relative;
        }

        .inputWithIcon i {
            position: absolute;
            left: 2px;
            top: 2px;
            padding: 9px 8px;
            color: #aaa;
            transition: 0.3s;
        }

        .inputWithIcon input[type="text"]:focus+i {
            color: dodgerBlue;
        }

        .inputWithIcon input[type="email"]:focus+i {
            color: dodgerBlue;
        }

        .inputWithIcon.inputIconBg i {
            background-color: #aaa;
            color: #fff;
            padding: 9px 4px;
            border-radius: 4px 0 0 4px;
        }

        .inputWithIcon.inputIconBg input[type="text"]:focus+i {
            color: #fff;
            background-color: dodgerBlue;
        }

        .inputWithIcon.inputIconBg input[type="email"]:focus+i {
            color: #fff;
            background-color: dodgerBlue;
        }

        .error {
            color: #a94442;
            margin-top: 5px;
            padding-left: 40px;
        }
        td {
            padding: 5px;
        }
        canvas {
            pointer-events:none;
        }
        input[type=text] {
            padding: 8px 15px;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
    </style>
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
            $flag = 0;
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $flag = 1;
                $name = mysqli_real_escape_string ($conn, clean($_POST["name"]));
                $designation = mysqli_real_escape_string ($conn, clean($_POST["designation"]));
                $institute_name = mysqli_real_escape_string ($conn, clean($_POST["institute"]));
                $acad_year = mysqli_real_escape_string ($conn, clean($_POST["year"]));
                $email = mysqli_real_escape_string ($conn, clean($_POST["email"]));
                $phone = mysqli_real_escape_string ($conn, clean($_POST["phone"]));
                $checkbox1=$_POST["opt"];  
                $chk="";
                foreach($checkbox1 as $chk1)  
                {  
                    $chk .= $chk1.", ";  
                }  
                $opted_events = mysqli_real_escape_string ($conn, clean($chk));
                $query = "SELECT * FROM `registration2019` WHERE (`institute_name`='$institute_name')";
                $result = mysqli_query ($conn, $query);
                if ($result) {
                    $rows = mysqli_num_rows ($result);
                    if ($rows) {
                        $flag = 2;
                    }
                    $query = "INSERT INTO `registration2019` (`name`, `designation`, `institute_name`, `acad_year`, `email`, `phone`, `opted_events`) VALUES ('$name', '$designation', '$institute_name', '$acad_year', '$email', '$phone', '$opted_events')";
                    $result = mysqli_query ($conn, $query);
                    if ($result) {
                        include("success.php");
                    }
                    else echo "Could not register: " . mysqli_error($conn);
                }
                else {
                    echo "Could not connect: ". mysqli_error($conn);
                }
            }
            echo '</div>';
            if ($flag == 0) {
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
                                Select Events&nbsp;<span style="color: red">*</span></a>
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
                            <div class="col-sm-6" style="margin-top: 30px;">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title text-center" style="color:#59ba00;">
                                            <a data-toggle="collapse" href="#collapse2" class="" style="color:#59ba00;">
                                                <i class="fa fa-female fa-lg"></i> Events for Girls </a>
                                        </h4>
                                    </div>
                                    <div id="collapse2" class="panel-collapse collapse in">
                                        <div class="panel-body expanded-panel">
                                            <div class="col-sm-6">
                                                <div class="checkbox">
                                                    <label><input type="checkbox" class="event" name="opt[]"
                                                            value="F-Athletics"><span>Athletics</span></label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" class="event" name="opt[]"
                                                            value="F-Badminton"><span>Badminton</span></label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" class="event" name="opt[]"
                                                            value="F-Basketball"><span>Basketball</span></label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" class="event" name="opt[]"
                                                            value="F-Boxing"><span>Boxing</span></label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" class="event" name="opt[]"
                                                            value="F-Carrom"><span>Carrom</span></label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" class="event" name="opt[]"
                                                            value="F-Kabaddi"><span>Kabaddi</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="checkbox">
                                                    <label><input type="checkbox" class="event" name="opt[]"
                                                            value="F-Kho-kho"><span>Kho-Kho</span></label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" class="event" name="opt[]"
                                                            value="F-Taekwondo"><span>Taekwondo</span></label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" class="event" name="opt[]"
                                                            value="F-Tennis"><span>Tennis</span></label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" class="event" name="opt[]"
                                                            value="F-Table-tennis"><span>Table Tennis</span></label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" class="event" name="opt[]"
                                                            value="F-Volleyball"><span>Volleyball</span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group ">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms"> <span style="color: red">*</span> By submitting this form, you agree to abide by
                        the <a target="_blank">"Rules of Spardha 2019."</a></label>
                        <div class="row">
                            <div class="col-sm-12" style="text-align: center;">
                                <div id="captcha" style="padding-left: 50px;"></div>
                                <label>Captcha:&nbsp;<span style="color: red">*</span></label>
                                <input type="text" placeholder="Enter the Captcha" id="captchaTextBox">
                            </div>
                        </div>
                    <div id="finalerror" class="alert alert-danger" style="margin-top: 20px; display: none;"></div>
                    <hr>
                    <button type="submit" name="submit" class="btn btn-default btn-success btn-block"> &nbsp;
                        <i class="fa fa-paper-plane"></i> Register
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