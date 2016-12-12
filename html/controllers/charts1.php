<?php
session_start();
include_once("dbconfig.php");
include_once('functions.php');
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if (isset($_POST['report-submit'])) {
  $month = $_POST['month'];
  echo $month;
}

$result = mysql_query( "SELECT COUNT(*) AS studioCount FROM `vow_data` WHERE bed = 0" ) or die("Couldn't execute query.".mysql_error()."Line 1");
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$studioCount = $row['studioCount'];

$result = mysql_query( "SELECT COUNT(*) AS br1Count FROM `vow_data` WHERE bed = 1" ) or die("Couldn't execute query.".mysql_error());
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$br1Count = $row['br1Count'];

$result = mysql_query( "SELECT COUNT(*) AS br2Count FROM `vow_data` WHERE bed = 2" ) or die("Couldn't execute query.".mysql_error()."Line 3");
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$br2Count = $row['br2Count'];

$result = mysql_query( "SELECT COUNT(*) AS br3Count FROM `vow_data` WHERE bed = 3" ) or die("Couldn't execute query.".mysql_error());
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$br3Count = $row['br3Count'];

//Queries for number of listings by contract -- using this to test array result 
$result = mysql_query( "SELECT COUNT(*) AS listCount, contract FROM `vow_data` GROUP BY contract" ) or die("Couldn't execute query.".mysql_error());

$chartData = array(
  'cols' => array(
    array('type' => 'string', 'label' => 'Contract'),
    array('type' => 'number', 'label' => 'Number')
  )
);

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  $chartData['rows'][] = array(
    'c' => array (
      array('v' => $row['contract']),
      array('v' => $row['listCount'])
    )
  );
}

$json = json_encode($chartData);

?>
<html>
  <head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">
    console.log(<?php echo $json;?>);
    
    // Load the Visualization API 
    google.load('visualization', '1', {'packages':['corechart']});
      
    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);
      
    function drawChart() {
      
      // NUMBER OF BEDROOM COUNT CHART
  		var data1 = new google.visualization.DataTable();
       data1.addColumn('string', 'Bedroom');
       data1.addColumn('number', 'Number');
       data1.addRows([
        ['Studio', <?php echo $studioCount;?>],
        ['1 BR', <?php echo $br1Count;?>],
        ['2 BR', <?php echo $br2Count;?>],
        ['3 BR', <?php echo $br3Count;?>],
      ]);

      // NUMBER OF LISTINGS BY CONTRACT CHART
     var data2 = new google.visualization.DataTable(<?php echo $json;?>);

  
  		var options1 = {
  		  title: 'Number of Bedrooms for <?php echo $month;?>',
  		  hAxis: {title: '', titleTextStyle: {color: 'red'}},
  			width: 500,
  			height: 300
  		};
  		
  		var options2 = {
  		  title: 'Number of Listings by Contract',
  		  hAxis: {title: '', titleTextStyle: {color: 'red'}},
  			width: 500,
  			height: 300
  		};

      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div1'));
      chart.draw(data1, options1);
      
      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div2'));
      chart.draw(data2, options2);
    }

    </script>
  </head>
  <body>
    <!--Div that will hold the chart-->
    <div id="chart_div1"></div>
    <div id="chart_div2"></div>
  
  </body>
</html> 