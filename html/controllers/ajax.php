<?php
session_start();
include_once("dbconfig.php");
include_once("emailconfig.php");
include_once("functions.php");
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());
  
// EMAIL A LISTING
if(isset($_GET['emailListing'])){
  $list_num = $_GET['list_num'];
  $to = $_GET['sendTo'];
  if(isset($_GET['from'])){ $from = $_GET['from']; }
  else{$from = $_SESSION['email'];}
  $guestName = $_GET['guestName'];
  $time = date('U');
  $comments = $_GET['comments'];
  $code = string_encrypt($list_num, $time);
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  
  $res = mysql_query("INSERT INTO emailed_listings(`from`,`list_num`, `to`, `code`, `time`) VALUES  ('".$from."','".$list_num."','".$to."','".$code."','".$time."')")  or die(mysql_error());

  if(isset($_SESSION['agent'])){
    $message = "Hello,";
    $message .= "<br><br>";
    $message .= $_SESSION['firstname'] . " " . $_SESSION['lastname'];
    $message .= " has sent you a listing from HomePik.com. You will find it below:";
    $message .= "<br><br>";
    $message .= 'Link: <a href="http://www.homepik.com/controllers/listing.php?code='. $code .'">http://www.homepik.com/controllers/listing.php?code='. $code .'</a>';
    $message .= "<br><br>";
    $message .= "Comments: ";
    $message .= $comments;    
    $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
    $message .= "HomePik.com is licensed by Nice Idea Media";
    
    $mail->addAddress($to);
    $mail->Subject = 'New Listing from HomePik';
    $mail->Body = $message;
    $mail->send();
  }
  else if(isset($_SESSION['buyer'])){
    $message = "Hello,";
    $message .= "<br><br>";
    $message .= $_SESSION['firstname'] . " " . $_SESSION['lastname'];
    $message .= " has sent you a listing from HomePik.com. You will find it below:";
    $message .= "<br><br>";
    $message .= 'Link: <a href="http://www.homepik.com/controllers/listing.php?code='. $code .'">http://www.homepik.com/controllers/listing.php?code='. $code .'</a>';
    $message .= "<br><br>";
    $message .= "Comments: ";
    $message .= $comments;
    $message .= "<br><br><center><a href='http://www.homepik.com/controllers/change-email-alert-settings.php?user=".$to."'>Change Email Alert Settings</a></center><br>";
    $message .= "<br><br><br><br>&copy; Nice Idea Media All Rights Reserved<br>";
    $message .= "HomePik.com is licensed by Nice Idea Media";
    
    $mail->addAddress($to);
    $mail->Subject = 'New Listing from HomePik';
    $mail->Body = $message;
    $mail->send();    
  }
  else{
    $message = "Hello,";
    $message .= "<br><br>";
    $message .= $guestName;
    $message .= " has sent you a listing from HomePik.com. You will find it below:";
    $message .= "<br><br>";
    $message .= 'Link: <a href="http://www.homepik.com/controllers/listing.php?code='. $code .'">http://www.homepik.com/controllers/listing.php?code='. $code .'</a>';
    $message .= "<br><br>";
    $message .= "Comments: ";
    $message .= $comments;
    $message .= "<br><br><br>";
    $message .= "You can email $guestName at: $from";
    $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
    $message .= "HomePik.com is licensed by Nice Idea Media";
    
    $mail->addAddress($to);
    $mail->Subject = 'New Listing from HomePik';
    $mail->Body = $message;
    $mail->send();
  }
}; // EMAIL END

// CONTACT A MANAGER
if(isset($_GET['contact'])){
  $list_num = $_GET['list_num'];
  $to = $_GET['manager'];
  $from = $_SESSION['email'];
  $guestName = $_GET['guestName'];
  $guestEmail = $_GET['guestEmail'];
  $time = date('U');
  $code = string_encrypt($from, $time);
  $comments = $_GET['comments'];
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  if(isset($_SESSION['buyer'])){
    $res = mysql_query("INSERT INTO contacted(`user`,`list_num`, `contacted`, `time`) VALUES  ('".$from."','".$list_num."','".$to."','".$time."')")  or die(mysql_error());

    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];

    $res = mysql_query( "SELECT first_name, last_name FROM `registered_agents` WHERE email = '".$to."'" ) or die(mysql_error());
    $row = mysql_fetch_array($res,MYSQL_ASSOC);
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];

    $SQL = "SELECT address, apt FROM `vow_data` WHERE list_numb = '".$list_num."'";
    $res = mysql_query($SQL) or die(mysql_error());
    $row = mysql_fetch_array($res,MYSQL_ASSOC);
    $address = $row['address'];
    $apt = $row['apt'];

    $message = "Hello " . $first_name . " " . $last_name . ",";
    $message .= "<br><br>";
    $message .= $firstname . ' ' . $lastname;
    $message .= " has contacted you regarding the following listing:<br><br>";
    $message .= "Listing number: " . $list_num . "<br>";
    $message .= "Address: " . $address  . "<br>";
    $message .= "Apartment Number: " .$apt . "<br>";
    $message .= "Comments: " . $comments;
    $message .= "<br><br>";
    $message .= "You can reach them at " . $from;
    $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
    $message .= "HomePik.com is licensed by Nice Idea Media";
    
    $mail->addAddress($to);
    $mail->Subject = 'HomePik Inquiry';
    $mail->Body = $message;    
    $mail->send();
  }
  else{
    $res = mysql_query("INSERT INTO contacted(`user`,`list_num`, `contacted`, `time`) VALUES  ('".$from."','".$list_num."','".$to."','".$time."')")  or die(mysql_error());

    $res = mysql_query( "SELECT first_name, last_name FROM `registered_agents` WHERE email = '".$to."'" ) or die(mysql_error());
    $row = mysql_fetch_array($res,MYSQL_ASSOC);
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];

    $res = mysql_query( "SELECT address, apt FROM `vow_data` WHERE list_numb = '".$list_num."'" ) or die(mysql_error());
    $row = mysql_fetch_array($res,MYSQL_ASSOC);
    $address = $row['address'];
    $apt = $row['apt'];

    $message = "Hello " . $first_name . " " . $last_name . ",";
    $message .= "<br><br>";
    $message .= $guestName;
    $message .= " has contacted you regarding the following listing:<br>";
    $message .= "Listing number: " . $list_num . "<br>";
    $message .= "Address: " . $address  . "<br>";
    $message .= "Apartment Number: " .$apt . "<br>";
    $message .= "Comments: " . $comments;
    $message .= "<br><br>";
    $message .= "You can reach them at " . $guestEmail;
    $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
    $message .= "HomePik.com is licensed by Nice Idea Media";
    
    $mail->addAddress($to);
    $mail->Subject = 'HomePik Inquiry';
    $mail->Body = $message;    
    $mail->send();
  }
}; // CONTACT END

//MAKE PRIMARY AND SECONDARY AGENTS
if(isset($_GET['makePrimary'])){
  $agentCode = $_GET['agentCode'];
  $user = $_GET['usersEmail'];

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  $res = mysql_query( "SELECT first_name, last_name, email FROM `registered_agents` WHERE agent_id = '".$agentCode."'" )  or die(mysql_error());
  $row = mysql_fetch_assoc($res);
  $agentEmail = $row['email'];
  $agentFirstName = $row['first_name'];
  $agentLastName = $row['last_name'];

  $res = mysql_query( "SELECT * FROM users WHERE email = '".$user."'" )  or die(mysql_error());
  $row = mysql_fetch_assoc($res);
  $buyerFirstName = $row['first_name'];
  $buyerLastName = $row['last_name'];
  $pAgent = $row['P_agent'];
  $pAgent2 = $row['P_agent2'];

  if($pAgent == ""){ //Add agent
    if($pAgent2 == "") { // No secondary - assign as primary

      $res = mysql_query( "UPDATE users SET P_agent = '".$agentCode."', P_agent_assign_time='".date('U')."' WHERE email = '".$user."'" ) or die(mysql_error());
      $result = mysql_query( "UPDATE users_folders SET agent = '".$agentCode."' WHERE (user = '".$user."')" ) or die("Couldn't execute query.".mysql_error());

      $message = "Hello ".$agentFirstName." ". $agentLastName .", <br><br>";
      $message .= "A new buyer has made you their primary agent. Their information is below:
        <br><br>
        First Name: $buyerFirstName  <br>
        Last Name: $buyerLastName <br>
        Email: $user";
      $message .= "<br><br><br>This email was sent to ".$agentEmail." because you are a listing agent at HomePik.com.<br><br>";
      $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
      $message .= "HomePik.com is licensed by Nice Idea Media";
      
      $mail->addAddress($agentEmail);
      $mail->Subject = 'HomePik.com New Buyer';
      $mail->Body = $message;
      $mail->send();

      $_SESSION['agent1'] = $agentCode;
      $result = 'assign';      
    } else if ($pAgent2 != "") { // Move secondary to primary
      $res = mysql_query( "UPDATE users SET P_agent = P_agent2, P_agent_assign_time=P_agent2_assign_time, P_agent2 = '', P_agent2_assign_time=0 WHERE email = '".$user."'" ) or die(mysql_error());
      
      $_SESSION['agent1'] = $_SESSION['agent2'];
      $_SESSION['agent2'] = '';
      $result = 'remove';
    }
  }
  else if ($pAgent != ""){ // Remove primary
    if($pAgent == $agentCode){ // Remove the first agent
      
      // Remove agent from the account
      if($pAgent2 != ""){ // If user has a second agent
        // Delete listings in the folder associated with the agent
        $result2 = mysql_query( "DELETE FROM `saved_listings` WHERE (user = '".$user."') AND (folder = 'Folder 1')" ) or die("Couldn't execute query.".mysql_error());
        
        // Delete formula associated with agent
        $result4 = mysql_query( "SELECT name, agent FROM `Users_Search` WHERE (email = '".$user."') AND (agent LIKE '%".$pAgent."%')" ) or die("Couldn't execute query.".mysql_error());
        while($row4 = mysql_fetch_array($result4,MYSQL_ASSOC)){
          $name = $row4['name'];
          $agents = $row4['agent'];
        
          if($agents == $pAgent){
            $result5 = mysql_query( "DELETE FROM `Users_Search` WHERE (email = '".$user."') AND (name = '".$name."') AND (agent LIKE '%".$pAgent."%')" ) or die("Couldn't execute query.".mysql_error());
          }
          else{
            $agents = str_replace($pAgent, '', $agents);
            $agents = str_replace(',', '', $agents);
            
            $result5 = mysql_query( "UPDATE `Users_Search` SET agent='".$agents."' WHERE (email = '".$user."') AND (name = '".$name."') AND (agent LIKE '%".$pAgent."%')" ) or die("Couldn't execute query.".mysql_error());
          }
        }
        
        // Delete folder associated with agent
        $result6 = mysql_query( "SELECT name, agent FROM `users_folders` WHERE (user = '".$user."') AND (agent LIKE '%".$pAgent."%')" ) or die("Couldn't execute query.".mysql_error());
        while($row6 = mysql_fetch_array($result6,MYSQL_ASSOC)){
          $name = $row6['name'];
          $agents = $row6['agent'];
        
          if($agents == $pAgent){
            $result7 = mysql_query( "DELETE FROM `users_folders` WHERE (user = '".$user."') AND (name = '".$name."') AND (agent LIKE '%".$pAgent."%')" ) or die("Couldn't execute query.".mysql_error()); 
          }
          else{
            $agents = str_replace($pAgent, '', $agents);
            $agents = str_replace(',', '', $agents);
            
            $result7 = mysql_query( "UPDATE `users_folders` SET agent='".$agents."' WHERE (user = '".$user."') AND (name = '".$name."') AND (agent LIKE '%".$pAgent."%')" ) or die("Couldn't execute query.".mysql_error());
          }
        }
      
        // Re-assign second agent as first if exists
        $res = mysql_query( "UPDATE `users` SET P_agent = P_agent2, P_agent_assign_time=P_agent2_assign_time, P_agent2 = '', P_agent2_assign_time=0 WHERE email = '".$user."'" ) or die(mysql_error());
        
        // Re-assign folder associated with second agent
        $result2 = mysql_query( "UPDATE `saved_listings` SET folder='Folder 1' WHERE (user = '".$user."') AND (folder = 'Folder 2')" ) or die("Couldn't execute query.".mysql_error());
        
        // Re-assign folder associated with second agent
        $result3 = mysql_query( "UPDATE `users_folders` SET name='Folder 1' WHERE (user = '".$user."') AND (agent = '".$pAgent2."') AND (name = 'Folder 2')" ) or die("Couldn't execute query.".mysql_error());
        
        // Re-assign formula associated with second agent
        $result4 = mysql_query( "UPDATE `Users_Search` SET name='Folder 1' WHERE (email = '".$user."') AND (agent = '".$pAgent2."')" ) or die("Couldn't execute query.".mysql_error());
        
        $_SESSION['agent1'] = $_SESSION['agent2'];
        $_SESSION['agent2'] = '';
      }
      else{ // If user only has the one agent
        $res = mysql_query( "UPDATE `users` SET P_agent = '', P_agent_assign_time=0 WHERE email = '".$user."' " ) or die(mysql_error());
        
        // Remove agent from folder associated with agent
        $result2 = mysql_query( "UPDATE `saved_listings` SET agent='' WHERE (user = '".$user."') AND (folder = 'Folder 1')" ) or die("Couldn't execute query.".mysql_error());
        
        // Remove agent from folder associated with agent
        $result3 = mysql_query( "UPDATE `users_folders` SET agent='' WHERE (user = '".$user."') AND (agent = '".$pAgent."') AND (name = 'Folder 1')" ) or die("Couldn't execute query.".mysql_error());
        
        // Remove agent from formula associated with agent
        $result4 = mysql_query( "UPDATE `Users_Search` SET agent='' WHERE (email = '".$user."') AND (agent = '".$pAgent."')" ) or die("Couldn't execute query.".mysql_error());
        
        $_SESSION['agent1'] = '';
      }
    }
    else if ($pAgent2 == $agentCode) { // Remove the second agent
      
      // Delete all listings in the folder associated with the second agent
      $result3 = mysql_query( "DELETE FROM `saved_listings` WHERE (user = '".$user."') AND (folder = 'Folder 2')" ) or die("Couldn't execute query.".mysql_error());
      
      // Delete the formual associated with the folder and second agent
      $result4 = mysql_query( "SELECT name, agent FROM `Users_Search` WHERE (email = '".$user."') AND (agent LIKE '%".$pAgent2."%')" ) or die("Couldn't execute query.".mysql_error());
      while($row4 = mysql_fetch_array($result4,MYSQL_ASSOC)){
        $name = $row4['name'];
        $agents = $row4['agent'];
      
        if($agents == $pAgent2){
          $result5 = mysql_query( "DELETE FROM `Users_Search` WHERE (email = '".$user."') AND (name = '".$name."') AND (agent LIKE '%".$pAgent2."%')" ) or die("Couldn't execute query.".mysql_error());
        }
        else{
          $agents = str_replace($pAgent2, '', $agents);
          $agents = str_replace(',', '', $agents);
          
          $result5 = mysql_query( "UPDATE `Users_Search` SET agent='".$agents."' WHERE (email = '".$user."') AND (name = '".$name."') AND (agent LIKE '%".$pAgent2."%')" ) or die("Couldn't execute query.".mysql_error());
        }
      }
      
      // Delete the folder associated with the second agent
      $result6 = mysql_query( "SELECT name, agent FROM `users_folders` WHERE (user = '".$user."') AND (agent LIKE '%".$pAgent2."%')" ) or die("Couldn't execute query.".mysql_error());
      while($row6 = mysql_fetch_array($result6,MYSQL_ASSOC)){
        $name = $row6['name'];
        $agents = $row6['agent'];
      
        if($agents == $pAgent2){
          $result7 = mysql_query( "DELETE FROM `users_folders` WHERE (user = '".$user."') AND (name = '".$name."') AND (agent LIKE '%".$pAgent2."%')" ) or die("Couldn't execute query.".mysql_error()); 
        }
        else{
          $agents = str_replace($pAgent2, '', $agents);
          $agents = str_replace(',', '', $agents);
          
          $result7 = mysql_query( "UPDATE `users_folders` SET agent='".$agents."' WHERE (user = '".$user."') AND (name = '".$name."') AND (agent LIKE '%".$pAgent2."%')" ) or die("Couldn't execute query.".mysql_error());
        }
      }
      
      // Delete the agent from the buyer's account
      $res = mysql_query( "UPDATE users SET P_agent2 = '', P_agent2_assign_time=0 WHERE email = '".$user."'" ) or die(mysql_error());
      
      $_SESSION['agent2'] = '';
      $result = 'remove';
    }

    $message = "Hello ". $agentFirstName . " " . $agentLastName . ", <br><br>";
    $message .= $buyerFirstName . " " . $buyerLastName . " has removed you as their agent.";
    $message .= "<br><br><br>This email was sent to ".$agentEmail." because you are a listing agent at HomePik.com.<br><br>";
    $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
    $message .= "HomePik.com is licensed by Nice Idea Media";
    
    $mail->addAddress($agentEmail);
    $mail->Subject = 'HomePik.com Buyer';
    $mail->Body = $message;    
    $mail->send();
    
    $result = "remove";
  }
  echo $result;
}; //MAKE PRIMARY AND SECONDARY AGENT END
//ADD A COMMENT
if(isset($_GET['addComment'])){
  $listing = $_GET['listing'];
  $user = $_GET['user'];
  $comments = $_GET['comments'];

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  if(strpos($user, "@bellmarc.com") !== false){ $SQL = "UPDATE queued_listings SET comments = '".$comments."' WHERE list_num = '".$listing."' AND user = '".$user."'"; }
  else{ $SQL = "UPDATE saved_listings SET comments = '".$comments."' WHERE list_num = '".$listing."' AND user = '".$user."'"; }
  $res = mysql_query($SQL) or die(mysql_error());

  if(isset($_GET['folder'])){ $res = mysql_query( "UPDATE users_folders SET last_update='".date('U')."' WHERE name='".$_GET['folder']."' AND user = '".$user."'" )  or die(mysql_error()); }
}; // ADD A COMMENT END

// EMAIL FOLDER
if(isset($_GET['emailFolder'])){
  $sender = $_GET['sender'];
  $agentSentBuyerFolder = $_GET['agentSentBuyerFolder'];
  $guestName = $_GET['guestName'];
  $recipient = $_GET['recipient'];
  $folder = $_GET['folder'];
  $comment = $_GET['comment'];	
  $sender_firstname = $_SESSION['firstname'];
  $sender_lastname = $_SESSION['lastname'];

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  
  if(strpos($recipient, "@bellmarc.com") !== false){
    $result = mysql_query( "SELECT first_name, last_name FROM `registered_agents` WHERE (email = '".$recipient."')" ) or die("Couldn't execute query.".mysql_error());
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $recipient_firstname = $row['first_name'];
    $recipient_lastname = $row['last_name'];
  }
  else{
    $result = mysql_query( "SELECT first_name, last_name FROM `users` WHERE (email = '".$recipient."')" ) or die("Couldn't execute query.".mysql_error());
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $recipient_firstname = $row['first_name'];
    $recipient_lastname = $row['last_name']; 
  }
  
  if($recipient_firstname != ""){ $message = "Hello " . $recipient_firstname . " " . $recipient_lastname . ","; }
  else{ $message = "Hello " . $recipient . ","; }
  
  if($agentSentBuyerFolder == "true"){ $message .= "<br><br>" . $sender_firstname . " " . $sender_lastname . " has sent you their buyer's folder, <b>$folder</b>, from HomePik.com"; }
  else if(isset($_SESSION['buyer']) || isset($_SESSION['agent'])){ $message .= "<br><br>" . $sender_firstname . " " . $sender_lastname . " has sent you their folder, <b>$folder</b>, from HomePik.com"; }
  else{ $message .= "<br><br>" . $guestName . " has sent you their folder, <b>$folder</b>, from HomePik.com"; }
  
  if(isset($comment) && $comment != ""){ $message .= " with the following message: " . $comment; }
  
  if(isset($_SESSION['buyer']) || isset($_SESSION['agent'])){ $message .= ".<br><br> Click <a href='www.homepik.com/controllers/emailed-folder.php?user=".$sender."&folder=".$folder."'>here</a> to view their folder. If the link doesn't work enter this into the URL: <a style='text-decoration:none !important; text-decoration:none;'>www.homepik.com/controllers/emailed-folder.php?user=".$sender."&folder=".$folder."</a>"; }
  else{ $message .= ".<br><br> Click <a href='www.homepik.com/controllers/emailed-folder.php?user=".$sender."&folder=".$folder."&name=".$guestName."'>here</a> to view their folder. If the link doesn't work enter this into the URL: <a style='text-decoration:none !important; text-decoration:none;'>www.homepik.com/controllers/emailed-folder.php?user=".$sender."&folder=".$folder."&name=".$guestName."</a>"; }
  
  $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
  $message .= "HomePik.com is licensed by Nice Idea Media";

  $mail->addAddress($recipient);
  $mail->Subject = 'Saved HomePik Folder';
  $mail->Body = $message;
  $mail->send();
  
}// END EMAIL FOLDER
//SAVE A LISTING GUEST
if(isset($_GET['saveGuestListing'])){
  $list_num = $_GET['list_num'];
  $guestID = $_SESSION['guestID'];
  $from = $_SESSION['email'];
  $role = 'guest';
  $time = date('U');
  
  $result = 0;
  $rs = mysql_query( "SELECT * FROM saved_listings WHERE user = '" . $guestID . "' AND list_num = '" . $list_num . "' AND folder = 'Guest Folder'" );
  $num = mysql_num_rows($rs);
  
  if ($num < 1){
    $res = mysql_query( "INSERT INTO saved_listings(`user`,`list_num`,`saved_by`, `role`, `folder`, `time`) VALUES  ('".$_SESSION['guestID']."','".$list_num."','".$from."','".$role."','Guest Folder','".$time."')" )  or die(mysql_error());
    $result = "Your listing has been saved!";
  }
  else{ $result = "You have already saved this listing!"; }
  print $result;
  return $result;
};
// SAVE A LISTING BUYER
if(isset($_GET['save'])){
  $list_num = $_GET['list_num'];
  $comments = $_GET['comments'];
  $from = $_SESSION['email'];
  $time = date('U');

  if(isset($_GET['buyer'])){ $user = $_GET['buyer']; }
  else { $user = $_SESSION['email']; }
  
  if(isset($_SESSION['agent'])){ $role = 'agent'; }
  elseif(isset($_SESSION['buyer'])){ $role = 'user'; }
  else{ $role = 'guest'; }  

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  if(isset($_SESSION['agent'])){
    $folders = $_GET['folders'];
    
    if(strpos($user, "@bellmarc.com") !== false){
      foreach($folders as $name){
        $rs = mysql_query( "SELECT * FROM queued_listings WHERE user = '" . $user . "' AND list_num = '" . $list_num . "' AND folder = '".$name."'" );
        $num = mysql_num_rows($rs);
      
        if ($num < 1){ $res = mysql_query( "INSERT INTO queued_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$user."','".$list_num."','".$from."','".$comments."','".$role."','".$name."','".$time."')" ) or die(mysql_error()); }
        
        $res2 = mysql_query( "UPDATE	users_folders SET last_update='".$time."' where name='". $name ."' AND user = '".$user."'" ) or die(mysql_error());
      }
    }
    else{
      foreach($folders as $name){
        $rs = mysql_query( "SELECT * FROM saved_listings WHERE user = '" . $user . "' AND list_num = '" . $list_num . "' AND folder = '".$name."'" );
        $num = mysql_num_rows($rs);
      
        if ($num < 1){
          $res = mysql_query( "INSERT INTO saved_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$user."','".$list_num."','".$from."','".$comments."','".$role."','".$name."','".$time."')" ) or die(mysql_error());
          
          $agent_firstname = $_SESSION['firstname'];
          $agent_lastname = $_SESSION['lastname'];
          
          $result = mysql_query( "SELECT first_name, last_name, notifications FROM `users` WHERE (email = '".$user."')" ) or die("Couldn't execute query.".mysql_error());
          $row = mysql_fetch_array($result,MYSQL_ASSOC);
          $buyer_firstname = $row['first_name'];
          $buyer_lastname = $row['last_name'];
          $notifications = $row['notifications'];
          
          if($notifications == 'all' || $notifications == 'folder'){
            $message = "Hello " . $buyer_firstname . " " . $buyer_lastname . ",";
            $message .= "<br><br>" . $agent_firstname . " " . $agent_lastname . " has saved a new listing to your folder: " . $folder;
            $message .= "<br><br>Listing Link: http://homepik.com/controllers/single-listing.php?". $list_num;
            $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
            $message .= "HomePik.com is licensed by Nice Idea Media";
            $message .= "<br><br><center><a href='http://www.homepik.com/controllers/change-email-alert-settings.php?user=".$user."'>Change Email Alert Settings</a></center><br>";
            $mail->addAddress($user);
            $mail->Subject = 'New Listing from HomePik';
            $mail->Body = $message;
            $mail->send();
          }
        }
        
        $res2 = mysql_query( "UPDATE	users_folders SET last_update='".$time."' where name='". $name ."' AND user = '".$user."'" ) or die(mysql_error());
      }
    }
  }
  else{
    $folders = $_GET['folders'];
    
    foreach($folders as $name){
      $rs = mysql_query( "SELECT * FROM saved_listings WHERE user = '" . $user . "' AND list_num = '" . $list_num . "' AND folder = '".$name."'" );
      $num = mysql_num_rows($rs);
    
      if ($num < 1){
        $res = mysql_query( "INSERT INTO saved_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$user."','".$list_num."','".$from."','".$comments."','".$role."','".$name."','".$time."')" ) or die(mysql_error());
        
        $result = mysql_query( "SELECT agent FROM `users_folders` WHERE (user = '".$user."') AND (name = '" . $name . "')" ) or die("Couldn't execute query." . mysql_error());
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        $folderAgent = $row['agent'];
        
        if($folderAgent != ""){
          $result3 = mysql_query( "SELECT first_name, last_name, email FROM `registered_agents` WHERE (agent_id = '".$folderAgent."')" ) or die("Couldn't execute query.".mysql_error());
          $row3 = mysql_fetch_array($result3,MYSQL_ASSOC);
          $agent_firstname = $row3['first_name'];
          $agent_lastname = $row3['last_name'];
          $agent_email = $row3['email'];
          
          $res5 = mysql_query( "SELECT first_name, last_name FROM `users` WHERE (email = '".$user."')" ) or die("Couldn't execute query." . mysql_error());
          $row5 = mysql_fetch_array($res5, MYSQL_ASSOC);
          $buyer_firstname = $row5['first_name'];
          $buyer_lastname = $row5['last_name'];
          
          $message = "Hello " . $agent_firstname . " " . $agent_lastname . ",";
          $message .= "<br><br>" . $buyer_firstname . " " . $buyer_lastname . " has saved a new listing to their folder: " . $name;
          $message .= "<br><br>Listing Link: http://homepik.com/controllers/single-listing.php?". $list_num;
          $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
          $message .= "HomePik.com is licensed by Nice Idea Media";
      
          $mail->addAddress($agent_email);
          $mail->Subject = 'HomePik Buyer Folder Update';
          $mail->Body = $message;
          $mail->send();
        }
      }
      
      $res2 = mysql_query( "UPDATE	users_folders SET last_update='".$time."' where name='". $name ."' AND user = '".$user."'" ) or die(mysql_error());
    }
  }
  
  print "Success";
}; // SAVE A LISTING END
// SAVE A LISTING AGENT
if(isset($_GET['agentSave'])){
  $list_num = $_GET['list_num'];
  $comments = $_GET['comments'];
  $buyers = $_GET['buyers'];  
  $agent_id = $_GET['agent_id'];
  $from = $_SESSION['email'];
  $time = date('U');

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  if(isset($_SESSION['agent'])){
    $agent_firstname = $_SESSION['firstname'];
    $agent_lastname = $_SESSION['lastname'];
     
    foreach($buyers as $buyer){	  
      if(strpos($buyer, "@bellmarc.com") !== false){
        $rs = mysql_query( "SELECT * FROM queued_listings WHERE user = '" . $buyer . "' AND list_num = '" . $list_num . "'" );
        $num = mysql_num_rows($rs);
      
        if ($num < 1){ $res = mysql_query( "INSERT INTO queued_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$buyer."','".$list_num."','".$from."','".$comments."','agent','','".$time."')" ) or die(mysql_error()); }
      }
      else{
        $result1 = mysql_query( "SELECT name FROM `users_folders` WHERE (user = '".$buyer."') AND (agent = '".$agent_id."')" ) or die("Couldn't execute query.".mysql_error());
        $row1 = mysql_fetch_array($result1,MYSQL_ASSOC);
        $folder = $row1['name'];
          
        $rs = mysql_query( "SELECT * FROM saved_listings WHERE user = '" . $buyer . "' AND list_num = '" . $list_num . "' AND folder = '".$folder."'" );
        $num = mysql_num_rows($rs);
      
        if ($num < 1){
          $res = mysql_query( "INSERT INTO saved_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$buyer."','".$list_num."','".$from."','".$comments."','agent','".$folder."','".$time."')" ) or die(mysql_error());
          $res2 = mysql_query( "UPDATE	users_folders SET last_update='".$time."' WHERE (user ='". $buyer ."') AND (name='". $folder ."')" ) or die(mysql_error());
          
          $result = mysql_query( "SELECT first_name, last_name, notifications FROM `users` WHERE (email = '".$buyer."')" ) or die("Couldn't execute query.".mysql_error());
          $row = mysql_fetch_array($result,MYSQL_ASSOC);
          $buyer_firstname = $row['first_name'];
          $buyer_lastname = $row['last_name'];
          $notifications = $row['notifications'];
          
          if($notifications == 'all' || $notifications == 'folder'){
            $message = "Hello " . $buyer_firstname . " " . $buyer_lastname . ",";
            $message .= "<br><br>" . $agent_firstname . " " . $agent_lastname . " has saved a new listing to your folder: " . $folder;
            $message .= "<br><br>Listing Link: http://homepik.com/controllers/single-listing.php?". $list_num;
            $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
            $message .= "HomePik.com is licensed by Nice Idea Media";
            $message .= "<br><br><center><a href='http://www.homepik.com/controllers/change-email-alert-settings.php?user=".$buyer."'>Change Email Alert Settings</a></center><br>";
            
            $mail->addAddress($buyer);
            $mail->Subject = 'New Listing from HomePik';
            $mail->Body = $message;
            $mail->send();
          }
        }
      }
    }
  }
  print "Success";
}; // SAVE A LISTING END
// SAVE THE PREVIOUS PAGE
if(isset($_GET['setPreviousPage'])){
  $_SESSION['previousPage'] = $_GET['page'];
}; // PREVIOUS PAGE END

// GET THE PREVIOUS PAGE
if(isset($_GET['getPreviousPage'])){
  echo (isset($_SESSION['previousPage']) ? $_SESSION['previousPage'] : "");
}; // PREVIOUS PAGE END

// FOLDER BUYER SAVED TO LAST
if(isset($_GET['getLastFolder'])){
  if(isset($_SESSION['lastFolderUsed'])){ echo json_encode($_SESSION['lastFolderUsed']); }
  else{ echo json_encode(""); }
}; // FOLDER BUYER/AGENT SAVED TO LASTEND

// REMOVE THE BUYER AGENT SAVED TO LAST
if(isset($_GET['removeLastBuyer'])){
  unset($_SESSION['lastBuyerUsed']);
}; // REMOVE BUYER AGENT SAVED TO LAST END

// REMOVE THE BUYER AGENT SAVED TO LAST AND FOLDER BUYER SAVED TO LAST
if(isset($_GET['removeLastBuyerAndFolder'])){
  unset($_SESSION['lastBuyerUsed']);
  unset($_SESSION['lastFolderUsed']);
}; // REMOVE THE BUYER AGENT SAVED TO LAST AND FOLDER BUYER SAVED TO LAST END

// BUYER AGENT SAVED TO LAST
if(isset($_GET['getLastBuyer'])){
  echo (isset($_SESSION['lastBuyerUsed']) ? json_encode($_SESSION['lastBuyerUsed']) : json_encode("") );
  return true;
}; // FOLDER BUYER/AGENT SAVED TO LAST END

// STORE SEARCH PAGE
if(isset($_GET["storeSearchPage"])){
  $_SESSION['searchPageLastOn'] = $_GET['page'];
} // STORE SEARCH PAGE END

// GET THE STORED SEARCH PAGE
if(isset($_POST["getStoredSearchPage"])){
  if(isset($_SESSION['searchPageLastOn'])){ echo json_encode($_SESSION['searchPageLastOn']); }
  else{ echo json_encode(""); }
} // GET THE STORED SEARCH PAGE END

// GET THE STORED OPEN LISTINGS
if(isset($_POST["getStoredOpenListing"])){
  if(isset($_SESSION['openListings'])){ echo json_encode($_SESSION['openListings']); }
  else{ echo json_encode(""); }
} // GET THE STORED OPEN LISTINGS END

// STORE THE CURRENT LISTING OPEN
if(isset($_GET["storeOpenListing"])){
  $listing = $_GET['listing'];
  $address = $_GET['address'];
  $apt = $_GET['apt'];
  $saved = $_GET['saved'];
  $code = $_GET['code'];
  $listing = array("list_numb"=>$listing, "address"=>$address, "apt"=>$apt , "saved"=>$saved, "code"=>$code);
  if(!isset($_SESSION['openListings'])){ $openListings = array(); }
  else{ $openListings = $_SESSION['openListings']; }
  array_push($openListings, $listing);
  $_SESSION['openListings'] = $openListings;
} // STORE THE CURRENT LISTINGS OPEN END

// UPDATE STORED LISTING
if(isset($_GET["updateStoredListing"])){
  $list_num = $_GET['listing'];
  $save = $_GET['save'];
  $openListings = $_SESSION['openListings'];
  foreach($openListings as &$listing){
    if($listing['list_numb'] == $list_num){ $listing['saved'] = $save; }
  }
  $_SESSION['openListings'] = $openListings;
} // UPDATE STORED LISTING END

// REMOVE STORED LISTING
if(isset($_GET["removeStoredOpenListing"])){
  $list_numb = $_GET['listing'];
  $openListings = $_SESSION['openListings'];
  $index = 0;
  foreach($openListings as $listing){
    if($listing['list_numb'] == $list_numb){ break; }
    else{ $index = $index + 1; }
  }
  array_splice($openListings, $index, 1);
  $_SESSION['openListings'] = $openListings;
} // REMOVE STORED LISTING END

// CLEAR ONE LISTING FROM BUYER FOLDER
if(isset($_GET['clear_one_saved_from_folder'])){
  $user = $_GET['buyer'];
  $delete_id = $_GET['delete_id'];
  $folder = $_GET['folder'];

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  $res = mysql_query( "DELETE FROM saved_listings WHERE (user = '".$user."') AND (list_num = '".$delete_id."') AND  (folder = '".$folder."')" ) or die(mysql_error());

  if($res == true){ $res = mysql_query( "UPDATE users_folders SET last_update='".date('U')."' WHERE name='".$folder."' AND user = '".$user."'" ) or die(mysql_error()); }
  print $res;
}; // DELETE END

// CLEAR ONE LISTING FROM BUYER FOLDERS
if(isset($_GET['clear_one_saved_from_folders'])){
  $email = $_GET['buyer'];
  $role = $_GET['role'];
  $list_num = $_GET['delete_id'];
  $folders = array();
  
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  $result1 = mysql_query( "SELECT folder FROM saved_listings WHERE (user = '".$email."') AND (list_num = '".$list_num."')" ) or die(mysql_error());
  while($row1 = mysql_fetch_array($result1,MYSQL_ASSOC)){
    array_push($folders, $row1['folder']);
  }
  
  $result2 = mysql_query( "DELETE FROM saved_listings WHERE (user = '".$email."') AND (list_num = '".$list_num."')" ) or die(mysql_error());

  if ($result2 == true) {
    foreach($folders as $folder){
      if($role == "buyer"){
        $result3 = mysql_query( "SELECT agent FROM users_folders WHERE (user='".$email."') AND (name='".$folder."')" )  or die(mysql_error());
        $row3 = mysql_fetch_array($result3,MYSQL_ASSOC);
        $agent_id = $row3['agent'];
        
        $result4 = mysql_query( "SELECT first_name, last_name FROM `users` WHERE (email = '".$email."')" ) or die("Couldn't execute query.".mysql_error());
        $row4 = mysql_fetch_array($result4,MYSQL_ASSOC);
        $buyer_firstname = $row4['first_name'];
        $buyer_lastname = $row4['last_name'];
        
        $result5 = mysql_query( "SELECT first_name, last_name, email FROM `registered_agents` WHERE (agent_id = '".$agent_id."')" ) or die("Couldn't execute query.".mysql_error());
        $row5 = mysql_fetch_array($result5,MYSQL_ASSOC);
        $agent_firstname = $row5['first_name'];
        $agent_lastname = $row5['last_name'];
        $agent_email = $row5['email'];
        
        $message = "Hello " . $agent_firstname . " " . $agent_lastname . ",";
        $message .= "<br><br>" . $buyer_firstname . " " . $buyer_lastname . " has removed a listing from thier folder: " . $folder;
        $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
        $message .= "HomePik.com is licensed by Nice Idea Media";
    
        $mail->addAddress($agent_email);
        $mail->Subject = 'HomePik Folder Change';
        $mail->Body = $message;
        $mail->send();
      }
      
      $result6 = mysql_query( "UPDATE users_folders SET last_update='".date('U')."' WHERE (user='".$email."') AND (name='".$folder."')" ) or die(mysql_error());
    }
  }	  
}; // DELETE END

// CLEAR ONE LISTING FROM AGENT FOLDER
if(isset($_GET['clear_one_queued_from_folder'])){
  $user = $_SESSION['email'];
  $delete_id = $_GET['delete_id'];
  $folder = $_GET['folder'];
  
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  $res = mysql_query( "DELETE FROM queued_listings WHERE (user = '".$user."') AND (list_num = '".$delete_id."') AND  (folder = '".$folder."')" ) or die(mysql_error());
    
  if ($res == true){ $res = mysql_query( "UPDATE users_folders SET last_update='".date('U')."' WHERE name='".$folder."' AND user = '".$user."'" ) or die(mysql_error()); }
}; // DELETE END

// CLEAR ONE LISTING FROM AGENT FOLDERS
if(isset($_GET['clear_one_queued_from_folders'])){
  $email = $_GET['agent'];
  $list_num = $_GET['delete_id'];
  $folders = array();
  
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

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
}; // DELETE END

// ADD AGENT AS PRIMARY
if(isset($_GET['AddPrimary'])){
  $email = $_GET['email'];
  $fn = $_SESSION['firstname'];
  $ln = $_SESSION['lastname'];
  $id = $_SESSION['agent_id'];
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  $result2 = mysql_query( "SELECT first_name, last_name, notifications FROM `users` WHERE (email = '".$email."')" ) or die("Couldn't execute query.".mysql_error());
  $row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
  $firstname = $row2['first_name'];
  $lastname = $row2['last_name'];
  $notifications = $row['notifications'];

  $result3 = mysql_query( "UPDATE `users` SET `P_agent` = '".$id."', `P_agent_assign_time`='".date('U')."' WHERE (email = '".$email."')" ) or die("Couldn't execute query.".mysql_error());
  
  if($notifications == "all" || $notifications == "messages"){
    $message = "Hello " . $firstname . " " . $lastname . ",<br><br>";
    $message .= $fn . " " . $ln . " is now your primary agent.";
    $message .= "<br><br><center><a href='http://www.homepik.com/controllers/change-email-alert-settings.php?user=".$email."'>Change Email Alert Settings</a></center><br>";
    $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
    $message .= "HomePik.com is licensed by Nice Idea Media";  
    
    $mail->addAddress($email);
    $mail->Subject = 'HomePik.com Primary Agent';
    $mail->Body = $message;  
    $mail->send();
  }
} // ADD AGENT AS PRIMARY END

// ADD AGENT AS PRIMARY
if(isset($_GET['AddPrimary2'])){
  $email = $_GET['email'];
  $fn = $_SESSION['firstname'];
  $ln = $_SESSION['lastname'];
  $id = $_SESSION['agent_id'];
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  $result2 = mysql_query( "SELECT first_name, last_name, notifications FROM `users` WHERE (email = '".$email."')" ) or die("Couldn't execute query.".mysql_error());
  $row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
  $firstname = $row2['first_name'];
  $lastname = $row2['last_name'];
  $notifications = $row['notifications'];

  $result3 = mysql_query( "UPDATE `users` SET `P_agent2` = '".$id."', `P_agent2_assign_time`='".date('U')."' WHERE (email = '".$email."')" ) or die("Couldn't execute query.".mysql_error());

  if($notifications == 'all' || $notifications == "messages"){
    $message = "Hello " . $firstname . " " . $lastname . ",<br><br>";
    $message .= $fn . " " . $ln . " is now your second primary agent.";
    $message .= "<br><br><center><a href='http://www.homepik.com/controllers/change-email-alert-settings.php?user=".$email."'>Change Email Alert Settings</a></center><br>";
    $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
    $message .= "HomePik.com is licensed by Nice Idea Media";  
  
    $mail->addAddress($email);
    $mail->Subject = 'HomePik.com Primary Agent';
    $mail->Body = $message;  
    $mail->send();
  }
} // ADD AGENT AS PRIMARY END

// ADD BUYER
if(isset($_GET['addBuyer'])){
  $registerTime = date('U');

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  $result = mysql_query( "SELECT * FROM `users` WHERE (email = '".$_GET['email']."')" ) or die("Couldn't execute query.".mysql_error());
  $num = mysql_num_rows($result);

  if($num == 0){
    $fn = $_SESSION['firstname'];
    $ln = $_SESSION['lastname'];
    $id = $_SESSION['agent_id'];

    $to = $_GET['firstname'] . " " . $_GET['lastname'] . " <" . $_GET['email'] . ">";
    $email = $_GET['email'];
    $res =  mysql_query("INSERT INTO users (email, first_name, last_name, phone, rtime, pass_set, assigned, active, P_agent, P_agent_assign_time, notifications) VALUES('".$_GET['email']."','".$_GET['firstname']."','".$_GET['lastname']."','".$_GET['phone']."','".$registerTime."','".$registerTime."','".$_SESSION['email']."','2','".$id."', '".$registerTime."', 'all') ON DUPLICATE KEY UPDATE assigned=VALUES(assigned), rtime=VALUES(rtime)");

    $message = "Hello " . $_GET['firstname'] . " " . $_GET['lastname'] . ", <br><br>";
    $message .= "$fn $ln from HomePik has invited you to join HomePik.com, which is a unique real-estate search engine that has a patented technology to allow users to compare listings. As a result, users are guided in finding the best listing on the market, based on their preferences.";
    $message .= " For detailed information go <a href='http://homepik.com/controllers/about.php'>here</a>.";
    $message .= "<br><br>To finish registering your account click <a href='http://homepik.com/controllers/complete-register.php?f=".$_GET['firstname']."&l=".$_GET['lastname']."&e=".$_GET['email']."&p=".$_GET['phone']."'>here</a>.<br><br>";
    $message .= "If the link to register doesn't work enter this URL into your web browser: http://homepik.com/controllers/complete-register.php?f=".$_GET['firstname']."&l=".$_GET['lastname']."&e=".$_GET['email']."";
    $message .= "<br><br><center><a href='http://www.homepik.com/controllers/change-email-alert-settings.php?user=".$email."'>Change Email Alert Settings</a></center><br>";
    $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
    $message .= "HomePik.com is licensed by Nice Idea Media";

    $mail->addAddress($email);
    $mail->Subject = 'HomePik.com Buyer Registration';
    $mail->Body = $message;    
    $mail->send();
    
    $message = "Below is a copy of the New York State Disclosure Form for Buyer and Seller that you will need to agree to when you complete your registration with HomePik.<br><br>";
    $message .= "New York State<br>
      DEPARTMENT OF STATE<br>
      Division of Licensing Services<br>
      P.O. Box 22001 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Customer Service: (518) 474-4429<br>
      Albany, NY 12201-2001  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; www.dos.state.ny.us<br><br>
      <b>New York State Disclosure Form for Buyer and Seller</b><br><br>
            <b>This is not a contract</b><br>
            <i>New York State law requires real estate licensees who are acting as agents of buyers or sellers of
      property to advise the potential buyers or sellers with whom they work of the nature of their agency
      relationship and the rights and obligations it creates. This disclosure will help you to make informed
      choices about your relationship with the real estate broker and its sales agents.<br>
      Throughout the transaction you may receive more than one disclosure form. The law may require each agent
      assisting in the transaction to present you with this disclosure form. A real estate agent is a person
      qualified to advise about real estate.<br>
      If you need legal, tax or other advice, consult with a professional in that field.</i><br><br>

      <b>Disclosure Regarding Real Estate Agency Relationships</b><br><br>

      <b>Seller’s Agent</b><br>
      A seller’s agent is an agent who is engaged by a seller to represent the seller’s interests. The seller’s
      agent does this by securing a buyer for the seller’s home at a price and on terms acceptable to the seller.
      A seller’s agent has, without limitation, the following fiduciary duties to the seller: reasonable care,
      undivided loyalty, confidentiality, full disclosure, obedience and duty to account. A seller’s agent does
      not represent the interests of the buyer. The obligations of a seller’s agent are also subject to any specific
      provisions set forth in an agreement between the agent and the seller. In dealings with the buyer, a seller’s
      agent should (a) exercise reasonable skill and care in performance of the agent’s duties; (b) deal honestly,
      fairly and in good faith; and (c) disclose all facts known to the agent materially affecting the value or
      desirability of property, except as otherwise provided by law.<br><br>

      <b>Buyer’s Agent</b><br>
      A buyer’s agent is an agent who is engaged by a buyer to represent the buyer’s interests. The buyer’s agent
      does this by negotiating the purchase of a home at a price and on terms acceptable to the buyer. A buyer’s
      agent has, without limitation, the following fiduciary duties to the buyer: reasonable care, undivided loyalty,
      confidentiality, full disclosure, obedience and duty to account. A buyer’s agent does not represent the interest
      of the seller. The obligations of a buyer’s agent are also subject to any specific provisions set forth in an
      agreement between the agent and the buyer. In dealings with the seller, a buyer’s agent should (a) exercise
      reasonable skill and care in performance of the agent’s duties; (b) deal honestly, fairly and in good faith;
      and (c) disclose all facts known to the agent materially affecting the buyer’s ability and/or willingness to
      perform a contract to acquire seller’s property that are not inconsistent with the agent’s fiduciary duties
      to the buyer.<br><br>

      <b>Broker’s Agents</b><br>
      A broker’s agent is an agent that cooperates or is engaged by a listing agent or a buyer’s agent (but does not
      work for the same firm as the listing agent or buyer’s agent) to assist the listing agent or buyer’s agent in
      locating a property to sell or buy, respectively, for the listing agent’s seller or the buyer agent’s buyer.
      The broker’s agent does not have a direct relationship with the buyer or seller and the buyer or seller can not
      provide instructions or direction directly to the broker’s agent. The buyer and the seller therefore do not
      have vicarious liability for the acts of the broker’s agent. The listing agent or buyer’s agent do provide
      direction and instruction to the broker’s agent and therefore the listing agent or buyer’s agent will have
      liability for the acts of the broker’s agent.<br><br>

      <b>Dual Agent</b><br>
      A real estate broker may represent both the buyer and seller if both the buyer and seller give their informed
      consent in writing. In such a dual agency situation, the agent will not be able to provide the full range of
      fiduciary duties to the buyer and seller. The obligations of an agent are also subject to any specific provisions
      set forth in an agreement between the agent, and the buyer and seller. An agent acting as a dual agent must explain
      carefully to both the buyer and seller that the agent is acting for the other party as well. The agent should also
      explain the possible effects of dual representation, including that by consenting to the dual agency relationship
      the buyer and seller are giving up their right to undivided loyalty. A buyer or seller should carefully consider
      the possible consequences of a dual agency relationship before agreeing to such representation. A seller or buyer
      may provide advance informed consent to dual agency by indicating the same on this form.<br><br>

      <b>Dual Agent with Designated Sales Agents</b><br>
      If the buyer and seller provide their informed consent in writing, the principals and the real estate broker who
      represents both parties as a dual agent may designate a sales agent to represent the buyer and another sales agent
      to represent the seller to negotiate the purchase and sale of real estate. A sales agent works under the supervision
      of the real estate broker. With the informed consent of the buyer and the seller in writing, the designated sales
      agent for the buyer will function as the buyer’s agent representing the interests of and advocating on behalf of
      the buyer and the designated sales agent for the seller will function as the seller’s agent representing the interests
      of and advocating on behalf of the seller in the negotiations between the buyer and seller. A designated sales agent
      cannot provide the full range of fiduciary duties to the buyer or seller. The designated sales agent must explain that
      like the dual agent under whose supervision they function, they cannot provide undivided loyalty. A buyer or seller
      should carefully consider the possible consequences of a dual agency relationship with designated sales agents before
      agreeing to such representation. A seller or buyer may provide advance informed consent to dual agency with designated
      sales agents by indicating the same on this form.<br><br>

      This form was provided to me by Bellmarc Realty LLC & it's associated agents of Bellmarc Realty
      LLC, a licensed real estate broker acting in the interest of the:<br>
      (__) Seller as a (check relationship below<br>
      &nbsp;&nbsp;(__) Seller's agent<br>
      &nbsp;&nbsp;(__) Broker's agent<br>

      (X) Buyer as a (check relationship below)<br>
      &nbsp;&nbsp;(X) Buyer's agent<br>
      &nbsp;&nbsp;(__) Broker's agent<br>

      (X) Dual Agent<br>
      (__) Dual agent with designated sales agent<br><br>

      For advance informed consent to either dual agency or dual agency with designated sales agents complete section below:<br>
      &nbsp;&nbsp; (X) Advance informed consent dual agency<br>
      &nbsp;&nbsp; (__) Advance informed consent to dual agency with designatd sales agents<br><br>

      If dual agent with designated sales agents is indicated above: " .$_GET['firstname'] . " " . $_GET['lastname'] . " is appointed to represent
      the buyer: and (N/A) is appointed to represent the seller in this transaction. (I)(We) " .$_GET['firstname'] . " " . $_GET['lastname'] . "
      acknowledge receipt of a copy of this disclosure form.";
    $message .= "<br><br><br><br>You are recieving this email because you created an account with HomePik with this email address. If you did not make this request please ignore this email.<br>";
    $message .= "<br><br><center><a href='http://www.homepik.com/controllers/change-email-alert-settings.php?user=".$email."'>Change Email Alert Settings</a></center><br>";
    $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";

    $mail->addAddress($email);
    $mail->Subject = 'HomePik Disclosure Form Copy';
    $mail->Body = $message;
    $mail->send();
    
    mysql_query("INSERT INTO users_folders(`user`,`name`,`agent`,`last_update`) VALUES  ('".$email."','Folder 1','".$id."','".date('U')."')");
  }
};

// ADD AGENT
if(isset($_GET['addAgent'])){
  $registerTime = date('U');
  $email = $_GET['email'];

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  $result = mysql_query( "SELECT * FROM `registered_agents` WHERE (email = '".$email."')" ) or die("Couldn't execute query.".mysql_error());
  $num = mysql_num_rows($result);

  if($num == 0){
    $firstname = trim($_GET['firstname']);
    $lastname = trim($_GET['lastname']);
    $password = generateRandomString();
    $pass = string_encrypt($password, $registerTime);
    $res =  mysql_query("INSERT INTO registered_agents(first_name, last_name, title, email, phone, agent_id, password, active, admin, rtime, pass_set) VALUES('".$firstname."','".$lastname."','".$_GET['title']."','".$_GET['email']."','".$_GET['phone']."','".$_GET['agent_id']."','".$pass."','".$_GET['status']."','".$_GET['admin']."','".$registerTime."','".$registerTime."') ON DUPLICATE KEY UPDATE rtime=VALUES(rtime)");
    
    $message = "Hello " . $_GET['firstname'] . " " . $_GET['lastname'] . ", <br/><br/>";
    $message .= "Your agent account with HomePik as been created. Below you will find your login information: <br/>";
    $message .= "Email / Username: $email <br/>";
    $message .= "Password: $password";
    $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";

    $mail->addAddress($email);
    $mail->Subject = 'HomePik Agent Account Created';
    $mail->Body = $message;
    $mail->send();
    
    $result = mysql_query( "SELECT * FROM `users_folders` WHERE (user = '".$email."') AND (name = 'Agent Folder')" ) or die("Couldn't execute query.".mysql_error());
    $num = mysql_num_rows($result);
  
    if($num == 0){ mysql_query("INSERT INTO users_folders(`user`,`name`,`agent`,`last_update`) VALUES  ('".$email."','Agent Folder','','".date('U')."')"); }
  }
};

// UPDATE AGENT
if(isset($_GET['updateAgent'])){
  $oldEmail = $_GET['oldEmail'];

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  $result = mysql_query( "SELECT * FROM `registered_agents` WHERE (email = '".$oldEmail."')" ) or die("Couldn't execute query.".mysql_error());
  $num = mysql_num_rows($result);
  
  if($num > 0){
    $res = mysql_query("UPDATE `registered_agents` SET `first_name`='".$_GET['firstname']."',`last_name`='".$_GET['lastname']."',`title`='".$_GET['title']."',`email`='".$_GET['email']."',`phone`='".$_GET['phone']."',`bio`='".$_GET['bio']."',`active`='".$_GET['status']."',`admin`='".$_GET['admin']."' WHERE (email = '".$oldEmail."')");
    
    if($oldEmail != $_GET['email']){
      $res2 = mysql_query("UPDATE `messages` SET `agent`='".$_GET['email']."' WHERE (agent = '".$oldEmail."')");
      $res3 = mysql_query("UPDATE `messages` SET `sender`='".$_GET['email']."' WHERE (sender = '".$oldEmail."')");
      $res4 = mysql_query("UPDATE `queued_listings` SET `user`='".$_GET['email']."',`saved_by`='".$_GET['email']."' WHERE (user = '".$oldEmail."')");
      $res5 = mysql_query("UPDATE `saved_listings` SET `saved_by`='".$_GET['email']."' WHERE (saved_by = '".$oldEmail."')");
      $res6 = mysql_query("UPDATE `users_folders` SET `user`='".$_GET['email']."' WHERE (user = '".$oldEmail."')");
      $res7 = mysql_query("UPDATE `viewed_listings` SET `user`='".$_GET['email']."' WHERE (user = '".$oldEmail."')");
    }
  }
  
  if(isset($_GET['newPass']) && $_GET['newPass'] != ""){
    $res = mysql_query("SELECT rtime FROM `registered_agents` WHERE email = '" . $_GET['email'] . "'");
    $row = mysql_fetch_assoc($res);
    $registerTime = $row['rtime'];
    $numRow = mysql_num_rows($res);
    
    if($numRow > 0){
      $newPassword = string_encrypt($_GET['newPass'], $registerTime);
      $res2 = mysql_query("UPDATE `registered_agents` SET password = '" . $newPassword . "' WHERE email = '" . $_GET['email'] . "'");
      $row = mysql_fetch_assoc($res2);		
    }
  }
};

// SAVE MESSAGE
if(isset($_GET['saveMessage'])){
  $buyer = $_GET['buyer'];
  $agent = $_GET['agent'];
  $sender = $_GET['sender'];
  $message = $_GET['message'];  
  $message = str_replace("'", "\'", $message);

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  $res = mysql_query("INSERT INTO messages(`buyer`,`agent`,`sender`, `message`, `time`) VALUES  ('".$buyer."','".$agent."','".$sender."','".$message."','".date('U')."')")  or die(mysql_error());
}; // SAVE MESSAGE END

// SAVE THE EMAIL OF THE BUYER THE AGENT IS WORKING WITH
if(isset($_GET['saveBuyer'])){
  $_SESSION['buyerSave'] = $_GET['email'];
};
// ARCHIVE BUYER
if(isset($_GET['archiveBuyer'])){
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  $res =  mysql_query("UPDATE users SET archived ='1' WHERE (email = '".$_GET['buyer']."')")  or die(mysql_error());
}; // ARCHIVE BUYER END

// DELETE BUYER
if(isset($_GET['deleteBuyer'])){
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  
  $res = mysql_query("DELETE FROM saved_listings WHERE (user = '".$_GET['buyer']."')")  or die(mysql_error()); // Delete buyer's saved listings  
  $res = mysql_query("DELETE FROM messages WHERE (buyer = '".$_GET['buyer']."')")  or die(mysql_error()); // Delete buyer's messages
  $res = mysql_query("DELETE FROM Users_Search WHERE (email = '".$_GET['buyer']."')")  or die(mysql_error()); // Delete buyer's saved formulas
  $res = mysql_query("DELETE FROM users_folders WHERE (user = '".$_GET['buyer']."')")  or die(mysql_error()); // Delete buyer's folders
  $res =  mysql_query("DELETE FROM users WHERE (email = '".$_GET['buyer']."')")  or die(mysql_error()); // Delete buyer account
}; // DELETE BUYER END

// ACTIVATE BUYER
if(isset($_GET['activateBuyer'])){
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  $res =  mysql_query("UPDATE users SET archived ='0' WHERE (email = '".$_GET['buyer']."')")  or die(mysql_error());
}; // ACTIVATE BUYER END

// ARCHIVE AGENT
if(isset($_GET['archiveAgent'])){
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  $agents = $_GET['agents'];
  
  foreach ($agents as $agent) {
    $res =  mysql_query("UPDATE registered_agents SET active ='N' WHERE (email = '".$agent."')")  or die(mysql_error());
  }
}; // ARCHIVE AGENT END

// ACTIVATE AGENT
if(isset($_GET['activateAgent'])){
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  $agents = $_GET['agents'];
  
  foreach ($agents as $agent) {
    $res =  mysql_query("UPDATE registered_agents SET active ='Y' WHERE (email = '".$agent."')")  or die(mysql_error());
  }
}; // ACTIVATE AGENT END

// DELETE AGENT
if(isset($_GET['deleteAgent'])){
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  $agents = $_GET['agents'];
  
  foreach ($agents as $agent) {
    $res =  mysql_query("DELETE FROM `registered_agents` WHERE (email = '".$agent."')")  or die(mysql_error());
  }
}; // DELETE AGENT END

// REASSIGN BUYER'S AGENT
if(isset($_GET['reassignAgent'])){
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  $buyer_email = $_GET['buyer'];
  $new_agent_id = $_GET['id'];
  
  // Get buyer's information
  $result = mysql_query( "SELECT CONCAT(first_name, ' ' ,last_name) as name FROM `users` WHERE (email = '".$buyer_email."')" ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);  
  $buyer_name = $row['name'];
  
  if($_GET['agent'] == "agent1"){
    $result2 = mysql_query("SELECT P_agent FROM `users` WHERE email='".$_GET['buyer']."'")  or die(mysql_error());
    $row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
    
    $res3 = mysql_query("UPDATE `users_folders` SET `agent`='".$new_agent_id."' WHERE (user='".$buyer_email."') AND (agent='".$row2['P_agent']."')") or die(mysql_error());
    $res4 = mysql_query("UPDATE `Users_Search` SET `agent`='".$new_agent_id."' WHERE (email='".$buyer_email."') AND (agent='".$row2['P_agent']."')") or die(mysql_error());
    $res5 = mysql_query("UPDATE `users` SET `P_agent`='".$new_agent_id."', `P_agent_assign_time`='".date('U')."' WHERE email='".$buyer_email."'") or die(mysql_error());
    
    // Get the name of the agent removed
    $result6 = mysql_query( "SELECT CONCAT(first_name, ' ' ,last_name) as name, email FROM `registered_agents` WHERE (agent_id = '".$row2['P_agent']."')" ) or die("Couldn't execute query.".mysql_error());
    $row6 = mysql_fetch_array($result6,MYSQL_ASSOC); 
    $old_agent_email = $row6['name'];
    $agent_email = $row6['email'];
    
    // Send email to agent that they have been removed from account.
    $message = "Hello ". $agent_name . ",<br><br>";
    $message .= "An administrator has removed you as $buyer_name's primary agent.\n\n ";
    $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
    $message .= "HomePik.com is licensed by Nice Idea Media";
    
    $mail->ClearAllRecipients(); // Clear all recipients
    $mail->addAddress($old_agent_email);
    $mail->Subject = 'HomePik.com Lost Buyer';
    $mail->Body = $message;
    $mail->send();
  }
  else if($_GET['agent'] == "agent2"){
    $result2 = mysql_query("SELECT P_agent2 FROM `users` WHERE email='".$buyer_email."'")  or die(mysql_error());
    $row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
    
    $res3 = mysql_query("UPDATE `users_folders` SET `agent`='".$new_agent_id."' WHERE (user='".$buyer_email."') AND (agent='".$row2['P_agent2']."')") or die(mysql_error());
    $res4 = mysql_query("UPDATE `Users_Search` SET `agent`='".$new_agent_id."' WHERE (email='".$buyer_email."') AND (agent='".$row2['P_agent2']."')") or die(mysql_error());
    $res5 = mysql_query("UPDATE `users` SET `P_agent2`='".$new_agent_id."', `P_agent2_assign_time`='".date('U')."' WHERE email='".$buyer_email."'") or die(mysql_error());
    
    // Get the name of the agent removed
    $result6 = mysql_query( "SELECT CONCAT(first_name, ' ' ,last_name) as name, email FROM `registered_agents` WHERE (agent_id = '".$row2['P_agent']."')" ) or die("Couldn't execute query.".mysql_error());
    $row6 = mysql_fetch_array($result6,MYSQL_ASSOC); 
    $old_agent_email = $row6['name'];
    $agent_email = $row6['email'];
    
    // Send email to agent that they have been removed from account.
    $message = "Hello ". $agent_name . ",<br><br>";
    $message .= "An administrator has removed you as $buyer_name's primary agent.\n\n ";
    $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
    $message .= "HomePik.com is licensed by Nice Idea Media";
    
    $mail->ClearAllRecipients(); // Clear all recipients
    $mail->addAddress($old_agent_email);
    $mail->Subject = 'HomePik.com Lost Buyer';
    $mail->Body = $message;
    $mail->send();
  }
  
  // Get the name of the agent reassigned to the buyer
  $result7 = mysql_query( "SELECT CONCAT(first_name, ' ' ,last_name) as name, email FROM `registered_agents` WHERE (agent_id = '".$new_agent_id."')" ) or die("Couldn't execute query.".mysql_error());
  $row7 = mysql_fetch_array($result7,MYSQL_ASSOC); 
  $agent_name = $row7['name'];
  $new_agent_email = $row7['email'];
  
  // Send email to agent that they have been added to the buyer's account.
  $message = "Hello ". $agent_name . ",<br><br>";
  $message .= "An administrator has assigned you as $buyer_name's primary agent.\n\n ";
  $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
  $message .= "HomePik.com is licensed by Nice Idea Media";
  
  $mail->ClearAllRecipients(); // Clear all recipients
  $mail->addAddress($new_agent_email);
  $mail->Subject = 'HomePik.com Gained Buyer';
  $mail->Body = $message;
  $mail->send();
  
  // Send email to buyer that an agent have been reassigned to the account.
  $message = "Hello ". $buyer_name . ",<br><br>";
  $message .= "An administrator has reassigned you a new agent. $agent_name is now one of your primary agent.\n\n ";
  $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
  $message .= "HomePik.com is licensed by Nice Idea Media";
  
  $mail->ClearAllRecipients(); // Clear all recipients
  $mail->addAddress($buyer_email);
  $mail->Subject = 'HomePik.com Agent Reassigned';
  $mail->Body = $message;
  $mail->send();
}; // REASSIGN BUYER'S AGENT END

// REMOVE BUYER'S AGENT
if(isset($_GET['removeAgent'])){
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  $buyer_email = $_GET['buyer'];  
  
  if($_GET['agent'] == "agent1"){
    // Get buyer's information
    $result = mysql_query( "SELECT CONCAT(first_name, ' ' ,last_name) as name, P_agent, P_agent2 FROM `users` WHERE (email = '".$buyer_email."')" ) or die("Couldn't execute query.".mysql_error());
    $row = mysql_fetch_array($result,MYSQL_ASSOC);  
    $buyer_name = $row['name'];
    $id = $row['P_agent'];
    $id2 = $row['P_agent2'];
    
    // Get the first agent's information
    $result2 = mysql_query( "SELECT CONCAT(first_name, ' ' ,last_name) as name, email FROM `registered_agents` WHERE (agent_id = '".$id."')" ) or die("Couldn't execute query.".mysql_error());
    $row2 = mysql_fetch_array($result2,MYSQL_ASSOC);  
    $old_agent_email = $row2['email'];
    $agent_name = $row2['name'];
    
    // Get folder associated with first agent
    $result3 = mysql_query( "SELECT name FROM `users_folders` WHERE (user = '".$buyer_email."') AND (agent = '".$id."')" ) or die("Couldn't execute query.".mysql_error());
    while($row3 = mysql_fetch_array($result3,MYSQL_ASSOC)){
      $name = $row3['name'];
    
      // Check if buyer has second agent
      if($id2 == "" || $id2 == null){
        // buyer only has one agent associated with their account
        mysql_query( "UPDATE `saved_listings` SET agent='' WHERE (user = '".$buyer_email."') AND (agent = '".$agent."')" ) or die("Couldn't execute query.".mysql_error()); // Remove the agent from the listings
        mysql_query( "UPDATE `Users_Search` SET agent='' WHERE (email = '".$buyer_email."') AND (name = '".$name."')" ) or die("Couldn't execute query.".mysql_error()); // Remove the agent from the formula associated with the folder
        mysql_query( "UPDATE `users_folders` SET agent='' WHERE (user = '".$buyer_email."') AND (name = '".$name."') AND (agent = '".$id."')" ) or die("Couldn't execute query.".mysql_error()); // Remove the agent from the folder
        mysql_query( "UPDATE `users` SET P_agent='', P_agent_assign_time=0 WHERE (email = '".$buyer_email."')" ) or die("Couldn't execute query.".mysql_error()); // Remove the first agent from the buyer's account
      }
      else{
        // buyer has a second agent associated with their account        
        mysql_query( "DELETE FROM `saved_listings` WHERE (user = '".$buyer_email."') AND (folder = '".$name."')" )  or die(mysql_error()); // Delete the listings inside that folder.   
        mysql_query( "DELETE FROM `Users_Search` WHERE (email = '".$buyer_email."') AND (name = '".$name."')" )  or die(mysql_error()); // Delete the buying formula associated with the folder.              
        mysql_query( "DELETE FROM `users_folders` WHERE (user = '".$buyer_email."') AND (name = '".$name."') AND (agent = '".$id."')" ) or die("Couldn't execute query.".mysql_error()); // Delete the folder                
        mysql_query( "UPDATE `users` set P_agent=P_agent2, P_agent_assign_time=P_agent2_assign_time, P_agent2='', P_agent2_assign_time=0 WHERE (email = '".$buyer_email."')" ) or die("Couldn't execute query.".mysql_error()); // Remove the first agent from the buyer's account and replace with the second agent
        mysql_query( "UPDATE `saved_listings` SET folder='Folder 1' WHERE (user = '".$buyer_email."') AND (agent = '".$id2."')" ) or die("Couldn't execute query.".mysql_error()); // Move all listings with second agent from Folder 2 to Folder 1       
        mysql_query( "UPDATE `Users_Search` SET name='Folder 1' WHERE (email = '".$buyer_email."') AND (agent = '".$id2."')" ) or die("Couldn't execute query.".mysql_error()); // Rename formula with second agent from Folder 2 to Folder 1
        mysql_query( "UPDATE `users_folders` SET name='Folder 1' WHERE (user = '".$buyer_email."') AND (agent = '".$id2."')" ) or die("Couldn't execute query.".mysql_error()); // Rename folder with second agent from Folder 2 to Folder 1
      }
    }
  
    // Send email to agent that they have been removed from the account.
    $message = "Hello ". $agent_name . ",<br><br>";
    $message .= "An administrator has removed you as $buyer_name's primary agent.\n\n ";
    $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
    $message .= "HomePik.com is licensed by Nice Idea Media";
    
    $mail->ClearAllRecipients(); // Clear all recipients
    $mail->addAddress($old_agent_email);
    $mail->Subject = 'HomePik.com Lost Buyer';
    $mail->Body = $message;
    $mail->send();
    
    // Send email to buyer that an agent have been removed from account.
    $message = "Hello ". $buyer_name . ",<br><br>";
    $message .= "An administrator has removed $agent_name as your primary agent.\n\n ";
    $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
    $message .= "HomePik.com is licensed by Nice Idea Media";
    
    $mail->ClearAllRecipients(); // Clear all recipients
    $mail->addAddress($buyer_email);
    $mail->Subject = 'HomePik.com Agent Removed';
    $mail->Body = $message;
    $mail->send();
  }
  else if($_GET['agent'] == "agent2"){
    //Get user information and agent 2 id
    $result = mysql_query( "SELECT CONCAT(first_name, ' ' ,last_name) as name, P_agent2 FROM `users` WHERE (email = '".$buyer_email."')" ) or die("Couldn't execute query.".mysql_error());
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $buyer_name = $row['name'];
    $id = $row['P_agent2'];
    
    // Get agent 2's information
    $result2 = mysql_query( "SELECT CONCAT(first_name, ' ' ,last_name) as name, email FROM `registered_agents` WHERE (agent_id = '".$id."')" ) or die("Couldn't execute query.".mysql_error());
    $row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
    $old_agent_email = $row2['email'];
    $agent_name = $row2['name'];
    
    // Get the folder associated with that agent.
    $result3 = mysql_query( "SELECT name, agent FROM `users_folders` WHERE (user = '".$buyer_email."') AND (agent LIKE '%".$id."%')" ) or die("Couldn't execute query.".mysql_error());
    while($row3 = mysql_fetch_array($result3,MYSQL_ASSOC)){
      $name = $row3['name'];
      $agents = $row3['agent'];
      
      mysql_query( "DELETE FROM `saved_listings` WHERE (user = '".$buyer_email."') AND (folder = '".$name."')" )  or die(mysql_error()); // Delete the listings inside that folder.     
      mysql_query( "DELETE FROM `Users_Search` WHERE (email = '".$buyer_email."') AND (name = '".$name."')" )  or die(mysql_error()); // Delete the buying formula associated with the folder.      
      mysql_query( "DELETE FROM `users_folders` WHERE (user = '".$buyer_email."') AND (name = '".$name."') AND (agent LIKE '%".$id."%')" ) or die("Couldn't execute query.".mysql_error()); // Delete the folder
    }
     
    mysql_query( "UPDATE `users` SET P_agent2='', P_agent2_assign_time=0 WHERE (email = '".$buyer_email."')" ) or die("Couldn't execute query.".mysql_error()); // Remove the agent from the buyer's account
    
    // Send email to agent that they have been removed from account.
    $message = "Hello ". $agent_name . ",<br><br>";
    $message .= "An administrator has removed you as $buyer_name's primary agent.\n\n ";
    $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
    $message .= "HomePik.com is licensed by Nice Idea Media";
    
    $mail->ClearAllRecipients(); // Clear all recipients
    $mail->addAddress($old_agent_email);
    $mail->Subject = 'HomePik.com Lost Buyer';
    $mail->Body = $message;
    $mail->send();
    
    // Send email to buyer that an agent have been removed from account.
    $message = "Hello ". $buyer_name . ",<br><br>";
    $message .= "An administrator has removed $agent_name as your primary agent.\n\n ";
    $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
    $message .= "HomePik.com is licensed by Nice Idea Media";
    
    $mail->ClearAllRecipients(); // Clear all recipients
    $mail->addAddress($buyer_email);
    $mail->Subject = 'HomePik.com Agent Removed';
    $mail->Body = $message;
    $mail->send();
  }  
}; // REMOVE BUYER'S AGENT END
?>