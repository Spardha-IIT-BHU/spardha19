<?php
        echo '<div class="col-sm-12" style="padding-top: 50px;">';
            echo '<div class="alert alert-success">';
                echo '<strong>Congrats!</strong> Your account is successfully created! Please login by clicking <a href="../../register.php?page=login#content" target="_parent">here</a>.';
            echo '</div>';
            echo '<img src="../../images/logos/spardha.png" style="width: 100%; max-width: 500px; display: block; margin: 0 auto 10px;">';
            $email = $_SESSION['email'];
            $query = "SELECT * FROM `users` WHERE (`email`='$email' AND `status`='1')";
            $result = mysqli_query ($conn, $query);
            $row = mysqli_fetch_row($result);
            if ($result) {
              echo 'The details received are: <br>';
              echo '<div class="alert alert-info" style="margin: 15px 30px 15px 30px;">';
                  echo '<table>';
                    echo '<tr>';
                          echo '<td>EMAIL ADDRESS:</td>';
                          echo '<td>' . $row[1] . '</td>';
                      echo '</tr>';
                      echo '<tr>';
                          echo '<td>USERNAME:</td>';
                          echo '<td>' . $row[2] . '</td>';
                      echo '</tr>';
                      echo '<tr>';
                          echo '<td>NAME:</td>';
                          echo '<td>' . $row[4] . '</td>';
                      echo '</tr>';
                      echo '<tr>';
                          echo '<td>DESIGNATION:</td>';
                          echo '<td>' . $row[5] . '</td>';
                      echo '</tr>';
                      echo '<tr>';
                          echo '<td>INSTITUTE NAME:</td>';
                          echo '<td>' . $row[6] . '</td>';
                      echo '</tr>';
                      echo '<tr>';
                          echo '<td>PHONE NUMBER:</td>';
                          echo '<td>' . $row[7] . '</td>';
                      echo '</tr>';
                  echo '</table>';
              echo '</div>';
              echo '<div style="margin: 0 30px 0 30px;">';
                  include("signupmail.php");
              echo '</div>';
            }
            else {
              echo "Unable to retrieve the details: " . mysqli_error($conn);
            }
    session_destroy();
?>