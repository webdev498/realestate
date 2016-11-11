<?php
session_start();
include('functions.php');
include("dbconfig.php");

// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if($_POST['code']){
  $code = $_POST['code'];
  
  $SQL = "SELECT firstname, lastname, id FROM `Agent_Import` where (id = '".$code."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  
  $id = $row['id'];
  
  if($id != ""){
    $SQL2 = "SELECT P_agent FROM `users` where (email = '".$_SESSION['email']."')";
    $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
    
    $row = mysql_fetch_array($result2,MYSQL_ASSOC);
  
    $pid = $row['P_agent'];
    
    if($pid == $id){
      $info = "used";
    }
    else{
      $info = "good";
    }
  }
  else{
    $info = "invalid";
  }
  
  echo json_encode($info);
}

if($_POST['name']){
  $firstName = $_POST['firstname'];
  $lastName = $_POST['lastname'];
  
  $code = "none";
  
  $SQL = "SELECT firstname, lastname, id FROM `Agent_Import` where (firstname = '".$firstName."') AND (lastname = '".$lastName."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    $id = $row['id'];
  }
  
  if($id != ""){
    $SQL2 = "SELECT P_agent FROM `users` where (email = '".$_SESSION['email']."')";
    $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
    $row = mysql_fetch_array($result2,MYSQL_ASSOC);
  
    $pid = $row['P_agent'];
    
    if($pid == $id){
      $info = "used";
    }
    else{
      $info = "good";
    }
  }
  else{
    $info = "invalid";
  }
  
  $info = array("exists"=>$info, "code"=>$id);
  
  echo json_encode($info);
}

if($_POST['getID']){
  $firstName = $_POST['firstname'];
  $lastName = $_POST['lastname'];
  
  $SQL = "SELECT firstname, lastname, id FROM `Agent_Import` where (firstname = '".$firstName."') AND (lastname = '".$lastName."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    $id = $row['id'];
  }
  
  echo json_encode($id);
}

if($_POST['getEmail']){
  $id = $_POST['id'];
  
  $SQL = "SELECT e_mail FROM `Agent_Import` where (id = '".$id."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    $email = $row['e_mail'];
  }
  
  echo json_encode($email);
}

?>