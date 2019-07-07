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

$events = array('id', 'email', 'institute_name', 'boys', 'girls', 'officials', 'aquatics', 'athletics', 'badminton', 'basketball', 'boxing', 'carrom', 'chess', 'cricket', 'football', 'handball', 'hockey', 'kabaddi', 'kho-kho', 'powerlifting', 'squash', 'taekwondo', 'table-tennis', 'tennis', 'volleyball', 'weightlifting', 'f-aquatics', 'f-athletics', 'f-badminton', 'f-basketball', 'f-boxing', 'f-carrom', 'f-kabaddi', 'f-kho-kho', 'f-taekwondo', 'f-table-tennis', 'f-tennis', 'f-volleyball');

$id = $_SESSION['id'];

if (!isset($_SESSION['flag1'])) {
    $_SESSION['flag1'] = 0;         // Editing events
}

if (!isset($_SESSION['flag2'])) {
    $_SESSION['flag2'] = 0;         // Events Players
}

if (!isset($_SESSION['flag3'])) {
    $_SESSION['flag3'] = 0;         // Total boys, girls, officials
}

if (isset($_POST['submit1'])) {
    $query = "UPDATE `events` SET `aquatics`='N', `athletics`='N', `badminton`='N', `basketball`='N', `boxing`='N', `carrom`='N', `chess`='N', `cricket`='N', `football`='N', `handball`='N', `hockey`='N', `kabaddi`='N', `kho-kho`='N', `powerlifting`='N', `squash`='N', `taekwondo`='N', `table-tennis`='N', `tennis`='N', `volleyball`='N', `weightlifting`='N', `f-aquatics`='N', `f-athletics`='N', `f-badminton`='N', `f-basketball`='N', `f-boxing`='N', `f-carrom`='N', `f-kabaddi`='N', `f-kho-kho`='N', `f-taekwondo`='N', `f-table-tennis`='N', `f-tennis`='N', `f-volleyball`='N' WHERE `id` = '$id'; ";
    foreach($events as $ev) {
        $query .= "UPDATE `".$ev."` SET `opted`='N' WHERE `id`='$id'; ";
    }
    $result = mysqli_multi_query ($conn, $query);
    mysqli_close ($conn);
    include("../register/db.php");
    if (isset($_POST['opt'])) {
        $opt = $_POST['opt'];
        $query = "UPDATE `events` SET ";
        $query1 = "";
        foreach ($opt as $chk) {
            $query .= "`".$chk."`='Y', ";
            $query1 .= "UPDATE `".$chk."` SET `opted`='Y' WHERE `id`='$id'; ";
        }
        $query = substr($query, 0, -2);
        $query .= " WHERE `id` = '$id'; ";
        $query .= $query1;
        $result1 = mysqli_multi_query ($conn, $query);
        if ($result && $result1) {
            mysqli_close ($conn);
            include("../register/db.php");
            $_SESSION['flag1'] = 1;
        }
        else {
            echo "Could not process: " . mysqli_error($conn);
        }
    }
}

if (isset($_POST['submit2'])) {
    $newboys = mysqli_real_escape_string ($conn, $_POST["boys"]);
    $newgirls = mysqli_real_escape_string ($conn, $_POST["girls"]);
    $newofficials = mysqli_real_escape_string ($conn, $_POST["officials"]);
    $query = "UPDATE `events` SET `boys` = '$newboys', `girls` = '$newgirls', `officials` = '$newofficials' WHERE `id` = '$id'";
    $result = mysqli_query ($conn, $query);
    if ($result) {
        $_SESSION['flag3'] = 1;
    }
    else {
        echo "Could not process: " . mysqli_error($conn);
    }
}

foreach($events as $ev) {
    if (isset($_POST['submit_'.$ev])) {
        $query = "UPDATE `".$ev."` SET ";
        $i = 1;
        array_pop($_POST);
        foreach ($_POST as $val) {
            $player = mysqli_real_escape_string ($conn, $val);
            $query .= "`p" . $i . "`='" . $player ."', ";
            $i++;
        }
        $query = substr($query, 0, -2);
        $query .= " WHERE `id` = '$id'";
        $result = mysqli_query ($conn, $query);
        if ($result) {
            $_SESSION['flag2'] = 1;
        }
        else {
            echo "Could not process: " . mysqli_error($conn);
        }
    }
}

$query = "SELECT * FROM `events` WHERE (`id`='$id')";
$result = mysqli_query ($conn, $query);
$rows = mysqli_num_rows ($result);
$row = mysqli_fetch_row($result);
if (!$rows) {
    $_SESSION['valid_login'] = 0;
    header("Location: ../register.php?page=login#content");
}

$boys = $row[3];
$girls = $row[4];
$officials = $row[5];

$reg_count = 0;
for ($i = 6; $i <= 37; $i++) {
    if ($row[$i] == 'Y') $reg_count++;
}

function fillbox ($data) {
    $temp = 0;
    global $row;
    global $events;
    for ($i = 6; $i <= 37; $i++) {
        if ($row[$i] == 'Y' && $events[$i] == $data) $temp = 1;
    }
    if ($temp) {
        echo ' checked = "checked" ';
    }
}

$_SESSION['reg_count'] = $reg_count;

if (empty($_GET) || ($_GET['mode']!='edit' && $_GET['mode']!='view' && $_GET['mode']!='editinfo')) {
    header('Location: registration.php?mode=view');
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="robots" content="noindex, nofollow">

    <title>Registration | Spardha'19 | India's Largest Games and Sports Festival</title>
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
                        <li class="active"><a href="registration.php"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Registration</span></a></li>
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
                                    <!-- <li class="hidden-xs"><a href="registration.php" class="register-now">Register Now</a></li> -->
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
                    <!-- <h1>Hello, <?php echo $_SESSION['name'] ?></h1> -->
                    &nbsp;
                    <?php
                        if ($_SESSION['flag1'] == 1) {
                            echo '<div class="alert alert-success" style="font-size: 15px;">
                            Events Updated Successfully!
                            </div>';
                            $_SESSION['flag1'] = 0;
                        }
                        if ($_SESSION['flag2'] == 1) {
                            echo '<div class="alert alert-success" style="font-size: 15px;">
                            Players Updated Successfully!
                            </div>';
                            $_SESSION['flag2'] = 0;
                        }
                        if ($_SESSION['flag3'] == 1) {
                            echo '<div class="alert alert-success" style="font-size: 15px;">
                            Details Updated Successfully!
                            </div>';
                            $_SESSION['flag3'] = 0;
                        }
                    ?>
                    <div class="row">
                        <div class="col-xs-12 gutter">

                            <div class="welcome-text">
                                <?php if ($_GET['mode'] == 'view') { ?>
                                <div style="text-align: center; font-size: 15px; color: red;"><b><u>NOTE:</u></b> Enter the <b>number</b> of players in 'Aquatics' and'Athletics', whereas the <b>name</b> of players in other events.</div>
                                <?php } ?>
                                <div class="text-justify">
                                    <h2>
                                    <?php if ($_GET['mode'] == 'view') { ?>
                                    <div style="text-align: right;"><a href="registration.php?mode=edit">Edit</a></div>
                                    <?php
                                    $e1 = array();
                                    $e2 = array();
                                    $e3 = array();
                                    for ($i = 6; $i <= 25; $i++) {
                                        if ($i == 12) continue;
                                        if ($row[$i] == 'Y') array_push($e1, $events[$i]);
                                    }
                                    for ($i = 26; $i <= 37; $i++) {
                                        if ($row[$i] == 'Y') array_push($e2, $events[$i]);
                                    }
                                    if ($row[12] == 'Y') array_push($e3, $events[12]);
                                    ?>
                                    <div class="events-heading">BOYS</div>
                                    <table align="center" cellpadding="20" class="events-table" border="1">
                                        <tr>
                                            <th class="left-column" style="text-align: center;">Event Name</th>
                                            <th class="middle-column" style="text-align: center;">Players Name / Count</th>
                                            <th class="right-column" style="text-align: center;">Edit Players</th>
                                        </tr>
                                        <?php
                                        $query = "";
                                        foreach ($e1 as $event) {
                                            $query .= "SELECT * FROM `".$event."` WHERE (`id`='$id'); ";
                                        }
                                        mysqli_multi_query ($conn, $query);
                                        foreach ($e1 as $event) {
                                            echo '<tr>';
                                            echo '<td>'.clean($event).'</td>';
                                            $result = mysqli_store_result($conn);
                                            if (mysqli_more_results($conn)) mysqli_next_result($conn);
                                            $row = mysqli_fetch_row($result);
                                            $output = '';
                                            $i = 0;
                                            $no_player = 1;
                                            foreach ($row as $p) {
                                                if ($i >= 4)
                                                {
                                                    if ($p != '') {
                                                        $output .= $p . ', '; $no_player = 0;
                                                    } 
                                                }
                                                $i++;
                                            }
                                            if ($no_player == 0) $output = substr($output, 0, -2);
                                            if ($event == 'aquatics' && $row[4]) $output = "Total Number of Boys: ".$row[4];
                                            if ($event == 'athletics' && $row[4]) $output = "Total Number of Boys: ".$row[4];
                                            
                                            echo '<td>'. $output .'</td>';
                                            echo '<td><a href="#" class="register-now" data-toggle="modal" data-target="#player_' . $event . '" style="margin-right: 0">Add&nbsp;/&nbsp;Edit</a></td></td>';
                                            echo '</tr>';
                                        }
                                        if (sizeof($e1) == 0) {
                                            echo '<tr style="text-align: center;">';
                                            echo '<td> - </td>';
                                            echo '<td> - </td>';
                                            echo '<td> - </td>';
                                            echo '<tr>';
                                        }
                                        ?>
                                    </table>
                                    <br><div class="events-heading">GIRLS</div>
                                    <table align="center" cellpadding="20" class="events-table" border="1">
                                        <tr>
                                            <th class="left-column" style="text-align: center;">Event Name</th>
                                            <th class="middle-column" style="text-align: center;">Players Name / Count</th>
                                            <th class="right-column" style="text-align: center;">Edit Players</th>
                                        </tr>
                                        <?php
                                        $query = "";
                                        foreach ($e2 as $event) {
                                            $query .= "SELECT * FROM `".$event."` WHERE (`id`='$id'); ";
                                        }
                                        mysqli_multi_query ($conn, $query);
                                        foreach ($e2 as $event) {
                                            echo '<tr>';
                                            echo '<td>'.clean($event).'</td>';
                                            $result = mysqli_store_result($conn);
                                            if (mysqli_more_results($conn)) mysqli_next_result($conn);
                                            $row = mysqli_fetch_row($result);
                                            $output = '';
                                            $i = 0;
                                            $no_player = 1;
                                            foreach ($row as $p) {
                                                if ($i >= 4)
                                                {
                                                    if ($p != '') {
                                                        $output .= $p . ', '; $no_player = 0;
                                                    } 
                                                }
                                                $i++;
                                            }
                                            if ($no_player == 0) $output = substr($output, 0, -2);
                                            if ($event == 'f-aquatics' && $row[4]) $output = "Total Number of Girls: ".$row[4];
                                            if ($event == 'f-athletics' && $row[4]) $output = "Total Number of Girls: ".$row[4];
                                            
                                            echo '<td>'. $output .'</td>';
                                            echo '<td><a href="#" class="register-now" data-toggle="modal" data-target="#player_' . $event . '" style="margin-right: 0">Add&nbsp;/&nbsp;Edit</a></td></td>';
                                            echo '</tr>';
                                        }
                                        if (sizeof($e2) == 0) {
                                            echo '<tr style="text-align: center;">';
                                            echo '<td> - </td>';
                                            echo '<td> - </td>';
                                            echo '<td> - </td>';
                                            echo '<tr>';
                                        }
                                        ?>
                                    </table>
                                    <br><div class="events-heading">MIXED</div>
                                    <table align="center" cellpadding="20" class="events-table" border="1">
                                        <tr>
                                            <th class="left-column" style="text-align: center;">Event Name</th>
                                            <th class="middle-column" style="text-align: center;">Players Name / Count</th>
                                            <th class="right-column" style="text-align: center;">Edit Players</th>
                                        </tr>
                                        <?php
                                        $query = "";
                                        foreach ($e3 as $event) {
                                            $query .= "SELECT * FROM `".$event."` WHERE (`id`='$id'); ";
                                        }
                                        mysqli_multi_query ($conn, $query);
                                        foreach ($e3 as $event) {
                                            echo '<tr>';
                                            echo '<td>'.clean($event).'</td>';
                                            $result = mysqli_store_result($conn);
                                            if (mysqli_more_results($conn)) mysqli_next_result($conn);
                                            $row = mysqli_fetch_row($result);
                                            $output = '';
                                            $i = 0;
                                            $no_player = 1;
                                            foreach ($row as $p) {
                                                if ($i >= 4)
                                                {
                                                    if ($p != '') {
                                                        $output .= $p . ', '; $no_player = 0;
                                                    } 
                                                }
                                                $i++;
                                            }
                                            if ($no_player == 0) $output = substr($output, 0, -2);
                                            
                                            echo '<td>'. $output .'</td>';
                                            echo '<td><a href="#" class="register-now" data-toggle="modal" data-target="#player_' . $event . '" style="margin-right: 0">Add&nbsp;/&nbsp;Edit</a></td></td>';
                                            echo '</tr>';
                                        }
                                        mysqli_close ($conn);
                                        include("../register/db.php");
                                        if (sizeof($e3) == 0) {
                                            echo '<tr style="text-align: center;">';
                                            echo '<td> - </td>';
                                            echo '<td> - </td>';
                                            echo '<td> - </td>';
                                            echo '<tr>';
                                        }
                                        ?>
                                    </table>
                                    <br>
                                    <div style="text-align: right;"><a href="registration.php?mode=editinfo">Edit</a></div>
                                    <table align="center" cellpadding="20" class="profile-table">
                                        <tr>
                                            <td class="left-column" style="text-align: left;">Total Number of Boys </td>
                                            <td class="right-column">: &emsp;<?php if ($boys == '') echo '-'; else echo $boys; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="left-column" style="text-align: left;">Total Number of Girls </td>
                                            <td class="right-column">: &emsp;<?php if ($girls == '') echo '-'; else echo $girls ?></td>
                                        </tr>
                                        <tr>
                                            <td class="left-column" style="text-align: left; line-height: 1.5;">Total Number of officials accompanying the contingent </td>
                                            <td class="right-column">: &emsp;<?php if ($officials == '') echo '-'; else echo $officials ?></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </table>

                                    <?php } else if ($_GET['mode'] == 'editinfo') { ?>
                                    <form name="editform2" method="post" action="registration.php?mode=view">
                                        <table align="center" cellpadding="20" class="profile-table">
                                        <tr>
                                            <td class="left-column" style="text-align: left;">Total Number of Boys : </td>
                                            <td class="right-column"> <input type="text" class="form-control" id="boys" name="boys" placeholder="Enter total no. of Boys" value="<?php echo $boys ?>"></td>
                                        </tr>
                                        <tr>
                                            <td class="left-column" style="text-align: left;">Total Number of Girls : </td>
                                            <td class="right-column"> <input type="text" class="form-control" id="girls" name="girls" placeholder="Enter total no. of Girls" value="<?php echo $girls ?>"></td>
                                        </tr>
                                        <tr>
                                            <td class="left-column" style="text-align: left; line-height: 1.5;">Total Number of officials accompanying the contingent : </td>
                                            <td class="right-column"> <input type="text" class="form-control" id="officials" name="officials" placeholder="Enter total no. of Officials" value="<?php echo $officials ?>"></td>
                                        </tr>
                                        <tr>
                                            <td class="left-column"><a href="registration.php" class="register-now" style="margin-right: 0">Cancel</a></td>
                                            <td class="right-column"> <a href="javascript: submitform2()" class="register-now">Submit</a></td>
                                        </tr>
                                        </table>
                                    <input type="hidden" name="submit2">
                                    </form>


                                    <?php } else if ($_GET['mode'] == 'edit') { ?>
                                    
                                    <form name="editform1" method="post" action="registration.php?mode=view">
                                        <div class="col-sm-12">
                                            <div class="col-sm-6">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title text-center" style="color:#59ba00;">
                                                        <a data-toggle="collapse" href="#collapse1" class="" style="color:#59ba00;">
                                                        <i class="fa fa-male fa-lg"></i>&nbsp; <b>BOYS</b> </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse1" class="panel-collapse collapse in">
                                                        <div class="panel-body expanded-panel">
                                                            <div class="col-sm-6">
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("aquatics") ?>
                                                                            value="aquatics"><span>Aquatics</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("athletics") ?>
                                                                            value="athletics"><span>Athletics</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("badminton") ?>
                                                                            value="badminton"><span>Badminton</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("basketball") ?>
                                                                            value="basketball"><span>Basketball</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("boxing") ?>
                                                                            value="boxing"><span>Boxing</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("carrom") ?>
                                                                            value="carrom"><span>Carrom</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("cricket") ?>
                                                                            value="cricket"><span>Cricket</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("football") ?>
                                                                            value="football"><span>Football</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("handball") ?>
                                                                            value="handball"><span>Handball</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("hockey") ?>
                                                                            value="hockey"><span>Hockey</span></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("kabaddi") ?>
                                                                            value="kabaddi"><span>Kabaddi</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("kho-kho") ?>
                                                                            value="kho-kho"><span>Kho-Kho</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("powerlifting") ?>
                                                                            value="powerlifting"><span>Powerlifting</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("squash") ?>
                                                                            value="squash"><span>Squash</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("taekwondo") ?>
                                                                            value="taekwondo"><span>Taekwondo</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("tennis") ?>
                                                                            value="tennis"><span>Tennis</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("table-tennis") ?>
                                                                            value="table-tennis"><span>Table Tennis</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("volleyball") ?>
                                                                            value="volleyball"><span>Volleyball</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("weightlifting") ?>
                                                                            value="weightlifting"><span>Weightlifting</span></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title text-center" style="color:#59ba00;">
                                                        <a data-toggle="collapse" href="#collapse2" class="" style="color:#59ba00;">
                                                        <i class="fa fa-female fa-lg"></i>&nbsp; <b>GIRLS</b> </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse2" class="panel-collapse collapse in">
                                                        <div class="panel-body expanded-panel">
                                                            <div class="col-sm-6" style="padding-left: 0;">
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("f-aquatics") ?>
                                                                        value="f-aquatics"><span>Aquatics</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("f-athletics") ?>
                                                                        value="f-athletics"><span>Athletics</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("f-badminton") ?>
                                                                            value="f-badminton"><span>Badminton</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("f-basketball") ?>
                                                                            value="f-basketball"><span>Basketball</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("f-boxing") ?>
                                                                            value="f-boxing"><span>Boxing</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("f-carrom") ?>
                                                                            value="f-carrom"><span>Carrom</span></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6" style="padding-left: 0;">
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("f-kabaddi") ?>
                                                                            value="f-kabaddi"><span>Kabaddi</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("f-kho-kho") ?>
                                                                            value="f-kho-kho"><span>Kho-Kho</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("f-taekwondo") ?>
                                                                            value="f-taekwondo"><span>Taekwondo</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("f-tennis") ?>
                                                                            value="f-tennis"><span>Tennis</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("f-table-tennis") ?>
                                                                            value="f-table-tennis"><span>Table Tennis</span></label>
                                                                </div>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("f-volleyball") ?>
                                                                            value="f-volleyball"><span>Volleyball</span></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title text-center" style="color:#59ba00;">
                                                        <a data-toggle="collapse" href="#collapse3" class="" style="color:#59ba00;">
                                                        <i class="fas fa-user-friends"></i>&nbsp; <b>MIXED</b> </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse3" class="panel-collapse collapse in">
                                                        <div class="panel-body expanded-panel">
                                                            <div class="col-sm-6" style="padding-left: 0;">
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" class="event" name="opt[]" <?php fillbox("chess") ?>
                                                                            value="chess"><span>Chess</span></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="text-align: center;">
                                            <a href="registration.php" class="register-now" style="margin-right: 0">Cancel</a>
                                            <a href="javascript: submitform1()" class="register-now">Submit</a>
                                        </div>
                                        <input type="hidden" name="submit1">
                                        </form>
                                        <?php } ?>
                                    </h2>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="player_aquatics" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Aquatics [BOYS]</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `aquatics` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <tr>
                                <td><b>Number of Boys: </b></td>
                                <td><input type="text" class="form-control" name="1" placeholder="Enter total number of boys" value="<?php echo $prow[4] ?>"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_aquatics" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_athletics" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Athletics [BOYS]</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `athletics` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <tr>
                                <td><b>Number of Boys: </b></td>
                                <td><input type="text" class="form-control" name="1" placeholder="Enter total number of boys" value="<?php echo $prow[4] ?>"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_athletics" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_badminton" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Badminton [BOYS] (Max: 5)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `badminton` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <tr>
                                <td><input type="text" class="form-control" name="1" placeholder="Player 1" value="<?php echo $prow[4] ?>"></td>
                                <td><input type="text" class="form-control" name="2" placeholder="Player 2" value="<?php echo $prow[5] ?>"></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control" name="3" placeholder="Player 3" value="<?php echo $prow[6] ?>"></td>
                                <td><input type="text" class="form-control" name="4" placeholder="Player 4" value="<?php echo $prow[7] ?>"></td>
                            </tr>
                        </table>
                        <table align="center" cellpadding="20">
                            <tr>
                                <td><input type="text" class="form-control" name="5" placeholder="Player 5" value="<?php echo $prow[8] ?>"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_badminton" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_basketball" class="modal fade" role="dialog" style="top: 8%;">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Basketball [BOYS] (Max: 12)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `basketball` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 6; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_basketball" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_boxing" class="modal fade" role="dialog" style="top: 0;">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Boxing [BOYS]</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `boxing` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <tr>
                                <td><b>Maximum 2 players are allowed in each weight category</b></td>
                            </tr>
                        </table>
                        <table align="center" cellpadding="20">
                            <tr>
                                <td><b>46kg - 49kg : </b></td>
                                <td><input type="text" class="form-control" name="1" placeholder="Player 1" value="<?php echo $prow[4] ?>"></td>
                                <td><input type="text" class="form-control" name="2" placeholder="Player 2" value="<?php echo $prow[5] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>49kg - 52kg : </b></td>
                                <td><input type="text" class="form-control" name="3" placeholder="Player 1" value="<?php echo $prow[6] ?>"></td>
                                <td><input type="text" class="form-control" name="4" placeholder="Player 2" value="<?php echo $prow[7] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>52kg - 56kg : </b></td>
                                <td><input type="text" class="form-control" name="5" placeholder="Player 1" value="<?php echo $prow[8] ?>"></td>
                                <td><input type="text" class="form-control" name="6" placeholder="Player 2" value="<?php echo $prow[9] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>56kg - 60kg : </b></td>
                                <td><input type="text" class="form-control" name="7" placeholder="Player 1" value="<?php echo $prow[10] ?>"></td>
                                <td><input type="text" class="form-control" name="8" placeholder="Player 2" value="<?php echo $prow[11] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>60kg - 64kg : </b></td>
                                <td><input type="text" class="form-control" name="9" placeholder="Player 1" value="<?php echo $prow[12] ?>"></td>
                                <td><input type="text" class="form-control" name="10" placeholder="Player 2" value="<?php echo $prow[13] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>64kg - 69kg : </b></td>
                                <td><input type="text" class="form-control" name="11" placeholder="Player 1" value="<?php echo $prow[14] ?>"></td>
                                <td><input type="text" class="form-control" name="12" placeholder="Player 2" value="<?php echo $prow[15] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>69kg - 75kg : </b></td>
                                <td><input type="text" class="form-control" name="13" placeholder="Player 1" value="<?php echo $prow[16] ?>"></td>
                                <td><input type="text" class="form-control" name="14" placeholder="Player 2" value="<?php echo $prow[17] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>75kg - 81kg : </b></td>
                                <td><input type="text" class="form-control" name="15" placeholder="Player 1" value="<?php echo $prow[18] ?>"></td>
                                <td><input type="text" class="form-control" name="16" placeholder="Player 2" value="<?php echo $prow[19] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>81kg - 91kg : </b></td>
                                <td><input type="text" class="form-control" name="17" placeholder="Player 1" value="<?php echo $prow[20] ?>"></td>
                                <td><input type="text" class="form-control" name="18" placeholder="Player 2" value="<?php echo $prow[21] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>91kg and above : </b></td>
                                <td><input type="text" class="form-control" name="19" placeholder="Player 1" value="<?php echo $prow[22] ?>"></td>
                                <td><input type="text" class="form-control" name="20" placeholder="Player 2" value="<?php echo $prow[23] ?>"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_boxing" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_carrom" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Carrom [BOYS] (Max: 4)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `carrom` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 2; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_carrom" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_chess" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Chess [MIXED] (Max: 6)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `chess` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 3; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_chess" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_cricket" class="modal fade" role="dialog" style="top: 0;">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Cricket [BOYS] (Max: 16)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `cricket` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 8; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_cricket" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_football" class="modal fade" role="dialog" style="top: 0;">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Football [BOYS] (Max: 16)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `football` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 8; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_football" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_handball" class="modal fade" role="dialog" style="top: 8%;">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Handball [BOYS] (Max: 12)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `handball` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 6; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_handball" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_hockey" class="modal fade" role="dialog" style="top: 0;">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Hockey [BOYS] (Max: 16)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `hockey` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 8; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_hockey" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_kabaddi" class="modal fade" role="dialog" style="top: 8%;">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Kabaddi [BOYS] (Max: 12)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `kabaddi` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 6; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_kabaddi" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_kho-kho" class="modal fade" role="dialog" style="top: 8%;">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Kho Kho [BOYS] (Max: 12)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `kho-kho` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 6; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_kho-kho" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_powerlifting" class="modal fade" role="dialog" style="top: 10%;">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Powerlifting [BOYS]</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `powerlifting` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <tr>
                                <td><b>Maximum 2 players are allowed in each weight category</b></td>
                            </tr>
                        </table>
                        <table align="center" cellpadding="20">
                            <tr>
                                <td><b>Below 59kg : </b></td>
                                <td><input type="text" class="form-control" name="1" placeholder="Player 1" value="<?php echo $prow[4] ?>"></td>
                                <td><input type="text" class="form-control" name="2" placeholder="Player 2" value="<?php echo $prow[5] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>59kg - 66kg : </b></td>
                                <td><input type="text" class="form-control" name="3" placeholder="Player 1" value="<?php echo $prow[6] ?>"></td>
                                <td><input type="text" class="form-control" name="4" placeholder="Player 2" value="<?php echo $prow[7] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>66kg - 74kg : </b></td>
                                <td><input type="text" class="form-control" name="5" placeholder="Player 1" value="<?php echo $prow[8] ?>"></td>
                                <td><input type="text" class="form-control" name="6" placeholder="Player 2" value="<?php echo $prow[9] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>74kg - 83kg : </b></td>
                                <td><input type="text" class="form-control" name="7" placeholder="Player 1" value="<?php echo $prow[10] ?>"></td>
                                <td><input type="text" class="form-control" name="8" placeholder="Player 2" value="<?php echo $prow[11] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>83kg and above : </b></td>
                                <td><input type="text" class="form-control" name="9" placeholder="Player 1" value="<?php echo $prow[12] ?>"></td>
                                <td><input type="text" class="form-control" name="10" placeholder="Player 2" value="<?php echo $prow[13] ?>"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_powerlifting" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_tennis" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Tennis [BOYS] (Max: 4)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `tennis` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 2; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_tennis" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_squash" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Squash [BOYS] (Max: 4)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `squash` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 2; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_squash" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_table-tennis" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Table Tennis [BOYS] (Max: 4)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `table-tennis` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 2; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_table-tennis" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_taekwondo" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Taekwondo [BOYS] (Max: 8)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `taekwondo` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 4; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_taekwondo" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_volleyball" class="modal fade" role="dialog" style="top: 8%;">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Volleyball [BOYS] (Max: 12)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `volleyball` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 6; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_volleyball" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_weightlifting" class="modal fade" role="dialog" style="top: 10%;">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Weightlifting [BOYS]</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `weightlifting` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <tr>
                                <td><b>Maximum 2 players are allowed in each weight category</b></td>
                            </tr>
                        </table>
                        <table align="center" cellpadding="20">
                            <tr>
                                <td><b>Below 56kg : </b></td>
                                <td><input type="text" class="form-control" name="1" placeholder="Player 1" value="<?php echo $prow[4] ?>"></td>
                                <td><input type="text" class="form-control" name="2" placeholder="Player 2" value="<?php echo $prow[5] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>56kg - 62kg : </b></td>
                                <td><input type="text" class="form-control" name="3" placeholder="Player 1" value="<?php echo $prow[6] ?>"></td>
                                <td><input type="text" class="form-control" name="4" placeholder="Player 2" value="<?php echo $prow[7] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>62kg - 69kg : </b></td>
                                <td><input type="text" class="form-control" name="5" placeholder="Player 1" value="<?php echo $prow[8] ?>"></td>
                                <td><input type="text" class="form-control" name="6" placeholder="Player 2" value="<?php echo $prow[9] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>69kg - 77kg : </b></td>
                                <td><input type="text" class="form-control" name="7" placeholder="Player 1" value="<?php echo $prow[10] ?>"></td>
                                <td><input type="text" class="form-control" name="8" placeholder="Player 2" value="<?php echo $prow[11] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>77kg - 85kg : </b></td>
                                <td><input type="text" class="form-control" name="9" placeholder="Player 1" value="<?php echo $prow[12] ?>"></td>
                                <td><input type="text" class="form-control" name="10" placeholder="Player 2" value="<?php echo $prow[13] ?>"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_weightlifting" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_f-aquatics" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Aquatics [GIRLS]</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `f-aquatics` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <tr>
                                <td><b>Number of Girls: </b></td>
                                <td><input type="text" class="form-control" name="1" placeholder="Enter total number of girls" value="<?php echo $prow[4] ?>"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_f-aquatics" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_f-athletics" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Athletics [GIRLS]</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `f-athletics` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <tr>
                                <td><b>Number of Girls: </b></td>
                                <td><input type="text" class="form-control" name="1" placeholder="Enter total number of girls" value="<?php echo $prow[4] ?>"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_f-athletics" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_f-badminton" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Badminton [GIRLS] (Max: 4)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `f-badminton` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 2; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_f-badminton" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_f-basketball" class="modal fade" role="dialog" style="top: 8%;">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Basketball [GIRLS] (Max: 12)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `f-basketball` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 6; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_f-basketball" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_f-boxing" class="modal fade" role="dialog" style="top: 0;">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Boxing [GIRLS]</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `f-boxing` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <tr>
                                <td><b>Maximum 2 players are allowed in each weight category</b></td>
                            </tr>
                        </table>
                        <table align="center" cellpadding="20">
                            <tr>
                                <td><b>46kg - 49kg : </b></td>
                                <td><input type="text" class="form-control" name="1" placeholder="Player 1" value="<?php echo $prow[4] ?>"></td>
                                <td><input type="text" class="form-control" name="2" placeholder="Player 2" value="<?php echo $prow[5] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>49kg - 52kg : </b></td>
                                <td><input type="text" class="form-control" name="3" placeholder="Player 1" value="<?php echo $prow[6] ?>"></td>
                                <td><input type="text" class="form-control" name="4" placeholder="Player 2" value="<?php echo $prow[7] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>52kg - 56kg : </b></td>
                                <td><input type="text" class="form-control" name="5" placeholder="Player 1" value="<?php echo $prow[8] ?>"></td>
                                <td><input type="text" class="form-control" name="6" placeholder="Player 2" value="<?php echo $prow[9] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>56kg - 60kg : </b></td>
                                <td><input type="text" class="form-control" name="7" placeholder="Player 1" value="<?php echo $prow[10] ?>"></td>
                                <td><input type="text" class="form-control" name="8" placeholder="Player 2" value="<?php echo $prow[11] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>60kg - 64kg : </b></td>
                                <td><input type="text" class="form-control" name="9" placeholder="Player 1" value="<?php echo $prow[12] ?>"></td>
                                <td><input type="text" class="form-control" name="10" placeholder="Player 2" value="<?php echo $prow[13] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>64kg - 69kg : </b></td>
                                <td><input type="text" class="form-control" name="11" placeholder="Player 1" value="<?php echo $prow[14] ?>"></td>
                                <td><input type="text" class="form-control" name="12" placeholder="Player 2" value="<?php echo $prow[15] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>69kg - 75kg : </b></td>
                                <td><input type="text" class="form-control" name="13" placeholder="Player 1" value="<?php echo $prow[16] ?>"></td>
                                <td><input type="text" class="form-control" name="14" placeholder="Player 2" value="<?php echo $prow[17] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>75kg - 81kg : </b></td>
                                <td><input type="text" class="form-control" name="15" placeholder="Player 1" value="<?php echo $prow[18] ?>"></td>
                                <td><input type="text" class="form-control" name="16" placeholder="Player 2" value="<?php echo $prow[19] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>81kg - 91kg : </b></td>
                                <td><input type="text" class="form-control" name="17" placeholder="Player 1" value="<?php echo $prow[20] ?>"></td>
                                <td><input type="text" class="form-control" name="18" placeholder="Player 2" value="<?php echo $prow[21] ?>"></td>
                            </tr>
                            <tr>
                                <td><b>91kg and above : </b></td>
                                <td><input type="text" class="form-control" name="19" placeholder="Player 1" value="<?php echo $prow[22] ?>"></td>
                                <td><input type="text" class="form-control" name="20" placeholder="Player 2" value="<?php echo $prow[23] ?>"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_f-boxing" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_f-carrom" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Carrom [GIRLS] (Max: 4)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `f-carrom` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 2; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_f-carrom" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_f-kabaddi" class="modal fade" role="dialog" style="top: 8%;">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Kabaddi [GIRLS] (Max: 12)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `f-kabaddi` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 6; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_f-kabaddi" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_f-kho-kho" class="modal fade" role="dialog" style="top: 8%;">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Kho Kho [GIRLS] (Max: 12)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `f-kho-kho` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 6; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_f-kho-kho" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_f-tennis" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Tennis [GIRLS] (Max: 4)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `f-tennis` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 2; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_f-tennis" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_f-table-tennis" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Table Tennis [GIRLS] (Max: 3)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `f-table-tennis` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <tr>
                                <td><input type="text" class="form-control" name="1" placeholder="Player 1" value="<?php echo $prow[4] ?>"></td>
                                <td><input type="text" class="form-control" name="2" placeholder="Player 2" value="<?php echo $prow[5] ?>"></td>
                            </tr>
                        </table>
                        <table align="center" cellpadding="20">
                            <tr>
                                <td><input type="text" class="form-control" name="3" placeholder="Player 3" value="<?php echo $prow[6] ?>"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_f-table-tennis" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_f-taekwondo" class="modal fade" role="dialog">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Taekwondo [GIRLS] (Max: 8)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `f-taekwondo` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 4; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_f-taekwondo" class="register-now">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="player_f-volleyball" class="modal fade" role="dialog" style="top: 8%;">
        <div class="modal-dialog modal-">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header login-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Players - Volleyball [GIRLS] (Max: 12)</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM `f-volleyball` WHERE (`id`='$id')";
                        $result = mysqli_query ($conn, $query);
                        $prow = mysqli_fetch_row($result);
                        ?>
                        <table align="center" cellpadding="20">
                            <?php for ($i = 1; $i <= 6; $i++) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="<?php echo $i*2-1 ?>" placeholder="<?php echo "Player ".($i*2-1) ?>" value="<?php echo $prow[$i*2+2] ?>"></td>
                                <td><input type="text" class="form-control" name="<?php echo $i*2 ?>" placeholder="<?php echo "Player ".$i*2 ?>" value="<?php echo $prow[$i*2+3] ?>"></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_f-volleyball" class="register-now">Submit</button>
                    </div>
                </form>
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
    function submitform1() {
        document.editform1.submit();
    }
    function submitform2() {
        document.editform2.submit();
    }


</script>
<?php
function clean($data) {
    if (substr($data, 0, 2)=='f-') $data = substr($data, 2);
    $data = str_replace('-', ' ', $data);
    $data = ucwords($data);
    return $data;
}
?>


</body>
</html>