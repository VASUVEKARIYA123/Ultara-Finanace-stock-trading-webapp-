<?php
require_once("../lib/phpchartdir.php");

# The data for the pyramid chart
$data = array(156, 123, 211, 179);

# The labels for the pyramid chart
$labels = array("Corporate Tax", "Working Capital", "Re-investment", "Dividend");

# The colors for the pyramid layers
$colors = array(0x66aaee, 0xeebb22, 0xcccccc, 0xcc88ff);

# Create a PyramidChart object of size 500 x 400 pixels
$c = new PyramidChart(500, 400);

# Set the funnel center at (200, 210), and width x height to 150 x 300 pixels
$c->setFunnelSize(200, 210, 150, 300);

# Set the elevation to 5 degrees
$c->setViewAngle(5);

# Set the pyramid data and labels
$c->setData($data, $labels);

# Set the layer colors to the given colors
$c->setColors2(DataColor, $colors);

# Leave 1% gaps between layers
$c->setLayerGap(0.01);

# Add labels at the right side of the pyramid layers using Arial Bold font. The labels will have 3
# lines showing the layer name, value and percentage.
$c->setRightLabel("{label}\nUS \${value}K\n({percent}%)", "Arial Bold");

# Output the chart
$viewer = new WebChartViewer("chart1");
$viewer->setChart($c, SVG);

# Include tool tip for the chart
$viewer->setImageMap($c->getHTMLImageMap("", "", "title='{label}: US\$ {value}M ({percent}%)'"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Funnel Chart</title>
    <!-- Include ChartDirector Javascript Library to support chart interactions -->
    <script type="text/javascript" src="cdjcv.js"></script>
</head>
<body style="margin:5px 0px 0px 5px">
    <div style="font:bold 18pt verdana;">
        Funnel Chart
    </div>
    <hr style="border:solid 1px #000080; background:#000080" />
    <div style="font:10pt verdana; margin-bottom:1.5em">
        <a href="viewsource.php?file=<?=basename(__FILE__)?>">View Chart Source Code</a>
    </div>
    <!-- ****** Here is the chart image ****** -->
    <?php echo $viewer->renderHTML(); ?>
</body>
</html>