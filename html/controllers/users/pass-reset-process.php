<?php
session_start();
include("../dbconfig.php");
include("../functions.php");
include("../basicHeadOld.php");
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());
if($_SESSION['role']){ $role = $_SESSION['role']; }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>HomePik - Processing Password Reset</title>
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
					$email = $_GET['email'];
					$newPassword = protect($_GET['newPassword']);
					$confirmPassword = protect($_GET['confirmPassword']);

					if (!$email || !$newPassword || !$confirmPassword) {
						//If field wasn't filled out
						echo "<center class='Text-1 clearfix title'>Password Reset Error</center>";
						echo "<br><br><center class='Text-1 clearfix'>You need to fill all fields.</center>";
						echo '<br><br><center class=Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Form</button></a></center>';
					} else {
						if (strlen($newPassword) < 5) {
							//if it is display error message
							echo "<center class='Text-1 clearfix title'>Password Reset Error</center>";
							echo "<br><br><center class='Text-1 clearfix'>Your <b>Password</b> must be more than 5 characters long.</center>";
							echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Form</button></a></center>';
						} else {
							if($newPassword == $confirmPassword) {
								if(strpos($email, "@bellmarc.com") !== false){ $res2 = mysql_query("SELECT email, password, rtime FROM registered_agents WHERE email = '" . $email . "'"); }
								else{ $res2 = mysql_query("SELECT email, password, rtime FROM users WHERE email = '" . $email . "'"); }
								$row = mysql_fetch_assoc($res2);
								$registerTime = $row['rtime'];
								$pass = $row['password'];
								$numRow = mysql_num_rows($res2);
								//Search for email
								if ($numRow >= 1) { 
									if(strpos($email, "@bellmarc.com") !== false){ $res = mysql_query("SELECT * FROM registered_agents WHERE email = '" . $email . "'"); }
									else{ $res = mysql_query("SELECT * FROM users WHERE email = '" . $email . "'"); }
									$num = mysql_num_rows($res);
									//check if there was not a match
									$newPassword = string_encrypt($newPassword, $registerTime);
									if(strpos($email, "@bellmarc.com") !== false){ $res3 = mysql_query("UPDATE registered_agents SET password = '" . $newPassword . "', rtime = '" . $registerTime . "' WHERE email = '" . $email . "'"); }
									else{ $res3 = mysql_query("UPDATE users SET password = '" . $newPassword . "', rtime = '" . $registerTime . "' WHERE email = '" . $email . "'"); }
									$row = mysql_fetch_assoc($res3);						
									
									if(strpos($email, "@bellmarc.com") !== false){
										$_SESSION['id'] = $row['id'];
										$_SESSION['email'] = $email;
										$_SESSION['role'] = 'agent';
										$_SESSION['agent'] = 'true';

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
										
										echo "<br><br><center class='Text-1 clearfix'>Welcome back. Your password is now reset and your agent privileges are reinstalled.</center><br><br>";
										echo '<br><center class="Text-1 clearfix"><a href="/menu.php"><button type="button" id="back">Go to Agent Home Page &nbsp;<i class="fa fa-chevron-right color-blue"></i></button></a></center>';
									}
									else{
										$_SESSION['id'] = $row['id'];
										$_SESSION['pass'] = $row['pass'];
										$_SESSION['assigned'] = $row['assigned'];
										$_SESSION['email'] = $email;
										$_SESSION['role'] = "buyer";
										$_SESSION['user'] = 'true';
										$_SESSION['buyer'] = 'true';
										$time = date('U');
										mysql_query("UPDATE users SET online = '" . $time . "' WHERE email = '" . $email . "' "); //update the online field
										$_SESSION['logged_in'] = $time;
										echo "<br><br><center class='Text-1 clearfix'>Welcome back. Your password is now reset and your registered buyer privileges are reinstalled.</center><br><br>";
										echo '<br><center class="Text-1 clearfix"><a href="/menu.php"><button type="button" id="back">Go to Buyer Home Page &nbsp;<i class="fa fa-chevron-right color-blue"></i></button></a></center>';
									}
								} else {
									//Display error message
									echo "<center class='Text-1 clearfix title'>Password Reset Error</center>";
									echo "<br><br><center class='Text-1 clearfix'>Email not found.</center>";
									echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Form</button></a></center>';
								}
							} else {
								echo "<center class='Text-1 clearfix title'>Password Reset Error</center>";
								echo "<br><br><center class='Text-1 clearfix'>Passwords don't match</center>";
								echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Form</button></a></center>';
							}
						}
					}	
				}
			?>
		</div>
  </div>
</body>
</html>