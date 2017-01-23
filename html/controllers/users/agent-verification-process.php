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
	<title>HomePik - Processing Agent Verification</title>	
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
					$email = $_POST['email'];
					$question = $_POST['security-question'];
					$answer = $_POST['security-answer'];

					if( !$email || !$question || !$answer ) {
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
								$res1 = mysql_query("SELECT * FROM registered_agents WHERE email = '" . $email . "'");
								$num1 = mysql_num_rows($res1);
								//if the number of matchs is 1
								if ($num1 >= 1) {
								$row = mysql_fetch_assoc($res1);

								if($question == $row['security_question'] && $answer == $row['security_answer']){
									$_SESSION['id'] = $row['id'];
									$_SESSION['agent_id'] = $row['agent_id'];
									$_SESSION['firstname'] = $row['first_name'];
									$_SESSION['lastname'] = $row['last_name'];
									$_SESSION['phone'] = $row['phone'];
									$_SESSION['email'] = $email;
									$_SESSION['admin'] = $row['admin'];
									$_SESSION['role'] = 'agent';
									$_SESSION['agent'] = 'true';
									
									// Get un-read message count
									$result2 = mysql_query("SELECT COUNT(*) as messages FROM `messages` as m LEFT JOIN `registered_agents` as r ON m.agent=r.email WHERE (agent = '".$email."') AND (sender != '".$email."') AND (m.time > r.online)") or die("Couldn't execute query.".mysql_error());
									$row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
									$_SESSION['unreadMessages'] = $row2['messages'];

									echo "<br><br><center class='Text-1 clearfix'><b>Logging In...</b></center>";
									print "<script> window.location = '/menu.php' </script>";
								}
								else{
									echo "<center class='Text-1 clearfix title'>Validation Error</center>";
									echo "<center class='Text-1 clearfix'><br><br>The information you entered did not match the information on file.</center>";
									echo '<br><br><center class="Text-1 clearfix"><a href="javascript:history.back()"><button type="button" id="back"><i class="fa fa-chevron-left color-blue"></i>&nbsp; Go back to Verification Form</button></a></center>';
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
			?>
		</div>
	</div>
</body>
</html>