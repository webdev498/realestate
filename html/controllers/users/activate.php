<?php
session_start();
include("../dbconfig.php");
include("../functions.php");
include("../basicHeadOld.php");
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());
$role='buyer';
?>
<html>
<head>
	<title>HomePik - Account Activation</title>
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
		<br/><br/><br/>
		<?php
			//get the code that is being checked and protect it before assigning it to a variable
			$url = $_SERVER['REQUEST_URI'];
			parse_str($url, $get_array);
			//print_r($get_array);
			$code = $get_array['/controllers/users/activate_php?code'];
			echo $code; //Error checking
			if(!$code){ // no code found
				echo "<center class='Text-1 clearfix'>Unfortunatly there was an error there.</center>";
			}else{ // code found
				$res = mysql_query("SELECT * FROM users");
				while($row = mysql_fetch_assoc($res)){
					if($code == $row['password']){ // activate account
						$res1 = mysql_query("UPDATE users SET active = '2', role = 'buyer' WHERE id = '".$row['id']."'");
						$_SESSION['user'] = 'true';
						$id = $row['id'];
						$_SESSION['id'] = $id;
						$pass = $row['password'];
						$_SESSION['pass'] = $pass;
						$_SESSION['email'] = $row['email'];
						$_SESSION['role'] = $role;
		
						//update the online field to 50 seconds into the future
						$time = date('U')+50;
						mysql_query("UPDATE users SET online = '".$time."' WHERE id = '".$row['id']."' ");
						header('Location: http://homepik.com/menu.php');
					} else { echo 'user not found.'; }
				}
			}
		?>
	</div>
</body>
</html>