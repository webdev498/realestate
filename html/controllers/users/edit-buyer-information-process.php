<?php
session_start();
include_once("../dbconfig.php");
include_once("../functions.php");
include_once("../emailconfig.php");
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
				//After form submits
				if(isset($_GET['submit'])) {
					$formStep = $_GET['formStep'];
					
					if($formStep == "editBuyerInfo"){
						$firstName = trim($_GET['firstName']);
						$lastName = trim($_GET['lastName']);
						$oldEmail = $_GET['oldEmail'];
						$email = $_GET['email'];
						$password = protect($_GET['password']);
						$agent1 = $_GET['agent1-code'];
						$agent2 = $_GET['agent2-code'];
						$securityOption = $_GET['security-option'];
						$phone = $_GET['phone'];
						$question = $_GET['security-question'];
						$answer = $_GET['security-answer'];
						
						$checkemail = "/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";
						$checkphone = "/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i";
						$errorMessage = " ";
	
						//Check if fields are filled in correctly
						if (preg_match('/[0-9]/', $firstName)) { $errorMessage .= "<center class='Text-1 clearfix'>First Name cannot have numeric values.</center><br>"; }
						if (preg_match('/[0-9]/', $lastName)) { $errorMessage .= "<center class='Text-1 clearfix'>Last Name cannot have numeric values.</center><br>"; }
						if (!preg_match($checkemail, $email)) { $errorMessage .= "<center class='Text-1 clearfix'>Email is invalid. An example of valid email is jsmith@email.com.</center><br>"; }
						if (strlen($password) < 5) { $errorMessage .= "<center class='Text-1 clearfix'>Password must be at least 5 characters long.</center><br>"; }
						if ( $phone != ""){ if (!preg_match($checkphone, $phone)) { $errorMessage .= "<center class='Text-1 clearfix'>Phone Number must be 10 digits.</center><br>"; } }
	
						if ($errorMessage != " "){
							echo "<center class='Text-1 clearfix title'>Buyer Information Error</center>";
							echo "<center class='Text-1 clearfix'><h3>Please return to the buyer information form to correct the following errors:</h3></center><br><br>";
							echo $errorMessage;
							//echo '<br><br><center class="Text-1 clearfix"><a href="../guest-register.php?f='.$firstName.'&l='.$lastName.'&e='.$email.'&p='.$phone.'&a='.$agent_code.'"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Registration Form</button></a></center>';
						}else {
							$res = mysql_query("SELECT EXISTS (SELECT * FROM users WHERE email = '" . $oldEmail . "') as num");
							$row = mysql_fetch_assoc($res);
							
							if($row['num'] == 0){
								echo "<center class='Text-1 clearfix title'>Buyer Information Error</center>";
								echo "<center class='Text-1 clearfix'><h3>There was an error finding your account.</h3></center><br><br>";
								//echo '<br><br><center class="Text-1 clearfix"><a href="../guest-register.php?f='.$firstName.'&l='.$lastName.'&e='.$email.'&p='.$phone.'&a='.$agent_code.'"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Registration Form</button></a></center>';
							}
							else{
								$res2 = mysql_query("SELECT * FROM users WHERE email = '" . $oldEmail . "'");
								$row2 = mysql_fetch_assoc($res2);
								$firstName = ucfirst($firstName);
								$lastName = ucfirst($lastName);
								$registerTime = $row2['rtime'];
								$pass = string_encrypt($password, $registerTime);
								
								if(strlen($agent1) > 3){
									$SQL = "SELECT agent_id FROM `registered_agents` WHERE (CONCAT(first_name, ' ', last_name) = '".$agent1."')";
									$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
									$row = mysql_fetch_array($result,MYSQL_ASSOC);
									
									if(isset($row['agent_id'])){ $agent1 = $row['agent_id']; }
									else{ $agent1 = ""; }
								}
								
								if(strlen($agent2) > 3){
									$SQL = "SELECT agent_id FROM `registered_agents` WHERE (CONCAT(first_name, ' ', last_name) = '".$agent2."')";
									$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
									$row = mysql_fetch_array($result,MYSQL_ASSOC);
									
									if(isset($row['agent_id'])){ $agent2 = $row['agent_id']; }
									else{ $agent2 = ""; }
								}
								
								if($agent1 != $row2['P_agent'] && $agent2 == $row2['P_agent2']){
									if($agent1 == ""){ $res3 = mysql_query("UPDATE users SET first_name = '".$firstName."', last_name = '".$lastName."', email = '".$email."', password = '".$pass."', P_agent = '".$agent1."', P_agent_assing_time=0, P_agent2 = '".$agent2."', security_option = '".$securityOption."', phone = '".$phone."', security_question = '".$question."', security_answer = '".$answer."'  WHERE email = '" . $email . "'"); }
									else{ $res3 = mysql_query("UPDATE users SET first_name = '".$firstName."', last_name = '".$lastName."', email = '".$email."', password = '".$pass."', P_agent = '".$agent1."', P_agent_assign_time='".date('U')."', P_agent2 = '".$agent2."', security_option = '".$securityOption."', phone = '".$phone."', security_question = '".$question."', security_answer = '".$answer."'  WHERE email = '" . $email . "'"); }
								}
								else if($agent1 == $row2['P_agent'] && $agent2 != $row2['P_agent2']){
									if($agent2 == ""){ $res3 = mysql_query("UPDATE users SET first_name = '".$firstName."', last_name = '".$lastName."', email = '".$email."', password = '".$pass."', P_agent = '".$agent1."', P_agent2 = '".$agent2."', P_agent2_assign_time=0, security_option = '".$securityOption."', phone = '".$phone."', security_question = '".$question."', security_answer = '".$answer."'  WHERE email = '" . $email . "'"); }
									else{ $res3 = mysql_query("UPDATE users SET first_name = '".$firstName."', last_name = '".$lastName."', email = '".$email."', password = '".$pass."', P_agent = '".$agent1."', P_agent2 = '".$agent2."', P_agent2_assign_time='".date('U')."', security_option = '".$securityOption."', phone = '".$phone."', security_question = '".$question."', security_answer = '".$answer."'  WHERE email = '" . $email . "'"); }
								}
								else if($agent1 != $row2['P_agent'] && $agent2 != $row2['P_agent2']){
									if($agent1 == "" && $agent2 == ""){ $res3 = mysql_query("UPDATE users SET first_name = '".$firstName."', last_name = '".$lastName."', email = '".$email."', password = '".$pass."', P_agent = '".$agent1."', P_agent_assign_time=0, P_agent2 = '".$agent2."', P_agent2_assign_time=0, security_option = '".$securityOption."', phone = '".$phone."', security_question = '".$question."', security_answer = '".$answer."'  WHERE email = '" . $email . "'"); }
									else if($agent1 != "" && $agent2 == ""){ $res3 = mysql_query("UPDATE users SET first_name = '".$firstName."', last_name = '".$lastName."', email = '".$email."', password = '".$pass."', P_agent = '".$agent1."', P_agent_assign_time='".date('U')."', P_agent2 = '".$agent2."', P_agent2_assign_time=0, security_option = '".$securityOption."', phone = '".$phone."', security_question = '".$question."', security_answer = '".$answer."'  WHERE email = '" . $email . "'"); }
									else if($agent1 == "" && $agent2 != ""){ $res3 = mysql_query("UPDATE users SET first_name = '".$firstName."', last_name = '".$lastName."', email = '".$email."', password = '".$pass."', P_agent = '".$agent1."', P_agent_assign_time=0, P_agent2 = '".$agent2."', P_agent2_assign_time='".date('U')."', security_option = '".$securityOption."', phone = '".$phone."', security_question = '".$question."', security_answer = '".$answer."'  WHERE email = '" . $email . "'"); }
									else{ $res3 = mysql_query("UPDATE users SET first_name = '".$firstName."', last_name = '".$lastName."', email = '".$email."', password = '".$pass."', P_agent = '".$agent1."', P_agent_assign_time='".date('U')."', P_agent2 = '".$agent2."', P_agent2_assign_time='".date('U')."', security_option = '".$securityOption."', phone = '".$phone."', security_question = '".$question."', security_answer = '".$answer."'  WHERE email = '" . $email . "'"); }
								}
								else{							
									$res3 = mysql_query("UPDATE users SET first_name = '".$firstName."', last_name = '".$lastName."', email = '".$email."', password = '".$pass."', P_agent = '".$agent1."', P_agent2 = '".$agent2."', security_option = '".$securityOption."', phone = '".$phone."', security_question = '".$question."', security_answer = '".$answer."'  WHERE email = '" . $email . "'");
								}
								
								$_SESSION['id'] = $row2['id'];
								$_SESSION['assigned'] = $row2['assigned'];
								$_SESSION['email'] = $email;
								$_SESSION['role'] = "buyer";
								$_SESSION['user'] = 'true';
								$_SESSION['buyer'] = 'true';
								
								$time = date('U');
								mysql_query("UPDATE users SET online = '" . $time . "' WHERE email = '" . $email . "' "); //update the online field							
								$_SESSION['logged_in'] = $time;
								
								$message = 'Hello ' . $firstname. ' ' . $lastname . ', <br><br>';
							  $message .= 'Your buyer information with HomePik has been updated.';
							  $message .= "<br><br><center><a href='http://www.homepik.com/controllers/change-email-alert-settings.php?user=".$email."'>Change Email Alert Settings</a></center><br>";
								$message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";

								$mail->addAddress($email);
								$mail->Subject = 'HomePik.com Profile Updated';
								$mail->Body = $message;
								$mail->send();
								
								echo "<br><br><center class='Text-1 clearfix'>Welcome back. Your buyer information has been updated and your registered buyer privileges are reinstalled.</center><br><br>";
								echo '<br><center class="Text-1 clearfix"><a href="/menu.php"><button type="button" id="back">Go to Buyer Home Page &nbsp;<i class="fa fa-chevron-right color-blue"></i></button></a></center>';
							}
						}
					}
					else if($formStep == "noEditContinue"){
						$email = $_GET['email'];
						
						$res2 = mysql_query("SELECT first_name, last_name, id, assigned, rtime FROM users WHERE email = '" . $email . "'");
						$row2 = mysql_fetch_assoc($res2);
											
						$_SESSION['id'] = $row2['id'];
						$_SESSION['assigned'] = $row2['assigned'];
						$_SESSION['email'] = $email;
						$_SESSION['role'] = "buyer";
						$_SESSION['user'] = 'true';
						$_SESSION['buyer'] = 'true';
						
						$time = date('U');
						mysql_query("UPDATE users SET online = '" . $time . "' WHERE email = '" . $email . "' "); //update the online field							
						$_SESSION['logged_in'] = $time;
						
						$message = "Hello " . $row2['first_name']. " " . $row2['lastname'] . ", <br><br>";
						$message .= 'Your password for HomePik has been reset.';
						$message .= "<br><br><center><a href='http://www.homepik.com/controllers/change-email-alert-settings.php?user=".$email."'>Change Email Alert Settings</a></center><br>";
						$message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";

						$mail->addAddress($email);
						$mail->Subject = 'HomePik.com Profile Updated';
						$mail->Body = $message;
						$mail->send();
						
						echo "<br><br><center class='Text-1 clearfix'>Welcome back. Your password is now reset and your registered buyer privileges are reinstalled.</center><br><br>";
						echo '<br><center class="Text-1 clearfix"><a href="/menu.php"><button type="button" id="back">Go to Buyer Home Page &nbsp;<i class="fa fa-chevron-right color-blue"></i></button></a></center>';
					}
				}
			?>
		</div>
	</div>
</body>
</html>
