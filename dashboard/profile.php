<?php
session_start();
if (!isset($_SESSION['valid_login'])) {
    $_SESSION['valid_login'] = 0;
}
if ($_SESSION['valid_login'] == 0) {
    header("Location: ../register.php?page=login#content");
}
if (!isset($_SESSION['flag1'])) {
    $_SESSION['flag1'] = 0;     // Username changed = 1, Username exist = -1
}
if (!isset($_SESSION['flag2'])) {
    $_SESSION['flag2'] = 0;     // Password changed = 1, Wrong Old password = -1
}
if (!isset($_SESSION['flag3'])) {
    $_SESSION['flag3'] = 0;
}
include("../register/db.php");
$email = $_SESSION['email'];
$query = "SELECT * FROM `users` WHERE (`email`='$email' AND `status`='1')";
$result = mysqli_query ($conn, $query);
$rows = mysqli_num_rows ($result);
$row = mysqli_fetch_row($result);
if (!$rows) {
    $_SESSION['valid_login'] = 0;
    header("Location: ../register.php?page=login#content");
}
$_SESSION['id'] = $row[0];
$_SESSION['email'] = $row[1];
$_SESSION['username'] = $row[2];
$_SESSION['password'] = $row[3];
$_SESSION['name'] = $row[4];
$_SESSION['designation'] = $row[5];
$_SESSION['institute_name'] = $row[6];
$_SESSION['phone_number'] = $row[7];

$id = $_SESSION['id'];

$query = "SELECT * FROM `events` WHERE (`id`='$id')";
$result = mysqli_query ($conn, $query);
$rows = mysqli_num_rows ($result);
$row = mysqli_fetch_row($result);
if (!$rows) {
    $_SESSION['valid_login'] = 0;
    header("Location: ../register.php?page=login#content");
}
$reg_count = 0;
for ($i = 6; $i <= 37; $i++) {
    if ($row[$i] == 'Y') $reg_count++;
} 
$_SESSION['reg_count'] = $reg_count;

$_SESSION['newusername'] = '';
$_SESSION['newpassword'] = '';
if (empty($_GET) || ($_GET['mode']!='edit' && $_GET['mode']!='view')) {
    header('Location: profile.php?mode=view');
}
if (isset($_POST['submit1'])) {
    $_SESSION['newusername'] = strtolower (mysqli_real_escape_string ($conn, $_POST["newusername"]));
    $newusername = $_SESSION['newusername'];
    $query = "SELECT * FROM `users` WHERE (`username`='$newusername' AND (`status`='-1' OR `status`='1'))";
    $result = mysqli_query ($conn, $query);
    $rows = mysqli_num_rows ($result);
    if ($result) {
        if ($rows) {
            $_SESSION['flag1'] = -1;
        }
        else {
            $query = "UPDATE `users` SET `username` = '$newusername' WHERE `email` = '$email' AND `status`='1'";
            $result = mysqli_query ($conn, $query);
            if ($result) {
                $_SESSION['username'] = $newusername;
                $_SESSION['flag1'] = 1;
            }
            else {
                echo "Could not change username: " . mysqli_error($conn);
            }
        }
    }
    else if (mysqli_connect_errno()) {
        echo "Could not connect: ". mysqli_connect_error($conn);
        session_destroy();
    }
}

else if (isset($_POST['submit2'])) {
    $oldpassword = mysqli_real_escape_string ($conn, $_POST["oldpassword"]);
    if ($oldpassword != $_SESSION['password']) {
        $_SESSION['flag2'] = -1;
    }
    else {
        $_SESSION['newpassword'] = mysqli_real_escape_string ($conn, $_POST["newpassword1"]);
        $newpassword = $_SESSION['newpassword'];
        $email = $_SESSION['email'];
        $query = "UPDATE `users` SET `password` = '$newpassword' WHERE `email` = '$email' AND `status`='1'";
        $result = mysqli_query ($conn, $query);
        if ($result) {
            $_SESSION['password'] = $newpassword;
            $_SESSION['flag2'] = 1;
        }
        else {
            echo "Could not process: " . mysqli_error($conn);
            session_destroy();
        }
    }
}

else if (isset($_POST['submit3'])) {
    $newname = mysqli_real_escape_string ($conn, clean($_POST["newname"]));
    $newdesignation = mysqli_real_escape_string ($conn, clean($_POST["newdesignation"]));
    $newphone = mysqli_real_escape_string ($conn, clean($_POST["newphone"]));
    if (strlen($newphone) == 11) {
        $newphone = substr($newphone, 1);
    }
    else if (strlen($newphone) == 13) {
        $newphone = substr($newphone, 3);
    }
    $query = "UPDATE `users` SET `name` = '$newname', `designation` = '$newdesignation', `phone_number` = '$newphone' WHERE `email` = '$email' AND `status`='1'";
    $result = mysqli_query ($conn, $query);
    if ($result) {
        $_SESSION['name'] = $newname;
        $_SESSION['designation'] = $newdesignation;
        $_SESSION['phone_number'] = $newphone;
        $_SESSION['flag3'] = 1;
    }
    else {
        echo "Could not process: " . mysqli_error($conn);
    }
}

else if (isset($_POST['submit_delete'])) {
    $deletepassword = mysqli_real_escape_string ($conn, $_POST["deletepassword"]);
    if ($deletepassword != $_SESSION['password']) {
        $_SESSION['flag2'] = -1;
    }
    else {
        include("deleteprofile.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="robots" content="noindex, nofollow">

    <title>Profile | Spardha'19 | India's Largest Games and Sports Festival</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="jquery-1.11.1.min.js"></script>
    <script src="bootstrap.min.js"></script>
    <link rel="shortcut icon" href="../images/logos/spardha-small.png" type="image/x-icon">
    <link rel="icon" href="../images/logos/spardha-small.png" type="image/x-icon">
</head>
<body class="home">

    <div class="container-fluid display-table">
        <div class="row display-table-row">
            <div class="col-md-2 col-sm-1 hidden-xs display-table-cell v-align box" id="navigation">
                <div class="logo">
                    <a hef="home.html"><img src="../images/logos/spardha-small-white.png" alt="spardha-logo" class="hidden-xs hidden-sm spardha-logo">
                        <img src="../images/logos/spardha-small-white.png" alt="spardha-logo" class="visible-xs visible-sm circle-logo">
                    </a>
                </div>
                <div class="navi">
                    <ul>
                        <li><a href="index.php"><i class="fa fa-home" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Home</span></a></li>
                        <li><a href="registration.php"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Registration</span></a></li>
                        <li class="active"><a href="profile.php"><i class="fa fa-user" aria-hidden="true"></i><span class="hidden-xs hidden-sm">User Profile</span></a></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Logout</span></a></li>
                    </ul>
                </div>
            </div>
            <!-- <div class="col-md-2 col-sm-1 hidden-xs display-table-cell v-align box"></div> -->
            <div class="col-md-10 col-sm-11 display-table-cell v-align">
                <!--<button type="button" class="slide-toggle">Slide Toggle</button> -->
                <div class="row">
                    <header>
                        <div class="col-xs-7">
                            <nav class="navbar-default pull-left">
                                <div class="navbar-header">
                                    <button type="button" class="navbar-toggle collapsed" data-toggle="offcanvas" data-target="#side-menu" aria-expanded="false">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                </div>
                            </nav>
                            <span class="events-reg hidden-xs"><h4> &ensp;Events Registered: <?php echo $_SESSION['reg_count']; ?> </h4></span>
                        </div>
                        <div class="col-xs-5">
                            <div class="header-rightside">
                                <ul class="list-inline header-top pull-right">
                                    <li class="hidden-xs"><a href="registration.php" class="register-now">Register Now</a></li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="../images/icons/user.png" alt="user">
                                            <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <div class="navbar-content">
                                                    <span><?php echo $_SESSION['name'] ?></span>
                                                    <p class="text-muted small">
                                                        <?php echo $_SESSION['email'] ?>
                                                    </p>
                                                    <!-- <div class="divider">
                                                    </div>
                                                    <a href="profile.php" class="view btn-sm active">View Profile</a> -->
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </header>
                </div>
                <div class="user-dashboard">
                    <!-- <h1>Hello, <?php echo $_SESSION['name'] ?></h1> -->
                    &nbsp;
                    <?php
                        if ($_SESSION['flag1'] == 1) {
                            echo '<div class="alert alert-success" style="font-size: 15px;">
                            Your username has been successfully changed!
                            </div>';
                            $_SESSION['flag1'] = 0;
                        }
                        else if ($_SESSION['flag1'] == -1) {
                            echo '<div class="alert alert-danger" style="font-size: 15px;">
                            Username <b>'. $_SESSION['newusername']. '</b> already exists. Please choose another username.
                            </div>';
                            $_SESSION['flag1'] = 0;
                        }
                        if ($_SESSION['flag2'] == 1) {
                            echo '<div class="alert alert-success" style="font-size: 15px;">
                            Your password has been successfully changed!
                            </div>';
                            $_SESSION['flag2'] = 0;
                        }
                        else if ($_SESSION['flag2'] == -1) {
                            echo '<div class="alert alert-danger" style="font-size: 15px;">
                            Old Password does not match. Please re-enter it.
                            </div>';
                            $_SESSION['flag2'] = 0;
                        }
                        if ($_SESSION['flag3'] == 1) {
                            echo '<div class="alert alert-success" style="font-size: 15px;">
                            Your changes have been saved.
                            </div>';
                            $_SESSION['flag3'] = 0;
                        }
                    ?>
                    <div class="row">
                        <div class="col-xs-12 gutter">

                            <div class="welcome-text" style="line-height: 1;">
                                <div class="text-justify">
                                    <h2>

                                    <?php if ($_GET['mode'] == 'view') { ?>

                                    <div style="text-align: right;"><a href="profile.php?mode=edit">Edit</a></div>
                                    <table align="center" cellpadding="20" class="profile-table">
                                        <tr>
                                            <td class="left-column">Name: </td>
                                            <td class="right-column"><?php echo $_SESSION['name'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="left-column">Email: </td>
                                            <td class="right-column"><?php echo $_SESSION['email'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="left-column">Username: </td>
                                            <td class="right-column"><?php echo $_SESSION['username'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="left-column">Designation: </td>
                                            <td class="right-column"><?php echo $_SESSION['designation'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="left-column">Institute Name: </td>
                                            <td class="right-column"><?php echo $_SESSION['institute_name'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="left-column">Phone Number: </td>
                                            <td class="right-column"><?php echo $_SESSION['phone_number'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="left-column"></td>
                                            <td class="right-column"></td>
                                        </tr>
                                        <tr>
                                            <td class="left-column"><a href="#" class="register-now" data-toggle="modal" data-target="#change_username" style="margin-right: 0">Change&nbsp;Username</a></td>
                                            <td class="right-column"><a href="#" class="register-now" data-toggle="modal" data-target="#change_password">Change&nbsp;Password</a></td>
                                        </tr>
                                    </table>
                                    <?php } else if ($_GET['mode'] == 'edit') {
                                        $name = $_SESSION['name'];
                                        $phone_number = $_SESSION['phone_number'];
                                        $designation = $_SESSION['designation'];
                                        ?>
                                        &nbsp;

                                    <form name="editform" method="post" action="profile.php?mode=view" onsubmit="return validate_edit(this);" novalidate>
                                        <table align="center" cellpadding="20" class="profile-table">
                                            <tr>
                                                <td class="left-column">Name: </td>
                                                <td class="right-column"><input type="text" class="form-control" id="newname" name="newname" placeholder="Enter your Name" value="<?php echo $name ?>" required></td>
                                            </tr>
                                            <tr>
                                                <td class="left-column">Email: </td>
                                                <td class="right-column"><?php echo $_SESSION['email'] ?></td>
                                            </tr>
                                            <tr>
                                                <td class="left-column">Username: </td>
                                                <td class="right-column"><?php echo $_SESSION['username'] ?></td>
                                            </tr>
                                            <tr>
                                                <td class="left-column">Designation: </td>
                                                <td class="right-column"><input type="text" class="form-control" id="newdesignation" name="newdesignation" placeholder="Enter your Designation" value="<?php echo $designation ?>" required></td>
                                            </tr>
                                            <tr>
                                                <td class="left-column">Institute Name: </td>
                                                <td class="right-column"><?php echo $_SESSION['institute_name'] ?></td>
                                            </tr>
                                            <tr>
                                                <td class="left-column">Phone Number: </td>
                                                <td class="right-column"><input type="text" class="form-control" id="newphone" name="newphone" placeholder="Enter your Phone Number" value="<?php echo $phone_number ?>" required></td>
                                            </tr>
                                            <tr>
                                                <td class="left-column"></td>
                                                <td class="right-column" style="color: red; font-size: 15px;"><div id="error-edit"></div></td>
                                            </tr>
                                            <tr>
                                                <td class="left-column"><a href="profile.php" class="register-now" style="margin-right: 0">Cancel</a></td>
                                                <td class="right-column"><a href="javascript: submitform()" class="register-now">Submit</a></td>
                                            </tr>
                                        </table>
                                        <input type="hidden" name="submit3">
                                    </form>

                                    <?php } ?>
                                    </h2>
                                </div>
                                
                            </div>
                            <?php if ($_GET['mode'] == 'view') { ?>
                            <div style="padding-top: 20px; text-align: center;">
                                <a href="#" class="delete-profile" data-toggle="modal" data-target="#delete_profile">Delete Profile</a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



    <!-- Modal -->

    <div id="delete_profile" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header" style="background: crimson;">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Delete Profile</h4>
                </div>
                <form method="post" action="" onsubmit="return validate_delete(this);" novalidate>
                    <div class="modal-body" style="font-size: 15px;">
                        Note that this action cannot be undone. Are you sure to delete your profile?
                        <table align="center" cellpadding="20">
                            <tr>
                                <td class="left-column"><b>Enter Password:</b></td>
                                <td><input type="password" class="form-control" id="deletepassword" name="deletepassword" placeholder="Re-enter your password" required></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="color: red;"><div id="error-delete"></div></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_delete" class="delete-profile">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="change_username" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Change Username</h4>
                </div>
                <form method="post" action="" onsubmit="return validate_username(this);" novalidate>
                    <div class="modal-body">
                        <table align="center" cellpadding="20">
                            <tr>
                                <td class="left-column"><b>New Username:</b></td>
                                <td><input type="text" class="form-control" id="newusername" name="newusername" placeholder="Enter New Username" required></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="color: red;"><div id="error-username"></div></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit1" class="register-now">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div id="change_password" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Change Password</h4>
                </div>
                <form method="post" action="" onsubmit="return validate_password(this);" novalidate>
                    <div class="modal-body">
                        <table align="center" cellpadding="20">
                            <tr>
                                <td class="left-column"><b>Old Password:</b></td>
                                <td><input type="password" class="form-control" id="oldpassword" name="oldpassword" placeholder="Enter Old Password" required></td>
                            </tr>
                            <tr>
                                <td class="left-column"><b>New Password:</b></td>
                                <td><input type="password" class="form-control" id="newpassword1" name="newpassword1" placeholder="Enter New Password" required></td>
                            </tr>
                            <tr>
                                <td class="left-column"><b>New Password confirmation:</b></td>
                                <td><input type="password" class="form-control" id="newpassword2" name="newpassword2" placeholder="Confirm New Password" required></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="color: red;"><div id="error-password"></div></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit2" class="register-now">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div id="add_project" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Add Project</h4>
                </div>
                <div class="modal-body">
                    <input type="text" placeholder="Project Title" name="name">
                    <input type="text" placeholder="Post of Post" name="mail">
                    <input type="text" placeholder="Author" name="passsword">
                    <textarea placeholder="Desicrption"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="cancel" data-dismiss="modal">Close</button>
                    <button type="button" class="register-now" data-dismiss="modal">Save</button>
                </div>
            </div>

        </div>
    </div>

    <div class="cover-spin"></div>

    <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <script src="profile.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('[data-toggle="offcanvas"]').click(function(){
        $("#navigation").toggleClass("hidden-xs");
        });
    });

    $(document).ready(function(){
        resizeDiv();
    });

    window.onresize = function(event) {
        resizeDiv();
    }

    function resizeDiv() {
        vph = $(window).height();
        $('#navigation').css({'height': vph + 'px'});
    }

	$(window).load(function() {
		$(".cover-spin").show(0).fadeOut("slow");
    });
    function submitform() {
        if (validate_edit())
            document.editform.submit();
    } 

</script>
<?php
function clean($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>


</body>
</html>