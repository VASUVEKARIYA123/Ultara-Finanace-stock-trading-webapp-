<?php
session_start();
function require_login()
{
    if ( !(isset($_SESSION["user_id"])) )
    {
        $_SESSION['login_message'] = 'Log in first!';
        return header('location: /revolution/login/login.php');
    }
}

function prevent_access_after_login()
{
    if ( (isset($_SESSION["user_id"])) )
    {
        return header('location: /revolution/features/wallet.php');
    }
}

function lookup(string $symbol)
{
    require_once("../helpers/HTML_parser/simple_html_dom_parser.php");
    $url = "https://finance.yahoo.com/quote/" . $symbol;

    $html = file_get_html(url: "$url", maxLen: 100000);

    if ( !$html )
    {
        return false;
    }

    $data = $html->find("fin-streamer[data-symbol={$symbol}]");

    if ( count($data) < 3 )
    {
        return false;
    }

    $price = $data[0]->innertext();
    $change = $data[1]->first_child()->innertext() . "  " . $data[2]->first_child()->innertext();
    $company_name = $html->find("title", 0)->innertext();

    $company_name = explode('(', $company_name)[0];

    $response = [ 'price' => $price, 'change' => $change, 'company_name' => $company_name ];

    return $response;
}

function make_chart(string $symbol)
{
    require_once("../ChartDirector/lib/phpchartdir.php");

    $apikey = "cglt6ihr01qrjukrdsogcglt6ihr01qrjukrdsp0";

    $url = "https://finnhub.io/api/v1/stock/candle?symbol={$symbol}&resolution=D&from=" . strtotime("-30 days") . "&to=" . strtotime("now") . "&token={$apikey}";

    $data = file_get_contents($url);

    $company_name = lookup($symbol)['company_name'];

    if ( ($data == false) )
    {
        return false;
    }
    $result = json_decode($data, true);

    if ( $result['s'] == 'no_data' )
    {
        return false;
    }

    $highData = $result['h'];
    $lowData = $result['l'];
    $openData = $result['o'];
    $closeData = $result['c'];

    $now = strtotime("now");
    $labels = array();

    foreach ($result['t'] as $timestamp)
    {
        $labels[] = date("d-m-y", $timestamp);
    }

    $c = new XYChart(600, 400);
    $c->setPlotArea(50, 25, 500, 250)->setGridColor(0xc0c0c0, 0xc0c0c0);
    $c->addTitle("$company_name stock prices of last one month");
    $c->addText(50, 25, "$company_name \n $symbol", "Arial Bold", 12, 0x4040c0);
    $c->xAxis->setTitle("dates");
    $c->xAxis->setLabels($labels)->setFontAngle(45);
    $c->yAxis->setTitle("Stock price");
    $layer = $c->addCandleStickLayer($highData, $lowData, $openData, $closeData, 0x00ff00, 0xff0000);
    $layer->setLineWidth(2);
    $viewer = new WebChartViewer("chart1");
    $viewer->setChart($c, SVG);
    $viewer->setImageMap(
        $c->getHTMLImageMap(
            "",
            "",
            "title='{xLabel} \nHigh:\t\${high}\nLow:\t\${low}\nOpen:\t\${open}\nClose:\t\${close}'"
        )
    );
    return $viewer;
}

function get_suggestions(string $symbol)
{
    require_once("../ChartDirector/lib/phpchartdir.php");

    $apikey = "cglt6ihr01qrjukrdsogcglt6ihr01qrjukrdsp0";

    $url = "https://finnhub.io/api/v1/stock/recommendation?symbol={$symbol}&token={$apikey}";

    $data = file_get_contents($url);

    if ( ($data === false) )
    {
        return false;
    }

    $result = json_decode($data, true);

    if ( empty($result) )
    {
        return false;
    }

    $result = $result[0];
    $labels = [ 'Strong Buy', 'Buy', 'Hold', 'Sell', 'Strong Sell' ];
    $data = [ $result['strongBuy'], $result['buy'], $result['hold'], $result['sell'], $result['strongSell'] ];

    $c = new XYChart(600, 360);
    $c->setPlotArea(70, 20, 500, 300, Transparent, -1, Transparent, 0xcccccc);
    $c->xAxis->setColors(Transparent);
    $c->yAxis->setColors(Transparent);
    $c->xAxis->setLabelStyle("Arial", 12);
    $c->yAxis->setLabelStyle("Arial", 12);
    $layer = $c->addBarLayer($data, 0x6699bb);
    $layer->setBorderColor(Transparent, barLighting(0.8, 1.3));
    $layer->setRoundedCorners();
    $layer->setAggregateLabelStyle("Arial", 12);
    $c->xAxis->setLabels($labels);
    $c->yAxis->setTickDensity(40);
    $c->xAxis->setTitle("Action", "Arial Bold", 14, 0x555555);
    $c->yAxis->setTitle("Number of recommendations", "Arial Bold", 14, 0x555555);
    $viewer = new WebChartViewer("chart1");
    $viewer->setChart($c, SVG);
    $viewer->setImageMap($c->getHTMLImageMap("", "", "title='Recommendations to {xLabel}: {value}'"));

    return $viewer;

}

// Function to generate OTP
function generate_OTP($n)
{
    // Taking a generator string that consists of
    // all the numeric digits
    $generator = "1234567890";

    $result = "";

    for ($i = 1; $i <= $n; $i++)
    {
        $result .= substr($generator, rand() % strlen($generator), 1);
    }

    return $result;
}

use PHPMailer\PHPMailer\PHPMailer;

function send_mail($to_email, $subject, $body)
{
    require_once("../PHPMailer/src/PHPMailer.php");
    require_once("../PHPMailer/src/SMTP.php");

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->isHTML(true);
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'ultrafinance100@gmail.com';
    $mail->Password = 'xoxipejlvmzibdbp';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('ultrafinance100@gmail.com');
    $mail->addAddress($to_email);
    $mail->Subject = $subject;
    $mail->Body = $body;

    $mail->send();
}
?>