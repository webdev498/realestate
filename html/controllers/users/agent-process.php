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
	<title>HomePik - Processing Buyer Login</title>
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
		<br><br>
		<?php
			if (isset($_POST['submit'])) { //Check to see if the form has been submitted
				$password = protect($_REQUEST['password']); //protect and then add the posted data to variables
				$email = protect($_REQUEST['email']);
				$formStep = protect($_REQUEST['formStep']);
				$role = 'agent';
				$stristr1 = stristr($email, 'bellmarc');
				$agent = 'false';
				if ($stristr1 != false) {
					$agent = 'true';
					$name = explode('@', $email);
					$name = $name[0];
					$pass = $password;
				}
				//check to see if any of the boxes were not filled in
				if ($formStep == 'login-register') {
					if (!$password || !$email) {
						echo "<center class='Text-1 clearfix title'>Login Error</center>";
						echo "<br><br><center class='Text-1 clearfix'>You need to fill in all of the required fields.</center>";
						echo '<br><br><center class="Text-1 clearfix"><a href="/controllers/agent-signin.php?e='.$email.'"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Login</button></a></center>';
					} else {
						//check if the password is less than 5 characters long
						if (strlen($password) < 5) {
							echo "<center class='Text-1 clearfix title'>Login Error</center>";
							echo "<br><br><center class='Text-1 clearfix'>Your <b>Password</b> must be more than 5 characters long.</center>";
							echo '<br><br><center class="Text-1 clearfix"><a href="/controllers/agent-signin.php?e='.$email.'"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Login</button></a></center>';
						} else {
							//Set the format we want to check out email address against
							$checkemail = "/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";
							//check if the formats match
							if (!preg_match($checkemail, $email)) {
								echo "<center class='Text-1 clearfix title'>Login Error</center>";
								echo "<br><br><center class='Text-1 clearfix'>The <b>E-mail</b> is not valid, must be name@server.tld.</center>";
								echo '<br><br><center class="Text-1 clearfix"><a href="/controllers/agent-signin.php?e='.$email.'"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Login</button></a></center>';
							}
							else {
								//select all rows from our users table where the emails match
								$res1 = mysql_query("SELECT * FROM registered_agents WHERE email = '" . $email . "'");
								$num1 = mysql_num_rows($res1);
								
								if($num1 > 0 || $agent == 'true') {
									//BEGIN LOGIN
									//protect the posted value then store them to variables
									$email = protect($_REQUEST['email']);
									$password = protect($_REQUEST['password']);
									//Check if the email or password boxes were not filled in
									if (!$email || !$password) {
										echo "<center class='Text-1 clearfix title'>Login Error</center>";
										echo "<br><br><center class='Text-1 clearfix'>You need to fill in a <b>email</b> and a <b>Password</b>.</center>";
										echo '<br><br><center class="Text-1 clearfix"><a href="/controllers/agent-signin.php?e='.$email.'"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Login</button></a></center>';
									} else {
										if ($agent != 'true') {
											echo "<center class='Text-1 clearfix title'>Login Error</center>";
											echo "<br><br><center class='Text-1 clearfix'>You must log in using Registered Buyer login.</center>";
											echo '<br><br><center class="Text-1 clearfix"><a href="/controllers/signin.php"><button type="button" id="back">Go to Buyer Login &nbsp;<i class="fa fa-chevron-right color-blue"></i></button></a></center>';
										}
										else{
											//select all rows from the table where the email matches the one entered by the user
											$res = mysql_query("SELECT rtime, password, id FROM registered_agents WHERE email = '" . $email . "'");
											$num = mysql_num_rows($res);
											$row = mysql_fetch_assoc($res);
											$id = $row['id'];
											$pass = $row['password'];
											$password = string_encrypt($password, $row['rtime']);
											//check if there was not a match
											if ($num == 0) {
													//if not display an error message
													echo "<center class='Text-1 clearfix title'>Login Error</center>";
													echo "<br><br><center class='Text-1 clearfix'>The <b>email</b> you supplied is not associated with any accounts.</center>";
													echo "<br><center class='Text-1 clearfix'>Please go back to the login to re-enter an email.</center> ";
													echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Login</button></a></center>';
											} else {
												//select all rows where the email and password match the ones submitted by the user
												$res = mysql_query("SELECT * FROM registered_agents WHERE email = '" . $email . "' AND password = '" . $password . "'");
												$num = mysql_num_rows($res);
												//check if there was not a match
												if ($num == 0) {
													//if not display error message
													print "<center class='Text-1 clearfix'>Re-directing...</center>";
													print "<script> window.location = 'http://homepik.com/controllers/agent-verification.php?x=1' </script>";
												} else {
													//split all fields fom the correct row into an associative array
													$row = mysql_fetch_assoc($res);
													// Set session data, but don't mark the user as logged in yet.
													$_SESSION['id'] = $id;
													$_SESSION['pass'] = $pass;
													$_SESSION['email'] = $email;
													$_SESSION['role'] = 'agent';
													$_SESSION['agent'] = 'true';
													
													//Clear the guest ID saved in session as user is no longer a guest
													unset($_SESSION['guestID']);
													
													//show message
													echo "<center class='Text-1 clearfix'>Logging in...</center>";
													//redirect them to the menu page
													print "<script> window.location = 'http://homepik.com/controllers/menu.php' </script>";
				
													// Set session for Google Analytics and Activity Analysis
													switch ($email) {
														case 'nbinder@bellmarc.com':
															$_SESSION['analytics'] = $email;
															$_SESSION['activity_analysis'] = $email;
															$_SESSION['admin_options'] = $email;
															break;
														case 'dbinder@bellmarc.com':
															$_SESSION['analytics'] = $email;
															$_SESSION['activity_analysis'] = $email;
															$_SESSION['admin_options'] = $email;
															break;
														case 'lpolanco@bellmarc.com':
															$_SESSION['analytics'] = $email;
															$_SESSION['activity_analysis'] = $email;
															$_SESSION['admin_options'] = $email;
															break;
														case 'dcroland@bellmarc.com':
															$_SESSION['analytics'] = $email;
															$_SESSION['activity_analysis'] = $email;																												
															$_SESSION['admin_options'] = '';
															break;
														case 'dblyth@bellmarc.com':
															$_SESSION['analytics'] = $email;
															$_SESSION['activity_analysis'] = $email;
															$_SESSION['admin_options'] = $email;
															break;
														case 'acannard@bellmarc.com':
															$_SESSION['analytics'] = $email;
															$_SESSION['activity_analysis'] = $email;
															$_SESSION['admin_options'] = $email;
															break;
														case 'jsarkodie@bellmarc.com':
															$_SESSION['analytics'] = $email;
															$_SESSION['activity_analysis'] = $email;
															$_SESSION['admin_options'] = $email;
															break;
														case 'jfranke@bellmarc.com':
															$_SESSION['analytics'] = $email;
															$_SESSION['activity_analysis'] = $email;
															$_SESSION['admin_options'] = $email;
															break;
														case 'wenglish@bellmarc.com':
															$_SESSION['analytics'] = $email;
															$_SESSION['activity_analysis'] = $email;																												
															$_SESSION['admin_options'] = '';
															break;
														case 'lburton@bellmarc.com':
															$_SESSION['analytics'] = $email;
															$_SESSION['activity_analysis'] = $email;																												
															$_SESSION['admin_options'] = '';
															break;
														default:
															$_SESSION['analytics'] = '';
															$_SESSION['activity_analysis'] = '';														
															$_SESSION['admin_options'] = '';
													}
				
													// Sets session for inactivity after 30 minutes
													$_SESSION['last_activity'] = time();
													//$_SESSION['end'] = 1800; // 30 minutes
													$_SESSION['end'] = 60; // 1 minute for testing
												}
											}
										}
									} // END LOGIN
								} else {
									echo "<center class='Text-1 clearfix title'>Login Error</center>";
									echo "<br><br><center class='Text-1 clearfix'>You must log in using Registered Buyer Login.</center>";
									echo '<br><br><center class="Text-1 clearfix"><a href="/controllers/signin.php"><button type="button" id="back">Go to Buyer Login &nbsp;<i class="fa fa-chevron-right color-blue"></i></button></a></center>';
								}
							}
						}
					}
				}
			}
		?>
  </div>
</body>
</html>
