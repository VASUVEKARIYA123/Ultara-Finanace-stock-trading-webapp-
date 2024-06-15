<?php
require_once("../helpers/config.php");
require_once("../helpers/help.php");

require_login();

const SYMBOL_REQUIRED = 'Required stock symbol not found! Try again.';
const SYMBOL_INVALID = 'No stock found with that the symbol: //// ' . '<br>' . 'Symbol for the company might have changed, look for the symbol' . '<a href="https://marketstack.com/search">here</a>';
const NO_OF_SHARES_INVALID = 'Please enter a valid positive number of shares';
const NOT_ENOUGH_SHARES = 'You don\'t own that many shares!';

$error = [];


if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
    $symbol = $_POST["symbol"];

    if ( $symbol == "" )
    {
        $error['sell'] = SYMBOL_REQUIRED;
    }

    $shares = $_POST["shares"];

    if ( !ctype_digit($shares) )
    {
        $error['sell'] = NO_OF_SHARES_INVALID;
    }
    else if ( intval($shares) === 0 )
    {
        $error['sell'] = NO_OF_SHARES_INVALID;
    }
    else
    {
        $shares = intval($shares);

        //get users owned shares
        $sql = "SELECT * FROM account WHERE user_id=? AND symbol=?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "is", $_SESSION["user_id"], $symbol);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ( mysqli_num_rows($result) == 0 )
        {
            $error['sell'] = NOT_ENOUGH_SHARES;
        }
        else
        {
            $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $owned = intval($result["shares"]);
            $rec_id = $result["record_id"];
            $cost_per_share = $result['cost_per_share'];

            if ( $owned < $shares )
            {
                $error['sell'] = NOT_ENOUGH_SHARES;
            }
            else
            {
                //get price of stock
                $result = lookup($symbol);

                if ( !$result )
                {
                    $error['sell'] = str_replace("////", $symbol, SYMBOL_INVALID);
                }
                else
                {
                    $price = floatval($result["price"]);

                    $total = $price * $shares;
                    $profit = ($price - $cost_per_share) * $shares;

                    //ad history
                    $sql = "INSERT INTO history(user_id,symbol,price,shares,action,profit) VALUES(?,?,?,?,?,?)";
                    $stmt = mysqli_prepare($link, $sql);
                    mysqli_stmt_bind_param($stmt, "isdisd", $_SESSION["user_id"], $symbol, $price, $shares, $action, $profit);
                    $action = "SOLD";
                    mysqli_stmt_execute($stmt);

                    //get user's cash
                    $sql = "SELECT cash FROM users WHERE id=?";
                    $stmt = mysqli_prepare($link, $sql);
                    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $cash = floatval($result["cash"]);

                    $cash = $cash + $total;

                    //update cash
                    $sql = "UPDATE users SET cash=? WHERE id=?";
                    $stmt = mysqli_prepare($link, $sql);
                    mysqli_stmt_bind_param($stmt, "di", $cash, $_SESSION["user_id"]);
                    mysqli_stmt_execute($stmt);

                    //update accounts
                    if ( $owned == $shares )
                    {
                        $sql = "DELETE FROM account WHERE record_id=?";
                        $stmt = mysqli_prepare($link, $sql);
                        mysqli_stmt_bind_param($stmt, "i", $rec_id);
                        mysqli_stmt_execute($stmt);
                    }
                    else
                    {
                        $owned = $owned - $shares;
                        $sql = "UPDATE account SET shares=? WHERE record_id=?";
                        $stmt = mysqli_prepare($link, $sql);
                        mysqli_stmt_bind_param($stmt, "ii", $owned, $rec_id);
                        mysqli_stmt_execute($stmt);
                    }

                    //redirect
                    $_SESSION['wallet_message'] = 'Successfully Sold!';
                    return header("location: /revolution/features/wallet.php");
                }
            }

        }
    }
    require_once("wallet.php");
}
else
{
    require_once("wallet.php");
}
?>