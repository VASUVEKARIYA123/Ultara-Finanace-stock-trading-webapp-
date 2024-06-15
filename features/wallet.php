<?php

require_once "../helpers/help.php";
require_once "../helpers/config.php";

require_login();

//get user's cash
$sql = "SELECT cash FROM users WHERE id=?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$result = mysqli_fetch_array($result);
$cash = $result["cash"];

//get user's shares
$sql = "SELECT * FROM account WHERE user_id=?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$money = $cash;
?>

<?php require_once "../helpers/header.php"; ?>

<title>Ultra finance: Wallet</title>

<?php require_once "../helpers/navbar.php"; ?>

<?php echo $error['sell'] ?? '' ?>
<?php echo $_SESSION['wallet_message'] ?? '' ?>
<table class="table responsive">
    <thead>
        <tr style="text-align: center;">
            <td><b>Symbol</b></td>
            <td><b>Name</b></td>
            <td><b>Shares</b></td>
            <td><b>price</b></td>
            <td><b>Change</b></td>
            <td><b>Total</b></td>
            <td><b>Hold cost</b></td>
            <td><b>Profit/loss(per share)</b></td>
            <td><b>Sell</b></td>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_array($result)): ?>
            <?php
            $data = lookup($row["symbol"]);
            $price = $data['price'];
            $name = $data["company_name"];
            $change = $data["change"];
            $share_total = $price * $row["shares"];
            $money += $share_total;
            $profit = $price - $row['cost_per_share'];
            ?>
            <tr>
                <td data-label="Symbol">
                    <?php echo $row["symbol"] ?>
                </td>
                <td data-label="Name">
                    <?php echo $name ?>
                </td>
                <td data-label="Shares">
                    <?php echo $row["shares"] ?>
                </td>
                <td data-label="Price">
                    <?php $price = number_format($price, 3, '.', ',') ?>
                    <?php echo "\${$price}" ?>
                </td>
                <td data-label="Price" style="color:<?php echo $change[0] == '+' ? 'green' : 'red' ?>">
                    <?php echo "{$change}" ?>
                </td>
                <td data-label="Total">
                    <?php $share_total = number_format($share_total, 3, '.', ',') ?>
                    <?php echo "\${$share_total}" ?>
                </td>
                </td>
                <td data-label="Total">
                    <?php $cost_per_share = number_format($row['cost_per_share'], 3, '.', ',') ?>
                    <?php echo "\$$cost_per_share" ?>
                </td>
                <td
                    style="color: <?php echo (floatval($profit) === 0.0 ? 'black' : (floatval($profit) > 0.0 ? 'green' : 'red')) ?>">
                    <?php $profit = number_format($profit, 3, '.', ',') ?>
                    <?php echo "\${$profit}" ?>
                </td>
                <td data-label="Total">
                    <form id="form" action="sell.php" method="post">
                        <div class="mb-3">
                            <input type="hidden" name="symbol" value="<?php echo $row['symbol'] ?>">
                        </div>
                        <div class="mb-3" style="display: inline;">
                            <input style="display: inline;" class="form-control mx-auto w-auto" id="shares" name="shares"
                                placeholder="No. of shares" type="number" min="1" max="<?php echo $row['shares'] ?>">
                        </div>
                        <button name="buy" class="btn btn-primary" type="submit">Buy</button>
                        <button name="sell" class="btn btn-primary" type="submit">Sell</button>
                    </form>
                </td>
            </tr>
            <?php flush() ?>
        <?php endwhile; ?>

    </tbody>
    <tfoot>
        <tr>
            <td class="border-0 fw-bold text-end" colspan="5">Cash</td>
            <td class="border-0 text-end">
                <?php $cash = number_format($cash, 3, '.', ',') ?>
                <?php echo "\${$cash}" ?>
            </td>
        </tr>
        <tr>
            <td class="border-0 fw-bold text-end" colspan="5">TOTAL</td>
            <td class="border-0 w-bold text-end">
                <?php $money = number_format($money, 3, '.', ',') ?>
                <?php echo "\${$money}" ?>
            </td>
        </tr>
    </tfoot>
</table>

<script src="wallet.js"></script>

<?php require_once "../helpers/footer.php"; ?>

<?php unset($_SESSION['wallet_message']) ?>