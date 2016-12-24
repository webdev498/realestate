<?php
session_start();
include("dbconfig.php");
include('emailconfig.php');

// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if ($_SESSION['user']){
  $user = $_SESSION['id'];
  $email = $_SESSION['email'];
  $role = 'user';
}

if($_POST['firstName']){
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  
  $SQL = "UPDATE `registered_agents` SET first_name='".$firstName."', last_name='".$lastName."' WHERE (email = '".$email."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  
  $_SESSION['firstname'] = $firstName;
  $_SESSION['lastname'] = $lastName;
}

if($_POST['phone']){
  $phone = $_POST['phone'];
  
  $SQL = "UPDATE `registered_agents` SET phone='".$phone."' WHERE (email = '".$email."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  
  $_SESSION['phone'] = $phone;
}

if($_POST['email']){
  $oldEmail = $email;
  $newEmail = $_POST['email'];
  
  $SQL = "UPDATE `registered_agents` SET email='".$newEmail."' WHERE (email = '".$oldEmail."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  
  $SQL = "UPDATE `users_folders` SET user='".$newEmail."' WHERE (user = '".$oldEmail."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  
  $SQL = "UPDATE `queued_listings` SET user='".$newEmail."', saved_by='".$newEmail."'  WHERE (user = '".$oldEmail."') AND (saved_by = '".$oldEmail."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());

  $SQL = "UPDATE `queued_listings` SET user='".$newEmail."' WHERE (user = '".$oldEmail."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());

  $_SESSION['email'] = $newEmail;
}

if($_POST['bio']){
  $bio = $_POST['bio'];
  
  $SQL = "UPDATE `registered_agents` SET bio='".$bio."' WHERE (email = '".$email."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
}

?>