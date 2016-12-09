<?php
session_start();
include_once("dbconfig.php");
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if(isset($_POST['email'])){$user = $_POST['email']; }
else{ $user = $_SESSION['email']; };

//Set session to retain buyer for save to buyer
$_SESSION['buyerSave'] = $user;
$num = 0;
    
if(isset($_POST['name'])){
  $name = $_POST['name'];
  
  $SQL = "SELECT * FROM `users_folders` where (user = '".$user."') AND (name LIKE '".$name."%')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $num = mysql_num_rows($result);
  
  echo json_encode($num);
}
?>