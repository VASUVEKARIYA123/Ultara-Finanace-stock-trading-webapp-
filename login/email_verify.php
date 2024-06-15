<?php
session_start();
if ( !isset($_SESSION['verification']) )
{
    return header('location: login.php');
}
$error = [];
const OTP_REQUIRED = "Please enter the OTP.";
const OTP_INVALID = "OTP is Invalid.";
const OTP_WRONG = "OTP does not match";
?>



<?php if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
    //
    $entered_otp = $_POST['otp'];
    $entered_otp = trim($entered_otp);

    if ( empty($entered_otp) )
    {
        $error['OTP'] = OTP_REQUIRED;
    }
    else
    {
        $entered_otp = filter_var($entered_otp, FILTER_VALIDATE_INT);
        if ( $entered_otp === false )
        {
            $error['OTP'] = OTP_INVALID;
        }
        elseif ( $entered_otp != $_SESSION['otp'] )
        {
            $error['OTP'] = OTP_WRONG;
        }
        else
        {
            unset($_SESSION['email']);
            unset($_SESSION['varification']);
            unset($_SESSION['otp']);
            $_SESSION['login_message'] = 'Your account has been successfully created.Log in.';
            return header('location: login.php');
        }
    }
    require_once('email_verification_form.php');
}
else
{
    require_once('email_verification_form.php');
}

?>