<?php
session_start();
include_once("/controllers/dbconfig.php");
include_once('/controllers/functions.php');
include_once('/controllers/basicHead2.php');
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());

if($_SESSION['role']){ $role = $_SESSION['role']; }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>HomePik - Processing Security Change</title>
	<link rel="stylesheet" type="text/css" href="/views/css/process.css"/>
</head>
<body>
	<div id="header">
		<div class="container-fluid header-container">
			<div class="row">
				<div class="clip_frame colelem" id="u25521">
					<div class="col-md-3 col-sm-3 col-xs-5" id="first-section">
						<a href='/controllers/index.php'><img class="block" id="u25521_img_1" src="/images/homepik_logo_bubbles_legend_7_part_1.png" alt=""/></a>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-7" id="second-section">
						<a href='/controllers/index.php'><img class="block" id="u25521_img_2" src="/images/homepik_logo_bubbles_legend_7_part_2.png" alt=""/></a>
					</div>
					<div class="col-md-5 col-sm-5 col-xs-12" id="third-section">
						<a href='/controllers/index.php'><img class="block" id="u25521_img_3" src="/images/homepik_logo_bubbles_legend_7_part_3.png" alt=""/></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="wrapper">
		<div id="main" style="position:relative;">
			<br><br>
			<?php
				//After form submits
				if(isset($_GET['submit'])) {
					//Put passwords in variable
					$securityQuestion = protect($_GET['security-question']);
					$securityAnswer = protect($_GET['security-answer']);
					$email = $_SESSION['email'];
					
				}
							
				if($_SESSION['role'] == 'agent'){ $res3 = mysql_query("UPDATE regustered_agents SET security_question = '" . $securityQuestion . "', security_answer = '" . $securityAnswer . "' WHERE email = '" . $email . "'"); }
				else{ $res3 = mysql_query("UPDATE users SET security_question = '" . $securityQuestion . "', security_answer = '" . $securityAnswer . "' WHERE email = '" . $email . "'"); }
				$row = mysql_fetch_assoc($res3);
				echo "<br><br><center class='Text-1 clearfix'><strong>Your security question and answer have been updated.</strong></center><br><br>";
				
			?>
		</div>
	</div>
</body>
</html>