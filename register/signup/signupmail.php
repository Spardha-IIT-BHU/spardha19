<?php

date_default_timezone_set('Etc/UTC');

// Edit this path if PHPMailer is in a different location.
require '../../PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;
$mail->isSMTP();

/*
 * Server Configuration
 */

include("../mailer.php");

/*
 * Message Configuration
 */

$mail->setFrom('info@spardha.co.in', "Spardha'19 IIT (BHU) Varanasi"); // Set the sender of the message.
$mail->addAddress($row[1], $row[4]); // Set the recipient of the message.
$mail->Subject = "Sign Up Complete || Spardha'19 IIT (BHU) Varanasi"; // The subject of the message.

/*
 * Message Content - Choose simple text or HTML email
 */
 
// Choose to send either a simple text email...
// $mail->Body = 'You have been successfully registered!!'; // Set a plain text body.

// ... or send an email with HTML.
$mail->AddEmbeddedImage('../../images/logos/spardha.png', 'spardhalogo');
$mail->msgHTML('Hello ' . $row[4] . ',<br><br><img src="cid:spardhalogo" style="width: 100%; max-width: 250px; display: block; margin: 0 auto 20px;">
<div style="text-align: center; font-size: 17px;"><strong>Greetings from Team Spardha!!</strong></div><br>
<div style="text-align: center; font-size: 15px;"><strong>Congrats!</strong> Your account is successfully created! <br><br>
The details received are: </div><br>
<table align="center" style="margin-top: 2px; font-size: 15px;">
<tr>
<td>EMAIL ADDRESS:</td>
<td>' . $row[1] . '</td>
</tr>
<tr>
<td>USERNAME:</td>
<td>' . $row[2] . '</td>
</tr>
<tr>
<td>NAME:</td>
<td>' . $row[4] . '</td>
</tr>
<tr>
<td>DESIGNATION:</td>
<td>' . $row[5] . '</td>
</tr>
<tr>
<td>INSTITUTE NAME:</td>
<td>' . $row[6] . '</td>
</tr>
<tr>
<td>PHONE NUMBER:</td>
<td>' . $row[7] . '</td>
</tr>
</table>
<br><br><br>
Regards,<br><br>Team Spardha,<br>IIT (BHU) Varanasi.');
// Optional when using HTML: Set an alternative plain text message for email clients who prefer that.
//$mail->AltBody = 'This is a plain-text message body'; 

// Optional: attach a file
// $mail->addAttachment('images/phpmailer_mini.png');

if ($mail->send()) {
    echo "This information is also sent to your inbox!";
} else {
    echo "Mailer Error: " . $mail->ErrorInfo;
}