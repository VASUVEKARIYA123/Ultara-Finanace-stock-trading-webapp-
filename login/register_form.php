<?php
require_once('../helpers/help.php');
prevent_access_after_login();
?>

<?php require_once "../helpers/header.php" ?>

<title>Ultra Finance: Register</title>

<?php require_once "../helpers/navbar.php" ?>
<style>
    #captcha_img {
        margin: 10px;
    }
</style>
<h1>Register</h1>
<form action="register.php" method="post">
    <div>
        <?php echo $error['username'] ?? '' ?>
    </div>
    <div class="mb-3">
        <input autocomplete="off" autofocus class="form-control mx-auto w-auto" id="username" name="username"
            placeholder="Username" type="text" value="<?php echo $input['username'] ?? '' ?>">
    </div>
    <div>
        <?php echo $error['email'] ?? '' ?>
    </div>
    <div class="mb-3">
        <input autocomplete="off" autofocus class="form-control mx-auto w-auto" id="email" name="email"
            placeholder="Email address" type="text" value="<?php echo $input['email'] ?? '' ?>">
    </div>
    <div>
        <?php echo $error['password'] ?? '' ?>
    </div>
    <div class="mb-3">
        <input class="form-control mx-auto w-auto" id="password" name="password" placeholder="Password" type="password"
            value="<?php echo $input['password'] ?? '' ?>">
    </div>
    <div>
        <?php echo $error['confirmation'] ?? '' ?>
    </div>
    <div class="mb-3">
        <input class="form-control mx-auto w-auto" id="confirmation" name="confirmation" placeholder="Confirm Password"
            type="password" value="<?php echo $input['confirmation'] ?? '' ?>">
    </div>
    <div>
        <img src="captcha.php" id="captcha_img">
        <input type="button" value="refresh" onclick="refresh()">
    </div>
    <div>
        <?php echo $error['captcha'] ?? '' ?>
    </div>
    <div class="mb-3">
        <input class="form-control mx-auto w-auto" id="captcha" name="captcha" placeholder="Captcha Code" type="text"
            value="<?php echo $input['captcha'] ?? '' ?>">
    </div>
    <button class="btn btn-primary" type="submit">Register</button>
    <div>
        <p>Already have an account?<a href="login.php">Log in</a></p>
    </div>
</form>

<script>
    function refresh() {
        var img = document.getElementById("captcha_img");
        img.src = "captcha.php";
    }
</script>

<?php require_once "../helpers/footer.php"; ?>