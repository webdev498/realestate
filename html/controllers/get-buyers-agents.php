<?php
session_start();
include_once("dbconfig.php");
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

$SQL = "SELECT * FROM `users` WHERE (email = '".$_SESSION['email']."')";
$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
$row = mysql_fetch_array($result,MYSQL_ASSOC);
$agents = array("agent1"=> $row['P_agent'], "agent2"=>$row['P_agent2']);

echo json_encode($agents);
?>