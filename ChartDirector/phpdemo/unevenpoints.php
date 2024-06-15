<?php
require_once("../lib/phpchartdir.php");

# Data points which more unevenly spaced in time
$data0Y = array(62, 69, 53, 58, 84, 76, 49, 61, 64, 77, 79);
$data0X = array(chartTime(2007, 1, 1), chartTime(2007, 1, 2), chartTime(2007, 1, 5), chartTime(2007,
    1, 7), chartTime(2007, 1, 10), chartTime(2007, 1, 14), chartTime(2007, 1, 17), chartTime(2007,
    1, 18), chartTime(2007, 1, 19), chartTime(2007, 1, 20), chartTime(2007, 1, 21));

# Data points which are evenly spaced in a certain time range
$data1Y = array(36, 25, 28, 38, 20, 30, 27, 35, 65, 60, 40, 73, 62, 90, 75, 72);
$data1Start = chartTime(2007, 1, 1);
$data1End = chartTime(2007, 1, 16);

# Data points which are evenly spaced in another time range, in which the spacing is different from
# the above series
$data2Y = array(25, 15, 30, 23, 32, 55, 45);
$data2Start = chartTime(2007, 1, 9);
$data2End = chartTime(2007, 1, 21);

# Create a XYChart object of size 600 x 400 pixels. Use a vertical gradient color from light blue
# (99ccff) to white (ffffff) spanning the top 100 pixels as background. Set border to grey (888888).
# Use rounded corners. Enable soft drop shadow.
$c = new XYChart(600, 400);
$c->setBackground($c->linearGradientColor(0, 0, 0, 100, 0x99ccff, 0xffffff), 0x888888);
$c->setRoundedFrame();
$c->setDropShadow();

# Add a title using 18pt Times New Roman Bold Italic font. Set top margin to 16 pixels.
$c->addTitle("Product Line Order Backlog", "Times New Roman Bold Italic", 18)->setMargin2(0, 0, 16,
    0);

# Set the plotarea at (60, 80) and of 510 x 275 pixels in size. Use transparent border and dark grey
# (444444) dotted grid lines
$plotArea = $c->setPlotArea(60, 80, 510, 275, -1, -1, Transparent, $c->dashLineColor(0x444444,
    0x0101), -1);

# Add a legend box where the top-center is anchored to the horizontal center of the plot area at y =
# 45. Use horizontal layout and 10 points Arial Bold font, and transparent background and border.
$legendBox = $c->addLegend($plotArea->getLeftX() + $plotArea->getWidth() / 2, 45, false,
    "Arial Bold", 10);
$legendBox->setAlignment(TopCenter);
$legendBox->setBackground(Transparent, Transparent);

# Set x-axis tick density to 75 pixels and y-axis tick density to 30 pixels. ChartDirector
# auto-scaling will use this as the guidelines when putting ticks on the x-axis and y-axis.
$c->yAxis->setTickDensity(30);
$c->xAxis->setTickDensity(75);

# Set all axes to transparent
$c->xAxis->setColors(Transparent);
$c->yAxis->setColors(Transparent);

# Set the x-axis margins to 15 pixels, so that the horizontal grid lines can extend beyond the
# leftmost and rightmost vertical grid lines
$c->xAxis->setMargin(15, 15);

# Set axis label style to 8pt Arial Bold
$c->xAxis->setLabelStyle("Arial Bold", 8);
$c->yAxis->setLabelStyle("Arial Bold", 8);
$c->yAxis2->setLabelStyle("Arial Bold", 8);

# Add axis title using 10pt Arial Bold Italic font
$c->yAxis->setTitle("Backlog in USD millions", "Arial Bold Italic", 10);

# Add the first data series
$layer0 = $c->addLineLayer2();
$layer0->addDataSet($data0Y, 0xff0000, "Quantum Computer")->setDataSymbol(GlassSphere2Shape, 11);
$layer0->setXData($data0X);
$layer0->setLineWidth(3);

# Add the second data series
$layer1 = $c->addLineLayer2();
$layer1->addDataSet($data1Y, 0x00ff00, "Atom Synthesizer")->setDataSymbol(GlassSphere2Shape, 11);
$layer1->setXData2($data1Start, $data1End);
$layer1->setLineWidth(3);

# Add the third data series
$layer2 = $c->addLineLayer2();
$layer2->addDataSet($data2Y, 0xff6600, "Proton Cannon")->setDataSymbol(GlassSphere2Shape, 11);
$layer2->setXData2($data2Start, $data2End);
$layer2->setLineWidth(3);

# Output the chart
$viewer = new WebChartViewer("chart1");
$viewer->setChart($c, SVG);

# Include tool tip for the chart
$viewer->setImageMap($c->getHTMLImageMap("", "",
    "title='Backlog of {dataSetName} at {x|mm/dd/yyyy}: US\$ {value}M'"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Uneven Data Points </title>
    <!-- Include ChartDirector Javascript Library to support chart interactions -->
    <script type="text/javascript" src="cdjcv.js"></script>
</head>
<body style="margin:5px 0px 0px 5px">
    <div style="font:bold 18pt verdana;">
        Uneven Data Points
    </div>
    <hr style="border:solid 1px #000080; background:#000080" />
    <div style="font:10pt verdana; margin-bottom:1.5em">
        <a href="viewsource.php?file=<?=basename(__FILE__)?>">View Chart Source Code</a>
    </div>
    <!-- ****** Here is the chart image ****** -->
    <?php echo $viewer->renderHTML(); ?>
</body>
</html>
