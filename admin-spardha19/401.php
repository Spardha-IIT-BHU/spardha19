<?php
    header("HTTP/1.1 401 Unauthorized");
    $mode = "Logout";
    include("save_log.php");
?>
Successfully Logged out!! Go to <a href="../..">Homepage</a>
