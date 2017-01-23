<?php
session_start();
include_once("../dbconfig.php");
include_once("../functions.php");
include_once("../basicHead.php");
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
				$role = $_REQUEST['role'];
				$agent = 'false';
				$stristr1 = stristr($email, 'bellmarc');
				if ($stristr1 != false || $stristr1 !== false) { $agent = 'true'; }
				//check to see if any of the boxes were not filled in
				if ($formStep == 'login-register') {
					if (!$password || !$email) {
							//if any weren't display the error message
							echo "<center class='Text-1 clearfix title'>Login Error</center>";
							echo "<br><br><center class='Text-1 clearfix'>You need to fill in all of the required fields.</center>";
							echo '<br><br><center class="Text-1 clearfix"><a href="/signin.php?e='.$email.'"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Login</button></a></center>';
					} else {
						//otherwise continue checking
						//check if the password is less than 5 characters long
						if (strlen($password) < 5) {
							//if it is display error message
							echo "<center class='Text-1 clearfix title'>Login Error</center>";
							echo "<br><br><center class='Text-1 clearfix'>Your <b>Password</b> must be more than 5 characters long.</center>";
							echo '<br><br><center class="Text-1 clearfix"><a href="/signin.php?e='.$email.'"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Login</button></a></center>';
						} else {
							//continue checking
							//Set the format we want to check out email address against
							$checkemail = "/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";
							//check if the formats match
							if (!preg_match($checkemail, $email)) {
								//if not display error message
								echo "<center class='Text-1 clearfix title'>Login Error</center>";
								echo "<br><br><center class='Text-1 clearfix'>The <b>E-mail</b> is not valid, must be name@server.tld.</center>";
								echo '<br><br><center class="Text-1 clearfix"><a href="/signin.php?e='.$email.'"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Login</button></a></center>';
							}
							else {
								//if they do, continue checking
								//select all rows from our users table where the emails match
								$res1 = mysql_query("SELECT * FROM users WHERE email = '" . $email . "'");
								$num1 = mysql_num_rows($res1);
								//if the number of matchs is 1
								if ($num1 > 0 || $agent == 'true') {
									// Log the user in
									$email = protect($_REQUEST['email']);
									$password = protect($_REQUEST['password']);
									//Check if the email or password boxes were not filled in
									if (!$email || !$password) {
										//if not display an error message
										echo "<center class='Text-1 clearfix title'>Login Error</center>";
										echo "<br><br><center class='Text-1 clearfix'>You need to fill in an <b>email</b> and a <b>Password</b>.</center>";
										echo '<br><br><center class="Text-1 clearfix"><a href="/signin.php?e='.$email.'"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Login</button></a></center>';
									} else {
										if ($agent != 'true') {
												//if the were continue checking
												//select all rows from the table where the email matches the one entered by the user
												$res = mysql_query("SELECT rtime, password, id FROM users WHERE email = '" . $email . "'");
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
														echo "<br><center class='Text-1 clearfix'>Please go to <b>Create an Account</b> to make an account or go back to the login to re-enter an email.</center> ";
														echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back" class="extraSpace"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Login</button></a>&nbsp;&nbsp;<a href="/guest-register.php"><button type="button" id="back">Create an Account &nbsp;<i class="fa fa-chevron-right color-blue"></i></button></a></center>';
												} else {
													//if there was a match continue checking
													//select all rows where the email and password match the ones submitted by the user
													$res = mysql_query("SELECT * FROM users WHERE email = '" . $email . "' AND password = '" . $password . "'");
													$num = mysql_num_rows($res);
													//check if there was not a match
													if ($num == 0) {
														//if not display error message
														print "<center class='Text-1 clearfix'>Re-directing...</center>";
														print "<script> window.location = '/verification.php?x=1' </script>";
													} else {
														//if there was continue checking
														//split all fields fom the correct row into an associative array
														$row = mysql_fetch_assoc($res);
														// Set session data, and mark the user as logged in.
														$_SESSION['id'] = $id;
														$_SESSION['pass'] = $pass;
														$_SESSION['assigned'] = $row['assigned'];
														$_SESSION['firstname'] = $row['first_name'];
														$_SESSION['lastname'] = $row['last_name'];
														$_SESSION['phone'] = $row['phone'];
														$_SESSION['email'] = $email;
														$_SESSION['role'] = $role;	
														$_SESSION['agent1'] = $row['P_agent'];
														$_SESSION['agent2'] = $row['P_agent2'];													
														$_SESSION['user'] = 'true'; // Set 'user' as 'true' in the session. This marks the user as "logged in"
														$_SESSION['buyer'] = 'true';														
														
														unset($_SESSION['guestID']); //Clear the guest ID saved in session as user is no longer a guest

														// Get un-read message count
														$result2 = mysql_query("SELECT COUNT(*) as messages FROM `messages` as m LEFT JOIN `users` as u ON m.buyer=u.email WHERE (buyer = '".$email."') AND (sender != '".$email."') AND (m.time > u.online)") or die("Couldn't execute query.".mysql_error());
														$row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
														$_SESSION['unreadMessages'] = $row2['messages'];
														
														//update the online field
														$time = date('U');
														mysql_query("UPDATE users SET online = '" . $time . "' WHERE id = '" . $_SESSION['id'] . "' ");
														$_SESSION['logged_in'] = $time;
														
														if( (!isset($row['security_question']) || $row['security_question'] == "" || $row['security_question'] == 'default') || (!isset($row['security_answer']) || $row['security_answer'] == "") ){
															echo "<center class='Text-1 clearfix'>Re-directing...</center>"; //show message
															print "<script> window.location = '/verification-setup.php' </script>"; //redirect them to the verification setup page
														}
														else{
															echo "<center class='Text-1 clearfix'>Logging in...</center>"; //show message
															print "<script> window.location = '/menu.php' </script>"; //redirect them to the menu page
														}
														
														// Sets session for inactivity
														$_SESSION['last_activity'] = time();
														//$_SESSION['end'] = 1800; // 30 minutes
														$_SESSION['end'] = 60; // 1 minute for testing
													}
												}
											} else{
												echo "<center class='Text-1 clearfix title'>Login Error</center>";
												echo "<br><br><center class='Text-1 clearfix'>You must log in using Registered Agent login.</center>";
												echo '<br><br><center class="Text-1 clearfix"><a href="/agent-signin.php"><button type="button" id="back">Go to Agent Login &nbsp;<i class="fa fa-chevron-right color-blue"></i></button></a></center>';
											}
										} // END LOGIN
									} else {
										echo "<center class='Text-1 clearfix title'>Login Error</center>";
										echo "<br><br><center class='Text-1 clearfix'>The <b>email</b> you supplied is not associated with any accounts.</center>";
										echo "<br><center class='Text-1 clearfix'>Please go to <b>Create an Account</b> to make an account or go back to the login to re-enter an email.</center> ";
										echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back" class="extraSpace"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Login</button></a>&nbsp;&nbsp;<a href="/guest-register.php"><button type="button" id="back">Create an Account &nbsp;<i class="fa fa-chevron-right color-blue"></i></button></a></center>';
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
