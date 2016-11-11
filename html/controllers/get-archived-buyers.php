<?php
session_start();
include("dbconfig.php");

// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

$agent_id = $_POST['agent_id'];
$buyers = array();
$id = 1;

if($agent_id != ''){
  $SQL = "SELECT * FROM `users` WHERE ((`P_agent` = '".$agent_id."') OR (`P_agent2` = '".$agent_id."')) AND (`archived` = '1') ORDER BY last_name ASC";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    $name = array("id"=>$id, "first_name"=> $row['first_name'], "last_name"=> $row['last_name'], "email"=>$row['email']);
    array_push($buyers, $name);
    $id = $id + 1;
  }
}
echo json_encode($buyers);
?>