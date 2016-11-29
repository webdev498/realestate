<?php
session_start();
include("dbconfig.php");
include('emailconfig.php');

// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if ($_SESSION['user']){
  $user = $_SESSION['id'];
  $role = 'user';
  $email = $_SESSION['email'];
}

if($_POST['firstName']){
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  
  $SQL = "UPDATE `users` SET first_name='".$firstName."', last_name='".$lastName."' WHERE (email = '".$email."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
}

if($_POST['phone']){
  $phone = $_POST['phone'];
  
  $SQL = "UPDATE `users` SET phone='".$phone."' WHERE (email = '".$email."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
}

if($_POST['email']){
  $oldEmail = $email;
  $newEmail = $_POST['email'];
  
  $SQL = "UPDATE `users` SET email='".$newEmail."' WHERE (email = '".$oldEmail."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  
  $SQL = "UPDATE `Users_Search` SET email='".$newEmail."' WHERE (email = '".$oldEmail."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  
  $SQL = "UPDATE `users_folders` SET user='".$newEmail."' WHERE (user = '".$oldEmail."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  
  $SQL = "UPDATE `saved_listings` SET user='".$newEmail."', saved_by='".$newEmail."'  WHERE (user = '".$oldEmail."') AND (saved_by = '".$oldEmail."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());

  $SQL = "UPDATE `saved_listings` SET user='".$newEmail."' WHERE (user = '".$oldEmail."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());

  $_SESSION['email'] = $newEmail;
}

if($_POST['code']){
  $code = $_POST['code'];
  
  // Add agent to the account
  $SQL = "UPDATE `users` SET P_agent='".$code."', P_agent_assign_time='".date('U')."' WHERE (email = '".$email."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  
  // Assign agent to the buyer's folder
  $SQL2 = "UPDATE users_folders SET agent = '".$code."' WHERE (user = '".$email."')";
  $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
  
  // Get the folder information
  $SQL3 = "SELECT name FROM `users_folders` WHERE (user = '".$email."')";
  $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());
  $row3 = mysql_fetch_array($result3,MYSQL_ASSOC);
  $folder_name = $row3['name'];
  
  // Check if folder has formula associated with it.
  $SQL4 = "SELECT * FROM `Users_Search` WHERE (email = '".$email."') and (name = '".$folder_name."')";
  $result4 = mysql_query( $SQL4 ) or die("Couldn't execute query.".mysql_error());
  $num_rows = mysql_num_rows($result4);
  
  // Check if formula exists if so assign agent to formula.
  if($num_rows > 0){
    $SQL5 = "UPDATE Users_Search SET agent = '".$code."' WHERE (email = '".$email."') and (name = '".$folder_name."')";
    $result5 = mysql_query( $SQL5 ) or die("Couldn't execute query.".mysql_error());
  }  
  
  // Get agent's information
  $SQL6 = "SELECT r.first_name, r.last_name, r.phone, r.email, r.agent_id, u.P_agent FROM `registered_agents` r, `users` u WHERE (u.email='".$email."') AND (r.agent_id = u.P_agent)";
  $result6 = mysql_query( $SQL6 ) or die("Couldn't execute query.".mysql_error());
  $row6 = mysql_fetch_array($result6,MYSQL_ASSOC);
  $agent = $row6['email'];
  $afirstname = $row6['first_name'];
  $alastname = $row6['last_name'];
  
  // Get buyer's information
  $SQL7 = "SELECT first_name, last_name FROM `users` WHERE (email='".$email."')";
  $result7 = mysql_query( $SQL7 ) or die("Couldn't execute query.".mysql_error());
  $row7 = mysql_fetch_array($result7,MYSQL_ASSOC);
  $firstname = $row7['first_name'];
  $lastname = $row7['last_name'];
  
  // Send email to agent they have been added to buyer account.
  $message = "Hello " . $afirstname . " " . $alastname . ",<br><br>";
  $message .= "A new buyer has made you their primary agent. Their information is below: 
      <br><br>
      First Name: " . $firstname . "<br>
      Last Name: " . $lastname . " <br>
      Email: " . $email . "\n\n ";
  $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
  $message .= "HomePik.com is licensed by Nice Idea Media";
  
  $mail->addAddress($agent);
  $mail->Subject = 'HomePik.com New Buyer';
  $mail->Body = $message;
  $mail->send();
  
  echo json_encode($row6);
}

if($_POST['code2']){
  $code = $_POST['code2'];
  
  // Get buyer's information
  $SQL1 = "SELECT first_name, last_name FROM `users` WHERE (email='".$email."')";
  $result1 = mysql_query( $SQL1 ) or die("Couldn't execute query.".mysql_error());
  $row1 = mysql_fetch_array($result1,MYSQL_ASSOC);
  $firstname = $row1['first_name'];
  $lastname = $row1['last_name'];
  
  // Add second agent to the acccount
  $SQL2= "UPDATE `users` SET P_agent2='".$code."', P_agent2_assign_time='".date('U')."' WHERE (email = '".$email."')";
  $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
    
  // Add the folder
  $SQL5 = "INSERT INTO users_folders(`user`,`name`,`agent`,`last_update`) VALUES  ('".$email."','Folder 2','".$code."','".date('U')."')";
  $result5 = mysql_query( $SQL5 ) or die("Couldn't execute query.".mysql_error());
  
  // Get the agent's information
  $SQL6 = "SELECT r.first_name, r.last_name, r.phone, r.email, r.agent_id, u.P_agent2 FROM `registered_agents` r, `users` u WHERE (u.email='".$email."') AND (r.agent_id = u.P_agent2)";
  $result6 = mysql_query( $SQL6 ) or die("Couldn't execute query.".mysql_error());
  $row6 = mysql_fetch_array($result6,MYSQL_ASSOC);
  $agent = $row6['email'];
  $afirstname = $row6['first_name'];
  $alastname = $row6['last_name'];
  
  // Send the agent an email they have been added to the account.
  $message = "Hello " . $afirstname . " " . $alastname . ",<br><br>";
  $message .= "A new buyer has made you their primary agent. Their information is below: 
      <br><br>
      First Name: " . $firstname . "<br>
      Last Name: " . $lastname . " <br>
      Email: " . $email . "\n\n ";;
  $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
  $message .= "HomePik.com is licensed by Nice Idea Media";
  
  $mail->addAddress($agent);
  $mail->Subject = 'HomePik.com New Buyer';
  $mail->Body = $message;
  $mail->send();
  
  echo json_encode($row6);
  
}

if($_POST['delete']){
  // Get buyer's information
  $SQL = "SELECT first_name, last_name, P_agent, P_agent2 FROM `users` WHERE (email = '".$email."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);  
  $firstname = $row['first_name'];
  $lastname = $row['last_name'];
  $id = $row['P_agent'];
  $id2 = $row['P_agent2'];
  
  // Get the first agent's information
  $SQL2 = "SELECT first_name, last_name, email FROM `registered_agents` WHERE (agent_id = '".$id."')";
  $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
  $row2 = mysql_fetch_array($result2,MYSQL_ASSOC);  
  $agent = $row2['email'];
  $afirstname = $row2['first_name'];
  $alastname = $row2['last_name'];
  
  // Get folder associated with first agent
  $SQL3 = "SELECT name, agent FROM `users_folders` WHERE (user = '".$email."') AND (agent LIKE '%".$id."%')";
  $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());
  while($row3 = mysql_fetch_array($result3,MYSQL_ASSOC)){
    $name = $row3['name'];
    $agents = $row3['agent'];
  
    // Check if buyer has second agent
    if($id2 == ""){
      // buyer only has one agent associated with their account
      // Remove the agent from the listings
      $SQL4 = "UPDATE `saved_listings` SET agent='' WHERE (user = '".$email."') AND (agent = '".$agent."')";
      $result4 = mysql_query( $SQL4 ) or die("Couldn't execute query.".mysql_error());
      
      // Remove the agent from the formula associated with the folder
      $SQL5 = "UPDATE `Users_Search` SET agent='' WHERE (email = '".$email."') AND (name = '".$name."')";
      $result5 = mysql_query( $SQL5 ) or die("Couldn't execute query.".mysql_error()); 
  
      // Remove the agent from the folder
      $SQL6 = "UPDATE `users_folders` SET agent='' WHERE (user = '".$email."') AND (name = '".$name."') AND (agent LIKE '%".$id."%')";
      $result6 = mysql_query( $SQL6 ) or die("Couldn't execute query.".mysql_error()); 
    }
    else{
      // buyer has a second agent associated with their account
      // Delete the listings inside that folder.
      $SQL4 = "DELETE FROM `saved_listings` WHERE (user = '".$email."') AND (folder = '".$name."')";
      $result4 = mysql_query($SQL4)  or die(mysql_error());
      
      // Delete the buying formula associated with the folder.
      $SQL5 = "DELETE FROM `Users_Search` WHERE (email = '".$email."') AND (name = '".$name."')";
      $result5 = mysql_query($SQL5)  or die(mysql_error());
    
      // Delete the folder
      $SQL6 = "DELETE FROM `users_folders` WHERE (user = '".$email."') AND (name = '".$name."') AND (agent LIKE '%".$id."%')";
      $result6 = mysql_query( $SQL6 ) or die("Couldn't execute query.".mysql_error()); 
    }
  }
  
  // Remove the first agent from the buyer's account
  $SQL7 = "UPDATE `users` SET P_agent='', P_agent_assign_time=0 WHERE (email = '".$email."')";
  $result7 = mysql_query( $SQL7 ) or die("Couldn't execute query.".mysql_error());

  // Send email to first agent that they have been removed from the account.
  $message = "Hello ". $afirstname . " " . $alastname . ",<br><br>";
  $message .= $firstname ." ". $lastname . " has removed you as their primary agent.\n\n ";
  $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
  $message .= "HomePik.com is licensed by Nice Idea Media";
  
  $mail->addAddress($agent);
  $mail->Subject = 'HomePik.com Lost Buyer';
  $mail->Body = $message;
  $mail->send();
}

if($_POST['delete2']){
  //Get user information and agent 2 id
  $SQL = "SELECT first_name, last_name, P_agent2 FROM `users` WHERE (email = '".$email."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $firstname = $row['first_name'];
  $lastname = $row['last_name'];
  $id = $row['P_agent2'];
  
  // Get agent 2's information
  $SQL2 = "SELECT first_name, last_name, email FROM `registered_agents` WHERE (agent_id = '".$id."')";
  $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
  $row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
  $agent = $row2['email'];
  $afirstname = $row2['first_name'];
  $alastname = $row2['last_name'];
  
  // Get the folder associated with that agent.
  $SQL3 = "SELECT name, agent FROM `users_folders` WHERE (user = '".$email."') AND (agent LIKE '%".$id."%')";
  $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());
  while($row3 = mysql_fetch_array($result3,MYSQL_ASSOC)){
    $name = $row3['name'];
    $agents = $row3['agent'];
    
    // Delete the listings inside that folder.
    $SQL4 = "DELETE FROM `saved_listings` WHERE (user = '".$email."') AND (folder = '".$name."')";
    $result4 = mysql_query($SQL4)  or die(mysql_error());
    
    // Delete the buying formula associated with the folder.
    $SQL5 = "DELETE FROM `Users_Search` WHERE (email = '".$email."') AND (name = '".$name."')";
    $result5 = mysql_query($SQL5)  or die(mysql_error());
  
    // Delete the folder
    $SQL6 = "DELETE FROM `users_folders` WHERE (user = '".$email."') AND (name = '".$name."') AND (agent LIKE '%".$id."%')";
    $result6 = mysql_query( $SQL6 ) or die("Couldn't execute query.".mysql_error());
  }
   
  // Remove the agent from the buyer's account
  $SQL7 = "UPDATE `users` SET P_agent2='', P_agent2_assign_time=0 WHERE (email = '".$email."')";
  $result7 = mysql_query( $SQL7 ) or die("Couldn't execute query.".mysql_error());
  
  // Send email to agent agent that they have been removed from account.
  $message = "Hello ". $afirstname . " " . $alastname . ",<br><br>";
  $message .= $firstname ." ". $lastname . " has removed you as their primary agent.\n\n ";
  $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
  $message .= "HomePik.com is licensed by Nice Idea Media";
  
  $mail->addAddress($agent);
  $mail->Subject = 'HomePik.com Lost Buyer';
  $mail->Body = $message;
  $mail->send();
}

if($_POST['update']){
  $SQL = "UPDATE users set P_agent = P_agent2, P_agent_assign_time=P_agent2_assign_time, P_agent2 = '', P_agent2_assing_time=0 WHERE (email = '".$email."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
}

if($_POST['updateListings']){
  $SQL = "SELECT r.email FROM `registered_agents` r, `users` u WHERE (u.email = '".$email."') AND (r.agent_id = u.P_agent)";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $P_agent = $row['email'];
  
  $SQL = "SELECT r.email FROM `registered_agents` r, `users` u WHERE (u.email = '".$email."') AND (r.agent_id = u.P_agent2)";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $P_agent2 = $row['email'];
  
  $SQL = "SELECT * FROM `saved_listings` WHERE (user = '".$email."') AND (agent = '')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
    $sql = "SELECT * FROM saved_listings WHERE (user = '" . $email . "') AND (list_num = '" . $row['list_num'] . "') AND (agent = '".$P_agent."')";
    $rs = mysql_query($sql);
    $num = mysql_num_rows($rs);
    
    if($num < 1){
      $SQL1 = "INSERT INTO saved_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `agent`, `time`) VALUES  ('".$email."','".$row['list_num']."','".$row['from']."','".$row['comments']."','".$row['role']."','".$P_agent."','".$row['time']."')";
      $res = mysql_query($SQL1)  or die(mysql_error());
    }
    
    $sql = "SELECT * FROM saved_listings WHERE (user = '" . $email . "') AND (list_num = '" . $row['list_num'] . "') AND (agent = '".$P_agent2."')";
    $rs = mysql_query($sql);
    $num = mysql_num_rows($rs);
    
    if($num < 1){
      $SQL2 = "INSERT INTO saved_listings(`user`,`list_num`,`saved_by`,`comments`, `role`, `agent`, `time`) VALUES  ('".$email."','".$row['list_num']."','".$row['from']."','".$row['comments']."','".$row['role']."','".$P_agent2."','".$row['time']."')";
      $res = mysql_query($SQL2)  or die(mysql_error());
    }
  }
  
  $SQL = "DELETE FROM saved_listings WHERE (user ='" . $email . "') AND (agent='')";
  $res = mysql_query($SQL)  or die(mysql_error());
}

?>