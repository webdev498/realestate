<?php
session_start();
include("dbconfig.php");

// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

$buyer_email = $_POST['buyer'];

if(isset($_POST['agentID'])){
 $SQL = "SELECT * FROM `users_folders` WHERE (user = '".$buyer_email."') AND (agent LIKE '%".$_POST['agentID']."%') ORDER BY name ASC"; 
}
else{
  $SQL = "SELECT * FROM `users_folders` WHERE (user = '".$buyer_email."') ORDER BY name ASC";
}

$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  
$folders = array();
$id = 1;

while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
 
  $folder = array("id"=>$id, "name"=>$row["name"], "agent"=>$row['agent'], "last_update"=>$row['last_update']);
  array_push($folders, $folder);
  $id = $id + 1;
}

echo json_encode($folders);
?>