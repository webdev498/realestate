<?php
session_start();
include_once('functions.php');
include_once('emailconfig.php');
include_once("dbconfig.php");
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if(isset($_SESSION['agent'])){
  $agent_firstname = $_SESSION['firstname'];
  $agent_lastname = $_SESSION['lastname'];
  $email = $_SESSION['email'];
  $agent_id = $_SESSION['agent_id'];
  $role = 'agent';
  if(isset($_SESSION['buyerSave'])){ $savedBuyer = $_SESSION['buyerSave']; }
  else{ $savedBuyer = ""; }
}
elseif ($_SESSION['user']){
  $email = $_SESSION['email'];
  $role = 'user';
}
 
if( isset($_GET['getMyFolders']) ){
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  $folders = array();
	
	if(isset($_GET['email'])){
    $SQL = "SELECT * FROM users_folders WHERE (user = '".$_GET['email']."') AND (agent LIKE '%".$agent_id."%') ORDER BY name ASC";
    $SQL2 = "SELECT CONCAT(first_name, ' ', last_name) AS name FROM `users` WHERE (email = '".$_GET['email']."')";
  }
  else if(isset($_SESSION['guestID'])){
    $SQL = "SELECT * FROM users_folders WHERE (user = '".$_SESSION['guestID']."')";
    $SQL2 = "SELECT CONCAT(first_name, ' ', last_name) AS name FROM `users` WHERE (email = '".$_SESSION['guestID']."')";
  }
  else{
    $SQL = "SELECT * FROM users_folders WHERE (user = '".$email."') ORDER BY name ASC";
    $SQL2 = "SELECT CONCAT(first_name, ' ', last_name) AS name FROM `users` WHERE (email = '".$email."')";
  }
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  while( $b = mysql_fetch_array($result,MYSQL_ASSOC) ){
    $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
    $row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
    
    $o = array( 'id' => $b['id'], 'name' => $b['name'], 'last_update' => $b['last_update'], 'buyerName' => $row2['name'] );
    array_push($folders, $o);
  }
        
  echo json_encode($folders);
  return true;
}
else if( isset($_GET['getAgentSave']) ){
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  $folders = array();
	$buyers = array();
	
  $result = mysql_query( "SELECT * FROM users_folders WHERE (user = '".$_SESSION['email']."') ORDER BY name ASC" ) or die("Couldn't execute query.".mysql_error());
  while( $b = mysql_fetch_array($result,MYSQL_ASSOC) ){
    $f = array('id'   => $b['id'], 'name' => $b['name'] );
    array_push($folders, $f);
  }

	$result = mysql_query( "SELECT u.*, f.* FROM `users` AS u LEFT JOIN `registered_agents` AS r ON u.P_agent=r.agent_id OR u.P_agent2=r.agent_id LEFT JOIN `users_folders` as f ON u.email=f.user WHERE (r.email = '".$email."') AND (u.archived != '1') AND (f.agent = r.agent_id) ORDER BY last_name ASC " ) or die("Couldn't execute query.".mysql_error());
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	  $name = array("id"=>$row['id'], "first_name"=> $row['first_name'], "last_name"=> $row['last_name'], "email"=>$row['email'], "folderName"=>$row['name']);
	  array_push($buyers, $name);
	}
	
	$info = array("folders"=>$folders, "buyers"=>$buyers, "agent_email"=>$_SESSION['email']);
	      
  echo json_encode($info);
  return true;
}
else if( isset($_POST['agentSaveListingInFolders']) ){  
  $list_num = $_POST['list_num'];
  $comments = $_POST['comment'];
  $buyers = $_POST['selectedBuyers'];
  $agent_id = $_SESSION['agent_id'];
  $agent_firstname = $_SESSION['firstname'];
  $agent_lastname = $_SESSION['lastname'];
  $time = date('U');
  $buyernames = array();
  $buyeremails = array();
  
  foreach ($buyers as $buyer) {
    if(strpos($buyer, "@bellmarc.com") !== false){
      array_push($buyernames, "Agent Folder");
      array_push($buyeremails, $_SESSION['email']);
      $rs = mysql_query( "SELECT * FROM queued_listings WHERE user = '" . $buyer . "' AND list_num = '" . $list_num . "'" );
      $num = mysql_num_rows($rs);
    
      if ($num < 1){ $res = mysql_query( "INSERT INTO queued_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$buyer."','".$list_num."','".$_SESSION['email']."','".$comments."','agent','Agent Folder','".$time."')" ) or die(mysql_error()); }
      
      $res2 = mysql_query( "UPDATE	users_folders SET last_update='".$time."' where name='Agent Folder' AND user = '".$buyer."'" ) or die(mysql_error());      
    }
    else{     
      array_push($buyeremails, $buyer);
      $result1 = mysql_query( "SELECT name FROM `users_folders` WHERE (user = '".$buyer."') AND (agent = '".$agent_id."')" ) or die("Couldn't execute query.".mysql_error());
      $row1 = mysql_fetch_array($result1,MYSQL_ASSOC);
      $folder = $row1['name'];
        
      $rs = mysql_query( "SELECT * FROM saved_listings WHERE user = '" . $buyer . "' AND list_num = '" . $list_num . "' AND folder = '".$folder."'" );
      $num = mysql_num_rows($rs);
    
      if ($num < 1){
        $res = mysql_query( "INSERT INTO saved_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$buyer."','".$list_num."','".$_SESSION['email']."','".$comments."','buyer','".$folder."','".$time."')" ) or die(mysql_error());
        $res2 = mysql_query( "UPDATE	users_folders SET last_update='".$time."' WHERE (user ='". $buyer ."') AND (name='". $folder ."')" ) or die(mysql_error());
        
        $result = mysql_query( "SELECT CONCAT(first_name, ' ' , last_name) as name FROM `users` WHERE (email = '".$buyer."')" ) or die("Couldn't execute query.".mysql_error());
        $row = mysql_fetch_array($result,MYSQL_ASSOC);
        $buyer_name = $row['name'];

        array_push($buyernames, $buyer_name); 
        
        $message = "Hello " . $buyer_name . ",";
        $message .= "<br><br>" . $agent_firstname . " " . $agent_lastname . " has saved a new listing to your folder: " . $folder;
        //$message .= "<br><br>Listing Link: http://homepik.com/controllers/single-listing.php?". $list_num;
        $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
        $message .= "HomePik.com is licensed by Nice Idea Media";
    
        $mail->addAddress($buyer);
        $mail->Subject = 'New Listing from HomePik';
        $mail->Body = $message;
        $mail->send();
      }
    }
  }
  
  $_SESSION['lastBuyerUsed'] = $buyeremails;
  echo implode(', ', $buyernames);
  return true;
}
else if( isset($_POST['saveListingInMyFolders']) ){
  $list_num = $_POST['list_num'];
  $comment = $_POST['comment'];
  $folders =  $_POST['selectedFolders'];
  $foldernames = array();
	
	if(isset($_POST['buyer'])){
	  $buyer = $_POST['buyer'];
    $agent_firstname = $_SESSION['firstname'];
    $agent_lastname = $_SESSION['lastname'];
	  $agent = $_SESSION['agent_id'];
	  
	  foreach ($folders as $folder) {
      $result = mysql_query( "SELECT * FROM `users_folders` WHERE (user = '".$buyer."') AND (name = '" . $folder . "')" ) or die("Couldn't execute query." . mysql_error());
      $num = mysql_num_rows($result);

      if($num == 0){ $res = mysql_query( "INSERT INTO users_folders(`user`,`name`, `agent`, `last_update`) VALUES  ('".$buyer."','".$folder."','".$agent."','".date('U')."')" ) or die(mysql_error()); }
		
      array_push($foldernames, $folder);
		
      if(strpos($buyer, "@bellmarc.com") !== false){
        $res = mysql_query( "INSERT INTO queued_listings(`user`, `folder`, `time`, `saved_by`, `comments`, `role`, `list_num`) VALUES  ('".$_SESSION['email']."', '".$folder."', '".date('U')."', '".$_SESSION['email']."', '".$comment."', 'agent', '".$list_num."')" ) or die(mysql_error());
        $res2 = mysql_query( "UPDATE `users_folders` SET `last_update`='".date('U')."' WHERE (`user` = '".$_SESSION['email']."') AND (`name` = '".$folder."')" ) or die(mysql_error());
      }
      else{
        $res = mysql_query( "INSERT INTO saved_listings(`user`, `folder`, `time`, `saved_by`, `comments`, `role`, `list_num`) VALUES  ('".$buyer."', '".$folder."', '".date('U')."', '".$_SESSION['email']."', '".$comment."', 'user', '".$list_num."')" ) or die(mysql_error());
        $res2 = mysql_query( "UPDATE `users_folders` SET `last_update`='".date('U')."' WHERE (`user` = '".$buyer."') AND (`name` = '".$folder."')" ) or die(mysql_error());
        
        $result = mysql_query( "SELECT first_name, last_name FROM `users` WHERE (email = '".$buyer."')" ) or die("Couldn't execute query.".mysql_error());
        $row = mysql_fetch_array($result,MYSQL_ASSOC);
        $buyer_firstname = $row['first_name'];
        $buyer_lastname = $row['last_name'];
        
        $message = "Hello " . $buyer_firstname . " " . $buyer_lastname . ",";
        $message .= "<br><br>" . $agent_firstname . " " . $agent_lastname . " has saved a new listing to your folder: " . $folder;
        //$message .= "<br><br>Listing Link: http://homepik.com/controllers/single-listing.php?". $list_num;
        $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
        $message .= "HomePik.com is licensed by Nice Idea Media";
    
        $mail->addAddress($buyer);
        $mail->Subject = 'New Listing from HomePik';
        $mail->Body = $message;
        $mail->send();
      }
	  }
	}
	else{
	  foreach ($folders as $folder) {
      $result = mysql_query( "SELECT * FROM `users_folders` WHERE (user = '".$_SESSION['email']."') AND (name = '" . $folder . "')" ) or die("Couldn't execute query." . mysql_error());
      $row = mysql_fetch_array($result, MYSQL_ASSOC);
      $num = mysql_num_rows($result);
      $folderAgent = $row['agent'];
      
      array_push($foldernames, $folder);
		
      if($_SESSION['agent']){
        $res = mysql_query( "INSERT INTO queued_listings(`user`,`list_num`,`saved_by`,`comments`, `folder`, `role`, `time`) VALUES  ('".$_SESSION['email']."','".$list_num."','".$_SESSION['email']."','".$comment."','".$folder."','".$role."','".date('U')."')" ) or die(mysql_error());
        $res2 = mysql_query( "UPDATE `users_folders` SET `last_update`='".date('U')."' WHERE (`user` = '".$_SESSION['email']."') AND (`name` = '".$folder."')" ) or die(mysql_error());
      }
      else{
        $res = mysql_query( "INSERT INTO saved_listings(`user`, `folder`, `time`, `saved_by`, `comments`, `role`, `list_num`) VALUES  ('".$_SESSION['email']."', '".$folder."', '".date('U')."', '".$_SESSION['email']."', '".$comment."', '".$role."', '".$list_num."')" ) or die(mysql_error());
        $res2 = mysql_query( "UPDATE `users_folders` SET `last_update`='".date('U')."' WHERE (`user` = '".$_SESSION['email']."') AND (`name` = '".$folder."')" ) or die(mysql_error());
        
        $result3 = mysql_query( "SELECT first_name, last_name, email FROM `registered_agents` WHERE (agent_id = '".$folderAgent."')" ) or die("Couldn't execute query.".mysql_error());
        $row3 = mysql_fetch_array($result3,MYSQL_ASSOC);
        $agent_firstname = $row3['first_name'];
        $agent_lastname = $row3['last_name'];
        $agent_email = $row3['email'];
        
        $message = "Hello " . $agent_firstname . " " . $agent_lastname . ",";
        $message .= "<br><br>" . $_SESSION['firstname'] . " " . $_SESSION['lastname'] . " has saved a new listing to their folder: " . $folder;
        //$message .= "<br><br>Listing Link: http://homepik.com/controllers/single-listing.php?". $list_num;
        $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
        $message .= "HomePik.com is licensed by Nice Idea Media";
    
        $mail->addAddress($agent_email);
        $mail->Subject = 'HomePik Buyer Folder Update';
        $mail->Body = $message;
        $mail->send();
  		}
	  }  
	}
  
  $_SESSION['lastFolderUsed'] = $foldernames;
  echo implode(', ', $foldernames);
  return true;
}
else if( isset($_POST['saveListingInMyOneFolder']) ){
  $list_num = $_POST['list_num'];
  $comment = $_POST['comment'];
  $folder =  $_POST['selectedFolders'];
  $foldername = array();
  array_push($foldername, $folder);
	
  $result = mysql_query( "SELECT * FROM `users_folders` WHERE (user = '".$_SESSION['email']."') AND (name = '" . $folder . "')" ) or die("Couldn't execute query." . mysql_error());
  $row = mysql_fetch_array($result, MYSQL_ASSOC);
  $num = mysql_num_rows($result);
  $folderAgent = $row['agent'];
  
  if(isset($_SESSION['agent'])){
    $res = mysql_query( "INSERT INTO queued_listings(`user`,`list_num`,`saved_by`,`comments`, `folder`, `role`, `time`) VALUES  ('".$_SESSION['email']."','".$list_num."','".$_SESSION['email']."','".$comment."','".$folder."','".$role."','".date('U')."')" ) or die(mysql_error());
    $res2 = mysql_query( "UPDATE `users_folders` SET `last_update`='".date('U')."' WHERE (`user` = '".$_SESSION['email']."') AND (`name` = '".$folder."')" ) or die(mysql_error());
  }
  else if($_SESSION['buyer']){
    $res = mysql_query( "INSERT INTO saved_listings(`user`, `folder`, `time`, `saved_by`, `comments`, `role`, `list_num`) VALUES  ('".$_SESSION['email']."', '".$folder."', '".date('U')."', '".$_SESSION['email']."', '".$comment."', '".$role."', '".$list_num."')" ) or die(mysql_error());
    $res2 = mysql_query( "UPDATE `users_folders` SET `last_update`='".date('U')."' WHERE (`user` = '".$_SESSION['email']."') AND (`name` = '".$folder."')" ) or die(mysql_error());
    
    if($folderAgent != "" && $folderAgent != null){
      $result3 = mysql_query( "SELECT first_name, last_name, email FROM `registered_agents` WHERE (agent_id = '".$folderAgent."')" ) or die("Couldn't execute query.".mysql_error());
      $row3 = mysql_fetch_array($result3,MYSQL_ASSOC);
      $agent_firstname = $row3['first_name'];
      $agent_lastname = $row3['last_name'];
      $agent_email = $row3['email'];
      
      $message = "Hello " . $agent_firstname . " " . $agent_lastname . ",";
      $message .= "<br><br>" . $_SESSION['firstname'] . " " . $_SESSION['lastname'] . " has saved a new listing to their folder: " . $folder;
      //$message .= "<br><br>Listing Link: http://homepik.com/controllers/single-listing.php?". $list_num;
      $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
      $message .= "HomePik.com is licensed by Nice Idea Media";
  
      $mail->addAddress($agent_email);
      $mail->Subject = 'HomePik Buyer Folder Update';
      $mail->Body = $message;
      $mail->send();
    }
  }
  else{
    $res = mysql_query( "INSERT INTO saved_listings(`user`, `folder`, `time`, `saved_by`, `comments`, `role`, `list_num`) VALUES  ('".$_SESSION['guestID']."', '".$folder."', '".date('U')."', '".$_SESSION['guestID']."', '".$comment."', 'guest', '".$list_num."')" ) or die(mysql_error());
    $res2 = mysql_query( "UPDATE `users_folders` SET `last_update`='".date('U')."' WHERE (`user` = '".$_SESSION['guestID']."') AND (`name` = '".$folder."')" ) or die(mysql_error());
  }

  $_SESSION['lastFolderUsed'] = $foldername;
  echo implode(', ', $foldername);
  return true;
}
else if( isset($_POST['deleteListingFromFolders']) ){
  $list_num = $_POST['list_num'];
  $email = $_SESSION['email'];
  $folders = array();
  
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

	if(isset($_SESSION['buyer'])){
    $result1 = mysql_query( "SELECT folder FROM saved_listings WHERE (user = '".$email."') AND (list_num = '".$list_num."')" ) or die(mysql_error());
    while($row1 = mysql_fetch_array($result1,MYSQL_ASSOC)){
      array_push($folders, $row1['folder']);
    }
    
    $result2 = mysql_query( "DELETE FROM saved_listings WHERE (user = '".$email."') AND (list_num = '".$list_num."')" ) or die(mysql_error());
  
    if ($result2 == true) {
      foreach($folders as $folder){
        $result3 = mysql_query( "SELECT agent FROM users_folders WHERE (user='".$email."') AND (name='".$folder."')" ) or die(mysql_error());
        $row3 = mysql_fetch_array($result3,MYSQL_ASSOC);
        $agent_id = $row3['agent'];
        
        $result5 = mysql_query( "SELECT first_name, last_name, email FROM `registered_agents` WHERE (agent_id = '".$agent_id."')" ) or die("Couldn't execute query.".mysql_error());
        $row5 = mysql_fetch_array($result5,MYSQL_ASSOC);
        $agent_firstname = $row5['first_name'];
        $agent_lastname = $row5['last_name'];
        $agent_email = $row5['email'];
        
        $message = "Hello " . $agent_firstname . " " . $agent_lastname . ",";
        $message .= "<br><br>" . $_SESSION['firstname'] . " " . $_SESSION['lastname'] . " has removed a listing from their folder: " . $folder;
        $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
        $message .= "HomePik.com is licensed by Nice Idea Media";
    
        $mail->addAddress($agent_email);
        $mail->Subject = 'HomePik Folder Change';
        $mail->Body = $message;
        $mail->send();
        
        $result6 = mysql_query( "UPDATE users_folders SET last_update='".date('U')."' WHERE (user='".$email."') AND (name='".$folder."')" ) or die(mysql_error());
      }
    }	  
	}
	else if(isset($_SESSION['agent'])){
    $result1 = mysql_query( "SELECT folder FROM queued_listings WHERE (user = '".$email."') AND (list_num = '".$list_num."')" ) or die(mysql_error());
    while($row1 = mysql_fetch_array($result1,MYSQL_ASSOC)){
      array_push($folders, $row1['folder']);
    }
    
    $result2 = mysql_query( "DELETE FROM queued_listings WHERE (user = '".$email."') AND (list_num = '".$list_num."')" ) or die(mysql_error());
      
    if ($result2 == true) {
      foreach($folders as $folder){
        $result3 = mysql_query( "UPDATE users_folders SET last_update='".date('U')."' WHERE (user='".$email."') AND (name='".$folder."')" ) or die(mysql_error());
      }
    }
  }
  else{
    $result1 = mysql_query( "SELECT folder FROM saved_listings WHERE (user = '".$_SESSION['guestID']."') AND (list_num = '".$list_num."')" ) or die(mysql_error());
    while($row1 = mysql_fetch_array($result1,MYSQL_ASSOC)){
      array_push($folders, $row1['folder']);
    }
    
    $result2 = mysql_query( "DELETE FROM saved_listings WHERE (user = '".$_SESSION['guestID']."') AND (list_num = '".$list_num."')" ) or die(mysql_error());
  
    if ($result2 == true) {
      foreach($folders as $folder){
        $result6 = mysql_query( "UPDATE users_folders SET last_update='".date('U')."' WHERE (user='".$_SESSION['guestID']."') AND (name='".$folder."')" ) or die(mysql_error());
      }
    }
  }
  
  echo implode(', ', $folders);
  return true;
}
else if ( isset($_GET['checkIfListingAlreadySaved'])){
  $list_num = $_GET['list_num'];
  /*check if the name is already used*/
	if(isset($_SESSION['agent'])){
	  $result = mysql_query( "SELECT * FROM queued_listings WHERE (user = '".$_SESSION['email']."') AND (list_num = '".$list_num."')" ) or die("Couldn't execute query.".mysql_error());
	  $num = mysql_num_rows($result);
	  if($num){ print 1; }
    else{ print 0; }
	}
	else if(isset($_SESSION['buyer'])){
	  $result = mysql_query( "SELECT * FROM saved_listings WHERE (user = '".$_SESSION['email']."') AND (list_num = '".$list_num."')" ) or die("Couldn't execute query.".mysql_error());
	  $num = mysql_num_rows($result);
	  if($num){ print 1; }
    else{ print 0; }
	}
	else{
	  $result = mysql_query( "SELECT * FROM saved_listings WHERE (user = '".$_SESSION['guestID']."') AND (list_num = '".$list_num."')" ) or die("Couldn't execute query.".mysql_error());
	  $num = mysql_num_rows($result);
	  if($num > 0){ print $num; }
    else{ print 0; }	
	}
}
?>