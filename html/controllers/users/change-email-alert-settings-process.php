<?php
session_start();
include_once("../dbconfig.php");
include_once("../functions.php");
include_once("../basicHead.php");
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());

if($_SESSION['role']){ $role = $_SESSION['role']; }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>HomePik - Processing Password Change</title>
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
					$user = $_GET['user'];
					$setting = $_GET['setting'];
					$secondSettings = $_GET['secondSetting'];

					$res2 = mysql_query("SELECT * FROM users WHERE email = '" . $user . "'");
					$row = mysql_fetch_assoc($res2);
					$numRow = mysql_num_rows($res2);
					
					if($numRow <= 0){
						//Display Error Message
						echo "<center class='Text-1 clearfix title'>Error</center>";
						echo "<br><br><center class='Text-1 clearfix'>The <b>email</b> supplied is not associated with any accounts.</center>";
						echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Change Email Alert Settings</button></a></center>';						
					}
					else{
						if($setting == "notify"){
							if(count($secondSettings) == 2){
								mysql_query("UPDATE users SET notifications = 'all' WHERE email = '".$user."'");
							}
							else if(count($secondSettings) == 1 && current($secondSettings) == "messages"){
								mysql_query("UPDATE users SET notifications = 'messages' WHERE email = '".$user."'");
							}
							else if(count($secondSettings) == 1 && current($secondSettings) == "folder"){
								mysql_query("UPDATE users SET notifications = 'folder' WHERE email = '".$user."'");
							}
							else{
								mysql_query("UPDATE users SET notifications = 'none' WHERE email = '".$user."'");
							}
						}
						else if($setting == "noNotify"){
							mysql_query("UPDATE users SET notifications = 'none' WHERE email = '".$user."'");
						}
						
						echo "<br><br><center class='Text-1 clearfix'><strong>Your settings have been updated.</strong></center><br><br>";
						echo '<br><center class="Text-1 clearfix"><a href="/index.php"><button type="button" id="back">Go to Homepage &nbsp;<i class="fa fa-chevron-right color-blue"></i></button></a></center>';
					}
				}
			?>
		</div>
	</div>
</body>
</html>