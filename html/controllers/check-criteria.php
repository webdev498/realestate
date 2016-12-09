<?php
session_start();
include_once("dbconfig.php");
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

$email = $_POST['email'];
if($email == ''){ $email = $_SESSION['email']; }

//Set session to retain buyer for save to buyer
$_SESSION['buyerSave'] = $email;
$num = 0;
    
if(isset($_POST['name'])){
  $name = $_POST['name'];
  
  $SQL = "SELECT * FROM `Users_Search` where (email = '".$email."') AND (name = '".$name."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $num = mysql_num_rows($result);
  
  echo json_encode($num);
}

else if(isset($_POST['numName'])){
  $name = $_POST['numName'];
  
  $SQL = "SELECT * FROM `Users_Search` where (email = '".$email."') AND (name LIKE '".$name."%')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $num = mysql_num_rows($result);
  
  echo json_encode($num);
}

else if(isset($_POST['search'])){
  $searches = array();
  
  $SQL = "SELECT * FROM `Users_Search` where (email = '".$email."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());    
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    array_push($searches, $row['name']);
  }
    
  echo json_encode($searches);
}

else{
  $SQL = "SELECT * FROM `Users_Search` where (email = '".$email."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());    
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
    $num = $num + 1;
  }
    
  echo json_encode($num);
}

?>