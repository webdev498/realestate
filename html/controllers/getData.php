<!DOCTYPE html>
<?php

session_start();
include("dbconfig.php");
include ("functions.php");
include("head2.php");

$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if (!$_SESSION['user']) {
  header('location: /users/logout.php');
}

$email = $_SESSION['email'];

$sql = "SELECT firstname, lastname, id FROM `Agent_Import` where (e_mail = '".$email."')";
$res = mysql_query( $sql ) or die("Couldn't execute query.".mysql_error());
while($row = mysql_fetch_array($res,MYSQL_ASSOC)) {
  $firstname = $row['firstname'];
  $lastname = $row['lastname'];
  $id = $row['id'];
}

$name = explode('@', $_SESSION['email']);
$name = $name[0];
    
?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head  profile="http://homepik.com">
	  <meta name="viewport" content="width=device-width, height=device-height" />
	  <script src="/js/react/build/react.js"></script>
    <script src="/js/react/build/react-dom.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.23/browser.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/0.3.5/marked.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.13.3/JSXTransformer.js"></script>
    <script src="/js/weTest.js" type="text/jsx"></script>
  </head>
  <body>
    <div id="chart1"></div>
    <br><br><br>
    <div id="chart1"></div>
  </body>
</html>