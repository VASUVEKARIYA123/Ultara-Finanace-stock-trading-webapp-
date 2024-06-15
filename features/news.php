<?php
require_once("../helpers/help.php");
require_login();

function get_news()
{
    $apikey = "cglt6ihr01qrjukrdsogcglt6ihr01qrjukrdsp0";
    $url = "https://finnhub.io/api/v1/news?category=general&minId=1&token={$apikey}";
    $data = file_get_contents($url);
    if ( ($data == false) )
    {
        return false;
    }
    $result = json_decode($data, true);
    return $result;
}

$result = get_news();
if ( $result == false )
{
    die("Could not show the news! Please try again later.");
}
?>

<?php require_once("../helpers/header.php") ?>
<title>Ultra Finance: News</title>
<style>
    * {
        box-sizing: border-box;
        font-family: cursive;
        background-color: #b1b1b1;
    }

    .card {
        /* padding:  */
        border: 1px solid black;
        margin: 10px;
        background-color: grey;
    }
</style>
<?php require_once("../helpers/navbar.php") ?>

<?php foreach ($result as $row): ?>
    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="<?php echo $row['image'] ?>" class="img-fluid rounded-start" alt="..." style="height: 100%;">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">
                        <?php echo $row['headline'] ?>
                    </h5>
                    <p class="card-text">
                        News category :
                        <?php echo $row['category'] ?>
                    </p>
                    <p class="card-text">
                        Summary :
                        <?php echo $row['summary'] ?>
                    </p>
                    <p class="card-text">
                        News source:
                        <?php echo $row['source'] ?>
                    </p>
                    <p class="card-text">
                        Published on :
                        <?php echo date('d-m-y', $row['datetime']) ?>
                    </p>
                    <p class="card-text">
                        Related stocks and companies mentioned in the article :
                        <?php echo $row['related'] ?? "--" ?>
                    </p>
                    <p class="card-text">
                        Read the original article : <a href="<?php echo $row['url'] ?>">Here </a>

                    </p>

                </div>
            </div>
        </div>
    </div>
    <?php flush() ?>
<?php endforeach; ?>

<?php require_once("../helpers/footer.php") ?>