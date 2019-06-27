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
$mail->addAddress($_SESSION['email'], $_SESSION['name']); // Set the recipient of the message.
$mail->Subject = "OTP Verification || Spardha'19 IIT (BHU) Varanasi"; // The subject of the message.

/*
 * Message Content - Choose simple text or HTML email
 */
 
// Choose to send either a simple text email...
// $mail->Body = 'You have been successfully registered!!'; // Set a plain text body.

// ... or send an email with HTML.

$_SESSION['otp'] = mt_rand(100000, 999999);

$msg = 'Hello ' . $_SESSION['name'] . ',<br><br>';
$msg .= '<b>You\'re almost there! Just verify your email to create your account.</b><br><br>';
$msg .= 'OTP is <b>' . $_SESSION['otp'] . '</b> for verifying your email. Please enter it in the appropriate box for confirming your registration for Spardha\'19.<br><br>';
$msg .= 'If you did not attempt to register for Spardha\'19, kindly ignore this email.<br><br><br>
Regards,<br><br>Team Spardha,<br>IIT (BHU) Varanasi.';

$mail->msgHTML($msg);
// Optional when using HTML: Set an alternative plain text message for email clients who prefer that.
//$mail->AltBody = 'This is a plain-text message body'; 

// Optional: attach a file
// $mail->addAttachment('images/phpmailer_mini.png');

if ($mail->send()) {
    echo "";
} else {
    echo "Mailer Error: " . $mail->ErrorInfo;
}