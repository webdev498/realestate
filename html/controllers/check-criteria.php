<?php
session_start();
include_once("dbconfig.php");
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

$email = (isset($_POST['email']) ? $_POST['email'] : $_SESSION['email']);

//Set session to retain buyer for save to buyer
$_SESSION['buyerSave'] = $email;
$num = 0;
    
if(isset($_POST['name'])){
  $result = mysql_query( "SELECT * FROM `Users_Search` where (email = '".$email."') AND (name = '".$_POST['name']."')" ) or die("Couldn't execute query.".mysql_error());
  $num = mysql_num_rows($result);
  
  echo json_encode($num);
}

else if(isset($_POST['numName'])){
  $result = mysql_query( "SELECT * FROM `Users_Search` where (email = '".$email."') AND (name LIKE '".$_POST['numName']."%')" ) or die("Couldn't execute query.".mysql_error());
  $num = mysql_num_rows($result);
  
  echo json_encode($num);
}

else if(isset($_POST['search'])){
  $searches = array();
  $result = mysql_query( "SELECT * FROM `Users_Search` where (email = '".$email."')" ) or die("Couldn't execute query.".mysql_error());    
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    array_push($searches, $row['name']);
  }
    
  echo json_encode($searches);
}

else{
  $result = mysql_query( "SELECT * FROM `Users_Search` where (email = '".$email."')" ) or die("Couldn't execute query.".mysql_error());    
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
    $num = $num + 1;
  }
    
  echo json_encode($num);
}

?>