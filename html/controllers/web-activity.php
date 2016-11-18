<? session_start();
include("dbconfig.php");
include 'functions.php';
include('head-reduced.php');
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());
limit();
if (!$_SESSION['user']) { header('location: /users/logout.php'); }
$email = $_SESSION['email'];

$sql = "SELECT first_name, last_name, agent_id FROM `registered_agents` WHERE (email = '".$email."')";
$res = mysql_query( $sql ) or die("Couldn't execute query.".mysql_error());
$row = mysql_fetch_array($res,MYSQL_ASSOC);
$firstname = $row['first_name'];
$lastname = $row['last_name'];
$id = $row['agent_id'];

$name = explode('@', $_SESSION['email']);
$name = $name[0];
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex">
	<meta name="google-site-verification" content="9_HsDzaGzRzyK9eWUxt0VJuVv6vLAT-m1fBE5cSvaNU" />
	<style>
	  body {
    	font-family: 'Lato', sans-serif;
    }
		.navbar-default{
			border-left-style: none;
			border-right-style: none;
			background-color: white;
		}
		#wrapper{
			max-width: 960px;
			margin: auto;
		}
		#header{
			max-width: 960px;
			margin: auto;
		}
		#head-content span{
			font-size: 0.6em;
			margin-bottom: 10px;
			color: #0098D4;
		}
		#logo {
			position: absolute;
			top: 1px;
			left: 10px;
			height: 70px;
			margin: 5px 4px;
			opacity: 0.8;
		}
	  .logout{
      right: 0;
    }
  
.axis path, .axis line
        {
            fill: none;
            stroke: #777;
            shape-rendering: crispEdges;
        }
        
        .axis text
        {
            font-family: 'Arial';
            font-size: 13px;
        }
        .tick
        {
            stroke-dasharray: 1, 2;
        }
        .bar
        {
            fill: FireBrick;
        }
	</style>
	<script src="//d3js.org/d3.v3.min.js"></script>
</head>  
<body>
	<div id="header">
		<div id="head-content">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<a href='http://homepik.com/menu.php' style="display:block"><img alt="selection portfolio" src="http://homepik.com/images/logoc.png" id="logo" alt="Homepik Logo" /></a><br><br><br><br>
						<span>Nice Idea Media Inc. All Rights Reserved. Patent Protected</span>
					</div>
					<div class="col-md-6"></div>
				</div>
			</div>
		</div>
	</div>
	<br>
	<div id="wrapper">
		<nav class="navbar navbar-default">
			<div class="container-fluid">
			  <div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar" aria-expanded="false">
				  <span class="sr-only">Toggle navigation</span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				</button>
			  </div>
			  <div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav">
					<li><a href="/menu.php" style="text-decoration:none;color:#3997D1;font-weight:100;font-size:16px;letter-spacing: 0;">Agent Home Page </a> </li>  
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="/users/logout.php" class="logout" style="text-decoration:none;color:RGB(83,83,83);cursor:pointer;right:0;font-weight:100;font-size:16px;letter-spacing: 0;">Logout <span class="name"><? echo $firstname . " " . $lastname ?></span></a></li>
				</ul>
			  </div>
			</div>
		</nav>
		<div class="container">
		  <div class="row">
				<div class="col-md-5">
            <form>
              <select id='month' onchange="show_month()">
                <option selected value=''>--Select Month--</option>
                <option value='Feb. 2016'>Feb. 2016</option>
                <option value='Mar. 2016'>Mar. 2016</option>
              </select> 

            </form>		
        </div>
				<div class="col-md-5">		
					<iframe width="404" height="253.54" seamless frameborder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/1DcPohzXMQb9ZHGrlCHlQgPhxiIONZ-LyBtwGkw-8fwc/pubchart?oid=185887001&amp;format=image"></iframe></iframe>
				</div>
			</div>
			<br><br>
			<div class="row">
				<div class="col-md-5">
					<iframe width="404" height="253.54" seamless frameborder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/1DcPohzXMQb9ZHGrlCHlQgPhxiIONZ-LyBtwGkw-8fwc/pubchart?oid=74061680&amp;format=image"></iframe>
				</div>
				<div class="col-md-5">		
					<iframe width="404" height="253.54" seamless frameborder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/1DcPohzXMQb9ZHGrlCHlQgPhxiIONZ-LyBtwGkw-8fwc/pubchart?oid=185887001&amp;format=image"></iframe></iframe>
				</div>
			</div>
			<br><br>
			<div class="row">
				<div class="col-md-5">
						<iframe width="404" height="300" seamless frameborder="1" scrolling="no" id="data-example" src="charts.php"></iframe>
</div>
				<div class="col-md-5">
					<iframe width="404" height="300" seamless frameborder="1" scrolling="no" src="area-example.html"></iframe>
				</div>
			</div>
			<br><br>
			<div class="row">
				<div class="col-md-5">
<iframe width="404" height="300" seamless frameborder="1" scrolling="no" src="pie-example-2.html"></iframe>
</div>
				<div class="col-md-5">
					<iframe width="404" height="253.54" seamless frameborder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/1DcPohzXMQb9ZHGrlCHlQgPhxiIONZ-LyBtwGkw-8fwc/pubchart?oid=189324738&amp;format=interactive"></iframe>
				</div>
			</div>
			<br><br>
			<div class="row">
				<div class="col-md-5">
					<iframe width="404" height="253.54" seamless frameborder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/1DcPohzXMQb9ZHGrlCHlQgPhxiIONZ-LyBtwGkw-8fwc/pubchart?oid=1734270620&amp;format=image"></iframe>
				</div>
				<div class="col-md-5">
					<iframe width="404" height="253.54" seamless frameborder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/1DcPohzXMQb9ZHGrlCHlQgPhxiIONZ-LyBtwGkw-8fwc/pubchart?oid=1130476696&amp;format=image"></iframe>
				</div>
			</div>
		</div>
		<div>
			<center><strong><br><br><p>Note: Not set is privacy control by Google Analytics</p><br><br></strong></center>
		</div>
	</div>
<?php include_once('autoLogout.php'); ?>
</body>
</html>
