<?php
//allow sessions to be passed so we can see if the user is logged in
session_start();
ob_start();

//include out functions file giving us access to the protect() function made earlier
include "../functions.php";

//If  a Bellmarc salesperson logged in from Bellmarc.net
if($_GET['agent']){
	$uid = protect($_GET['uid']);
	$code = protect($_GET['code']);

  $con = mysql_connect('localhost', 'webadmin', '$abc456g8') or die(mysql_error());
  $db = mysql_select_db('drupal', $con) or die(mysql_error());

	//select all rows from the table where the email matches the one entered by the user
	$res = mysql_query("SELECT pass, login, uid, mail FROM users WHERE uid = '".$uid."'");
	$num = mysql_num_rows($res);
  $row = mysql_fetch_assoc($res);
  $pass = $row['pass'];
  $aid = $row['uid'];
  $email = $row['mail'];
  $login = $row['login'];
  $code2 = string_encrypt($row['pass'], $row['login']);

	//check if there was not a match
	if($num == 0){
		//if not display an error message
		echo "<center>ERROR: Your email does not exist in the database.</center>";
	}else{
		if($code != $code2){
			//if not display error message
			echo "<center>The <b>Password</b> you supplied does not match the one for that email.</center>";
		}else{
			//if there was continue checking
			//split all fields fom the correct row into an associative array
			$row = mysql_fetch_assoc($res);
			//check to see if the user has not activated their account yet
				//if they have log them in
				//set the login session storing there id - we use this to see if they are logged in or not
				$_SESSION['agent'] = 'true';
        $_SESSION['aid'] = $aid;
        $_SESSION['email'] = $email;
        $_SESSION['pass'] = $pass;
				//show message
				echo "<center>You have successfully logged in.</center>";
				header('Location: /menu.php');
			}
		}
	}

ob_end_flush();
?>


