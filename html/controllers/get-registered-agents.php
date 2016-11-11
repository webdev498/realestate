<?php
session_start();
include("dbconfig.php");

// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

$agents = array();

if(isset($_POST['allAgents'])){
  $SQL = "SELECT first_name, last_name, email FROM `registered_agents` ORDER BY last_name ASC";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    array_push($agents, $row);
  }
}

if(isset($_POST['activeAgents'])){
  $SQL = "SELECT first_name, last_name, email FROM `registered_agents` WHERE (active = 'Y' ) ORDER BY last_name ASC";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    array_push($agents, $row);
  }
}

if(isset($_POST['archivedAgents'])){
  $SQL = "SELECT first_name, last_name, email FROM `registered_agents` WHERE (active = 'N' ) ORDER BY last_name ASC";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    array_push($agents, $row);
  }
}

echo json_encode($agents);
?>