<?php
        echo '<div class="col-sm-12" style="padding-top: 50px;">';
            echo '<div class="alert alert-success">';
                echo '<strong>Congrats!</strong> Your password is changed successfully! Please login by clicking <a href="../../register.php?page=login#content" target="_parent">here</a>.';
            echo '</div>';
            include("forgotmail.php");
    session_destroy();
?>
