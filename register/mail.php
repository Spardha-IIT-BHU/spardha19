<?php

date_default_timezone_set('Etc/UTC');

// Edit this path if PHPMailer is in a different location.
require '../PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;
$mail->isSMTP();

/*
 * Server Configuration
 */

$mail->Host = 'smtp.gmail.com'; // Which SMTP server to use.
$mail->Port = 587; // Which port to use, 587 is the default port for TLS security.
$mail->SMTPSecure = 'tls'; // Which security method to use. TLS is most secure.
$mail->SMTPAuth = true; // Whether you need to login. This is almost always required.
$mail->Username = ""; // Your Gmail address.
$mail->Password = ""; // Your Gmail login password or App Specific Password.

/*
 * Message Configuration
 */

$mail->setFrom('info@spardha.co.in', "Spardha'19 IIT (BHU) Varanasi"); // Set the sender of the message.
$mail->addAddress($row[5], $row[1]); // Set the recipient of the message.
$mail->Subject = "Registration Successful || Spardha'19 IIT (BHU) Varanasi"; // The subject of the message.

/*
 * Message Content - Choose simple text or HTML email
 */
 
// Choose to send either a simple text email...
// $mail->Body = 'You have been successfully registered!!'; // Set a plain text body.

// ... or send an email with HTML.
$mail->AddEmbeddedImage('../images/logos/spardha.png', 'spardhalogo');
$mail->msgHTML('Hello ' . $row[1] . ',<br><br><img src="cid:spardhalogo" style="width: 100%; max-width: 250px; display: block; margin: 0 auto 20px;">
<div style="text-align: center; font-size: 17px;"><strong>Greetings from Team Spardha!!</strong></div><br>
<div style="text-align: center; font-size: 15px;"><strong>Congrats!</strong> You have successfully registered for Spardha 2019, the annual
Games and Sports Festival of IIT (BHU) Varanasi! <br><br>
The details received are: </div><br>
<table align="center" style="margin-top: 2px; font-size: 15px;">
<tr>
<td>REGISTRATION NO:&emsp;</td>
<td>S19-' . sprintf("%03d", $row[0]) . '</td>
</tr>
<tr>
<td>NAME:</td>
<td>' . $row[1] . '</td>
</tr>
<tr>
<td>DESIGNATION:</td>
<td>' . $row[2] . '</td>
</tr>
<tr>
<td>INSTITUTE NAME:</td>
<td>' . $row[3] . '</td>
</tr>
<tr>
<td>ACADEMIC YEAR:</td>
<td>' . $row[4] . '</td>
</tr>
<tr>
<td>EMAIL ADDRESS:</td>
<td>' . $row[5] . '</td>
</tr>
<tr>
<td>PHONE NUMBER:</td>
<td>' . $row[6] . '</td>
</tr>
<tr>
<td>EVENTS:</td>
<td>' . $row[7] . '</td>
</tr>
<tr>GIRLS EVENTS:</td>
<td>' . $row[8] . '</td>
</table>
<br><br><br>
Regards,<br><br>Team Spardha<br>IIT (BHU) Varanasi');
// Optional when using HTML: Set an alternative plain text message for email clients who prefer that.
//$mail->AltBody = 'This is a plain-text message body'; 

// Optional: attach a file
// $mail->addAttachment('images/phpmailer_mini.png');

if ($mail->send()) {
    echo "This information is also sent to your inbox!";
} else {
    echo "Mailer Error: " . $mail->ErrorInfo;
}