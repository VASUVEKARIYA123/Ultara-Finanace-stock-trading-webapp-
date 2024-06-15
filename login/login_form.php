<?php
require_once('../helpers/help.php');
prevent_access_after_login();
?>


<?php require_once "../helpers/header.php" ?>
<title>Ultra Finance: Login</title>
<?php require_once "../helpers/navbar.php" ?>

<h1>Log In</h1>

<?php echo $_SESSION['login_message'] ?? '' ?>

<form action="login.php" method="post">
    <div>
        <?php echo $error['username'] ?? '' ?>
    </div>
    <div class="mb-3">
        <input autocomplete="off" autofocus class="form-control mx-auto w-auto" id="username" name="username"
            placeholder="Username" type="text"
            value="<?php echo $input['username'] ?? $_SESSION['registered_email'] ?? '' ?>">
    </div>
    <div>
        <?php echo $error['password'] ?? '' ?>
    </div>
    <div class="mb-3">
        <input class="form-control mx-auto w-auto" id="password" name="password" placeholder="Password" type="password">
        <input type="submit" name="reset" class="button" value="Forgot password?" />
    </div>
    <button class="btn btn-primary" type="submit">Log In</button>
    <div>
        <p>Don't have an account?<a href="register.php">Create a new account.</a></p>
    </div>
</form>

<?php require_once "../helpers/footer.php"; ?>
<?php unset($_SESSION['login_message']) ?>
<?php unset($_SESSION['registered_email']) ?>