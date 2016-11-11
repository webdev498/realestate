<?php
//allow sessions to be passed so we can see if the user is logged in
session_start();
ob_start();

//include out functions file giving us access to the protect() function made earlier
include "../functions.php";
?>
<html>
<head>
	<title>HomePik - Account Expired</title>
	<link rel="stylesheet" type="text/css" href="/views/css/site_global.css"/>
</head>
<body>
	<?php

		$id = $_SESSION['id'];
		$pass = $_SESSION['pass'];
		$email = $_SESSION['email'];

		//If the user has submitted the form
		if($_POST['submit']){

			include("../dbconfig.php");
			$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
			$db = mysql_select_db('sp', $con) or die(mysql_error());

			//if the were continue checking
			//select all rows from the table where the email matches the one entered by the user
			$res = mysql_query("SELECT rtime, password, id FROM users WHERE email = '".$email."'");
			$num = mysql_num_rows($res);
			$row = mysql_fetch_assoc($res);
			$new_password = string_encrypt($_POST['password'], $row['rtime']);
			$password = $row['password'];

			//check if the password is the same as the old
			if($new_password == $password){
				echo "<center class='Text-1 clearfix title'>Password Expiration Error</center>";
				echo "<br><br><center class='Text-1 clearfix'>Error: Your new password must be different than your old one.</center><br/>";
			}else{
				// Set 'user' as 'true' in the session. This marks the user as "logged in"
				$_SESSION['user'] = 'true';
				$time = date('U');
				mysql_query("UPDATE users SET online = '".$time."', pass_set = '".$time."', password = '".$new_password."' WHERE id = '".$id."' ");
				header('Location: /menu.php');
			}
	}
?>
<form action="expired.php" method="post">
	<div id="border">
		<table cellpadding="2" cellspacing="0" border="0">
			<tr>
				<td>Your password must be changed every six months. Please create a new password:</td>
			</tr>
			<tr>
				<td><input type="password" name="password" /></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" name="submit" value="Continue" /></td>
			</tr>
			<tr>
				<!--<td align="center" colspan="2"><a href="register.php">Register</a> | <a href="forgot.php">Forgot Pass</a></td>-->
			</tr>
		</table>
	</div>
</form>
</body>
</html>
<?
ob_end_flush();
?>




