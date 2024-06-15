<?php
require_once('../helpers/help.php');
if ( !(isset($_SESSION['password_reset'])) )
{
    return header('location: /revolution/login/login.php');
}
?>

<?php require_once "../helpers/header.php" ?>

<title>Ultra Finance: Reset password</title>

<?php require_once "../helpers/navbar.php" ?>
<style>
    #captcha_img {
        margin: 10px;
    }
</style>
<h1>Reset your password</h1>
<form action="password_reset.php" method="post">
    <div>
        <?php echo $error['password'] ?? '' ?>
    </div>
    <div class="mb-3">
        <input class="form-control mx-auto w-auto" id="password" name="password" placeholder="Password" type="password">
    </div>
    <div>
        <?php echo $error['confirmation'] ?? '' ?>
    </div>
    <div class="mb-3">
        <input class="form-control mx-auto w-auto" id="confirmation" name="confirmation" placeholder="Confirm Password"
            type="password">
    </div>
    <img src="captcha.php" id="captcha_img">
    <input type="button" value="refresh" onclick="refresh()">
    <div>
        <?php echo $error['captcha'] ?? '' ?>
    </div>
    <div class="mb-3">
        <input class="form-control mx-auto w-auto" id="captcha" name="captcha" placeholder="Captcha Code" type="text">
    </div>
    <button class="btn btn-primary" type="submit">Reset</button>
</form>

<script>
    function refresh() {
        var img = document.getElementById("captcha_img");
        img.src = "captcha.php";
    }
</script>

<?php require_once "../helpers/footer.php"; ?>