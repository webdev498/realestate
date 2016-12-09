<?php
session_start();
include("../dbconfig.php");
include("../functions.php");
include("../basicHeadOld.php");
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
					$formStep = $_REQUEST['formStep'];

					if($formStep == 'verification1'){
						$firstName = strtolower($_REQUEST['firstName']);
					  $lastName = strtolower($_REQUEST['lastName']);
						$email = $_REQUEST['email'];
						$phone = $_REQUEST['phone'];
						$question = $_REQUEST['security-question'];
						$answer = $_REQUEST['security-answer'];
						$name = explode('@', $email);

						if (!$firstName || !$lastName || !$email) {
							//if any weren't display the error message
							echo "<center class='Text-1 clearfix title'>Validation Error</center>";
							echo "<center class='Text-1 clearfix'><br><br>You need to fill in all of the required fields.</center>";
							echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Verification Form</button></a></center>';
					  } else {
							//otherwise continue checking
							//Set the format we want to check out email address against
							$checkemail = "/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";
							//check if the formats match
							if (!preg_match($checkemail, $email)) {
								//if not display error message
								echo "<center class='Text-1 clearfix title'>Validation Error</center>";
								echo "<center class='Text-1 clearfix'><br><br>The email you put in does not appear valid. Please check your email and re-enter.</center>";
								echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Verification Form</button></a></center>';
							} else {
								//if they do, continue checking
								//select all rows from our users table where the emails match
								$res1 = mysql_query("SELECT * FROM users WHERE email = '" . $email . "'");
								$num1 = mysql_num_rows($res1);
								//if the number of matchs is 1
								if ($num1 >= 1) {
									//select all rows from the table where the email matches the one entered by the user
									$res = mysql_query("SELECT first_name, last_name, phone, security_question, security_answer, active FROM users WHERE email = '" . $email . "'");
									$row = mysql_fetch_assoc($res);
									$fn = strtolower($row['first_name']);
									$ln = strtolower($row['last_name']);
									$p = $row['phone'];
									$sq = $row['security_question'];
									$sa = $row['security_answer'];

									if($phone != ""){
										if($firstName === $fn && $lastName === $ln && $phone === $p){
										  echo "<center class='Text-1 clearfix'>Re-directing...</center>";
										  print "<script> window.location = 'http://homepik.com/controllers/password-reset.php' </script>";
										}
										else{
											echo "<center class='Text-1 clearfix title'>Validation Error</center>";
										  echo "<center class='Text-1 clearfix'><br><br>The information you entered did not match the information on file.</center>";
										  echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Verification Form</button></a></center>';										
										}
									}
									else if($question != "default"){
										if($firstName == $fn && $lastName == $ln && $question == $sq && $answer == $sa){
										  echo "<center class='Text-1 clearfix'>Re-directing...</center>";
										  print "<script> window.location = 'http://homepik.com/controllers/password-reset.php' </script>";
										}
										else{
											echo "<center class='Text-1 clearfix title'>Validation Error</center>";
										  echo "<center class='Text-1 clearfix'><br><br>The information you entered did not match the information on file.</center>";
										  echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Verification Form</button></a></center>';
										}
									}
									else{
										// Do nothing maybe later add error message
									}
			 					} else {
									echo "<center class='Text-1 clearfix title'>Validation Error</center>";
									echo "<center class='Text-1 clearfix'><br><br>No account is associated with the <b>Email</b> you supplied.</center>";
									echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Verification Form</button></a></center>';
								}
							}
						}
					}

					if($formStep == 'verification2'){
  					$firstName = strtolower($_REQUEST['firstName']);
  					$lastName = strtolower($_REQUEST['lastName']);
  					$email = $_REQUEST['email'];
  					$phone = $_REQUEST['phone'];
  					$question = $_REQUEST['security-question'];
  					$answer = $_REQUEST['security-answer'];
  					$name = explode('@', $email);

						if (!$firstName || !$lastName || !$email) {
							//if any weren't display the error message
							echo "<center class='Text-1 clearfix title'>Validation Error</center>";
							echo "<center class='Text-1 clearfix'><br><br>You need to fill in all of the required fields.</center>";
							echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Verification Form</button></a></center>';
						} else {
							  //otherwise continue checking
							  //Set the format we want to check out email address against
							  $checkemail = "/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";
							  //check if the formats match
							  if (!preg_match($checkemail, $email)) {
								  //if not display error message
									echo "<center class='Text-1 clearfix title'>Validation Error</center>";
								  echo "<center class='Text-1 clearfix'><br><br>The email you put in does not appear valid. Please check your email and re-enter.</center>";
								  echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Verification Form</button></a></center>';
							  } else {
								  //if they do, continue checking
								  //select all rows from our users table where the emails match
								  $res1 = mysql_query("SELECT * FROM users WHERE email = '" . $email . "'");
								  $num1 = mysql_num_rows($res1);
								  //if the number of matchs is 1
								  if ($num1 >= 1) {
									//select all rows from the table where the email matches the one entered by the user
									$res = mysql_query("SELECT first_name, last_name, password, phone, security_question, security_answer, active FROM users WHERE email = '" . $email . "'");
									$row = mysql_fetch_assoc($res);
									$fn = strtolower($row['first_name']);
									$ln = strtolower($row['last_name']);
									$p = $row['phone'];
									$sq = $row['security_question'];
									$sa = $row['security_answer'];

									if($phone != ""){
										if($firstName == $fn && $lastName == $ln && $phone == $p){
										  $_SESSION['id'] = $row['id'];
										  $_SESSION['assigned'] = $row['assigned'];
										  $_SESSION['email'] = $email;
										  $_SESSION['role'] = "buyer";
										  $_SESSION['user'] = 'true';
										  $_SESSION['buyer'] = 'true';

										  //update the online field
										  $time = date('U');
										  mysql_query("UPDATE users SET online = '" . $time . "' WHERE email = '" . $email . "' ");
										  $_SESSION['logged_in'] = $time;

										  echo "<br><br><center class='Text-1 clearfix'><b>Logging In...</b></center>";
										  print "<script> window.location = 'http://homepik.com/controllers/menu.php' </script>";
										}
										else{
											echo "<center class='Text-1 clearfix title'>Validation Error</center>";
										  echo "<center class='Text-1 clearfix'><br><br>The information you entered did not match the information on file.</center>";
										  echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Verification Form</button></a></center>';
										}
									}
									else if($question != "default"){
  								  if($firstName == $fn && $lastName == $ln && $question == $sq && $answer == $sa){
  										  $_SESSION['id'] = $row['id'];
  										  $_SESSION['assigned'] = $row['assigned'];
  										  $_SESSION['email'] = $email;
  										  $_SESSION['role'] = "buyer";
  										  $_SESSION['user'] = 'true';
  										  $_SESSION['buyer'] = 'true';

  										  //update the online field
  										  $time = date('U');
  										  mysql_query("UPDATE users SET online = '" . $time . "' WHERE email = '" . $email . "' ");
  										  $_SESSION['logged_in'] = $time;

  										  echo "<br><br><center class='Text-1 clearfix'><b>Logging In...</b></center>";
  										  print "<script> window.location = 'http://homepik.com/controllers/menu.php' </script>";
  										}
								    }
								  }
								  else{
										echo "<center class='Text-1 clearfix title'>Validation Error</center>";
										echo "<center class='Text-1 clearfix'><br><br>No account is associated with the <b>Email</b> you supplied.</center>";
										echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Verification Form</button></a></center>';
								  }
							  }
  						}
  					}
  				}
			?>
		</div>
	</div>
</body>
</html>
