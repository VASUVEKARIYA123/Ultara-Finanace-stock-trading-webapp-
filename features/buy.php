<?php
require_once("../helpers/config.php");
require_once("../helpers/help.php");

require_login();

const SYMBOL_REQUIRED = 'Required stock symbol not found! Try again.';
const SYMBOL_INVALID = 'No stock found with that the symbol: //// ' . '<br>' . 'Symbol for the company might have changed, look for the symbol' . '<a href="https://marketstack.com/search">here</a>';
const NO_OF_SHARES_INVALID = 'Please enter a valid positive number';
const NOT_ENOUGH_CASH = 'You don\'t have enough cash to buy that many shares';

$error = [];
?>

<?php

if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
    $symbol = $_POST["symbol"];

    if ( $symbol == "" )
    {
        $_SESSION['quote_message'] = SYMBOL_REQUIRED;
        return header("location: /revolution/features/quote.php");
    }

    $shares = $_POST["shares"];

    if ( !ctype_digit($shares) )
    {
        $error['shares'] = NO_OF_SHARES_INVALID;
    }
    else if ( intval($shares) === 0 )
    {
        $error['shares'] = NO_OF_SHARES_INVALID;
    }
    else
    {
        $shares = intval($shares);

        //get price of stock
        $result = lookup($symbol);

        if ( !$result )
        {
            $_SESSION['quote_message'] = str_replace("////", $symbol, SYMBOL_INVALID);
            return header("location: /revolution/features/quote.php");
        }

        $price = floatval($result["price"]);

        //get users cash
        $sql = "SELECT cash FROM users WHERE id=?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $cash = floatval($result["cash"]);

        $total = floatval(($price) * ($shares));

        if ( $total > $cash )
        {
            $error['shares'] = NOT_ENOUGH_CASH;
        }
        else
        {

            $cash = $cash - $total;

            //insert into history
            $sql = "INSERT INTO history(user_id,symbol,price,shares,action) VALUES(?,?,?,?,?)";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, "isdis", $_SESSION["user_id"], $symbol, $price, $shares, $action);
            $action = "BOUGHT";
            mysqli_stmt_execute($stmt);

            //update cash
            $sql = "UPDATE users SET cash=? WHERE id=?";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, "di", $cash, $_SESSION["user_id"]);
            mysqli_stmt_execute($stmt);

            //update accounts
            $sql = "SELECT * FROM account WHERE user_id=? AND symbol=?";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, "is", $_SESSION["user_id"], $symbol);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ( mysqli_num_rows($result) == 0 )
            {
                $sql = "INSERT INTO account(user_id,symbol,shares,cost_per_share) VALUES(?,?,?,?)";
                $stmt = mysqli_prepare($link, $sql);
                mysqli_stmt_bind_param($stmt, "isid", $_SESSION["user_id"], $symbol, $shares, $price);
                mysqli_stmt_execute($stmt);
            }
            else
            {
                $result = mysqli_fetch_array($result);
                $shares = $result["shares"] + $shares;
                $cost_per_share = (($result['cost_per_share'] * $result['shares']) + ($total)) / ($shares);
                $sql = "UPDATE account SET shares=?,cost_per_share=? WHERE user_id=? AND symbol=?";
                $stmt = mysqli_prepare($link, $sql);
                mysqli_stmt_bind_param($stmt, "idis", $shares, $cost_per_share, $_SESSION["user_id"], $symbol);
                mysqli_stmt_execute($stmt);
            }

            //redirect
            $_SESSION['wallet_message'] = 'Successfully Bought!';
            return header("location: /revolution/features/wallet.php");
        }
    }
    if ( isset($_POST['request_page']) && $_POST['request_page'] == 'buy' )
    {
        require_once('quote_result.php');
    }
    else
    {
        $_SESSION['wallet_message'] = $error['shares'];
        return header("location: /revolution/features/wallet.php");
    }
}
else
{
    require_once("quote_result.php");
}
?>