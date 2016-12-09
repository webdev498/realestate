<?php
session_start();
include_once("dbconfig.php");
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

$buyer = $_POST['buyer'];
$agent = $_POST['agent'];

$messages = array();
date_default_timezone_set('America/New_York');

$SQL = "SELECT * FROM `messages` WHERE (buyer = '".$buyer."') AND (agent = '".$agent."') ORDER BY `id` ASC";
$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
  $time = date( 'm/d/y&\nb\sp;&\nb\sp;&\nb\sp; h:ia', $row['time']);
  $info = array("sender"=> $row['sender'], "message"=> $row['message'], "time"=>$time);
  array_push($messages, $info);
}

echo json_encode($messages);

?>