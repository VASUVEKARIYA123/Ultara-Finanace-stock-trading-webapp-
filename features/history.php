<?php
require_once("../helpers/config.php");
require_once("../helpers/help.php");

require_login();
$sql = "SELECT * FROM history WHERE user_id=? ORDER BY trn_id DESC";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<?php require_once("../helpers/header.php") ?>

<title>Ultra finance: History</title>

<?php require_once("../helpers/navbar.php") ?>

<table class="table responsive">
  <thead>
    <tr style="text-align: center;">
      <td><b>Symbol</b></td>
      <td><b>Shares</b></td>
      <td><b>price</b></td>
      <td><b>Action</b></td>
      <td><b>Profit/Loss</b></td>
      <td><b>Transacted</b></td>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = mysqli_fetch_array($result)): ?>
      <tr>
        <td>
          <?php echo $row["symbol"] ?>
        </td>
        <td>
          <?php echo $row["shares"] ?>
        </td>
        <td>
          <?php $price = number_format($row['price'], 3, '.', ',') ?>
          <?php echo "\${$price}" ?>
        </td>
        <td style="color:<?php echo $row['action'] == 'BOUGHT' ? 'green' : 'red' ?>;">
          <?php echo $row["action"] ?>
        </td>
        <?php if ( $row['action'] == "BOUGHT" ): ?>
          <td style="color:black">
            <?php echo '---------' ?>
          </td>
        <?php else: ?>
          <td
            style="color: <?php echo (floatval($row['profit']) === 0.0 ? 'black' : (floatval($row['profit']) > 0.0 ? 'green' : 'red')) ?>">
            <?php $profit = number_format($row['profit'], 3, '.', ',') ?>
            <?php echo "\${$profit}" ?>
          </td>
        <?php endif ?>
        <td>
          <?php echo $row["time"] ?>
        </td>
      </tr>
      <?php flush() ?>
    <?php endwhile; ?>
  </tbody>
</table>

<?php require_once("../helpers/footer.php") ?>