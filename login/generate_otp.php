<?php
require_once('../helpers/help.php');
session_start();
if ( !isset($_SESSION['verification']) )
{
    return header('location: login.php');
}
?>


<?php
$otp = generate_OTP(6);
$_SESSION['otp'] = $otp;
$to_email = $_SESSION['email'];
$subject = "Registration OTP";
$body = "<h1>Verify your Email.</h1>" . "<h3>OTP : {$otp}</h3>" . "<h3>Thank you for choosing Ultra-Finance</h3>";

send_mail($to_email, $subject, $body);
return header('location: email_verify.php');
?>