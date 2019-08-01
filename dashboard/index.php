<?php
session_start();
if (!isset($_SESSION['valid_login'])) {
    $_SESSION['valid_login'] = 0;
}
if ($_SESSION['valid_login'] == 0) {
    header("Location: ../register.php?page=login#content");
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
for ($i = 8; $i <= 39; $i++) {
    if ($row[$i] == 'Y') $reg_count++;
} 
$_SESSION['reg_count'] = $reg_count;

?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="robots" content="noindex, nofollow">

    <title>Dashboard | Spardha'19 | India's Largest Games and Sports Festival</title>
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
                        <li class="active"><a href="index.php"><i class="fa fa-home" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Home</span></a></li>
                        <li><a href="registration.php"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Registration</span></a></li>
                        <li><a href="profile.php"><i class="fa fa-user" aria-hidden="true"></i><span class="hidden-xs hidden-sm">User Profile</span></a></li>
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
                                                    <div class="divider">
                                                    </div>
                                                    <a href="profile.php" class="view btn-sm active">View Profile</a>
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
                    <h1>Hello, <?php echo $_SESSION['name'] ?></h1>
                    <div class="row">
                        <div class="col-xs-12 gutter">

                            <div class="welcome-text">
                                <div class="text-justify">
                                    <h2>
                                    Note: Since we allow only college registration, so you're supposed to register for <?php echo $_SESSION['institute_name'] ?>.
                                    In case you want someone else to register for your college, you need to first delete your account in <u><a href="profile.php">User Profile</a></u> section,
                                    before another user can create an account with same college name.
                                    </h2>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-xs-12 gutter">

                            <div class="welcome-text">
                                <div class="text-justify">
                                    <h2>
                                        You've registered for <?php echo $_SESSION['reg_count']; ?> events. Click <u><a href="registration.php">here</a></u> to modify or add events.
                                    </h2>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-xs-12 gutter">

                            <div class="welcome-text">
                                <div class="text-justify">
                                    <h2>
                                        Please read the <u><a href="../pdf/RuleBook.pdf" target="_blank">Rule Book</a></u> before registering for events.
                                    </h2>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



    <!-- Modal -->
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
                    <button type="button" class="add-project" data-dismiss="modal">Save</button>
                </div>
            </div>

        </div>
    </div>
    <div class="cover-spin"></div>

    <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>

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

</script>


</body>
</html>
