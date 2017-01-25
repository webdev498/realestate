<?php
session_start();
include_once("../dbconfig.php");
include_once("../basicHead.php");
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>HomePik - Processing Buyer Verification</title>	
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
				if (isset($_POST['submit']))  {
					$formStep = $_POST['formStep'];
					$email = $_SESSION['email'];
					$question = $_POST['security-question'];
					$answer = $_POST['security-answer'];
					
					if(isset($_SESSION['buyer'])){
						//select all rows from our users table where the emails match
						$res1 = mysql_query("SELECT * FROM users WHERE email = '" . $email . "'");
						$num1 = mysql_num_rows($res1);
						//if the number of matchs is 1
						if ($num1 >= 1) {
							if($question != "default"){	
									mysql_query("UPDATE users SET security_question = '" . $question . "', security_answer = '" . $answer . "' WHERE email = '" . $email . "' ");
	
									echo "<br><br><center class='Text-1 clearfix'>Welcome back. Your security question and answer has been saved. You can now use this to access your account if you forget your password.</center><br><br>";
									echo '<br><center class="Text-1 clearfix"><a href="/menu.php"><button type="button" id="back">Go to Buyer Home Page &nbsp;<i class="fa fa-chevron-right color-blue"></i></button></a></center>';
							}
							else{
								echo "<center class='Text-1 clearfix title'>Validation Error</center>";
								echo "<center class='Text-1 clearfix'><br><br>The security question and answer cannot be empty.</center>";
								echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Verification Setup</button></a></center>';
							}
						}
						else{
							echo "<center class='Text-1 clearfix title'>Validation Error</center>";
							echo "<center class='Text-1 clearfix'><br><br>No account was found. Please login.</center>";
							echo '<br><br><center class="Text-1 clearfix"><a href="/index.php"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Homepage</button></a></center>';
						}
					}
					elseif(isset($_SESSION['agent'])){
						//select all rows from our users table where the emails match
						$res1 = mysql_query("SELECT * FROM registered_agents WHERE email = '" . $email . "'");
						$num1 = mysql_num_rows($res1);
						//if the number of matchs is 1
						if ($num1 >= 1) {
							if($question != "default"){	
									mysql_query("UPDATE registered_agents SET security_question = '" . $question . "', security_answer = '" . $answer . "' WHERE email = '" . $email . "' ");
	
									echo "<br><br><center class='Text-1 clearfix'>Welcome back. Your security question and answer has been saved. You can now use this to access your account if you forget your password.</center><br><br>";
									echo '<br><center class="Text-1 clearfix"><a href="/menu.php"><button type="button" id="back">Go to Agent Home Page &nbsp;<i class="fa fa-chevron-right color-blue"></i></button></a></center>';
							}
							else{
								echo "<center class='Text-1 clearfix title'>Validation Error</center>";
								echo "<center class='Text-1 clearfix'><br><br>The security question and answer cannot be empty.</center>";
								echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Verification Setup</button></a></center>';
							}
						}
						else{
							echo "<center class='Text-1 clearfix title'>Validation Error</center>";
							echo "<center class='Text-1 clearfix'><br><br>No account was found. Please login.</center>";
							echo '<br><br><center class="Text-1 clearfix"><a href="/index.php"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Homepage</button></a></center>';
						}
					}
					else{
						echo "<center class='Text-1 clearfix title'>Validation Error</center>";
							echo "<center class='Text-1 clearfix'><br><br>Please login before setting up a security question and answer.</center>";
							echo '<br><br><center class="Text-1 clearfix"><a href="/index.php"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Homepage</button></a></center>';
					}
				}
			?>
		</div>
	</div>
</body>
</html>