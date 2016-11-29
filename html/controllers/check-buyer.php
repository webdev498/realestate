<?php
session_start();
include('functions.php');
include("dbconfig.php");

// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

 
if(isset($_POST['partialValidation'])){
  $email = $_POST['email'];
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];

  $SQL = "SELECT * FROM `users` WHERE (email = '".$email."') AND (first_name = '".$firstName."') AND (last_name = '".$lastName."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $info = $row;
  
  if($row['P_agent'] != '' && $row['P_agent'] != null){
    $SQL2 = "SELECT first_name, last_name FROM `registered_agents` WHERE agent_id = '".$row['P_agent']."'";
    $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
    $row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
    $agent1 = $row2['first_name'] . " " . $row2['last_name'];
    
    $info['agent1'] = $agent1;
  }
  else{ $info['agent1'] = ""; }
  
  if($row['P_agent2'] != '' && $row['P_agent2'] != null){
    $SQL3 = "SELECT first_name, last_name FROM `registered_agents` WHERE agent_id = '".$row['P_agent2']."'";
    $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());
    $row3 = mysql_fetch_array($result3,MYSQL_ASSOC);
    $agent2 = $row3['first_name'] . " " . $row3['last_name'];
    
    $info['agent2'] = $agent2;
  }
  else{ $info['agent2'] = ""; }
  
  echo json_encode($info);
}
else if(isset($_POST['fullValidation'])){
  $email = $_POST['email'];
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $phone = $_POST['phone'];
  $question = $_POST['question'];
  $answer = $_POST['answer'];
  
  if($phone != "" && $phone != null){ $SQL = "SELECT EXISTS (SELECT * FROM `users` WHERE (email = '".$email."') AND (first_name = '".$firstName."') AND (last_name = '".$lastName."') AND (phone = '".$phone."')) as num"; }
  else{ $SQL = "SELECT EXISTS (SELECT * FROM `users` WHERE (email = '".$email."') AND (first_name = '".$firstName."') AND (last_name = '".$lastName."') AND (security_question = '".$question."') AND (security_answer = '".$answer."')) as num"; }
  
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  if($row['num'] == 0){
    echo "doesn't exist";
  }
  else{
    echo "exists";
  }
}
else if(isset($_POST['passReset'])){
  $email = $_POST['email'];
  $newPassword = $_POST['password'];
  
  $res = mysql_query("SELECT email, password, rtime FROM users WHERE email = '" . $email . "'");
  $row = mysql_fetch_assoc($res);
  $registerTime = $row['rtime'];
  $numRow = mysql_num_rows($res);
  
  if($numRow > 0){
    $newPassword = string_encrypt($newPassword, $registerTime);
    $res2 = mysql_query("UPDATE users SET password = '" . $newPassword . "' WHERE email = '" . $email . "'");
    $row = mysql_fetch_assoc($res2);		
  }
}
else{
  $SQL = "SELECT * FROM `users` WHERE (email = '".$email."')"; 
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $info = $row; 
  
  echo json_encode($info);
}

?>