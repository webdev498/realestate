<?php
session_start();
include("../dbconfig.php");
include("../functions.php");
include("../basicHead3.php");
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());
if(isset($_SESSION['role'])){ $role = $_SESSION['role']; }
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
						<a href='/index.php'><img class="block" id="u25521_img_1" src="/images/homepik_logo_bubbles_legend_7_part_1.png" alt=""/></a>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-7" id="second-section">
						<a href='/index.php'><img class="block" id="u25521_img_2" src="/images/homepik_logo_bubbles_legend_7_part_2.png" alt=""/></a>
					</div>
					<div class="col-md-5 col-sm-5 col-xs-12" id="third-section">
						<a href='/index.php'><img class="block" id="u25521_img_3" src="/images/homepik_logo_bubbles_legend_7_part_3.png" alt=""/></a>
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
					
					if($formStep == "editAgentInfo"){
						$firstName = trim($_GET['firstName']);
						$lastName = trim($_GET['lastName']);
						$oldEmail = $_GET['oldEmail'];
						$email = $_GET['email'];
						$password = protect($_GET['password']);
						$phone = $_GET['phone'];
						$bio = $_GET['bio'];
						
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
							echo "<center class='Text-1 clearfix title'>Agent Information Error</center>";
							echo "<center class='Text-1 clearfix'><h3>Please return to the agent information form to correct the following errors:</h3></center><br><br>";
							echo $errorMessage;
							//echo '<br><br><center class="Text-1 clearfix"><a href="../guest-register.php?f='.$firstName.'&l='.$lastName.'&e='.$email.'&p='.$phone.'&a='.$agent_code.'"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Registration Form</button></a></center>';
						}else {
							$res = mysql_query("SELECT EXISTS (SELECT * FROM registered_agents WHERE email = '" . $oldEmail . "') as num");
							$row = mysql_fetch_assoc($res);
							
							if($row['num'] == 0){
								echo "<center class='Text-1 clearfix title'>Agent Information Error</center>";
								echo "<center class='Text-1 clearfix'><h3>There was an error finding your account.</h3></center><br><br>";
								//echo '<br><br><center class="Text-1 clearfix"><a href="../guest-register.php?f='.$firstName.'&l='.$lastName.'&e='.$email.'&p='.$phone.'&a='.$agent_code.'"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Registration Form</button></a></center>';
							}
							else{
								$res2 = mysql_query("SELECT id, agent_id, admin, rtime FROM registered_agents WHERE email = '" . $oldEmail . "'");
								$row2 = mysql_fetch_assoc($res2);
								$firstName = ucfirst($firstName);
								$lastName = ucfirst($lastName);
								$registerTime = $row2['rtime'];
								$pass = string_encrypt($password, $registerTime);								
								
								$res3 = mysql_query("UPDATE registered_agents SET first_name = '".$firstName."', last_name = '".$lastName."', email = '".$email."', password = '".$pass."', phone = '".$phone."', bio = '".$bio."'  WHERE email = '" . $oldEmail . "'");						
															
								$_SESSION['id'] = $row2['id'];
								$_SESSION['pass'] = $pass;
								$_SESSION['agent_id'] = $row2['agent_id'];
								$_SESSION['firstname'] = $firstName;
								$_SESSION['lastname'] = $lastName;
								$_SESSION['phone'] = $phone;
								$_SESSION['email'] = $email;
								$_SESSION['role'] = 'agent';
								$_SESSION['agent'] = 'true';
								$_SESSION['admin'] = $row2['admin'];
								
								// Get un-read message count
								$result = mysql_query("SELECT COUNT(*) as messages FROM `messages` as m LEFT JOIN `registered_agents` as r ON m.agent=r.email WHERE (agent = '".$email."') AND (sender != '".$email."') AND (m.time > r.online)") or die("Couldn't execute query.".mysql_error());
								$row = mysql_fetch_array($result,MYSQL_ASSOC);
								$_SESSION['unreadMessages'] = $row['messages'];
								
								mysql_query("UPDATE registered_agents SET online = '" . date('U') . "' WHERE email = '" . $email . "' "); //update the online field
								
								$message = 'Hello ' . $firstname. ' ' . $lastname . ', <br><br>';
							  $message .= 'Your agent information with HomePik has been updated.';
								$message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";

								$mail->addAddress($email);
								$mail->Subject = 'HomePik.com Profile Updated';
								$mail->Body = $message;
								$mail->send();
								
								echo "<br><br><center class='Text-1 clearfix'>Welcome back. Your agent information has been updated and your registered agent privileges are reinstalled.</center><br><br>";
								echo '<br><center class="Text-1 clearfix"><a href="/menu.php"><button type="button" id="back">Go to Agent Home Page &nbsp;<i class="fa fa-chevron-right color-blue"></i></button></a></center>';
							}
						}
					}
					else if($formStep == "noEditContinue"){
						$email = $_GET['email'];
						
						$res2 = mysql_query("SELECT id, first_name, last_name, phone, admin, agent_id, rtime FROM registered_agents WHERE email = '" . $email . "'");
						$row2 = mysql_fetch_assoc($res2);
											
						$_SESSION['id'] = $row2['id'];
						$_SESSION['agent_id'] = $row2['agent_id'];
						$_SESSION['firstname'] = $row2['first_name'];
						$_SESSION['lastname'] = $row2['last_name'];
						$_SESSION['phone'] = $row2['phone'];;
						$_SESSION['email'] = $email;
						$_SESSION['role'] = 'agent';
						$_SESSION['agent'] = 'true';
						$_SESSION['admin'] = $row2['admin'];
						
						mysql_query("UPDATE registered_agents SET online = '" . date('U') . "' WHERE email = '" . $email . "' "); //update the online field
						
						echo "<br><br><center class='Text-1 clearfix'>Welcome back. Your password is now reset and your registered agent privileges are reinstalled.</center><br><br>";
						echo '<br><center class="Text-1 clearfix"><a href="/menu.php"><button type="button" id="back">Go to Agent Home Page &nbsp;<i class="fa fa-chevron-right color-blue"></i></button></a></center>';
					}
				}
			?>
		</div>
  </div>
</body>
</html>