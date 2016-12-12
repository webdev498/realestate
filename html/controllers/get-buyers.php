<?php
session_start();
include_once("dbconfig.php");
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if(isset($_POST['agent_id']) && $_POST['agent_id'] != ''){
  $buyers = array();
  $id = 1;

  $result = mysql_query( "SELECT * FROM `users` WHERE ((`P_agent` = '".$_POST['agent_id']."') OR (`P_agent2` = '".$_POST['agent_id']."')) AND (`archived` != '1') ORDER BY last_name ASC" ) or die("Couldn't execute query.".mysql_error());
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    $name = array("id"=>$id, "first_name"=> $row['first_name'], "last_name"=> $row['last_name'], "email"=>$row['email']);
    array_push($buyers, $name);
    $id = $id + 1;
  }
  
  echo json_encode($buyers);
}

else if(isset($_POST['allBuyers'])){
  $buyers = array();
  $id = 1;
  
  $result = mysql_query( "SELECT * FROM `users` ORDER BY last_name ASC" ) or die("Couldn't execute query.".mysql_error());
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    $name = array("id"=>$id, "first_name"=> $row['first_name'], "last_name"=> $row['last_name'], "email"=>$row['email']);
    array_push($buyers, $name);
    $id = $id + 1;
  }
  
  echo json_encode($buyers);
}

else if(isset($_POST['buyerInformation'])){
  $result = mysql_query( "SELECT * FROM `users` WHERE email = '".$_POST['email']."'" ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  
  $result2 = mysql_query( "SELECT CONCAT(first_name, ' ', last_name) as name FROM `registered_agents` WHERE agent_id = '".$row['P_agent']."'" ) or die("Couldn't execute query.".mysql_error());
  $row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
  $row['P_agent'] = $row2['name'];
  
  $result3 = mysql_query( "SELECT CONCAT(first_name, ' ', last_name) as name FROM `registered_agents` WHERE agent_id = '".$row['P_agent2']."'" ) or die("Couldn't execute query.".mysql_error());
  $row3 = mysql_fetch_array($result3,MYSQL_ASSOC);
  $row['P_agent2'] = $row3['name'];
  
  echo json_encode($row);
}
?>