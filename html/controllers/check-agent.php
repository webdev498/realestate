<?php
session_start();
include_once("dbconfig.php");
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if(isset($_POST['code'])){
  $code = $_POST['code'];
  
  $SQL = "SELECT agent_id FROM `registered_agents` where (agent_id = '".$code."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);  
  $id = $row['agent_id'];
  
  if($id != ""){
    $SQL2 = "SELECT P_agent FROM `users` where (email = '".$_SESSION['email']."')";
    $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());    
    $row = mysql_fetch_array($result2,MYSQL_ASSOC);  
    $pid = $row['P_agent'];
    
    if($pid == $id){ $info = "used"; }
    else{ $info = "good"; }
  }
  else{
    $info = "invalid";
  }
  
  echo json_encode($info);
}

if(isset($_POST['reassignCheck'])){
  
  if(isset($_POST['id'])){ $id = $_POST['id']; }
  else if(isset($_POST['firstname'])){
    $SQL1 = "SELECT agent_id FROM `registered_agents` WHERE (first_name = '".$_POST['firstname']."') AND (last_name = '".$_POST['lastname']."')";
    $result1 = mysql_query( $SQL1 ) or die("Couldn't execute query.".mysql_error());    
    $row1 = mysql_fetch_array($result1,MYSQL_ASSOC); 
    $id = $row1['agent_id'];
  }
  else{ $id = ""; }
  
  if($id != ""){
    $SQL = "SELECT EXISTS (SELECT * FROM `users` where (email = '".$_POST['email']."') AND (P_agent = '".$id."' || P_agent2 = '".$id."')) as num";
    $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());    
    $row = mysql_fetch_array($result,MYSQL_ASSOC); 
    $num = $row['num'];
    
    if($num > 0){ $status = "used"; }
    else{ $status = "good"; }
  }
  else{
    $status = "invalid";
  }
  
  echo json_encode($status);
}

if(isset($_POST['name'])){
  $firstName = $_POST['firstname'];
  $lastName = $_POST['lastname'];
  
  $code = "none";
  
  $SQL = "SELECT agent_id FROM `registered_agents` where (first_name = '".$firstName."') AND (last_name = '".$lastName."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());  
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $id = $row['agent_id'];
  
  if($id != ""){
    $SQL2 = "SELECT P_agent FROM `users` where (email = '".$_SESSION['email']."')";
    $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
    $row = mysql_fetch_array($result2,MYSQL_ASSOC);  
    $pid = $row['P_agent'];
    
    if($pid == $id){ $info = "used"; }
    else{ $info = "good"; }
  }
  else{
    $info = "invalid";
  }
  
  $info = array("exists"=>$info, "code"=>$id);
  
  echo json_encode($info);
}

if(isset($_POST['getID'])){
  $firstName = $_POST['firstname'];
  $lastName = $_POST['lastname'];
  
  $SQL = "SELECT agent_id FROM `registered_agents` where (first_name = '".$firstName."') AND (last_name = '".$lastName."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());  
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $id = $row['agent_id'];
  
  echo json_encode($id);
}

if(isset($_POST['getEmail'])){
  $id = $_POST['id'];
  
  $SQL = "SELECT email FROM `registered_agents` where (agent_id = '".$id."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());  
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $email = $row['email'];
  
  echo json_encode($email);
}

if(isset($_POST['fullValidation'])){
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

?>