<?php
session_start();
include("dbconfig.php");

// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if(isset($_POST['allAgents'])){
  $agents = array();
  
  $SQL = "SELECT first_name, last_name, email, admin FROM `registered_agents` ORDER BY last_name ASC";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    array_push($agents, $row);
  }
  
  echo json_encode($agents);
}

else if(isset($_POST['activeAgents'])){
  $agents = array();
  
  $SQL = "SELECT first_name, last_name, email, admin FROM `registered_agents` WHERE (active = 'Y' ) ORDER BY last_name ASC";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    array_push($agents, $row);
  }
  
  echo json_encode($agents);
}

else if(isset($_POST['archivedAgents'])){
  $agents = array();
  
  $SQL = "SELECT first_name, last_name, email, admin FROM `registered_agents` WHERE (active = 'N' ) ORDER BY last_name ASC";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    array_push($agents, $row);
  }
  
  echo json_encode($agents);
}

else if(isset($_POST['information'])){
  $SQL = "SELECT * FROM `registered_agents` WHERE (email = '".$_POST['email']."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  
  echo json_encode($row);
}
?>