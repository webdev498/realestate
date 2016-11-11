<?php
session_start();
include("dbconfig.php");

// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

$agents = array();

if(isset($_POST['new'])){
  $SQL = "SELECT `first_name`, `last_name`, `agent_id` FROM `registered_agents` WHERE (`id` != '') and (`active` = 'Y') ORDER BY last_name ASC";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
    
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    if($row['agent_id'] != "JLF" && $row['agent_id'] != "JSK" && $row['agent_id'] != "AG1" && $row['agent_id'] != "AG2" && $row['agent_id'] != "DB1" && $row['agent_id'] != "DJB" && $row['agent_id'] != "WLE"){
      $name = $row['last_name'] . ",  " . $row['first_name'];
      array_push($agents, $name);
    }
  }
}
else{
  $SQL = "SELECT `firstname`, `lastname`, `id` FROM `Agent_Import` WHERE (`id` != '') and (`status` != '3') and (`active` = 'Y') ORDER BY lastname ASC";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
      
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    if($row['firstname'] != ".T." && $row['firstname'] != ".F." && $row['lastname'] != ".T." && $row['lastname'] != ".F." && $row['firstname'] != 'Andika' && $row['lastname'] != 'Bijaya'){
      $name = $row['lastname'] . ",  " . $row['firstname'];
      array_push($agents, $name);
    }
  }
}

echo json_encode($agents);
?>