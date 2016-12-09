<?php
session_start();
include_once('functions.php');
include_once("dbconfig.php");
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if(isset($_POST['email'])){ $email = $_POST['email']; }
if(isset($_POST['name'])){ $searchName = $_POST['name']; }

$SQL = "DELETE FROM `Users_Search` WHERE (email = '".$email."') AND (name = '".$searchName."')";
$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());

?>