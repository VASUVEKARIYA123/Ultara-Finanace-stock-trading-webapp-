<?php
require_once('../helpers/config.php');
require_once('../helpers/help.php');

require_login();

if ( isset($_GET['q']) && !empty(trim($_GET['q'])) )
{
    $name = $_GET['q'];

    $parts = explode(" ", $name);

    $name = implode("%", $parts);

    $sql = "SELECT * FROM symbols WHERE Name LIKE '%$name%Common Stock%' OR Symbol LIKE '%$name%'";

    $response = MySQLi_query($link, $sql);

    $result = array();

    while ($row = mysqli_fetch_assoc($response))
    {
        $result[] = $row;
    }

    $json = json_encode($result);

    echo $json;
}
?>