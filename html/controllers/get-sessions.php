<?php
session_start();
include_once("dbconfig.php");
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());

if(isset($_POST['getAddress'])){ echo json_encode($_SESSION['address']); }
if(isset($_POST['getListing'])){ echo json_encode($_SESSION['listing']); }
if(isset($_POST['getLoadSaved'])){ echo json_encode($_SESSION['loadSaved']); }
if(isset($_POST['getPreviousPage'])){ echo json_encode($_SESSION['previousPage']); }
if(isset($_POST['getEmail'])){ echo json_encode($_SESSION['email']); }
if(isset($_POST['getLastName'])){
  if(isset($_POST['email'])){ $email = $_POST['email']; }
  else{ $email = $_SESSION['email']; }
  $SQL = "SELECT last_name FROM `users` where (email = '" . $email . "')";
  $result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
  $row = mysql_fetch_array($result, MYSQL_ASSOC);
  $name = $row['last_name'];
  
  echo json_encode($name);
}
if(isset($_POST['getAgentLastName'])){
  if(isset($_SESSION['lastname'])){ $name = $_SESSION['lastname']; }
  else{ $_name = explode('@', $_SESSION['email']); $name = $_name[0]; }
  
  echo json_encode($name);
}
if(isset($_POST['getInformation'])){
  if($_SESSION['buyer']){
    $name = $_SESSION['firstname'] . " " . $_SESSION['lastname'];
    $role = "user";
  }
  elseif($_SESSION['agent']){
    $name = $_SESSION['firstname'] . " " . $_SESSION['lastname'];
    $role = "agent";
    
    if(!isset($name)){
      $_name = explode('@', $_SESSION['email']);
      $name = $_name[0];
    }
  }
  else{
    $name = "Guest";
    $role = "guest";
  }
  
  $info = array("name"=>$name, "email"=>$_SESSION['email'], "role"=>$role, "adminOptions"=>$_SESSION['admin'], "unreadMessages"=>$_SESSION['unreadMessages']);
  
  echo json_encode($info);
}
if(isset($_POST['getRole'])){
  if($_SESSION['buyer']){ $role = "user"; }
  elseif($_SESSION['agent']){ $role = "agent"; }
  else{ $role = "guest"; }
  
  echo json_encode($role);
}
?>