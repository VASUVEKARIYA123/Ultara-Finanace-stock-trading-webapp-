<?php
require_once("../helpers/help.php");
require_login();

const SYMBOL_REQUIRED = 'Please enter a stock symbol!';
$error = [];

if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
    $symbol = strtoupper($_POST['symbol']);
    if ( $symbol == '' )
    {
        $error['symbol'] = SYMBOL_REQUIRED;
    }
    else
    {
        return header("location: /revolution/features/buy.php?symbol=$symbol");
    }
    require_once("quote_form.php");
}
else
{
    require_once("quote_form.php");
}


?>