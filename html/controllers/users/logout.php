<?php
session_start();

include("../dbconfig.php");

$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());

include "../functions.php";
?>
<html>
<head>
	<script>
	  $( document ).ready(function() {
			$.removeCookie("location");
			deleteAllCookies();

			function deleteAllCookies() {
				var cookies = document.cookie.split(";");

				for (var i = 0; i < cookies.length; i++) {
					var cookie = cookies[i];
					var eqPos = cookie.indexOf("=");
					var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
					document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
				}
			}


	  });
	</script>
</head>
<body>
	<?php
		//check if the login session does no exist
		if(authentication() == 'anonymous') {
			//if it doesn't display an error message
			//	print "<center>You need to be logged in to log out</center>.";
			session_unset();
			session_destroy();
			header('Location: ../index.php');
		}else{
			if(isset($_GET['listings']) && $_GET['listings'] == "clear"){
				mysql_query("DELETE FROM viewed_listings WHERE ( user ='".$_SESSION['email']."')");
			}
			//if it does continue checking
			//update to set this users online field to the current time
			if(authentication() == 'user'){
				mysql_query("UPDATE users SET online = '".date('U')."' WHERE id = '".$_SESSION['uid']."'");
				//destroy all sessions canceling the login session
				session_unset();
				session_destroy();
				header('Location: ../index.php');
			} elseif (authentication () == 'agent'){
				session_unset();
				session_destroy();
				header('Location: ../index.php');
			}
			elseif (authentication () == 'guest'){
				mysql_query("DELETE FROM users_folders WHERE name = 'Guest Folder'");
				mysql_query("DELETE FROM saved_listings WHERE user = '".$_SESSION['guestID']."'");
				session_unset();
				session_destroy();
				session_commit();
				unset($_SESSION['guestID']);
				header('Location: ../index.php');
			}
		}
	?>
</body>
</html>
