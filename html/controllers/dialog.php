<?php
session_start();
include('functions.php');
include('emailconfig.php');
include("dbconfig.php");

// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

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
 
/*code to get user folders*/
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
	
  $SQL = "SELECT * FROM users_folders WHERE (user = '".$_SESSION['email']."') ORDER BY name ASC";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
    
  while( $b = mysql_fetch_array($result,MYSQL_ASSOC) ){
    $f = array('id'   => $b['id'], 'name' => $b['name'] );
    array_push($folders, $f);
  }
    
	$SQL = "SELECT u.* FROM `users` AS u LEFT JOIN `Agent_Import` AS a ON u.P_agent=a.id OR u.P_agent2=a.id WHERE (a.e_mail = '".$email."') AND (u.archived != '1') ORDER BY last_name ASC";
	$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	  $name = array("id"=>$row['id'], "first_name"=> $row['first_name'], "last_name"=> $row['last_name'], "email"=>$row['email']);
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
  $time = date('U');
  $buyernames = array();
  $buyeremails = array();
  
  $SQL = "SELECT id FROM `Agent_Import` WHERE (e_mail = '".$_SESSION['email']."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $agent_id = $row['id'];
  	
  foreach ($buyers as $buyer) {
    if(strpos($buyer, "@bellmarc.com") !== false){
      array_push($buyernames, "Agent Folder");
      array_push($buyeremails, $_SESSION['email']);
      $sql = "SELECT * FROM queued_listings WHERE user = '" . $buyer . "' AND list_num = '" . $list_num . "'";
      $rs = mysql_query($sql);
      $num = mysql_num_rows($rs);
    
      if ($num < 1){
        $SQL = "INSERT INTO queued_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$buyer."','".$list_num."','".$_SESSION['email']."','".$comments."','agent','Agent Folder','".$time."')";
        $res = mysql_query($SQL)  or die(mysql_error());
      }
      
      $SQL2 = "UPDATE	users_folders SET last_update='".$time."' where name='Agent Folder' AND user = '".$buyer."'";
      $res2 = mysql_query($SQL2)  or die(mysql_error());
      
    }
    else{     
      array_push($buyeremails, $buyer);
      $SQL1 = "SELECT name FROM `users_folders` WHERE (user = '".$buyer."') AND (agent = '".$agent_id."')";
      $result1 = mysql_query( $SQL1 ) or die("Couldn't execute query.".mysql_error());
      $row1 = mysql_fetch_array($result1,MYSQL_ASSOC);
      $folder = $row1['name'];
        
      $sql = "SELECT * FROM saved_listings WHERE user = '" . $buyer . "' AND list_num = '" . $list_num . "' AND folder = '".$folder."'";
      $rs = mysql_query($sql);
      $num = mysql_num_rows($rs);
    
      if ($num < 1){
        $SQL = "INSERT INTO saved_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$buyer."','".$list_num."','".$_SESSION['email']."','".$comments."','buyer','".$folder."','".$time."')";
        $res = mysql_query($SQL)  or die(mysql_error());
        
        $SQL2 = "UPDATE	users_folders SET last_update='".$time."' WHERE (user ='". $buyer ."') AND (name='". $folder ."')";
        $res2 = mysql_query($SQL2)  or die(mysql_error());
        
        $SQL = "SELECT CONCAT(first_name, ' ' , last_name) as name FROM `users` WHERE (email = '".$buyer."')";
        $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
        $row = mysql_fetch_array($result,MYSQL_ASSOC);
        $buyer_name = $row['name'];

        array_push($buyernames, $buyer_name); 
        
        $message = "Hello " . $buyer_name . ",";
        $message .= "<br><br>" . $agent_firstname . " " . $agent_lastname . " has saved a new listing to your folder: " . $folder;
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
	  
	  $SQL = "SELECT firstname, lastname, id FROM `Agent_Import` WHERE (e_mail = '".$_SESSION['email']."')";
	  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
	  $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $agent_firstname = $row['firstname'];
    $agent_lastname = $row['lastname'];
	  $agent = $row['id'];
	  
	  foreach ($folders as $folder) {
      $SQL = "SELECT * FROM `users_folders` WHERE (user = '".$buyer."') AND (name = '" . $folder . "')";
      $result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
      $num = mysql_num_rows($result);
		
      if($num == 0){
        $SQL1 = "INSERT INTO users_folders(`user`,`name`, `agent`, `last_update`) VALUES  ('".$buyer."','".$folder."','".$agent."','".date('U')."')";
        $res = mysql_query($SQL1)  or die(mysql_error());
      }
		
      array_push($foldernames, $folder);
		
      if(strpos($buyer, "@bellmarc.com") !== false){
        $SQL = "INSERT INTO queued_listings(`user`, `folder`, `time`, `saved_by`, `comments`, `role`, `list_num`) VALUES  ('".$_SESSION['email']."', '".$folder."', '".date('U')."', '".$_SESSION['email']."', '".$comment."', 'agent', '".$list_num."')";
        $res = mysql_query($SQL)  or die(mysql_error());
        
        $SQL2 = "UPDATE `users_folders` SET `last_update`='".date('U')."' WHERE (`user` = '".$_SESSION['email']."') AND (`name` = '".$folder."')";
        $res2 = mysql_query($SQL2)  or die(mysql_error());
      }
      else{
        $SQL = "INSERT INTO saved_listings(`user`, `folder`, `time`, `saved_by`, `comments`, `role`, `list_num`) VALUES  ('".$buyer."', '".$folder."', '".date('U')."', '".$_SESSION['email']."', '".$comment."', 'user', '".$list_num."')";
        $res = mysql_query($SQL)  or die(mysql_error());
        
        $SQL2 = "UPDATE `users_folders` SET `last_update`='".date('U')."' WHERE (`user` = '".$buyer."') AND (`name` = '".$folder."')";
        $res2 = mysql_query($SQL2)  or die(mysql_error());
        
        $SQL = "SELECT first_name, last_name FROM `users` WHERE (email = '".$buyer."')";
        $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
        $row = mysql_fetch_array($result,MYSQL_ASSOC);
        $buyer_firstname = $row['first_name'];
        $buyer_lastname = $row['last_name'];
        
        $message = "Hello " . $buyer_firstname . " " . $buyer_lastname . ",";
        $message .= "<br><br>" . $agent_firstname . " " . $agent_lastname . " has saved a new listing to your folder: " . $folder;
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
      $SQL = "SELECT * FROM `users_folders` WHERE (user = '".$_SESSION['email']."') AND (name = '" . $folder . "')";
      $result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
      $row = mysql_fetch_array($result, MYSQL_ASSOC);
      $num = mysql_num_rows($result);
      $folderAgent = $row['agent'];
      
      array_push($foldernames, $folder);
		
      if($_SESSION['agent']){
        $SQL = "INSERT INTO queued_listings(`user`,`list_num`,`saved_by`,`comments`, `folder`, `role`, `time`) VALUES  ('".$_SESSION['email']."','".$list_num."','".$_SESSION['email']."','".$comment."','".$folder."','".$role."','".date('U')."')";
        $res = mysql_query($SQL)  or die(mysql_error());
        
        $SQL2 = "UPDATE `users_folders` SET `last_update`='".date('U')."' WHERE (`user` = '".$_SESSION['email']."') AND (`name` = '".$folder."')";
        $res2 = mysql_query($SQL2)  or die(mysql_error());
      }
      else{
        $SQL = "INSERT INTO saved_listings(`user`, `folder`, `time`, `saved_by`, `comments`, `role`, `list_num`) VALUES  ('".$_SESSION['email']."', '".$folder."', '".date('U')."', '".$_SESSION['email']."', '".$comment."', '".$role."', '".$list_num."')";
        $res = mysql_query($SQL)  or die(mysql_error());
        
        $SQL2 = "UPDATE `users_folders` SET `last_update`='".date('U')."' WHERE (`user` = '".$_SESSION['email']."') AND (`name` = '".$folder."')";
        $res2 = mysql_query($SQL2)  or die(mysql_error());
        
        $SQL3 = "SELECT firstname, lastname, e_mail FROM `Agent_Import` WHERE (id = '".$folderAgent."')";
        $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());
        $row3 = mysql_fetch_array($result3,MYSQL_ASSOC);
        $agent_firstname = $row3['firstname'];
        $agent_lastname = $row3['lastname'];
        $agent_email = $row3['e_mail'];
        
        $SQL5 = "SELECT first_name, last_name FROM `users` WHERE (email = '".$_SESSION['email']."')";
        $res5 = mysql_query($SQL5) or die("Couldn't execute query." . mysql_error());
        $row5 = mysql_fetch_array($res5, MYSQL_ASSOC);
        $buyer_firstname = $row5['first_name'];
        $buyer_lastname = $row5['last_name'];
        
        $message = "Hello " . $agent_firstname . " " . $agent_lastname . ",";
        $message .= "<br><br>" . $buyer_firstname . " " . $buyer_lastname . " has saved a new listing to their folder: " . $folder;
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
	
  $SQL = "SELECT * FROM `users_folders` WHERE (user = '".$_SESSION['email']."') AND (name = '" . $folder . "')";
  $result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
  $row = mysql_fetch_array($result, MYSQL_ASSOC);
  $num = mysql_num_rows($result);
  $folderAgent = $row['agent'];
  
  if($_SESSION['agent']){
    $SQL = "INSERT INTO queued_listings(`user`,`list_num`,`saved_by`,`comments`, `folder`, `role`, `time`) VALUES  ('".$_SESSION['email']."','".$list_num."','".$_SESSION['email']."','".$comment."','".$folder."','".$role."','".date('U')."')";
    $res = mysql_query($SQL)  or die(mysql_error());
    
    $SQL2 = "UPDATE `users_folders` SET `last_update`='".date('U')."' WHERE (`user` = '".$_SESSION['email']."') AND (`name` = '".$folder."')";
    $res2 = mysql_query($SQL2)  or die(mysql_error());
  }
  else if($_SESSION['buyer']){
    $SQL = "INSERT INTO saved_listings(`user`, `folder`, `time`, `saved_by`, `comments`, `role`, `list_num`) VALUES  ('".$_SESSION['email']."', '".$folder."', '".date('U')."', '".$_SESSION['email']."', '".$comment."', '".$role."', '".$list_num."')";
    $res = mysql_query($SQL)  or die(mysql_error());
    
    $SQL2 = "UPDATE `users_folders` SET `last_update`='".date('U')."' WHERE (`user` = '".$_SESSION['email']."') AND (`name` = '".$folder."')";
    $res2 = mysql_query($SQL2)  or die(mysql_error());
    
    if($folderAgent != "" && $folderAgent != null){
      $SQL3 = "SELECT firstname, lastname, e_mail FROM `Agent_Import` WHERE (id = '".$folderAgent."')";
      $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());
      $row3 = mysql_fetch_array($result3,MYSQL_ASSOC);
      $agent_firstname = $row3['firstname'];
      $agent_lastname = $row3['lastname'];
      $agent_email = $row3['e_mail'];
      
      $SQL5 = "SELECT first_name, last_name FROM `users` WHERE (email = '".$_SESSION['email']."')";
      $res5 = mysql_query($SQL5) or die("Couldn't execute query." . mysql_error());
      $row5 = mysql_fetch_array($res5, MYSQL_ASSOC);
      $buyer_firstname = $row5['first_name'];
      $buyer_lastname = $row5['last_name'];
      
      $message = "Hello " . $agent_firstname . " " . $agent_lastname . ",";
      $message .= "<br><br>" . $buyer_firstname . " " . $buyer_lastname . " has saved a new listing to their folder: " . $folder;
      $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
      $message .= "HomePik.com is licensed by Nice Idea Media";
  
      $mail->addAddress($agent_email);
      $mail->Subject = 'HomePik Buyer Folder Update';
      $mail->Body = $message;
      $mail->send();
    }
  }
  else{
    $SQL = "INSERT INTO saved_listings(`user`, `folder`, `time`, `saved_by`, `comments`, `role`, `list_num`) VALUES  ('".$_SESSION['guestID']."', '".$folder."', '".date('U')."', '".$_SESSION['guestID']."', '".$comment."', 'guest', '".$list_num."')";
    $res = mysql_query($SQL)  or die(mysql_error());
    
    $SQL2 = "UPDATE `users_folders` SET `last_update`='".date('U')."' WHERE (`user` = '".$_SESSION['guestID']."') AND (`name` = '".$folder."')";
    $res2 = mysql_query($SQL2)  or die(mysql_error());
  }

  $_SESSION['lastFolderUsed'] = $foldername;
  echo implode(', ', $foldername);
  return true;
}
else if( isset($_POST['saveListingInMyFoldersID']) ){

  $list_num = $_POST['list_num'];
  $folders =  $_POST['selectedFolders'];
  $foldernames = array();
  foreach ($folders as $folder) {        
    $SQL = "SELECT name FROM `users_folders` where (id = '" . $folder . "')";
    $result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $foldername = $row['name'];
    }
    array_push($foldernames, $foldername);
		
		if($_SESSION['agent']){
		  $SQL = "INSERT INTO queued_listings(`user`,`list_num`,`saved_by`,`comments`, `folder`, `role`, `time`) VALUES  ('".$_SESSION['email']."','".$list_num."','".$_SESSION['email']."','','".$foldername."','".$role."','".date('U')."')";
		  $res = mysql_query($SQL)  or die(mysql_error());
		  
		  $SQL2 = "UPDATE `users_folders` SET `last_update`='".date('U')."' WHERE (`user` = '".$_SESSION['email']."') AND (`name` = '".$foldername."')";
		  $res2 = mysql_query($SQL2)  or die(mysql_error());
		}
		else{
		  $SQL = "INSERT INTO saved_listings(`user`, `folder`, `time`, `saved_by`, `comments`, `role`, `list_num`) VALUES  ('".$_SESSION['email']."', '".$foldername."', '".date('U')."', '".$_SESSION['email']."', '', '".$role."', '".$list_num."')";
		  $res = mysql_query($SQL)  or die(mysql_error());
		  
		  $SQL2 = "UPDATE `users_folders` SET `last_update`='".date('U')."' WHERE (`user` = '".$_SESSION['email']."') AND (`name` = '".$foldername."')";
		  $res2 = mysql_query($SQL2)  or die(mysql_error());
    }
  }
  
  $_SESSION['lastFolderUsed'] = $foldernames;
  echo implode(', ', $foldernames);
  return true;
}
else if( isset($_POST['saveListingToLastFolders']) ){
  
  $list_num = $_POST['list_num'];
  $folders = $_SESSION['lastFolderUsed'];
  $buyer = $_SESSION['email'];
  $time = date('U');
  $foldernames = array();
  	
  foreach ($folders as $folder) {
    array_push($foldernames, $folder);
     
    $sql = "SELECT * FROM saved_listings WHERE user = '" . $buyer . "' AND list_num = '" . $list_num . "' AND folder = '".$folder."'";
    $rs = mysql_query($sql);
    $num = mysql_num_rows($rs);
  
    if ($num < 1){
      $SQL = "INSERT INTO saved_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$buyer."','".$list_num."','".$_SESSION['email']."','','buyer','".$folder."','".$time."')";
      $res = mysql_query($SQL)  or die(mysql_error());
      
      $SQL2 = "UPDATE	users_folders SET last_update='".$time."' WHERE (user ='". $buyer ."') AND (name='". $folder ."')";
      $res2 = mysql_query($SQL2)  or die(mysql_error());
      
      $SQL = "SELECT first_name, last_name FROM `users` WHERE (email = '".$buyer."')";
      $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
      $row = mysql_fetch_array($result,MYSQL_ASSOC);
      $buyer_firstname = $row['first_name'];
      $buyer_lastname = $row['last_name'];
      
      $SQL = "SELECT agent FROM `users_folders` WHERE (user = '".$buyer."') AND (name = '".$folder."')";
      $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
      $row = mysql_fetch_array($result,MYSQL_ASSOC);
      $agent_id = $row['agent'];
      
      $SQL = "SELECT firstname, lastname, e_mail FROM `Agent_Import` WHERE (id = '".$agent_id."')";
      $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
      $row = mysql_fetch_array($result,MYSQL_ASSOC);
      $agent_firstname = $row['firstname'];
      $agent_lastname = $row['lastname'];
      $agent_email = $row['e_mail'];
      
      $message = "Hello " . $agent_firstname . " " . $agent_lastname . ",";
      $message .= "<br><br>" . $buyer_firstname . " " . $buyer_lastname . " has saved a new listing to your folder: " . $folder;
      $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
      $message .= "HomePik.com is licensed by Nice Idea Media";
  
      $mail->addAddress($agent_email);
      $mail->Subject = 'New Listing from HomePik';
      $mail->Body = $message;
      $mail->send();
    }
  }
    
  echo implode(', ', $foldernames);
  return true;
}
else if( isset($_POST['saveListingToLastBuyers']) ){
  $list_num = $_POST['list_num'];
  $buyers = $_SESSION['lastBuyerUsed'];
  $time = date('U');
  $buyernames = array();
  
  $SQL = "SELECT id FROM `Agent_Import` WHERE (e_mail = '".$_SESSION['email']."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $agent_id = $row['id'];
  	
  foreach ($buyers as $buyer) {
    
    if(strpos($buyer, "@bellmarc.com") !== false){
      array_push($buyernames, "Agent Folder");
      
      $sql = "SELECT * FROM queued_listings WHERE user = '" . $buyer . "' AND list_num = '" . $list_num . "'";
      $rs = mysql_query($sql);
      $num = mysql_num_rows($rs);
    
      if ($num < 1){
        $SQL = "INSERT INTO queued_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$buyer."','".$list_num."','".$_SESSION['email']."','','agent','Agent Folder','".$time."')";
        $res = mysql_query($SQL)  or die(mysql_error());
      }
      
      $SQL2 = "UPDATE	users_folders SET last_update='".$time."' where name='Agent Folder' AND user='".$buyer."'";
      $res2 = mysql_query($SQL2)  or die(mysql_error());
      
    }
    else{
      $SQL1 = "SELECT name FROM `users_folders` WHERE (user = '".$buyer."') AND (agent = '".$agent_id."')";
      $result1 = mysql_query( $SQL1 ) or die("Couldn't execute query.".mysql_error());
      $row1 = mysql_fetch_array($result1,MYSQL_ASSOC);
      $folder = $row1['name'];
        
      $sql = "SELECT * FROM saved_listings WHERE user = '" . $buyer . "' AND list_num = '" . $list_num . "' AND folder = '".$folder."'";
      $rs = mysql_query($sql);
      $num = mysql_num_rows($rs);
    
      if ($num < 1){
        $SQL = "INSERT INTO saved_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$buyer."','".$list_num."','".$_SESSION['email']."','','buyer','".$folder."','".$time."')";
        $res = mysql_query($SQL)  or die(mysql_error());
        
        $SQL2 = "UPDATE	users_folders SET last_update='".$time."' WHERE (user ='". $buyer ."') AND (name='". $folder ."')";
        $res2 = mysql_query($SQL2)  or die(mysql_error());
        
        $SQL = "SELECT CONCAT(first_name, ' ' , last_name) as name FROM `users` WHERE (email = '".$buyer."')";
        $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
        $row = mysql_fetch_array($result,MYSQL_ASSOC);
        $buyer_name = $row['name'];
                
        array_push($buyernames, $buyer_name);
        
        $message = "Hello " . $buyer_name . ",";
        $message .= "<br><br>" . $agent_firstname . " " . $agent_lastname . " has saved a new listing to your folder: " . $folder;
        $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
        $message .= "HomePik.com is licensed by Nice Idea Media";
    
        $mail->addAddress($buyer);
        $mail->Subject = 'New Listing from HomePik';
        $mail->Body = $message;
        $mail->send();
      }
    }
  }
  
  echo implode(', ', $buyernames);
  return true;
}
else if ( isset($_GET['saveGuestListing'])){
	$list_num = $_GET['list_num'];
  $SQL = "INSERT INTO saved_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$_SESSION['guestID']."','".$list_num."','".$_SESSION['guestID']."','NULL','guest','Guest','".date('U')."')";
  $res = mysql_query($SQL)  or die(mysql_error());
  if($res){ print 1; }
  else{ print 0; }
}
else if ( isset($_GET['createGuestFolder'])){
	$SQL1 = "SELECT * FROM users_folders WHERE `user` = '".$_SESSION['guestID']."'";
	$res = mysql_query($SQL1)  or die(mysql_error());
	$num = mysql_num_rows($result);
	if($num > 0){ print 1; }
  else{
    $SQL1 = "INSERT INTO users_folders(`user`,`name`, `agent`, `last_update`) VALUES  ('".$_SESSION['guestID']."','Guest','NULL','".date('U')."')";
    $res = mysql_query($SQL1)  or die(mysql_error());
    if($res){ print 1; }
    else{ print 0; }      
	}    	
}
else if( isset($_POST['deleteListingFromFolders']) ){

  $list_num = $_POST['list_num'];
  $email = $_SESSION['email'];
  $folders = array();
  
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

	if(isset($_SESSION['buyer'])){    
    $SQL1 = "SELECT folder FROM saved_listings WHERE (user = '".$email."') AND (list_num = '".$list_num."')";
    $result1 = mysql_query($SQL1)  or die(mysql_error());
    while($row1 = mysql_fetch_array($result1,MYSQL_ASSOC)){
      array_push($folders, $row1['folder']);
    }
    
    $SQL2 = "DELETE FROM saved_listings WHERE (user = '".$email."') AND (list_num = '".$list_num."')";
    $result2 = mysql_query($SQL2)  or die(mysql_error());
  
    if ($result2 == true) {
      foreach($folders as $folder){
        $SQL3 = "SELECT agent FROM users_folders WHERE (user='".$email."') AND (name='".$folder."')";
        $result3 = mysql_query($SQL3)  or die(mysql_error());
        $row3 = mysql_fetch_array($result3,MYSQL_ASSOC);
        $agent_id = $row3['agent'];
        
        $SQL4 = "SELECT first_name, last_name FROM `users` WHERE (email = '".$email."')";
        $result4 = mysql_query( $SQL4 ) or die("Couldn't execute query.".mysql_error());
        $row4 = mysql_fetch_array($result4,MYSQL_ASSOC);
        $buyer_firstname = $row4['first_name'];
        $buyer_lastname = $row4['last_name'];
        
        $SQL5 = "SELECT firstname, lastname, e_mail FROM `Agent_Import` WHERE (id = '".$agent_id."')";
        $result5 = mysql_query( $SQL5 ) or die("Couldn't execute query.".mysql_error());
        $row5 = mysql_fetch_array($result5,MYSQL_ASSOC);
        $agent_firstname = $row5['firstname'];
        $agent_lastname = $row5['lastname'];
        $agent_email = $row5['e_mail'];
        
        $message = "Hello " . $agent_firstname . " " . $agent_lastname . ",";
        $message .= "<br><br>" . $buyer_firstname . " " . $buyer_lastname . " has removed a listing from thier folder: " . $folder;
        $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
        $message .= "HomePik.com is licensed by Nice Idea Media";
    
        $mail->addAddress($agent_email);
        $mail->Subject = 'HomePik Folder Change';
        $mail->Body = $message;
        $mail->send();
        
        $SQL6 = "UPDATE users_folders SET last_update='".date('U')."' WHERE (user='".$email."') AND (name='".$folder."')";
        $result6 = mysql_query($SQL6)  or die(mysql_error());
      }
    }	  
	}
	else if(isset($_SESSION['agent'])){
    $SQL1 = "SELECT folder FROM queued_listings WHERE (user = '".$email."') AND (list_num = '".$list_num."')";
    $result1 = mysql_query($SQL1)  or die(mysql_error());
    while($row1 = mysql_fetch_array($result1,MYSQL_ASSOC)){
      array_push($folders, $row1['folder']);
    }
    
    $SQL2 = "DELETE FROM queued_listings WHERE (user = '".$email."') AND (list_num = '".$list_num."')";
    $result2 = mysql_query($SQL2)  or die(mysql_error());
      
    if ($result2 == true) {
      foreach($folders as $folder){
        $SQL3 = "UPDATE users_folders SET last_update='".date('U')."' WHERE (user='".$email."') AND (name='".$folder."')";
        $result3 = mysql_query($SQL3)  or die(mysql_error());
      }
    }
  }
  else{
    $SQL1 = "SELECT folder FROM saved_listings WHERE (user = '".$_SESSION['guestID']."') AND (list_num = '".$list_num."')";
    $result1 = mysql_query($SQL1)  or die(mysql_error());
    while($row1 = mysql_fetch_array($result1,MYSQL_ASSOC)){
      array_push($folders, $row1['folder']);
    }
    
    $SQL2 = "DELETE FROM saved_listings WHERE (user = '".$_SESSION['guestID']."') AND (list_num = '".$list_num."')";
    $result2 = mysql_query($SQL2)  or die(mysql_error());
  
    if ($result2 == true) {
      foreach($folders as $folder){
        $SQL6 = "UPDATE users_folders SET last_update='".date('U')."' WHERE (user='".$_SESSION['guestID']."') AND (name='".$folder."')";
        $result6 = mysql_query($SQL6)  or die(mysql_error());
      }
    }
  }
  
  echo implode(', ', $folders);
  return true;
}
else if ( isset($_GET['checkIfListingAlreadySaved'])){
  $list_num = $_GET['list_num'];
  /*check if the name is already used*/
	if($_SESSION['agent']){
	  $SQL = "SELECT * FROM queued_listings WHERE (user = '".$_SESSION['email']."') AND (list_num = '".$list_num."')";
	  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
	  $num = mysql_num_rows($result);
	  if($num){ print 1; }
    else{ print 0; }
	}
	else if ($_SESSION['role']=='guest'){
	  $SQL = "SELECT * FROM saved_listings WHERE (user = '".$_SESSION['guestID']."') AND (list_num = '".$list_num."')";
	  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
	  $num = mysql_num_rows($result);
	  if($num > 0){ print $num; }
    else{ print 0; }	
	}
	else{
	  $SQL = "SELECT * FROM saved_listings WHERE (user = '".$_SESSION['email']."') AND (list_num = '".$list_num."')";
	  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
	  $num = mysql_num_rows($result);
	  if($num){ print 1; }
    else{ print 0; }
	}
}

?>