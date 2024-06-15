<?php
require_once "../helpers/config.php";
require_once "../helpers/help.php";

//
if ( !(isset($_SESSION['password_reset'])) )
{
    return header('location: /revolution/login/login.php');
}

const NAME_REQUIRED = 'Please enter the username/email first';
const WRONG_CREDS = 'No such username/email is registered.<a href="register.php">Register here.</a>';
?>


<?php
if ( isset($_SESSION['email']) )
{
    $email = $_SESSION['email'];

    //
    $is_input_email = true;

    // sanitize and validate username
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $email = trim($email);

    if ( empty($email) )
    {
        $_SESSION['login_message'] = NAME_REQUIRED;
        unset($_SESSION['password_reset']);
        unset($_SESSION['email']);
        return header('location: login.php');
    }

    if ( !(filter_var($email, FILTER_VALIDATE_EMAIL)) )
    {
        $is_input_email = false;
    }

    //
    if ( $is_input_email )
    {
        $sql = "SELECT * FROM users WHERE email=?";
    }
    else
    {
        $sql = "SELECT * FROM users WHERE username=?";
    }

    //
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ( mysqli_num_rows($result) != 1 )
    {
        $_SESSION['login_message'] = WRONG_CREDS;
        unset($_SESSION['password_reset']);
        unset($_SESSION['email']);
        return header('location: login.php');
    }

    $result = mysqli_fetch_array($result);
    $email = $result['email'];
    $_SESSION['email'] = $email;

    //
    $code = generate_OTP(10);
    $_SESSION['forgot_password_key'] = $code;

    //
    $url = "localhost/revolution/login/password_reset.php?code=" . urlencode(base64_encode($code));
    $subject = 'Reset password';
    $body = "<h1>Reset your password</h1>" . "<h3>We have received a request to reset password of Ultra-Finance account linked with this email</h3>" . "<h3>Here is your password reset link: " . "<a href={$url}>Reset</a></h3>" . "<h3>If it is not you quickly contact us.</h3>" . "<h2>Please Open this link in the same brower on the same device that you are trying to log in with.</h2>" . "<h2>Thanks for choosing Ultra-Finance.Happy Trading ;)</h2>";

    send_mail($email, $subject, $body);

    $_SESSION['login_message'] = 'Reset password link has been sent to your email.Reset the password and then login';
    return header('location: login.php');
}
?>