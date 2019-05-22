<?php
$servername = "localhost";
$username = "ashishkr";
$password = "12345";
$database = "spardha19";
$conn = mysqli_connect($servername, $username, $password, $database);
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>
