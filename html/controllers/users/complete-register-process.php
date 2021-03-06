<?php
session_start();
include_once("../basicHead.php");
include_once("../dbconfig.php");
include_once("../emailconfig.php");
include_once("../functions.php");
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>HomePik - Processing Guest Registration</title>
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
					$firstName = mysql_real_escape_string($_POST['firstName']);
					$lastName = mysql_real_escape_string($_POST['lastName']);
					$phone = mysql_real_escape_string($_POST['phone']);
					$email = mysql_real_escape_string($_POST['email']);
					$newPassword = mysql_real_escape_string($_POST['newPassword']);
					$copyPass = mysql_real_escape_string($_POST['newPassword']);
					$question = $_POST['security-question'];
					$answer = mysql_real_escape_string($_POST['security-answer']);
					$terms = $_POST['terms'];
					$name = explode('@', $email);	
					$agent = 'false';
					$stristr1 = stristr($email, 'bellmarc');
					if ($stristr1 != false || $stristr1 !== false) { $agent = 'true'; }			
					
					$checkemail = "/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";
					$checkphone = "/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i";
					$errorMessage = " ";

					//Check if fields are filled in correctly
					if (preg_match('/[0-9]/', $firstName)) { $errorMessage .= "<center class='Text-1 clearfix'>First Name cannot have numeric values.</center><br>"; }
					if (preg_match('/[0-9]/', $lastName)) { $errorMessage .= "<center class='Text-1 clearfix'>Last Name cannot have numeric values.</center><br>"; }
					if (!preg_match($checkemail, $email)) { $errorMessage .= "<center class='Text-1 clearfix'>Email is invalid. An example of valid email is jsmith@email.com.</center><br>"; }
					if (strlen($newPassword) < 5) { $errorMessage .= "<center class='Text-1 clearfix'>Password must be at least 5 characters long.</center><br>"; }
					if ( $phone != ""){  
					  if (!preg_match($checkphone, $phone)) { $errorMessage .= "<center class='Text-1 clearfix'>Phone Number must be 10 digits.</center><br>"; }
					}					
					
					//Check if any errors found
					if ($errorMessage != " "){						
						echo "<center class='Text-1 clearfix title'>Complete Registration Error</center>";
					  echo "<center class='Text-1 clearfix'><h3>Please return to the registration form to correct the following errors:</h3></center><br><br>";
					  echo $errorMessage;
					  echo '<br><br><center class="Text-1 clearfix"><a href="http://homepik.com/controllers/complete-register.php?f='.$firstName.'&l='.$lastName.'&e='.$email.'&p='.$phone.'&a='.$agent_code.'"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Registration Form</button></a></center>';
					} else {
						//select all rows from our users table where the emails match
						$res1 = mysql_query("SELECT * FROM users WHERE email = '" . $email . "'");
						$num1 = mysql_num_rows($res1);
						//if the number of matchs is 1
						if ($num1 > 0 || $agent != 'true'){
							//BEGIN REGISTRATION COMPLETION
							//protect the posted value then store them to variables
							$email = protect($_POST['email']);
							$newPassword = protect($_POST['newPassword']);
							//Check if the email or password boxes were not filled in
							if (!$email || !$newPassword) {
								//if not display an error message
								echo "<center class='Text-1 clearfix title'>Complete Registration Error</center>";
								echo "<br><br><center class='Text-1 clearfix'>You need to fill in a <b>email</b> and a <b>Password</b>.</center>";
								echo '<br><br><center class="Text-1 clearfix"><a href="http://homepik.com/controllers/complete-register.php?f='.$firstName.'&l='.$lastName.'&e='.$email.'&p='.$phone.'&a='.$agent_code.'"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Registration Form</button></a></center>';
							} else {
								//select all rows from the table where the email matches the one entered by the user
								$res = mysql_query("SELECT rtime, password, id FROM users WHERE email = '" . $email . "'");
								$num = mysql_num_rows($res);
								$row = mysql_fetch_assoc($res);
								$id = $row['id'];
								$pass = $row['password'];
								$registerTime = $row['rtime'];
								$newPassword = string_encrypt($newPassword, $registerTime);
								//check if there was not a match
								if ($num == 0) {
									echo "<center class='Text-1 clearfix title'>Complete Registration Error</center>";
									echo "<br><br><center class='Text-1 clearfix'>The <b>email</b> you supplied is not associated with any accounts.</center>";
									echo "<br><center class='Text-1 clearfix'>Please go to <b>Create an Account</b> to make an account or go back to the registration form to re-enter an email.</center> ";
									echo '<br><br><center class="Text-1 clearfix"><a href="http://homepik.com/controllers/complete-register.php?f='.$firstName.'&l='.$lastName.'&e='.$email.'&p='.$phone.'&a='.$agent_code.'"><button type="button" id="back" class="extraSpace"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Registration Form</button></a>&nbsp;&nbsp;<a href="/controllers/signin.php"><button type="button" id="back">Create Account &nbsp;<i class="fa fa-chevron-right color-blue"></i></button></a></center>';
								} else {
									
									if($phone != ''){ $securityOption = "Phone"; }
									else if( $question != "default" ){ $securityOption = "Question"; }
									
									$res = mysql_query("UPDATE users SET password = '" . $newPassword . "', rtime = '" . $registerTime . "', security_option = '" . $securityOption . "', phone = '" . $phone . "', security_question = '" . $question . "', security_answer = '" . $answer . "' WHERE email = '" . $email . "'");
									$row = mysql_fetch_assoc($res);
									//if there was a match continue checking
									//select all rows where the email and password match the ones submitted by the user
									$res = mysql_query("SELECT * FROM users WHERE email = '" . $email . "' AND password = '" . $newPassword . "'");
									$num = mysql_num_rows($res);
									//check if there was not a match
									if ($num == 0) {
										//if not display error message
										echo "<center class='Text-1 clearfix title'>Complete Registration Error</center>";
										echo "<center class='Text-1 clearfix'><br><br>An account associated with the <b>Email</b> you supplied already exists.</center>";
										echo "<center class='Text-1 clearfix'>Please click <b>Reset Password</b> to reset your password.</center>";
										echo '<br><br><center class="Text-1 clearfix"><a href="/verification.php"><button type="button" id="back">Reset Password &nbsp;<i class="fa fa-chevron-right color-blue"></i></button></a></center>';
									} else { 
										//if there was continue checking
										//split all fields fom the correct row into an associative array
										$row = mysql_fetch_assoc($res);
										// Set session data, set 'user' as 'true' in the session. This marks the user as "logged in"
										$_SESSION['id'] = $id;
										$_SESSION['firstname'] = $firstName;
										$_SESSION['lastname'] = $lastName;
										$_SESSION['phone'] = $phone;
										$_SESSION['email'] = $email;
										$_SESSION['role'] = "buyer";	
										$_SESSION['agent1'] = $row['P_agent'];
										$_SESSION['agent2'] = '';
										$_SESSION['pass'] = $pass;
										$_SESSION['assigned'] = $row['assigned'];
										$_SESSION['user'] = 'true';										
										$_SESSION['buyer'] = 'true';
										
										$message = "Hello ".$firstName." ".$lastName.",<br><br>";
										$message .= "Thank you for registering on HomePik.com.<br><br>";
										$message .= "Your log in information is:<br>";
										$message .= "Username: ". $email . "<br>";
										$message .= "Password: ". $copyPass . "<br><br>";
										$message .= "Please confirm you account <a href='http://homepik.com/controllers/users/activate.php?code=" . $newPassword . "'>here</a><br><br>";
										$message .= "If the link doesn't work, please copy and paste the address below into your browser address bar.<br><br>";
										$message .= "http://homepik.com/controllers/users/activate.php?code=".$newPassword."</a><br><br>";
										$message .= "Again, thank you for choosing HomePik.com.<br><br>";
										$message .= "<br><br>You are recieving this email because you created an account with HomePik with this email address. If you did not make this request please ignore this email.<br>";
										$message .= "<br><br><center><a href='http://www.homepik.com/controllers/change-email-alert-settings.php?user=".$email."'>Change Email Alert Settings</a></center><br>";
										$message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
										$message .= "HomePik.com is licensed to Bellmarc";

										$mail->addAddress($email);
										$mail->Subject = 'HomePik Account';
										$mail->Body = $message;
										$mail->send();
								
										$message = "New York State<br>
													DEPARTMENT OF STATE<br>
													Division of Licensing Services<br>
													P.O. Box 22001 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Customer Service: (518) 474-4429<br>
													Albany, NY 12201-2001  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; www.dos.state.ny.us<br><br>
													<b>New York State Disclosure Form for Buyer and Seller</b><br><br>
																<b>This is not a contract</b><br>
																<i>New York State law requires real estate licensees who are acting as agents of buyers or sellers of
													property to advise the potential buyers or sellers with whom they work of the nature of their agency
													relationship and the rights and obligations it creates. This disclosure will help you to make informed
													choices about your relationship with the real estate broker and its sales agents.<br> 
													Throughout the transaction you may receive more than one disclosure form. The law may require each agent
													assisting in the transaction to present you with this disclosure form. A real estate agent is a person
													qualified to advise about real estate.<br>
													If you need legal, tax or other advice, consult with a professional in that field.</i><br><br>
													
													<b>Disclosure Regarding Real Estate Agency Relationships</b><br><br>
													
													<b>Seller’s Agent</b><br>
													A seller’s agent is an agent who is engaged by a seller to represent the seller’s interests. The seller’s
													agent does this by securing a buyer for the seller’s home at a price and on terms acceptable to the seller.
													A seller’s agent has, without limitation, the following fiduciary duties to the seller: reasonable care,
													undivided loyalty, confidentiality, full disclosure, obedience and duty to account. A seller’s agent does
													not represent the interests of the buyer. The obligations of a seller’s agent are also subject to any specific
													provisions set forth in an agreement between the agent and the seller. In dealings with the buyer, a seller’s
													agent should (a) exercise reasonable skill and care in performance of the agent’s duties; (b) deal honestly,
													fairly and in good faith; and (c) disclose all facts known to the agent materially affecting the value or
													desirability of property, except as otherwise provided by law.<br><br>
													
													<b>Buyer’s Agent</b><br>
													A buyer’s agent is an agent who is engaged by a buyer to represent the buyer’s interests. The buyer’s agent
													does this by negotiating the purchase of a home at a price and on terms acceptable to the buyer. A buyer’s
													agent has, without limitation, the following fiduciary duties to the buyer: reasonable care, undivided loyalty,
													confidentiality, full disclosure, obedience and duty to account. A buyer’s agent does not represent the interest
													of the seller. The obligations of a buyer’s agent are also subject to any specific provisions set forth in an
													agreement between the agent and the buyer. In dealings with the seller, a buyer’s agent should (a) exercise
													reasonable skill and care in performance of the agent’s duties; (b) deal honestly, fairly and in good faith;
													and (c) disclose all facts known to the agent materially affecting the buyer’s ability and/or willingness to
													perform a contract to acquire seller’s property that are not inconsistent with the agent’s fiduciary duties
													to the buyer.<br><br>
													
													<b>Broker’s Agents</b><br> 
													A broker’s agent is an agent that cooperates or is engaged by a listing agent or a buyer’s agent (but does not
													work for the same firm as the listing agent or buyer’s agent) to assist the listing agent or buyer’s agent in
													locating a property to sell or buy, respectively, for the listing agent’s seller or the buyer agent’s buyer.
													The broker’s agent does not have a direct relationship with the buyer or seller and the buyer or seller can not
													provide instructions or direction directly to the broker’s agent. The buyer and the seller therefore do not
													have vicarious liability for the acts of the broker’s agent. The listing agent or buyer’s agent do provide
													direction and instruction to the broker’s agent and therefore the listing agent or buyer’s agent will have
													liability for the acts of the broker’s agent.<br><br> 
													
													<b>Dual Agent</b><br> 
													A real estate broker may represent both the buyer and seller if both the buyer and seller give their informed
													consent in writing. In such a dual agency situation, the agent will not be able to provide the full range of
													fiduciary duties to the buyer and seller. The obligations of an agent are also subject to any specific provisions
													set forth in an agreement between the agent, and the buyer and seller. An agent acting as a dual agent must explain
													carefully to both the buyer and seller that the agent is acting for the other party as well. The agent should also
													explain the possible effects of dual representation, including that by consenting to the dual agency relationship
													the buyer and seller are giving up their right to undivided loyalty. A buyer or seller should carefully consider
													the possible consequences of a dual agency relationship before agreeing to such representation. A seller or buyer
													may provide advance informed consent to dual agency by indicating the same on this form.<br><br> 
													
													<b>Dual Agent with Designated Sales Agents</b><br> 
													If the buyer and seller provide their informed consent in writing, the principals and the real estate broker who
													represents both parties as a dual agent may designate a sales agent to represent the buyer and another sales agent
													to represent the seller to negotiate the purchase and sale of real estate. A sales agent works under the supervision
													of the real estate broker. With the informed consent of the buyer and the seller in writing, the designated sales
													agent for the buyer will function as the buyer’s agent representing the interests of and advocating on behalf of
													the buyer and the designated sales agent for the seller will function as the seller’s agent representing the interests
													of and advocating on behalf of the seller in the negotiations between the buyer and seller. A designated sales agent
													cannot provide the full range of fiduciary duties to the buyer or seller. The designated sales agent must explain that
													like the dual agent under whose supervision they function, they cannot provide undivided loyalty. A buyer or seller
													should carefully consider the possible consequences of a dual agency relationship with designated sales agents before
													agreeing to such representation. A seller or buyer may provide advance informed consent to dual agency with designated
													sales agents by indicating the same on this form.<br><br>
													
													This form was provided to me by Bellmarc Realty LLC & it's associated agents of Bellmarc Realty
													LLC, a licensed real estate broker acting in the interest of the:<br>
													(__) Seller as a (check relationship below<br>
													&nbsp;&nbsp;(__) Seller's agent<br>
													&nbsp;&nbsp;(__) Broker's agent<br>
													
													(X) Buyer as a (check relationship below)<br>
													&nbsp;&nbsp;(X) Buyer's agent<br>
													&nbsp;&nbsp;(__) Broker's agent<br>
													
													(X) Dual Agent<br>
													(__) Dual agent with designated sales agent<br><br>
													
													For advance informed consent to either dual agency or dual agency with designated sales agents complete section below:<br>
													&nbsp;&nbsp; (X) Advance informed consent dual agency<br>
													&nbsp;&nbsp; (__) Advance informed consent to dual agency with designatd sales agents<br><br>
													
													If dual agent with designated sales agents is indicated above: " .$firstName . " " . $lastName . " is appointed to represent
													the buyer: and (N/A) is appointed to represent the seller in this transaction. (I)(We) " .$firstName . " " . $lastName . " 
													acknowledge receipt of a copy of this disclosure form.";
										$message .= "<br><br><br><br>You are recieving this email because you created an account with HomePik with this email address. If you did not make this request please ignore this email.<br>";
										$message .= "<br><br><center><a href='http://www.homepik.com/controllers/change-email-alert-settings.php?user=".$email."'>Change Email Alert Settings</a></center><br>";
										$message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
										$message .= "HomePik.com is licensed to Bellmarc";

										$mail->addAddress($email);
										$mail->Subject = 'HomePik Disclosure Form Copy';
										$mail->Body = $message;
										$mail->send();

										//update the online field
										$time = date('U');
										mysql_query("UPDATE users SET online = '" . $time . "' WHERE id = '" . $_SESSION['id'] . "' ");
										$_SESSION['logged_in'] = $time;
										
										//show message
										echo "<br><br><br><div class='Text-1 clearfix' id='registrationVerfication'>
										<div>Thank you for becoming a Registered Buyer with HomePik!</div> <br><br>
										<div>Please be aware of the following: <br><br>
										You are entering into the HomePik broker listing book. These are the listings which agents associated with HomePik have available at any given time to present to customers.
										In gaining access to this listing book you are using a private information source which is only available to you and not to others who are not registered.
										<br><br>
										This is not a public portal and the information being shown to you is confidential. HomePik is not seeking to advertise or promote any particular property rather our goal is
										to assist you in identifying viable properties in our capacity as licensed real estate broker in the State of New York.
										<br><br>
										While you are not required to do so, we urge you to identify a selected HomePik agent to assist you in your effort. Agents have additional tools that will be valuable to you
										including information about market inventories as well as a valuation tool to determine how a given property compares in price relative to other properties currently for sale
										that are similar to it.
										<br><br>
										Thank you for permitting us to serve you.
										<br><br>
										<span id='disclaimer'>HomePik Inc. <br>
										Licensed Real Estate Broker in the State of New York</span></div>
										<div><a href='/menu.php'><button type='button' id='continue'>Continue to Buyer Home Page &nbsp;<i class='fa fa-chevron-right color-blue'></i></button></a></div></div>";
									}
								}
							} // END REGISTRATION COMPLETION
						} else {
							if($agent != 'true'){
								echo "<center class='Text-1 clearfix title'>Complete Registration Error</center>";
								echo "<br><br><center class='Text-1 clearfix'>The <b>email</b> you supplied is not associated with any accounts.</center>";
								echo "<br><center class='Text-1 clearfix'>Please go to <b>Create an Account</b> to make an account or go back to the <b>Registration Form</b> to re-enter an email.</center> ";
								echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back" class="extraSpace"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Registration Form</button></a>&nbsp;&nbsp;<a href="/controllers/sigin.php"><button type="button" id="back">Create Account &nbsp;<i class="fa fa-chevron-right color-blue"></i></button></a></center>';
							}
							else{
								echo "<center class='Text-1 clearfix title'>Complete Registration Error</center>";
								echo "<br><br><center class='Text-1 clearfix'>You must log in using Registered Agent login.</center>";
								echo '<br><br><center class="Text-1 clearfix"><a href="/controllers/agent-signin.php"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go to Agent Login</button></a></center>';
							}
						}
					}
				}
	    ?>
		</div>
	</div>
</body>
</html>