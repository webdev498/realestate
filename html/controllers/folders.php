<?php
session_start();
include("dbconfig.php");
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");
$list_num = $_GET['listnum'];
$buyer_email = $_POST['buyer'];

if ($_SESSION['agent']){
  $user = $_SESSION['aid'];
  $role = 'agent';
  $email = $_SESSION['email'];
  if(isset($_SESSION['buyerSave'])){
    $savedBuyer = $_SESSION['buyerSave'];
  }else{
    $savedBuyer = "";
  }
  
  $SQL = "SELECT id FROM `Agent_Import` WHERE (e_mail = '".$email."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $agent_id = $row['id'];
} elseif ($_SESSION['user']){
  $user = $_SESSION['id'];
  $role = 'user';
  $email = $_SESSION['email'];
}

if($_SESSION['agent']){
	  $SQL = "SELECT * FROM queued_listings WHERE (user = '".$_SESSION['email']."') AND (list_num = '".$list_num."')";
	  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
	  $num = mysql_num_rows($result);
	  if($num){
		  print 1;        
	  } else{
		  print 0;        
	  }
	}
	else{
	  $SQL = "SELECT * FROM saved_listings WHERE (user = '".$_SESSION['email']."') AND (list_num = '".$list_num."')";
	  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
	  $num = mysql_num_rows($result);
	  if($num){
	   //Listing is saved
	   print "<p>Listing has already been saved!</p>";
		  //print 1;        
	  } else{
	   //Listing is NOT saved
	   $folders = array();
	
	if(isset($_SESSION['email'])){
	  $SQL = "SELECT * FROM users_folders WHERE (user = '".$_SESSION['email']."') ORDER BY name ASC";
	}
    else{
	  $SQL = "SELECT * FROM users_folders WHERE (user = '".$_SESSION['email']."') ORDER BY name ASC";
	}
    $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
    
    while( $b = mysql_fetch_array($result,MYSQL_ASSOC) ){
        
        $o = array(
            'id'   => $b['id'],
            'name' => $b['name']
            'last_update' => $b['last_update'];
        );
        array_push($folders, $o);
    }
		 // print 0;        
	  }
	}
/* if(isset($_POST['agentID'])){
 $SQL = "SELECT * FROM `users_folders` WHERE (user = '".$buyer_email."') AND (agent LIKE '%".$_POST['agentID']."%') ORDER BY name ASC"; 
}
else{
  $SQL = "SELECT * FROM `users_folders` WHERE (user = '".$buyer_email."') ORDER BY name ASC";
}
$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  
$folders = array();
$id = 1;
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
 
  $folder = array("id"=>$id, "name"=>$row["name"], "agent"=>$row['agent'], "last_update"=>$row['last_update']);
  array_push($folders, $folder);
  $id = $id + 1;
}
echo json_encode($folders); */
?>
