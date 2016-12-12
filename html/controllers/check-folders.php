<?php
session_start();
include_once("dbconfig.php");
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

$user = (isset($_POST['email']) ? $_POST['email'] : $_SESSION['email']);

//Set session to retain buyer for save to buyer
$_SESSION['buyerSave'] = $user;
$num = 0;
    
if(isset($_POST['name'])){
  $result = mysql_query( "SELECT * FROM `users_folders` where (user = '".$user."') AND (name LIKE '".$_POST['name']."%')" ) or die("Couldn't execute query.".mysql_error());
  $num = mysql_num_rows($result);
  
  echo json_encode($num);
}
?>