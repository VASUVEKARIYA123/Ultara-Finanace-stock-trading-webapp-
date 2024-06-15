<?php
require_once("../helpers/help.php");
require_login();
?>


<?php require_once "../helpers/header.php"; ?>

<title>Ultra Finance: Quote</title>

<?php require_once "../helpers/navbar.php"; ?>

<?php echo $_SESSION['quote_message'] ?? '' ?>
<form action="quote.php" method="post">
    <div class="mb-3">
        <input autofocus class="form-control mx-auto w-auto" id="symbol" name="symbol"
            placeholder="Enter a company name" type="text">
    </div>
    <button class="btn btn-primary" type="submit">Search</button>
    <div>
        <ul id="suggestions"></ul>
    </div>
</form>

<script src="search_bar.js"></script>

<?php require_once "../helpers/footer.php"; ?>
<?php unset($_SESSION['quote_message']) ?>