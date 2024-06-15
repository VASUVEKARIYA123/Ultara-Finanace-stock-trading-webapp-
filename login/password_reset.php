<?php

require_once('../helpers/config.php');

session_start();

if ( !(isset($_SESSION['password_reset'])) )
{
    return header('location: /revolution/login/login.php');
}

$error = [];

const PASS_REQUIRED = 'Please enter a password';
const CONFIRMATION_REQUIRED = 'Please confirm the password';
const CONFIRMATION_INVALID = 'Password and confirmation password does not match!';
const CAPTCHA_REQUIRED = 'Please fill up the captcha';
const CAPTCHA_INVALID = 'Captcha code is wrong';
?>

<?php if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
    //sanitise and validate password
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    $password = trim($password);

    if ( empty($password) )
    {
        $error['password'] = PASS_REQUIRED;
    }

    //
    $confirmation = filter_input(INPUT_POST, 'confirmation', FILTER_SANITIZE_SPECIAL_CHARS);

    $confirmation = trim($confirmation);

    if ( empty($confirmation) )
    {
        $error['confirmation'] = CONFIRMATION_REQUIRED;
    }
    else if ( $password != $confirmation )
    {
        $error['confirmation'] = CONFIRMATION_INVALID;
    }

    //
    $captcha = filter_input(INPUT_POST, 'captcha', FILTER_SANITIZE_SPECIAL_CHARS);

    $captcha = trim($captcha);

    if ( empty($captcha) )
    {
        $error['captcha'] = CAPTCHA_REQUIRED;
    }
    else if ( $captcha != $_SESSION['captcha_code'] )
    {
        $error['captcha'] = CAPTCHA_INVALID;
    }

    if ( count($error) === 0 )
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE users SET hash=? where email=?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $password, $_SESSION['email']);
        mysqli_stmt_execute($stmt);
        $_SESSION['login_message'] = $_SESSION['email'];
        unset($_SESSION['password_reset']);
        unset($_SESSION['forgot_password_key']);
        unset($_SESSION['email']);
        return header('location: /revolution/login/login.php');
    }
    else
    {
        require('password_reset_form.php');
    }
}
else
{
    if ( $_SESSION['forgot_password_key'] != (base64_decode(urldecode($_GET['code']))) )
    {
        $_SESSION['login_message'] = 'link was currepted.Please try again later.';
        unset($_SESSION['password_reset']);
        unset($_SESSION['forgot_password_key']);
        unset($_SESSION['email']);
        return header('location: /revolution/login/login.php');
    }
    require_once('password_reset_form.php');
}
?>