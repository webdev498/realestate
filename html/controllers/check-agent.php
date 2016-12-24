<?php
session_start();
include_once("dbconfig.php");
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if(isset($_POST['fullValidationInfo'])){
  $email = $_POST['email'];
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $agent_id = $_POST['id'];

  $SQL = "SELECT * FROM `registered_agents` WHERE (email = '".$email."') AND (first_name = '".$firstName."') AND (last_name = '".$lastName."') AND (agent_id = '".$agent_id."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  
  echo json_encode($row);
}
else if(isset($_POST['fullValidation'])){
  $email = $_POST['email'];
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $id = $_POST['id'];
  
  $SQL = "SELECT EXISTS (SELECT * FROM `registered_agents` WHERE (email = '".$email."') AND (first_name = '".$firstName."') AND (last_name = '".$lastName."') AND (agent_id = '".$id."')) as num";
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
  
  $res = mysql_query("SELECT rtime FROM registered_agents WHERE email = '" . $email . "'");
  $row = mysql_fetch_assoc($res);
  $registerTime = $row['rtime'];
  $numRow = mysql_num_rows($res);
  
  if($numRow > 0){
    $newPassword = string_encrypt($newPassword, $registerTime);
    $res2 = mysql_query("UPDATE registered_agents SET password = '" . $newPassword . "' WHERE email = '" . $email . "'");
    $row = mysql_fetch_assoc($res2);		
  }
}
else if(isset($_POST['code'])){
  $result = mysql_query( "SELECT agent_id FROM `registered_agents` where (agent_id = '".$_POST['code']."')" ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);  
  $id = $row['agent_id'];
  
  if($id != ""){
    if($_SESSION['agent1'] == $id){ $info = "used"; }
    else{ $info = "good"; }
  }
  else{
    $info = "invalid";
  }
  
  echo json_encode($info);
}
else if(isset($_POST['reassignCheck'])){
  if(isset($_POST['id'])){ $id = $_POST['id']; }
  else if(isset($_POST['firstname'])){
    $result1 = mysql_query( "SELECT agent_id FROM `registered_agents` WHERE (first_name = '".$_POST['firstname']."') AND (last_name = '".$_POST['lastname']."')" ) or die("Couldn't execute query.".mysql_error());    
    $row1 = mysql_fetch_array($result1,MYSQL_ASSOC); 
    $id = $row1['agent_id'];
  }
  else{ $id = ""; }
  
  if($id != ""){
    $result = mysql_query( "SELECT EXISTS (SELECT * FROM `users` where (email = '".$_POST['email']."') AND (P_agent = '".$id."' || P_agent2 = '".$id."')) as num" ) or die("Couldn't execute query.".mysql_error());    
    $row = mysql_fetch_array($result,MYSQL_ASSOC); 
    $num = $row['num'];
    
    if($num > 0){ $status = "used"; }
    else{ $status = "good"; }
  }
  else{ $status = "invalid"; }
  
  echo json_encode($status);
}
else if(isset($_POST['name'])){
  $code = "none";
  $result = mysql_query( "SELECT agent_id FROM `registered_agents` where (first_name = '".$_POST['firstname']."') AND (last_name = '".$_POST['lastname']."')" ) or die("Couldn't execute query.".mysql_error());  
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $id = $row['agent_id'];
  
  if($id != ""){    
    if($_SESSION['agent1'] == $id){ $info = "used"; }
    else{ $info = "good"; }
  }
  else{ $info = "invalid"; }
  
  $info = array("exists"=>$info, "code"=>$id);
  
  echo json_encode($info);
}
else if(isset($_POST['getID'])){
  $result = mysql_query( "SELECT agent_id FROM `registered_agents` where (first_name = '".$_POST['firstname']."') AND (last_name = '".$_POST['lastname']."')" ) or die("Couldn't execute query.".mysql_error());  
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $id = $row['agent_id'];
  
  echo json_encode($id);
}
else if(isset($_POST['getEmail'])){
  $result = mysql_query( "SELECT email FROM `registered_agents` where (agent_id = '".$_POST['id']."')" ) or die("Couldn't execute query.".mysql_error());  
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $email = $row['email'];
  
  echo json_encode($email);
}
?>