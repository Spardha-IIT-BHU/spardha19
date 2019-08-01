<?php
session_start();
$mode = 'profile_logout';
include("../register/sql/users_save_log.php");
if (!isset($_SESSION['valid_login'])) {
    $_SESSION['valid_login'] = 0;
}
if ($_SESSION['valid_login'] == 0) {
    header("Location: ../register.php?page=login#content");
}
session_destroy();  
session_start();
$_SESSION['is_logout'] = 1;
header("Location: ../register.php?page=login#content");
?>  
