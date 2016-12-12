<?php
session_start();
include("dbconfig.php");
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");
$list_num = $_GET['listnum'];
$buyer_email = $_POST['buyer'];

if(isset($_SESSION['agent'])){
  $email = $_SESSION['email'];
  if(isset($_SESSION['buyerSave'])){ $savedBuyer = $_SESSION['buyerSave']; }
  else{ $savedBuyer = ""; }
  
  $result = mysql_query( "SELECT * FROM queued_listings WHERE (user = '".$email."') AND (list_num = '".$list_num."')" ) or die("Couldn't execute query.".mysql_error());
  $num = mysql_num_rows($result);
  if($num){ print 1; }
  else{ print 0; }
}
else{
  $email = $_SESSION['email'];
  
  $result = mysql_query( "SELECT * FROM saved_listings WHERE (user = '".$email."') AND (list_num = '".$list_num."')" ) or die("Couldn't execute query.".mysql_error());
  $num = mysql_num_rows($result);
  if($num > 0){
   print "<p>Listing has already been saved!</p>"; //Listing is saved
  }
  else{
    //Listing is NOT saved
    $folders = array();
	
    if(isset($_SESSION['email'])){ $SQL = "SELECT * FROM users_folders WHERE (user = '".$email."') ORDER BY name ASC"; }
    else{ $SQL = "SELECT * FROM users_folders WHERE (user = '".$email."') ORDER BY name ASC"; }
    $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());    
    while( $b = mysql_fetch_array($result,MYSQL_ASSOC) ){        
      $o = array(
        'id' => $b['id'],
        'name' => $b['name'],
        'last_update' => $b['last_update']
      );
      array_push($folders, $o);
    }
	}
}
?>
