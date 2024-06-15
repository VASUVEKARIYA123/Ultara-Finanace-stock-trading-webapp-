<?php
require_once('../helpers/config.php');
require_once('../helpers/help.php');

require_login();

if ( (!isset($_GET['symbol']) || empty(trim($_GET['symbol']))) && (!isset($_POST['symbol']) || empty(trim($_POST['symbol']))) )
{
    return header('location: /revolution/features/quote.php');
}

$symbol = $_GET['symbol'] ?? $_POST['symbol'];
$result = lookup($symbol);
$chart = make_chart($symbol);
$suggestions = get_suggestions($symbol);

if ( !$result || !$chart || !$suggestions )
{
    $_SESSION['quote_message'] = str_replace("////", $symbol, SYMBOL_INVALID);
    return header("location: /revolution/features/quote.php");
}

$price = $result["price"];
$change = $result["change"];
$name = $result["company_name"];


?>

<?php require_once "../helpers/header.php" ?>
<title>Ultra Finance: Buy</title>
<?php require_once "../helpers/navbar.php" ?>

<div>
    <h1>
        <?php echo $name;
        echo "(" . $symbol . ")" ?>
    </h1>
    <h2>
        price :
        <span>
            <strong>
                <?php echo "\$$price" ?>
            </strong>
        </span>
    </h2>
    <h2>
        change:
        <span style='color:<?php echo ($change[0] == '+' ? 'green' : 'red') ?>'>
            <?php echo $change ?>
        </span>
    </h2>
</div>
<form action="buy.php" method="post">
    <div class="mb-3">
        <input type="hidden" name="symbol" value="<?php echo $symbol ?>">
    </div>
    <div class="mb-3">
        <input type="hidden" name="request_page" value="buy">
    </div>
    <?php echo $error['shares'] ?? '' ?>
    <div class="mb-3">
        <input class="form-control mx-auto w-auto" id="shares" name="shares" placeholder="No. of shares" type="number"
            min='1'>
    </div>
    <button class="btn btn-primary" type="submit">Buy</button>
</form>

<hr style="border:solid 1px #000080; background:#000080" />
<?php flush() ?>
<div style="margin:5px 0px 0px 5px">
    <div style="font:bold 18pt verdana;">
        Candlestick Chart
    </div>
    <?php echo $chart->renderHTML(); ?>
</div>
<hr style="border:solid 1px #000080; background:#000080" />
<?php flush() ?>
<div style="margin:5px 0px 0px 5px">
    <div style="font:bold 18pt verdana;">
        <?php echo $symbol ?> Recommendation Trends
    </div>
    <?php echo $suggestions->renderHTML(); ?>
</div>
<?php require_once "../helpers/footer.php"; ?>