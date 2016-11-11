<?
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
session_start();

include_once("dbconfig.php");
include_once("emailconfig.php");
include_once("functions.php");

$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());
  
//if ($logged_in = 'true') {

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
    $SQL = "SELECT firstname, lastname FROM `Agent_Import` WHERE e_mail = '".$from."'";
    $res = mysql_query($SQL) or die(mysql_error());
    $row = mysql_fetch_array($res,MYSQL_ASSOC);
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];

    $message = "Hello,";
    $message .= "<br><br>";
    $message .= $firstname . " " . $lastname;
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
  else if(isset($_SESSION['user']) && ($_SESSION['email'] != "guest@email.com")){
    $SQL = "SELECT first_name, last_name, notifications FROM `users` WHERE email = '".$from."'";
    $res = mysql_query($SQL) or die(mysql_error());
    $row = mysql_fetch_array($res,MYSQL_ASSOC);
    $firstname = $row['first_name'];
    $lastname = $row['last_name'];
    $notifications = $row['notifications'];

    if($notifications == 'all' || $notifications == "folder"){
      $message = "Hello,";
      $message .= "<br><br>";
      $message .= $firstname . " " . $lastname;
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

  if(isset($_SESSION['user']) && $from != "guest@email.com"){
    $res = mysql_query("INSERT INTO contacted(`user`,`list_num`, `contacted`, `time`) VALUES  ('".$from."','".$list_num."','".$to."','".$time."')")  or die(mysql_error());

    $SQL = "SELECT first_name, last_name FROM `users` WHERE email = '".$from."'";
    $res = mysql_query($SQL) or die(mysql_error());
    while($row = mysql_fetch_array($res,MYSQL_ASSOC)) {
      $firstname = $row['first_name'];
      $lastname = $row['last_name'];
    }

    $SQL = "SELECT firstname, lastname FROM `Agent_Import` WHERE e_mail = '".$to."'";
    $res = mysql_query($SQL) or die(mysql_error());
    while($row = mysql_fetch_array($res,MYSQL_ASSOC)) {
      $first_name = $row['firstname'];
      $last_name = $row['lastname'];
    }

    $SQL = "SELECT * FROM `vow_data` WHERE list_numb = '".$list_num."'";
    $res = mysql_query($SQL) or die(mysql_error());
    while($row = mysql_fetch_array($res,MYSQL_ASSOC)) {
      $address = $row['address'];
      $apt = $row['apt'];
    }

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

    $SQL = "SELECT firstname, lastname FROM `Agent_Import` WHERE e_mail = '".$to."'";
    $res = mysql_query($SQL) or die(mysql_error());
    while($row = mysql_fetch_array($res,MYSQL_ASSOC)) {
      $first_name = $row['firstname'];
      $last_name = $row['lastname'];
    }

   $SQL = "SELECT * FROM `vow_data` WHERE list_numb = '".$list_num."'";
    $res = mysql_query($SQL) or die(mysql_error());
    while($row = mysql_fetch_array($res,MYSQL_ASSOC)) {
      $address = $row['address'];
      $apt = $row['apt'];
    }

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

//SEND AGENT CHART
if(isset($_GET['emailChart'])){
  $agentEmail = (!empty($_GET['agentEmail']) ? $_GET['agentEmail'] : null);
  $to = (!empty($_GET['toEmail']) ? $_GET['toEmail'] : null);
  $comments = (!empty($_GET['comments']) ? $_GET['comments'] : null);
  $table = (!empty($_GET['table']) ? $_GET['table'] : null);

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  $SQL = "SELECT firstname, lastname FROM `Agent_Import` WHERE (e_mail = '".$agentEmail."')";
  $res = mysql_query($SQL) or die(mysql_error());
  while($row = mysql_fetch_array($res,MYSQL_ASSOC)) {
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
  }

  if($_SESSION['email']==$to){
    $message = "Hello ".$to.",";
    $message .= "<br><br>";
    $message .= "Here are your listings from HomePik.com. You will find them below:";
    $message .= "<br><br>";
    $message .= "Comments: ";
    $message .= $comments;
    $message .= "<br><br>";
    $message .= 'Link: <a href="http://homepik.com/controllers/agent-listings.php?user='. $agentEmail .'">http://homepik.com/controllers/agent-listings.php?user='. $agentEmail .'</a>';
    $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
    $message .= "HomePik.com is licensed by Nice Idea Media";
    
    $mail->addAddress($agentEmail);
    $mail->Subject = 'Agent Chart';
    $mail->Body = $message;    
    $mail->send();
  }
  else{
    $message = "Hello ".$to.",";
    $message .= "<br><br>";
    $message .= $firstname . " " . $lastname;
    $message .= " has sent you their listings from HomePik.com. You will find them below:";
    $message .= "<br><br>";
    $message .= "Comments: ";
    $message .= $comments;
    $message .= "<br><br>";
    $message .= 'Link: <a href="http://homepik.com/controllers/agent-listings.php?user='. $agentEmail .'">http://homepik.com/controllers/agent-listings.php?user='. $agentEmail .'</a>';
    $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
    $message .= "HomePik.com is licensed by Nice Idea Media";
    
    $mail->addAddress($to);
    $mail->Subject = 'Agent Chart';
    $mail->Body = $message;    
    $mail->send();
  }
}; //SEND AGENT CHART END

//MAKE PRIMARY AND SECONDARY AGENTS
if(isset($_GET['makePrimary'])){
  $agentCode = $_GET['agentCode'];
  $user = $_GET['usersEmail'];

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  $SQL = "SELECT firstname, lastname, e_mail FROM Agent_Import WHERE id = '".$agentCode."'";
  $res = mysql_query($SQL)  or die(mysql_error());

  while ($row = mysql_fetch_assoc($res)) {
    $agentEmail = $row['e_mail'];
    $agentFirstName = $row['firstname'];
    $agentLastName = $row['lastname'];
  }

  $SQL = "SELECT * FROM users WHERE email = '".$user."'";
  $res = mysql_query($SQL)  or die(mysql_error());

  while ($row = mysql_fetch_assoc($res)) {
    $buyerFirstName = $row['first_name'];
    $buyerLastName = $row['last_name'];
    $pAgent = $row['P_agent'];
    $pAgent2 = $row['P_agent2'];
  }

  if ($pAgent == ""){ //Add agent
    if ($pAgent2 == "") { // No secondary - assign as primary

      $SQL = "UPDATE users SET P_agent = '".$agentCode."' WHERE email = '".$user."'";
      $res = mysql_query($SQL)  or die(mysql_error());
      
      $SQL = "UPDATE users_folders SET agent = '".$agentCode."' WHERE (user = '".$user."')";
      $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());

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

      $result = 'assign';

    } else if ($pAgent2 != "") { // Move secondary to primary

      $SQL = "UPDATE users SET P_agent = P_agent2, P_agent2 = '' WHERE email = '".$user."'";
      $res = mysql_query($SQL)  or die(mysql_error());
      print $SQL;

      $result = 'remove';
    }
  }
  else if ($pAgent != ""){ // Remove primary
    if($pAgent == $agentCode){ // Remove the first agent
      
      // Remove agent from the account
      if($pAgent2 != ""){ // If user has a second agent
        // Delete listings in the folder associated with the agent
        $SQL2 = "DELETE FROM `saved_listings` WHERE (user = '".$user."') AND (folder = 'Folder 1')";
        $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
        
        // Delete formula associated with agent
        $SQL4 = "SELECT name, agent FROM `Users_Search` WHERE (email = '".$user."') AND (agent LIKE '%".$pAgent."%')";
        $result4 = mysql_query( $SQL4 ) or die("Couldn't execute query.".mysql_error());
        while($row4 = mysql_fetch_array($result4,MYSQL_ASSOC)){
          $name = $row4['name'];
          $agents = $row4['agent'];
        
          if($agents == $pAgent){
            $SQL5 = "DELETE FROM `Users_Search` WHERE (email = '".$user."') AND (name = '".$name."') AND (agent LIKE '%".$pAgent."%')";
            $result5 = mysql_query( $SQL5 ) or die("Couldn't execute query.".mysql_error());
          }
          else{
            $agents = str_replace($pAgent, '', $agents);
            $agents = str_replace(',', '', $agents);
            
            $SQL5 = "UPDATE `Users_Search` SET agent='".$agents."' WHERE (email = '".$user."') AND (name = '".$name."') AND (agent LIKE '%".$pAgent."%')";
            $result5 = mysql_query( $SQL5 ) or die("Couldn't execute query.".mysql_error());
          }
        }
        
        // Delete folder associated with agent
        $SQL6 = "SELECT name, agent FROM `users_folders` WHERE (user = '".$user."') AND (agent LIKE '%".$pAgent."%')";
        $result6 = mysql_query( $SQL6 ) or die("Couldn't execute query.".mysql_error());
        while($row6 = mysql_fetch_array($result6,MYSQL_ASSOC)){
          $name = $row6['name'];
          $agents = $row6['agent'];
        
          if($agents == $pAgent){
            $SQL7 = "DELETE FROM `users_folders` WHERE (user = '".$user."') AND (name = '".$name."') AND (agent LIKE '%".$pAgent."%')";
            $result7 = mysql_query( $SQL7 ) or die("Couldn't execute query.".mysql_error()); 
          }
          else{
            $agents = str_replace($pAgent, '', $agents);
            $agents = str_replace(',', '', $agents);
            
            $SQL7 = "UPDATE `users_folders` SET agent='".$agents."' WHERE (user = '".$user."') AND (name = '".$name."') AND (agent LIKE '%".$pAgent."%')";
            $result7 = mysql_query( $SQL7 ) or die("Couldn't execute query.".mysql_error());
          }
        }
      
        // Re-assign second agent as first if exists
        $SQL = "UPDATE `users` SET P_agent = P_agent2, P_agent2 = '' WHERE email = '".$user."'";
        $res = mysql_query($SQL)  or die(mysql_error());
        
        // Re-assign folder associated with second agent
        $SQL2 = "UPDATE `saved_listings` SET folder='Folder 1' WHERE (user = '".$user."') AND (folder = 'Folder 2')";
        $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
        
        // Re-assign folder associated with second agent
        $SQL3 = "UPDATE `users_folders` SET name='Folder 1' WHERE (user = '".$user."') AND (agent = '".$pAgent2."') AND (name = 'Folder 2')";
        $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());
        
        // Re-assign formula associated with second agent            
        $SQL4 = "UPDATE `Users_Search` SET name='Folder 1' WHERE (email = '".$user."') AND (agent = '".$pAgent2."')";
        $result4 = mysql_query( $SQL4 ) or die("Couldn't execute query.".mysql_error());
      }
      else{ // If user only has the one agent
        $SQL = "UPDATE `users` SET P_agent = '' WHERE email = '".$user."' ";
        $res = mysql_query($SQL)  or die(mysql_error());
        
        // Remove agent from folder associated with agent
        $SQL2 = "UPDATE `saved_listings` SET agent='' WHERE (user = '".$user."') AND (folder = 'Folder 1')";
        $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
        
        // Remove agent from folder associated with agent
        $SQL3 = "UPDATE `users_folders` SET agent='' WHERE (user = '".$user."') AND (agent = '".$pAgent."') AND (name = 'Folder 1')";
        $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());
        
        // Remove agent from formula associated with agent            
        $SQL4 = "UPDATE `Users_Search` SET agent='' WHERE (email = '".$user."') AND (agent = '".$pAgent."')";
        $result4 = mysql_query( $SQL4 ) or die("Couldn't execute query.".mysql_error());
      }
    }
    else if ($pAgent2 == $agentCode) { // Remove the second agent
      
      // Delete all listings in the folder associated with the second agent
      $SQL3 = "DELETE FROM `saved_listings` WHERE (user = '".$user."') AND (folder = 'Folder 2')";
      $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());
      
      // Delete the formual associated with the folder and second agent
      $SQL4 = "SELECT name, agent FROM `Users_Search` WHERE (email = '".$user."') AND (agent LIKE '%".$pAgent2."%')";
      $result4 = mysql_query( $SQL4 ) or die("Couldn't execute query.".mysql_error());
      while($row4 = mysql_fetch_array($result4,MYSQL_ASSOC)){
        $name = $row4['name'];
        $agents = $row4['agent'];
      
        if($agents == $pAgent2){
          $SQL5 = "DELETE FROM `Users_Search` WHERE (email = '".$user."') AND (name = '".$name."') AND (agent LIKE '%".$pAgent2."%')";
          $result5 = mysql_query( $SQL5 ) or die("Couldn't execute query.".mysql_error());
        }
        else{
          $agents = str_replace($pAgent2, '', $agents);
          $agents = str_replace(',', '', $agents);
          
          $SQL5 = "UPDATE `Users_Search` SET agent='".$agents."' WHERE (email = '".$user."') AND (name = '".$name."') AND (agent LIKE '%".$pAgent2."%')";
          $result5 = mysql_query( $SQL5 ) or die("Couldn't execute query.".mysql_error());
        }
      }
      
      // Delete the folder associated with the second agent
      $SQL6 = "SELECT name, agent FROM `users_folders` WHERE (user = '".$user."') AND (agent LIKE '%".$pAgent2."%')";
      $result6 = mysql_query( $SQL6 ) or die("Couldn't execute query.".mysql_error());
      while($row6 = mysql_fetch_array($result6,MYSQL_ASSOC)){
        $name = $row6['name'];
        $agents = $row6['agent'];
      
        if($agents == $pAgent2){
          $SQL7 = "DELETE FROM `users_folders` WHERE (user = '".$user."') AND (name = '".$name."') AND (agent LIKE '%".$pAgent2."%')";
          $result7 = mysql_query( $SQL7 ) or die("Couldn't execute query.".mysql_error()); 
        }
        else{
          $agents = str_replace($pAgent2, '', $agents);
          $agents = str_replace(',', '', $agents);
          
          $SQL7 = "UPDATE `users_folders` SET agent='".$agents."' WHERE (user = '".$user."') AND (name = '".$name."') AND (agent LIKE '%".$pAgent2."%')";
          $result7 = mysql_query( $SQL7 ) or die("Couldn't execute query.".mysql_error());
        }
      }
      
      // Delete the agent from the buyer's account
      $SQL = "UPDATE users SET P_agent2 = '' WHERE email = '".$user."'";
      $res = mysql_query($SQL)  or die(mysql_error());
      
      print $SQL;

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

// EMAIL SAVED LISTINGS

if(isset($_GET['email_saved'])){

  $email = (!empty($_GET['email']) ? $_GET['email'] : null);
  $to = (!empty($_GET['toEmail']) ? $_GET['toEmail'] : null);
  $comments = (!empty($_GET['comments']) ? $_GET['comments'] : null);
  $agent = (!empty($_GET['agent']) ? $_GET['agent'] : null);
  $table = (!empty($_GET['table']) ? $_GET['table'] : null);

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  $SQL = "SELECT first_name, last_name FROM `users` WHERE (email = '".$email."')";
  $res = mysql_query($SQL) or die(mysql_error());
  while($row = mysql_fetch_array($res,MYSQL_ASSOC)) {
      $firstname = $row['first_name'];
      $lastname = $row['last_name'];
  }

  if ($_SESSION['email'] == $email){ // went through buyer side
    $message = "Hello,";
    $message .= "<br><br>";
    $message .= $firstname . " " . $lastname;
    $message .= " has sent you their listings from HomePik.com. You will find them below:";
    $message .= "<br><br>";
    $message .= "Comments: ";
    $message .= $comments;
    $message .= "<br><br>";
    $message .= 'Listings Link: <a href="http://homepik.com/controllers/saved.php?user='. $email .'&agent='.$agent.'">http://homepik.com/controllers/saved.php?user='. $email .'&agent='.$agent.'</a>';
    $message .= "<br><br><br><br>Copyright Nice Idea Media  All Rights Reserved<br>";
    $message .= "<br>HomePik.com is licensed by Nice Idea Media";

    $mail->addAddress($to);
    $mail->Subject = 'Buyer Chart';
    $mail->Body = $message;    
    $mail->send();
  } else { // went through agent side
    $SQL = "SELECT firstname, lastname FROM `Agent_Import` WHERE (e_mail = '".$agent."')";
    $res = mysql_query($SQL) or die(mysql_error());
    while($row = mysql_fetch_array($res,MYSQL_ASSOC)) {
      $firstname = $row['firstname'];
      $lastname = $row['lastname'];
    }
  
    $message = "Hello,";
    $message .= "<br><br>";
    $message .= $firstname . " " . $lastname;
    $message .= " has sent you their listings from HomePik.com. You will find them below:";
    $message .= "<br><br>";
    $message .= "Comments: ";
    $message .= $comments;
    $message .= "<br><br>";
    $message .= 'Listings Link: <a href="http://homepik.com/controllers/saved.php?user='. $email .'&agent='.$agent.'">http://homepik.com/controllers/saved.php?user='. $email .'&agent='.$agent.'</a>';
    $message .= "<br><br><br><br>Copyright Nice Idea Media  All Rights Reserved<br>";
    $message .= "<br>HomePik.com is licensed by Nice Idea Media";

    $mail->addAddress($to);
    $mail->Subject = 'Buyer Chart';
    $mail->Body = $message;    
    $mail->send();
  }
}; // EMAIL SAVED LISTINGS END

//ADD A COMMENT
if(isset($_GET['addComment'])){
  $listing = $_GET['listing'];
  $user = $_GET['user'];
  $comments = $_GET['comments'];

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  if(strpos($user, "@bellmarc.com") !== false){
    $SQL = "UPDATE queued_listings SET comments = '".$comments."' WHERE list_num = '".$listing."' AND user = '".$user."'";
    $res = mysql_query($SQL) or die(mysql_error());
  }
  else{
    $SQL = "UPDATE saved_listings SET comments = '".$comments."' WHERE list_num = '".$listing."' AND user = '".$user."'";
    $res = mysql_query($SQL) or die(mysql_error());
  }

  if (isset($_GET['folder'])) {
    $time = date('U');
    $SQL = "UPDATE users_folders SET last_update='".$time."' WHERE name='".$_GET['folder']."' AND user = '".$user."'";
    $res = mysql_query($SQL)  or die(mysql_error());
  }
}; // ADD A COMMENT END

//REMOVE A COMMENT
if(isset($_GET['deleteComment'])){
  $listing = $_GET['listing'];
  $user = $_GET['user'];
  $agent = $_GET['agent'];

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  if($_SESSION['agent']){
    $SQL = "UPDATE queued_listings SET comments = '' WHERE list_num = '".$listing."' AND user = '".$user."'";
    $res = mysql_query($SQL) or die(mysql_error());
  }

  $SQL = "UPDATE saved_listings SET comments = '' WHERE list_num = '".$listing."' AND user = '".$user."' AND agent = '".$agent."'";
  $res = mysql_query($SQL) or die(mysql_error());

}; // REMOVE A COMMENT END


// QUEUE A LISTING
if(isset($_GET['queue'])){
  $list_num = $_GET['list_num'];

  if(!$_GET['buyer']){
    $user = $_SESSION['email'];

    if ($_SESSION['agent']){
      $role = 'agent';
    } elseif ($_SESSION['user']){
      $role = 'user';
    }
  } else {
    $user = $_GET['buyer'];
    $role = 'user';
  }
  $from = $_SESSION['email'];
  $time = date('U');

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  // CHECK FOR DUPLICATE SAVES
  $sql = "(SELECT user, list_num FROM queued_listings WHERE saved_by='" . $_SESSION['email'] . "' AND list_num='". $list_num . "')";
  $rs = mysql_query($sql);
  $num_rows = mysql_num_rows($rs);
  if ($num_rows <= 0)
  {
    $res = mysql_query("INSERT INTO queued_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `time`) VALUES  ('".$user."','".$list_num."','".$from."','".$_GET['comments']."','".$role."','".$time."')")  or die(mysql_error());
  }
  // GET THE LIST_NUM OF ALL SAVED LISTINGS (BOTH SAVED TO USERS AND OPEN LISTINGS) SO WE CAN MARK THEM WITH A SAVED ICON IN SEARCH RESULTS
  $sql = "(SELECT user, list_num FROM queued_listings WHERE saved_by='" . $_SESSION['email'] . "') UNION (SELECT user, list_num FROM saved_listings WHERE saved_by='" . $_SESSION['email'] . "')";
  $rs = mysql_query($sql);
  $arr_rows = array();
  while ($row = mysql_fetch_array($rs)) {
   $saved_listings[] = $row;
  }
  $_SESSION['saved_listings'] = $saved_listings;

}; // QUEUE END

// MAKE NEW SAVED FOLDER
if(isset($_GET['makeFolder'])){
  $folder = $_GET['name'];
  if(isset($_GET['email'])){$user = $_GET['email']; }
  else{ $user = $_SESSION['email']; };
  if(isset($_GET['agent'])){$agent = $_GET['agent']; }
  else{ $agent = ""; }
  $time = date('U');

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  $SQL = "SELECT * FROM users_folders WHERE (user = '".$user."') AND (name = '".$folder."')";
  $rs = mysql_query($SQL);
    $num_rows = mysql_num_rows($rs);

  if($num_rows <= 0){
    $SQL = "INSERT INTO users_folders(`user`,`name`,`agent`,`last_update`) VALUES  ('".$user."','".$folder."','".$agent."','".$time."')";
    $res = mysql_query($SQL)  or die(mysql_error());
  }

}// END MAKE NEW SAVED FOLDER

// EMAIL A LISTING
if(isset($_GET['checkListings'])){
  $listNum = $_GET['list_num'];
  $user = $_SESSION['email'];
  
}

// UPDATE BUYER'S SAVED FOLDER
if(isset($_GET['updateFolder'])){
  $oldFolderName = $_GET['oldName'];
  $newFolderName = $_GET['newName'];
  if(isset($_GET['agents'])){$agents = $_GET['agents'];}
  if(isset($_GET['email'])){$user = $_GET['email']; }
  else{ $user = $_SESSION['email']; };
  $time = date('U');
  
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  $SQL = "SELECT * FROM users_folders WHERE (user = '".$user."') AND (name = '".$oldFolderName."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $agent = $row['agent'];
  
  $SQL = "UPDATE `saved_listings` SET `folder`='".$newFolderName."' WHERE (`user`='".$user."') AND (`folder`='".$oldFolderName."')";
  $rs = mysql_query($SQL);

  $SQL = "DELETE FROM `users_folders` WHERE (user = '".$user."') AND (name = '".$oldFolderName."')";
  $res = mysql_query($SQL)  or die(mysql_error());

  $SQL = "SELECT * FROM users_folders WHERE (user = '".$user."') AND (name = '".$newFolderName."')";
  $rs = mysql_query($SQL);
  $num_rows = mysql_num_rows($rs);

  if($num_rows <= 0){
    $SQL = "INSERT INTO users_folders(`user`,`name`,`agent`,`last_update`) VALUES  ('".$user."','".$newFolderName."','".$agent."','".$time."')";
    $res = mysql_query($SQL)  or die(mysql_error());
  }
    
  if(isset($agents)){
    if($agents == "no-change"){
      // Do nothing
    }
    else{
      if($agents == "none"){
        $SQL = "UPDATE `users_folders` SET `agent`='' WHERE (`user`='".$user."') AND (`name`='".$newFolderName."')";
        $rs = mysql_query($SQL);
      }
      else{
        $SQL = "SELECT * FROM `users` WHERE (email = '".$user."')";
        $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
        $row = mysql_fetch_array($result,MYSQL_ASSOC);
        $agent1 = $row['P_agent'];
        $agent2 = $row['P_agent2'];
        
        if($agents == "agent1"){
          $SQL = "UPDATE `users_folders` SET `agent`='".$agent1."' WHERE (`user`='".$user."') AND (`name`='".$newFolderName."')";
          $rs = mysql_query($SQL);
        }
        elseif($agents == "agent2"){
          $SQL = "UPDATE `users_folders` SET `agent`='".$agent2."' WHERE (`user`='".$user."') AND (`name`='".$newFolderName."')";
          $rs = mysql_query($SQL);
        }
        elseif($agents == "both"){
          $a = $agent1 . "," . $agent2;
          $SQL = "UPDATE `users_folders` SET `agent`='".$a."' WHERE (`user`='".$user."') AND (`name`='".$newFolderName."')";
          $rs = mysql_query($SQL);
        }
        else{
          // Do nothing
        }
      }
    }
  }
  
  $results = mysql_query("UPDATE Users_Search SET `name` = '".$newFolderName."' WHERE (email = '".$user."') AND (name = '".$oldFolderName."')")  or die(mysql_error());
}// END UPDATE SAVED FOLDER

// UPDATE AGENT'S SAVED FOLDER
if(isset($_GET['updateAgentFolder'])){
  $oldFolderName = $_GET['oldName'];
  $newFolderName = $_GET['newName'];
  if(isset($_GET['agents'])){$agents = $_GET['agents'];}
  $user = $_SESSION['email'];
  $time = date('U');
  
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  $SQL = "UPDATE `queued_listings` SET `folder`='".$newFolderName."' WHERE (`user`='".$user."') AND (`folder`='".$oldFolderName."')";
  $rs = mysql_query($SQL);

  $SQL = "DELETE FROM `users_folders` WHERE (user = '".$user."') AND (name = '".$oldFolderName."')";
  $res = mysql_query($SQL)  or die(mysql_error());

  $SQL = "SELECT * FROM users_folders WHERE (user = '".$user."') AND (name = '".$newFolderName."')";
  $rs = mysql_query($SQL);
  $num_rows = mysql_num_rows($rs);

  if($num_rows <= 0){
    $SQL = "INSERT INTO users_folders(`user`,`name`,`last_update`) VALUES  ('".$user."','".$newFolderName."','".$time."')";
    $res = mysql_query($SQL)  or die(mysql_error());
}
}// END UPDATE SAVED FOLDER

// DELETE BUYER'S SAVED FOLDER
if(isset($_GET['deleteFolder'])){
  $folderName = $_GET['name'];
  if(isset($_GET['email'])){$user = $_GET['email']; }
  else{ $user = $_SESSION['email']; };

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  $SQL = "DELETE FROM `saved_listings` WHERE (user = '".$user."') AND (folder = '".$folderName."')";
  $res = mysql_query($SQL)  or die(mysql_error());

  $SQL = "DELETE FROM `users_folders` WHERE (user = '".$user."') AND (name = '".$folderName."')";
  $res = mysql_query($SQL)  or die(mysql_error());
  
  $SQL = "DELETE FROM `Users_Search` WHERE (email = '".$user."') AND (name = '".$folderName."')";
  $res = mysql_query($SQL)  or die(mysql_error());
}// END DELETE BUYER FOLDER

// DELETE AGENT'S SAVED FOLDER
if(isset($_GET['deleteAgentFolder'])){
  $folderName = $_GET['name'];
  $user = $_SESSION['email'];

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  $SQL = "DELETE FROM `queued_listings` WHERE (user = '".$user."') AND (folder = '".$folderName."')";
  $res = mysql_query($SQL)  or die(mysql_error());

  $SQL = "DELETE FROM `users_folders` WHERE (user = '".$user."') AND (name = '".$folderName."')";
  $res = mysql_query($SQL)  or die(mysql_error());
}// END DELETE BUYER FOLDER

// EMAIL FOLDER
if(isset($_GET['emailFolder'])){
  $sender = $_GET['sender'];
  $agentSentBuyerFolder = $_GET['agentSentBuyerFolder'];
  $guestName = $_GET['guestName'];
  $recipient = $_GET['recipient'];
  $folder = $_GET['folder'];
  $comment = $_GET['comment'];	

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  
  if($_SESSION['agent']){
    $SQL = "SELECT firstname, lastname FROM `Agent_Import` WHERE (e_mail = '".$_SESSION['email']."')";
    $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $sender_firstname = $row['firstname'];
    $sender_lastname = $row['lastname'];
  }
  else{
    $SQL = "SELECT first_name, last_name FROM `users` WHERE (email = '".$sender."')";
    $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $sender_firstname = $row['first_name'];
    $sender_lastname = $row['last_name']; 
  }
  
  if(strpos($recipient, "@bellmarc.com") !== false){
    $SQL = "SELECT firstname, lastname FROM `Agent_Import` WHERE (e_mail = '".$recipient."')";
    $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $recipient_firstname = $row['firstname'];
    $recipient_lastname = $row['lastname'];
  }
  else{
    $SQL = "SELECT first_name, last_name FROM `users` WHERE (email = '".$recipient."')";
    $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $recipient_firstname = $row['first_name'];
    $recipient_lastname = $row['last_name']; 
  }
  
  if($recipient_firstname != ""){ $message = "Hello " . $recipient_firstname . " " . $recipient_lastname . ","; }
  else{ $message = "Hello " . $recipient . ","; }
  
  if($agentSentBuyerFolder == "true"){ $message .= "<br><br>" . $sender_firstname . " " . $sender_lastname . " has sent you their buyer's folder, <b>$folder</b>, from HomePik.com"; }
  else if($_SESSION['buyer'] || $_SESSION['agent']){ $message .= "<br><br>" . $sender_firstname . " " . $sender_lastname . " has sent you their folder, <b>$folder</b>, from HomePik.com"; }
  else{ $message .= "<br><br>" . $guestName . " has sent you their folder, <b>$folder</b>, from HomePik.com"; }
  
  if(isset($comment) && $comment != ""){ $message .= " with the following message: " . $comment; }
  
  if($_SESSION['buyer'] || $_SESSION['agent']){ $message .= ".<br><br> Click <a href='www.homepik.com/controllers/emailed-folder.php?user=".$sender."&folder=".$folder."'>here</a> to view their folder. If the link doesn't work enter this into the URL: <a style='text-decoration:none !important; text-decoration:none;'>www.homepik.com/controllers/emailed-folder.php?user=".$sender."&folder=".$folder."</a>"; }
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
  $sql = "SELECT * FROM saved_listings WHERE user = '" . $guestID . "' AND list_num = '" . $list_num . "' AND folder = 'Guest Folder'";
  $rs = mysql_query($sql);
  $num = mysql_num_rows($rs);

  if ($num < 1){
    $SQL = "INSERT INTO saved_listings(`user`,`list_num`,`saved_by`, `role`, `folder`, `time`) VALUES  ('".$_SESSION['guestID']."','".$list_num."','".$from."','".$role."','Guest Folder','".$time."')";
    $res = mysql_query($SQL)  or die(mysql_error());
    $result = "Your listing has been saved!";
  }
  else
  {
    $result = "You have already saved this listing!";
  }
  print $result;
  return $result;
};

// SAVE A LISTING
if(isset($_GET['save'])){
  $list_num = $_GET['list_num'];
  $comments = $_GET['comments'];

  if(isset($_GET['buyer'])){ $user = $_GET['buyer']; }
  else { $user = $_SESSION['email']; }
  
  if($_SESSION['agent']){ $role = 'agent'; }
  elseif($_SESSION['user']){ $role = 'user'; }
  else{ $role = 'guest'; }
  
  $from = $_SESSION['email'];
  $time = date('U');

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  if($_SESSION['agent']){
    $folders = $_GET['folders'];
    
    if(strpos($user, "@bellmarc.com") !== false){
      foreach($folders as $name){
        $sql = "SELECT * FROM queued_listings WHERE user = '" . $user . "' AND list_num = '" . $list_num . "' AND folder = '".$name."'";
        $rs = mysql_query($sql);
        $num = mysql_num_rows($rs);
      
        if ($num < 1){
          $SQL = "INSERT INTO queued_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$user."','".$list_num."','".$from."','".$comments."','".$role."','".$name."','".$time."')";
          $res = mysql_query($SQL)  or die(mysql_error());
        }
        
        $SQL2 = "UPDATE	users_folders SET last_update='".$time."' where name='". $name ."' AND user = '".$user."'";
        $res2 = mysql_query($SQL2)  or die(mysql_error());
      }
    }
    else{
      foreach($folders as $name){
        $sql = "SELECT * FROM saved_listings WHERE user = '" . $user . "' AND list_num = '" . $list_num . "' AND folder = '".$name."'";
        $rs = mysql_query($sql);
        $num = mysql_num_rows($rs);
      
        if ($num < 1){
          $SQL = "INSERT INTO saved_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$user."','".$list_num."','".$from."','".$comments."','".$role."','".$name."','".$time."')";
          $res = mysql_query($SQL)  or die(mysql_error());
          
          $SQL3 = "SELECT firstname, lastname FROM `Agent_Import` WHERE (e_mail = '".$_SESSION['email']."')";
          $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());
          $row3 = mysql_fetch_array($result3,MYSQL_ASSOC);
          $agent_firstname = $row3['firstname'];
          $agent_lastname = $row3['lastname'];
          
          $SQL = "SELECT first_name, last_name, notifications FROM `users` WHERE (email = '".$user."')";
          $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
          $row = mysql_fetch_array($result,MYSQL_ASSOC);
          $buyer_firstname = $row['first_name'];
          $buyer_lastname = $row['last_name'];
          $notifications = $row['notifications'];
          
          if($notifications == 'all' || $notifications == 'folder'){
            $message = "Hello " . $buyer_firstname . " " . $buyer_lastname . ",";
            $message .= "<br><br>" . $agent_firstname . " " . $agent_lastname . " has saved a new listing to your folder: " . $folder;
            $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
            $message .= "HomePik.com is licensed by Nice Idea Media";
            $message .= "<br><br><center><a href='http://www.homepik.com/controllers/change-email-alert-settings.php?user=".$user."'>Change Email Alert Settings</a></center><br>";
            $mail->addAddress($user);
            $mail->Subject = 'New Listing from HomePik';
            $mail->Body = $message;
            $mail->send();
          }
        }
        
        $SQL2 = "UPDATE	users_folders SET last_update='".$time."' where name='". $name ."' AND user = '".$user."'";
        $res2 = mysql_query($SQL2)  or die(mysql_error());
      }
    }
  }
  else{
    $folders = $_GET['folders'];
    
    foreach($folders as $name){
      $sql = "SELECT * FROM saved_listings WHERE user = '" . $user . "' AND list_num = '" . $list_num . "' AND folder = '".$name."'";
      $rs = mysql_query($sql);
      $num = mysql_num_rows($rs);
    
      if ($num < 1){
        $SQL = "INSERT INTO saved_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$user."','".$list_num."','".$from."','".$comments."','".$role."','".$name."','".$time."')";
        $res = mysql_query($SQL)  or die(mysql_error());
        
        $SQL = "SELECT agent FROM `users_folders` WHERE (user = '".$user."') AND (name = '" . $name . "')";
        $result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        $folderAgent = $row['agent'];
        
        if($folderAgent != ""){      
          $SQL3 = "SELECT firstname, lastname, e_mail FROM `Agent_Import` WHERE (id = '".$folderAgent."')";
          $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());
          $row3 = mysql_fetch_array($result3,MYSQL_ASSOC);
          $agent_firstname = $row3['firstname'];
          $agent_lastname = $row3['lastname'];
          $agent_email = $row3['e_mail'];
          
          $SQL5 = "SELECT first_name, last_name FROM `users` WHERE (email = '".$user."')";
          $res5 = mysql_query($SQL5) or die("Couldn't execute query." . mysql_error());
          $row5 = mysql_fetch_array($res5, MYSQL_ASSOC);
          $buyer_firstname = $row5['first_name'];
          $buyer_lastname = $row5['last_name'];
          
          $message = "Hello " . $agent_firstname . " " . $agent_lastname . ",";
          $message .= "<br><br>" . $buyer_firstname . " " . $buyer_lastname . " has saved a new listing to their folder: " . $name;
          $message .= "<br><br><br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
          $message .= "HomePik.com is licensed by Nice Idea Media";
      
          $mail->addAddress($agent_email);
          $mail->Subject = 'HomePik Buyer Folder Update';
          $mail->Body = $message;
          $mail->send();
        }
      }
      
      $SQL2 = "UPDATE	users_folders SET last_update='".$time."' where name='". $name ."' AND user = '".$user."'";
      $res2 = mysql_query($SQL2)  or die(mysql_error());
    }
  }
  
  print "Success";

}; // SAVE A LISTING END

// SAVE A LISTING
if(isset($_GET['agentSave'])){
  $list_num = $_GET['list_num'];
  $comments = $_GET['comments'];
  $buyers = $_GET['buyers'];  
  $agent_id = $_GET['agent_id'];
  $from = $_SESSION['email'];
  $time = date('U');

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  if($_SESSION['agent']){
    foreach($buyers as $buyer){	  
      if(strpos($buyer, "@bellmarc.com") !== false){
        //$sql = "SELECT * FROM queued_listings WHERE user = '" . $buyer . "' AND list_num = '" . $list_num . "' AND folder = '".$name."'";
        $sql = "SELECT * FROM queued_listings WHERE user = '" . $buyer . "' AND list_num = '" . $list_num . "'";
        $rs = mysql_query($sql);
        $num = mysql_num_rows($rs);
      
        if ($num < 1){
          //$SQL = "INSERT INTO queued_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$user."','".$list_num."','".$from."','".$comments."','".$role."','".$name."','".$time."')";
          $SQL = "INSERT INTO queued_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$buyer."','".$list_num."','".$from."','".$comments."','agent','','".$time."')";
          $res = mysql_query($SQL)  or die(mysql_error());
        }
        /*
        $SQL2 = "UPDATE	users_folders SET last_update='".$time."' where name='". $name ."'";
        $res2 = mysql_query($SQL2)  or die(mysql_error());
        */
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
          $SQL = "INSERT INTO saved_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `folder`, `time`) VALUES  ('".$buyer."','".$list_num."','".$from."','".$comments."','agent','".$folder."','".$time."')";
          $res = mysql_query($SQL)  or die(mysql_error());
          
          $SQL2 = "UPDATE	users_folders SET last_update='".$time."' WHERE (user ='". $buyer ."') AND (name='". $folder ."')";
          $res2 = mysql_query($SQL2)  or die(mysql_error());
          
          $SQL = "SELECT first_name, last_name, notifications FROM `users` WHERE (email = '".$buyer."')";
          $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
          $row = mysql_fetch_array($result,MYSQL_ASSOC);
          $buyer_firstname = $row['first_name'];
          $buyer_lastname = $row['last_name'];
          $notifications = $row['notifications'];
          
          if($notifications == 'all' || $notifications == 'folder'){
            $message = "Hello " . $buyer_firstname . " " . $buyer_lastname . ",";
            $message .= "<br><br>" . $agent_firstname . " " . $agent_lastname . " has saved a new listing to your folder: " . $folder;
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

if(isset($_GET['saveAndEmail'])){
  $list_num = $_GET['list_num'];
  $comments = $_GET['comments'];

  if(!$_GET['buyer']){
    $user = $_SESSION['email'];

    if ($_SESSION['agent']){
      $role = 'agent';
    } elseif ($_SESSION['user']){
      $role = 'user';
    }
  } else {
    $list_num = $_GET['list_num'];
    $comments = $_GET['comments'];
    $user = $_GET['buyer'];
    $role = 'user';
  }
  $from = $_SESSION['email'];
  $time = date('U');

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  if($_SESSION['agent']){
    $SQL = "SELECT firstname, lastname FROM `Agent_Import` where (e_mail = '".$_SESSION['email']."')";
    $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
    while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
      $firstname = $row['firstname'];
      $lastname = $row['lastname'];
    }

    $SQL = "SELECT first_name, last_name, notifications FROM `users` where (email = '".$user."')";
    $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
    while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
      $first_name = $row['first_name'];
      $last_name = $row['last_name'];
      $notifications = $row['notifications'];
    }

    if(strpos($user, "@bellmarc.com") === false){
      $sql = "SELECT * FROM saved_listings WHERE user = '" . $user . "' AND list_num = '" . $list_num . "' AND agent = '".$_SESSION['email']."'";
      $rs = mysql_query($sql);
      $num = mysql_num_rows($rs);

      if ($num >= 1)
      {
        $_SESSION['buyerSave'] = $user;
      }
      else
      {
        $SQL = "INSERT INTO saved_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `agent`, `time`) VALUES  ('".$user."','".$list_num."','".$from."','".$comments."','".$role."','".$_SESSION['email']."','".$time."')";
        $res = mysql_query($SQL)  or die(mysql_error());

        $_SESSION['buyerSave'] = $user;

        if($notifications == 'all' || $notifications == 'folder'){
          $message = "Hello $first_name $last_name, <br><br>";
          $message .= $firstname . " " . $lastname;
          $message .= " has added a listing to your folder. You will find the listing below: <br><br>";
          $message .= "Comments: ";
          $message .= $comments;
          $message .= "<br><br>";
          $message .= 'Listing Link: http://homepik.com/controllers/saved.php?user='. $user;
          $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
          $message .= "HomePik.com is licensed by Nice Idea Media";
          $message .= "<br><br><center><a href='http://www.homepik.com/controllers/change-email-alert-settings.php?user=".$user."'>Change Email Alert Settings</a></center><br>";
          
          $mail->addAddress($user);
          $mail->Subject = 'New HomePik Listing';
          $mail->Body = $message;        
          $mail->send();
        }
      }
    }

    if(strpos($user, "@bellmarc.com") !== false){

      $sql1 = "SELECT * FROM queued_listings WHERE user = '" . $from . "' AND list_num = '" . $list_num . "'";
      $rs1 = mysql_query($sql1);
      $num1 = mysql_num_rows($rs1);

      if($num1  >= 1){
        //Do nothing
      }
      else{
        $SQL = "INSERT INTO queued_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `time`) VALUES  ('".$from."','".$list_num."','".$from."','".$comments."','".$role."','".$time."')";
        $res = mysql_query($SQL)  or die(mysql_error());
      }

      $message = "Hello $firstname $lastname, <br><br>";
      $message .= "You added a listing to your folder. You will find the listing below: <br><br>";
      $message .= "Comments: ";
      $message .= $comments;
      $message .= "<br><br>";
      $message .= 'Listing Link: http://homepik.com/controllers/agent-listings.php?user='. $user;
      $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
      $message .= "HomePik.com is licensed by Nice Idea Media";

      $mail->addAddress($user);
      $mail->Subject = 'New HomePik Listing';
      $mail->Body = $message;      
      $mail->send();
    }

  }
  else{
    $sql = "SELECT * FROM saved_listings WHERE user = '" . $user . "' AND list_num = '" . $list_num . "'";
    $rs = mysql_query($sql);
    $num = mysql_num_rows($rs);

    if ($num >= 1)
    {
      //Do nothing
    }
    else
    {
      $SQL = "INSERT INTO saved_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `time`) VALUES  ('".$user."','".$list_num."','".$from."','".$comments."','".$role."','".$time."')";
      $res = mysql_query($SQL)  or die(mysql_error());
    }
  }

  print "Success";
};

// SAVE ALL LISTINGS IN AGENT FOLDER TO BUYER
if(isset($_GET['saveAll'])){
  $buyer = $_GET['buyer'];
  $agent = $_SESSION['email'];
  $time = date('U');
  $listings = "";

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  $SQL = "SELECT * FROM queued_listings WHERE user = '" . $agent . "'";
  $result = mysql_query($SQL) or die(mysql_error());

  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {

    $SQL3 = "SELECT * FROM saved_listings WHERE user = '" . $buyer . "' AND list_num = '" . $row['list_num'] . "'";
    $rs = mysql_query($SQL3);
    $num = mysql_num_rows($rs);

    if ($num >= 1)
    {
      //Do nothing
    }
    else
    {
      $SQL2 = "INSERT INTO saved_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `agent`, `time`) VALUES  ('".$buyer."','".$row['list_num']."','".$agent."','".$row['comments']."','agent','".$agent."','".$time."')";
      $res2 = mysql_query($SQL2) or die(mysql_error());
    }
  }

  $SQL4 = "SELECT firstname, lastname FROM `Agent_Import` where (e_mail = '".$agent."')";
  $result4 = mysql_query( $SQL4 ) or die("Couldn't execute query.".mysql_error());
  while($row = mysql_fetch_array($result4,MYSQL_ASSOC)) {
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
  }
  
  $message .= $firstname . " " . $lastname . " has added listings to your folder.";
  $message .= "<br><br><center><a href='http://www.homepik.com/controllers/change-email-alert-settings.php?user=".$buyer."'>Change Email Alert Settings</a></center><br>";

  $mail->addAddress($buyer);
  $mail->Subject = 'New HomePik Listing';
  $mail->Body = $message;  
  $mail->send();

}; // SAVE ALL LISTINGS IN AGENT FOLDER TO BUYER

//};

// MOVE LISTINGS FROM ONE FOLDER TO ANOTHER
if(isset($_GET['moveListings'])){
  $listings = $_GET['listing_nums'];
  $folders = $_GET['move_folders'];
  $folderEditing = $_GET['folder_editing'];
  if(isset($_GET['email'])){$user = $_GET['email']; }
  else{ $user = $_SESSION['email']; };
  $time = date('U');
  
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  
  foreach($listings as $listing){
    $SQL = "SELECT * FROM saved_listings WHERE (user = '".$user."') AND (list_num = '".$listing."')";
    $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $saved_by = $row['saved_by'];
    $comments = $row['comments'];
    $role = $row['role'];
    
    $SQL3 = "DELETE FROM `saved_listings` WHERE (user = '".$user."') AND (list_num = '".$listing."') AND (folder = '".$folderEditing."')";
    $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());
    
    foreach($folders as $folder){
      $SQL4 = "SELECT * FROM saved_listings WHERE (user = '".$user."') AND (list_num = '".$listing."') AND (folder = '".$folder."')";
      $rs = mysql_query($SQL4);
      $num = mysql_num_rows($rs);
      
      if($num < 1){
        $SQL2 = "INSERT INTO saved_listings(`user`, `list_num`, `saved_by`, `comments`, `folder`, `role`, `time`) VALUES ('".$user."','".$listing."','".$saved_by."','".$comments."','".$folder."','".$role."','".$time."')";
        $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query. ".mysql_error());
      }
    }	

  }
};
// END OF MOVE LISTINGS

// MOVE LISTINGS FROM ONE FOLDER TO ANOTHER
if(isset($_GET['moveAgentListings'])){
  $listings = $_GET['listing_nums'];
  $folders = $_GET['move_folders'];
  $folderEditing = $_GET['folder_editing'];
  $user = $_SESSION['email'];
  $time = date('U');
  
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  
  foreach($listings as $listing){
    $SQL = "SELECT * FROM queued_listings WHERE (user = '".$user."') AND (list_num = '".$listing."')";
    $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $saved_by = $row['saved_by'];
    $comments = $row['comments'];
    $role = $row['role'];
    
    $SQL3 = "DELETE FROM `queued_listings` WHERE (user = '".$user."') AND (list_num = '".$listing."') AND (folder = '".$folderEditing."')";
    $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());
    
    foreach($folders as $folder){
      $SQL4 = "SELECT * FROM queued_listings WHERE (user = '".$user."') AND (list_num = '".$listing."') AND (folder = '".$folder."')";
      $rs = mysql_query($SQL4);
      $num = mysql_num_rows($rs);
      
      if($num < 1){
        $SQL2 = "INSERT INTO queued_listings(`user`,`list_num`,`saved_by`,`comments`, `folder`, `role`, `time`) VALUES  ('".$user."','".$listing."','".$saved_by."','".$comments."','".$folder."','".$role."','".$time."')";
        $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
      }
    }
  }
};
// END OF MOVE LISTINGS

// SET FORMULA AND FOLDER PERMISSIONS FOR AN AGENT
if(isset($_GET['setPermissions'])){
  $formulas = $_GET['formulas'];
  $folders = $_GET['folders'];
  $agentID = $_GET['agent_id'];
  $user = $_SESSION['email'];
  $time = date('U');

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  
  foreach($formulas as $formula){
    $SQL = "SELECT * FROM Users_Search WHERE (email = '".$user."') AND (name = '".$formula."')";
    $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $agent = $row['agent'];
    if($agent == ""){
      $agent = $agentID;
    }
    else{
      $agent = $agent . ',' . $agentID;
    }
    
    $SQL2 = "UPDATE Users_Search SET `agent`='".$agent."' WHERE  (email = '".$user."') AND (name = '".$formula."')";
    $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
  }
      
  foreach($folders as $folder){
    $SQL3 = "SELECT * FROM users_folders WHERE (user = '".$user."') AND (name = '".$folder."')";
    $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());
    $row3 = mysql_fetch_array($result3,MYSQL_ASSOC);
    $agent = $row3['agent'];
    
    if($agent == ""){
      $agent = $agentID;
    }
    else{
      $agent = $agent . ',' . $agentID;
    }
    
    $SQL4 = "UPDATE users_folders SET `agent`='".$agent."' WHERE  (user = '".$user."') AND (name = '".$folder."')";
    $result4 = mysql_query( $SQL4 ) or die("Couldn't execute query.".mysql_error());
  }	

};
// END OF SET PERMISSIONS

// GET THE LIST_NUM OF ALL SAVED LISTINGS (BOTH SAVED TO USERS AND OPEN LISTINGS) SO WE CAN MARK THEM WITH A SAVED ICON IN SEARCH RESULTS
$sql = "(SELECT user, list_num FROM queued_listings WHERE saved_by='" . $_SESSION['email'] . "') UNION (SELECT user, list_num FROM saved_listings WHERE saved_by='" . $_SESSION['email'] . "')";
$rs = mysql_query($sql);
$arr_rows = array();
if(!empty($rs)){


    while ($row = mysql_fetch_array($rs)) {
    $saved_listings[] = $row;
    }
}else{
  $saved_listings = array();
}
$_SESSION['saved_listings'] = $saved_listings;
// SAVE END

// SEARCH - SAVE LOCATION
if(isset($_GET['saveLocation'])){
  $location = $_GET['locationGrade'];
  $_SESSION['LOCATION'] = $location;
}; // CONTACT END

// SAVE THE PREVIOUS PAGE
if(isset($_GET['setPreviousPage'])){
  $_SESSION['previousPage'] = $_GET['page'];
}; // PREVIOUS PAGE END

// GET THE PREVIOUS PAGE
if(isset($_GET['getPreviousPage'])){
  echo $_SESSION['previousPage'];
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
  if(isset($_SESSION['lastBuyerUsed'])){ echo json_encode($_SESSION['lastBuyerUsed']); }
  else{ echo json_encode(""); }
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

// CLEAR SAVED LISTINGs
if(isset($_GET['clear_saved'])){
  if(!$_GET['buyer']){
    $user = $_SESSION['email'];

    if ($_SESSION['agent']){
      $role = 'agent';
    } elseif ($_SESSION['user']){
      $role = 'user';
    }
  } else {
    $user = $_GET['buyer'];
    $role = 'user';
  }
  $agent = $_GET['agent'];
  $from = $_SESSION['email'];
  $time = date('U');

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  $SQL = "DELETE FROM saved_listings WHERE (user = '".$user."') and ((agent = '".$agent."') OR (agent = ''))";
  $res = mysql_query($SQL)  or die(mysql_error());
  print $SQL;
}; // SAVE END

// CLEAR ONE LISTING FROM OPEN WORK
if(isset($_GET['clear_one_queued'])){
  if(!$_GET['buyer']){
    $user = $_SESSION['email'];

    if ($_SESSION['agent']){
      $role = 'agent';
    } elseif ($_SESSION['user']){
      $role = 'user';
    }
  } else {
    $user = $_GET['buyer'];
    $role = 'user';
  }
  $from = $_SESSION['email'];
  $time = date('U');
  $delete_id = $_GET['delete_id'];

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  $SQL = "DELETE FROM queued_listings WHERE (user = '".$user."') AND (list_num = '".$delete_id."')";
  $res = mysql_query($SQL)  or die(mysql_error());
  print $SQL;
}; // DELETE END

// CLEAR ONE LISTING FROM BUYER FOLDER
if(isset($_GET['clear_one_saved'])){
  if(!$_GET['buyer']){
    $user = $_SESSION['email'];

    if ($_SESSION['agent']){
      $role = 'agent';
    } elseif ($_SESSION['user']){
      $role = 'user';
    }
  } else {
    $user = $_GET['buyer'];
    $role = 'user';
  }
  $agent = $_GET['agent'];
  $from = $_SESSION['email'];
  $time = date('U');
  $delete_id = $_GET['delete_id'];

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  $SQL = "DELETE FROM saved_listings WHERE (user = '".$user."') AND (list_num = '".$delete_id."') and ((agent = '".$agent."') OR (agent = ''))";
  $res = mysql_query($SQL)  or die(mysql_error());
  print $SQL;
}; // DELETE END

// CLEAR ONE LISTING FROM BUYER FOLDER
if(isset($_GET['clear_one_saved_from_folder'])){

  $user = $_GET['buyer'];
  $delete_id = $_GET['delete_id'];
  $folder = $_GET['folder'];

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  $SQL = "DELETE FROM saved_listings WHERE (user = '".$user."') AND (list_num = '".$delete_id."') AND  (folder = '".$folder."')";
  $res = mysql_query($SQL)  or die(mysql_error());

  if ($res == true) {
    $time = date('U');
    $SQL = "UPDATE users_folders SET last_update='".$time."' WHERE name='".$folder."' AND user = '".$user."'";
    $res = mysql_query($SQL)  or die(mysql_error());
  }
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

  $SQL1 = "SELECT folder FROM saved_listings WHERE (user = '".$email."') AND (list_num = '".$list_num."')";
  $result1 = mysql_query($SQL1)  or die(mysql_error());
  while($row1 = mysql_fetch_array($result1,MYSQL_ASSOC)){
    array_push($folders, $row1['folder']);
  }
  
  $SQL2 = "DELETE FROM saved_listings WHERE (user = '".$email."') AND (list_num = '".$list_num."')";
  $result2 = mysql_query($SQL2)  or die(mysql_error());

  if ($result2 == true) {
    foreach($folders as $folder){
      if($role == "buyer"){
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
      }
      
      $SQL6 = "UPDATE users_folders SET last_update='".date('U')."' WHERE (user='".$email."') AND (name='".$folder."')";
      $result6 = mysql_query($SQL6)  or die(mysql_error());
    }
  }	  
}; // DELETE END

// CLEAR ONE LISTING FROM BUYER FOLDER
if(isset($_GET['clear_multiple_saved_from_folder'])){

  $user = $_GET['buyer'];
  $delete_ids = $_GET['delete_ids'];
  $folder = $_GET['folder'];
  
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  
  foreach($delete_ids as $delete_id){
    $SQL = "DELETE FROM saved_listings WHERE (user = '".$user."') AND (list_num = '".$delete_id."') AND  (folder = '".$folder."')";
    $res = mysql_query($SQL)  or die(mysql_error());
    
    if ($res == true) {
      $time = date('U');
      $SQL = "UPDATE users_folders SET last_update='".$time."' WHERE (user = '".$user."') AND (name='".$folder."')";
      $res = mysql_query($SQL)  or die(mysql_error());
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
  $SQL = "DELETE FROM queued_listings WHERE (user = '".$user."') AND (list_num = '".$delete_id."') AND  (folder = '".$folder."')";
  $res = mysql_query($SQL)  or die(mysql_error());
    
  if ($res == true) {
    $time = date('U');
    $SQL = "UPDATE users_folders SET last_update='".$time."' WHERE name='".$folder."' AND user = '".$user."'";
    $res = mysql_query($SQL)  or die(mysql_error());
  }
}; // DELETE END

// CLEAR ONE LISTING FROM AGENT FOLDERS
if(isset($_GET['clear_one_queued_from_folders'])){

  $email = $_GET['agent'];
  $list_num = $_GET['delete_id'];
  $folders = array();
  
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

	
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
}; // DELETE END

// CLEAR ONE LISTING FROM BUYER FOLDER
if(isset($_GET['clear_multiple_queued_from_folder'])){

  $user = $_SESSION['email'];
  $delete_ids = $_GET['delete_ids'];
  $folder = $_GET['folder'];
  
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  
  foreach($delete_ids as $delete_id){
    $SQL = "DELETE FROM queued_listings WHERE (user = '".$user."') AND (list_num = '".$delete_id."') AND  (folder = '".$folder."')";
    $res = mysql_query($SQL)  or die(mysql_error());
    
    if ($res == true) {
      $time = date('U');
      $SQL = "UPDATE users_folders SET last_update='".$time."' WHERE (user = '".$user."') AND (name='".$folder."')";
      $res = mysql_query($SQL)  or die(mysql_error());
    }
  }
}; // DELETE END


//CLEAR QUEUED LISTING
if(isset($_GET['clear_queued'])){
  $list_num = $_GET['list_num'];

  if(!$_GET['buyer']){
    $user = $_SESSION['email'];

    if ($_SESSION['agent']){
      $role = 'agent';
    } elseif ($_SESSION['user']){
      $role = 'user';
    }
  } else {
    $user = $_GET['buyer'];
    $role = 'user';
  }
  $from = $_SESSION['email'];
  $time = date('U');

  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  $SQL = "DELETE FROM queued_listings WHERE (user = '".$user."')";
  $res = mysql_query($SQL)  or die(mysql_error());
  print $SQL;
}; //CLEAR QUEUED LISTING END

// ADD AGENT AS PRIMARY
if(isset($_GET['AddPrimary'])){
  $email = $_GET['email'];
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  $SQL = "SELECT firstname, lastname, id FROM `Agent_Import` WHERE (e_mail = '".$_SESSION['email']."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());

  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    $fn = $row['firstname'];
    $ln = $row['lastname'];
    $id = $row['id'];
  }

  $SQL2 = "SELECT first_name, last_name, notifications FROM `users` WHERE (email = '".$email."')";
  $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
  while($row2 = mysql_fetch_array($result2,MYSQL_ASSOC)) {
    $firstname = $row2['first_name'];
    $lastname = $row2['last_name'];
    $notifications = $row['notifications'];
  }

  $SQL3 = "UPDATE `users` SET `P_agent` = '".$id."' WHERE (email = '".$email."')";
  $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());

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
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  $SQL = "SELECT firstname, lastname, id FROM `Agent_Import` WHERE (e_mail = '".$_SESSION['email']."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());

  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    $fn = $row['firstname'];
    $ln = $row['lastname'];
    $id = $row['id'];
  }

  $SQL2 = "SELECT first_name, last_name, notifications FROM `users` WHERE (email = '".$email."')";
  $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
  while($row2 = mysql_fetch_array($result2,MYSQL_ASSOC)) {
    $firstname = $row2['first_name'];
    $lastname = $row2['last_name'];
    $notifications = $row['notifications'];
  }

  $SQL3 = "UPDATE `users` SET `P_agent2` = '".$id."' WHERE (email = '".$email."')";
  $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());

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

  $SQL = "SELECT * FROM `users` WHERE (email = '".$_GET['email']."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $num = mysql_num_rows($result);

  if($num == 0){

    $SQL = "SELECT firstname, lastname, id FROM `Agent_Import` WHERE (e_mail = '".$_SESSION['email']."')";
    $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());

    while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
      $fn = $row['firstname'];
      $ln = $row['lastname'];
      $id = $row['id'];
    }

    $to = $_GET['firstname'] . " " . $_GET['lastname'] . " <" . $_GET['email'] . ">";
    $email = $_GET['email'];
    $res =  mysql_query("INSERT INTO users (email, first_name, last_name, phone, rtime, pass_set, assigned, active, P_agent, notifications) VALUES('".$_GET['email']."','".$_GET['firstname']."','".$_GET['lastname']."','".$_GET['phone']."','".$registerTime."','".$registerTime."','".$_SESSION['email']."','2','".$id."', 'all') ON DUPLICATE KEY UPDATE assigned=VALUES(assigned), rtime=VALUES(rtime)");

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
    $message .= "HomePik.com is licensed to Bellmarc";

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

  $SQL = "SELECT * FROM `registered_agents` WHERE (email = '".$email."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $num = mysql_num_rows($result);

  if($num == 0){
    $password = generateRandomString();
    $pass = string_encrypt($password, $registerTime);
    $res =  mysql_query("INSERT INTO registered_agents(first_name, last_name, email, agent_id, password, active, rtime, pass_set) VALUES('".$_GET['firstname']."','".$_GET['lastname']."','".$_GET['email']."','".$_GET['agent_id']."','".$pass."','".$_GET['status']."','".$registerTime."','".$registerTime."') ON DUPLICATE KEY UPDATE rtime=VALUES(rtime)");
    
    $message = "Hello " . $_GET['firstname'] . " " . $_GET['lastname'] . ", <br/><br/>";
    $message .= "Your agent account with HomePik as been created. Below you will find your login information: <br/>";
    $message .= "Email / Username: $email <br/>";
    $message .= "Password: $password";
    $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
    //$message .= "HomePik.com is licensed to Bellmarc";

    $mail->addAddress($email);
    $mail->Subject = 'HomePik Agent Account Created';
    $mail->Body = $message;
    $mail->send();
    
    $SQL = "SELECT * FROM `users_folders` WHERE (user = '".$email."') AND (name = 'Agent Folder')";
    $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
    $num = mysql_num_rows($result);
  
    if($num == 0){
      mysql_query("INSERT INTO users_folders(`user`,`name`,`agent`,`last_update`) VALUES  ('".$email."','Agent Folder','','".date('U')."')");
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

if(isset($_GET['saveBuyer'])){
  $email = $_GET['email'];
  $_SESSION['buyerSave'] = $email;
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

  // Delete buyer's saved listings
  $res = mysql_query("DELETE FROM saved_listings WHERE (user = '".$_GET['buyer']."')")  or die(mysql_error());
  
  // Delete buyer's messages
  $res = mysql_query("DELETE FROM messages WHERE (buyer = '".$_GET['buyer']."')")  or die(mysql_error());

  // Delete buyer's saved formulas
  $res = mysql_query("DELETE FROM Users_Search WHERE (email = '".$_GET['buyer']."')")  or die(mysql_error());
  
  // Delete buyer's folders
  $res = mysql_query("DELETE FROM users_folders WHERE (user = '".$_GET['buyer']."')")  or die(mysql_error());

  // Delete buyer account
  $res =  mysql_query("DELETE FROM users WHERE (email = '".$_GET['buyer']."')")  or die(mysql_error());
}; // DELETE BUYER END

//MARK SAVED LISTINGS AS VIEWED
if(isset($_GET['savedViewed']) && $_GET['savedViewed']== 'true'){
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  
  if ($_SESSION['agent']){
    $res =  mysql_query("UPDATE saved_listings SET aviewed ='".$_SESSION['email']."' WHERE (id = '".$_GET['dataId']."')")  or die(mysql_error());
  } elseif ($_SESSION['user']){
    $res =  mysql_query("UPDATE saved_listings SET bviewed ='".$_SESSION['email']."' WHERE (id = '".$_GET['dataId']."')")  or die(mysql_error());
  }
}; //MARK SAVED LISTINGS AS VIEWED END

// ACTIVATE BUYER
if(isset($_GET['activateBuyer'])){
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());
  $res =  mysql_query("UPDATE users SET archived ='0' WHERE (email = '".$_GET['buyer']."')")  or die(mysql_error());
}; // ACTIVATE BUYER END

// LIST BUYERS
if (isset($_GET['listBuyers']) &&  $_GET['listBuyers'] == 'true'){
  $SQL = "SELECT * FROM `users` where (assigned = '".$_GET['agent']."' and archived != '1') ORDER BY last_name ASC";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());

  $options = array();
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    array_push($options,$row['email']);
  }

  echo implode(",",$options);
}; // LIST BUYERS END

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
?>
