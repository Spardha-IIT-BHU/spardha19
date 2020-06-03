<?php
$mail->Host = getenv("SMTP_HOST"); // Which SMTP server to use.
$mail->Port = (int)getenv("SMTP_PORT"); // Which port to use, 587 is the default port for TLS security.
$mail->SMTPSecure = 'tls'; // Which security method to use. TLS is most secure.
$mail->SMTPAuth = true; // Whether you need to login. This is almost always required.
$mail->Username = getenv("GMAIL_ADDRESS"); // Your Gmail address.
$mail->Password = getenv("GMAIL_APP_PASSWORD"); // Your Gmail login password or App Specific Password.
?>
