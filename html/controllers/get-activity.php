<?php
session_start();
include_once("dbconfig.php");
include_once('functions.php');
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

date_default_timezone_set('America/New_York');
$activity = array();

if(isset($_POST['user']) && $_POST['user'] == "agent"){
  $email = $_POST['email'];
  $agentID = $_POST['id'];
  if($_POST['day'] != "default"){ $date = $_POST['month'] . "/" . $_POST['day'] . "/" . $_POST['year']; }
  else{ $date = $_POST['month'] . "/1/" . $_POST['year']; }
  $time = strtotime($date);
    
  // Last Login
  $SQL = mysql_query("SELECT online FROM registered_agents WHERE (email = '" . $email . "')");
  $row = mysql_fetch_assoc($SQL);
  $login = date( 'm/d/y h:ia', $row['online']);
  $activity['login'] = $login;
  
  // Number New Buyers added / buyers assigned
  $SQL2 = mysql_query("SELECT COUNT(*) as buyers FROM users WHERE (P_agent = '" . $agentID . "' AND P_agent_assign_time >= '" . $time . "') OR (P_agent2 = '" . $agentID . "' AND P_agent2_assign_time >= '" . $time . "')");
  $row2 = mysql_fetch_assoc($SQL2);
  $activity['buyers'] = $row2['buyers'];
  
  // Number Search performed
  $SQL3 = mysql_query("SELECT COUNT(*) as searches FROM search_history WHERE (user = '" . $email . "') AND (time >= '" . $time . "' )");
  $row3 = mysql_fetch_assoc($SQL3);
  $activity['searches'] = $row3['searches'];
  
  // Number Listings viewed
  $SQL4 = mysql_query("SELECT COUNT(*) as listings FROM viewed_listings WHERE (user = '" . $email . "') AND (time >= '" . $time . "' )");
  $row4 = mysql_fetch_assoc($SQL4);
  $activity['listingsViewed'] = $row4['listings'];
  
  // Number Listings Saved to agent folder
  $SQL5 = mysql_query("SELECT COUNT(*) as listings FROM queued_listings WHERE (user = '" . $email . "') AND (saved_by = '" . $email . "') AND (time >= '" . $time . "' )");
  $row5 = mysql_fetch_assoc($SQL5);
  $activity['listingsSavedFolder'] = $row5['listings'];
  
  // Number Listings Saved to buyer folders
  $SQL6 = mysql_query("SELECT COUNT(*) as listings FROM saved_listings WHERE (saved_by = '" . $email . "') AND (time >= '" . $time . "' )");
  $row6 = mysql_fetch_assoc($SQL6);
  $activity['listingsSavedBuyers'] = $row6['listings'];
  
  // Number Listings Emailed
  $SQL7 = mysql_query("SELECT COUNT(*) as listings FROM emailed_listings WHERE (`from` = '" . $email . "') AND (time >= '" . $time . "' )");
  $row7 = mysql_fetch_assoc($SQL7);
  $activity['listingsEmailed'] = $row7['listings'];
  
  // Number messages received
  $SQL8 = mysql_query("SELECT COUNT(*) as messages FROM messages WHERE (agent = '" . $email . "') AND (sender != '" . $email . "') AND (buyer = sender) AND (time >= '" . $time . "' )");
  $row8 = mysql_fetch_assoc($SQL8);
  $activity['messagesReceived'] = $row8['messages'];
  
  // Numbers messages sent
  $SQL9 = mysql_query("SELECT COUNT(*) as messages FROM messages WHERE (agent = '" . $email . "') AND (sender = '" . $email . "') AND (time >= '" . $time . "' )");
  $row9 = mysql_fetch_assoc($SQL9);
  $activity['messagesSent'] = $row9['messages'];
}

echo json_encode($activity);
?>
