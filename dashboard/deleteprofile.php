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
$query = "UPDATE `users` SET `status` = '0' WHERE `email` = '$email' AND `status`='1'";
$result = mysqli_query ($conn, $query);
if ($result) {
    session_destroy();  
    session_start();
    $_SESSION['is_delete'] = 1;
    header("Location: ../register.php?page=login#content");
}
else {
    echo "Could not process: " . mysqli_error($conn);
    session_destroy();
}
?>