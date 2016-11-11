<?php
session_start();
include('functions.php');
include("dbconfig.php");

// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

$email = $_POST['email'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
 
if(isset($firstName)){ $SQL = "SELECT * FROM `users` WHERE (email = '".$email."') AND (first_name = '".$firstName."') AND (last_name = '".$lastName."')"; }
else{ $SQL = "SELECT * FROM `users` where (email = '".$email."')"; }

$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
  $info = $row;
}

echo json_encode($info);

?>