<?php
session_start();
include('functions.php');
include("dbconfig.php");

$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");
$SQL = "SELECT COUNT(*) AS count FROM `vow_data` WHERE bed = 0";
$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$studioCount = $row['count'];
echo json_encode($studioCount);


?>

<!DOCTYPE HTML>
<html>

<head>  
 <title>Basic HTML5 Column Chart </title>
  <script type="text/javascript">
  window.onload = function () {
    var myvar = <?php echo $studioCount; ?>;
    var chart = new CanvasJS.Chart("chartContainer",
    {
      title:{
        text: "Number of Bedrooms"    
      },
      animationEnabled: true,
      axisY: {
        title: "Total Inventory"
      },
      legend: {
        verticalAlign: "bottom",
        horizontalAlign: "center"
      },
      theme: "theme2",
      data: [

      {        
        type: "column",  
        showInLegend: true, 
        legendMarkerColor: "grey",
        legendText: "Total Inventory",
        myvar: myvar,
        dataPoints: [      
        {y: <?php echo $studioCount;?> label: "Studio"},
        {y: 267017,  label: "1 BR" },
        {y: 175200,  label: "2 BR"},
        {y: 154580,  label: "3 BR"}        
        ]
      }   
      ]
    });

    chart.render();
  }
  </script>
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.min.js"></script>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
  <body>
  <div id="chartContainer" style="height: 280px; width: 350px;">
    </div>
  </body>
</html>