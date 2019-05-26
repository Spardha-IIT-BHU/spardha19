<?php
        echo '<div class="col-sm-12" style="padding-top: 50px;">';
        if ($flag == 4) {
          echo '<div class="alert alert-warning">';
          echo '<strong>NOTE:</strong> Someone already registered from your institute but we have saved your response.';
          echo '</div>';
        }
            echo '<div class="alert alert-success">';
                echo '<strong>Congrats!</strong> You have successfully registered for Spardha 2019!';
            echo '</div>';
            echo '<img src="../images/logos/spardha.png" style="width: 100%; max-width: 500px; display: block; margin: 0 auto 10px;">';
            $id = mysqli_insert_id ($conn);
            $email = $_SESSION['email'];
            $query = "SELECT * FROM `registration2019` WHERE (`email`='$email')";
            $result = mysqli_query ($conn, $query);
            $row = mysqli_fetch_row($result);
            if ($result) {
              echo 'The details received are: <br>';
              echo '<div class="alert alert-info" style="margin: 15px 30px 15px 30px;">';
                  echo '<table>';
                      echo '<tr>';
                          echo '<td>REGISTRATION NO:</td>';
                          echo '<td>S19-' . sprintf("%03d", $row[0]) . '</td>';
                      echo '</tr>';
                      echo '<tr>';
                          echo '<td>NAME:</td>';
                          echo '<td>' . $row[1] . '</td>';
                      echo '</tr>';
                      echo '<tr>';
                          echo '<td>DESIGNATION:</td>';
                          echo '<td>' . $row[2] . '</td>';
                      echo '</tr>';
                      echo '<tr>';
                          echo '<td>INSTITUTE NAME:</td>';
                          echo '<td>' . $row[3] . '</td>';
                      echo '</tr>';
                      echo '<tr>';
                          echo '<td>ACADEMIC YEAR:</td>';
                          echo '<td>' . $row[4] . '</td>';
                      echo '</tr>';
                      echo '<tr>';
                          echo '<td>EMAIL ADDRESS:</td>';
                          echo '<td>' . $row[5] . '</td>';
                      echo '</tr>';
                      echo '<tr>';
                          echo '<td>PHONE NUMBER:</td>';
                          echo '<td>' . $row[6] . '</td>';
                      echo '</tr>';
                      echo '<tr>';
                          echo '<td>EVENTS:</td>';
                          echo '<td>' . $row[7] . '</td>';
                      echo '</tr>';
                      echo '<tr>';
                          echo '<td>GIRLS EVENTS:</td>';
                          echo '<td>' . $row[8] . '</td>';
                      echo '</tr>';
                  echo '</table>';
              echo '</div>';
              echo '<div style="margin: 0 30px 0 30px;">';
                  echo '<strong>IMPORTANT: </strong>Please note above details for any future reference.';
                  include("mail.php");
              echo '</div>';
            }
            else {
              echo "Unable to retrieve the details: " . mysqli_error($conn);
            }
session_destroy();
?>