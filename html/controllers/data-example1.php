<?php
session_start();
include('functions.php');
include("dbconfig.php");
include("head-reduced.php");
?>

<!DOCTYPE HTML>
<html>

<head>  
 <title>Basic HTML5 Column Chart </title>
  <script type="text/javascript">
  window.onload = function () {
    
    
    console.log('start');
    
  $.getJSON("getData.php", function (result) {
    
      console.log('next');
      
      dataPoints = [];
      
      if (result !== null){
        for(var i = 0; i <= result.length-1; i++) {
              dataPoints.push({label: result[i].label, y: parseInt(result[i].y)});
              console.log(result.length);
        }
      }

      console.log(result);
      
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
          dataPoints: dataPoints
        }   
        ]
      });
  
      chart.render();
  });
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