<?php
session_start();
include_once("dbconfig.php");
include_once('emailconfig.php');
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if(isset($_SESSION['buyer'])){ $email = $_SESSION['email']; }

if(isset($_POST['firstName'])){
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  
  $SQL = "UPDATE `users` SET first_name='".$firstName."', last_name='".$lastName."' WHERE (email = '".$email."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  
  $_SESSION['firstname'] = $firstName;
  $_SESSION['lastname'] = $lastName;
}

if(isset($_POST['phone'])){
  $phone = $_POST['phone'];
  
  $SQL = "UPDATE `users` SET phone='".$phone."' WHERE (email = '".$email."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  
  $_SESSION['phone'] = $phone;
}

if(isset($_POST['email'])){
  $oldEmail = $email;
  $newEmail = $_POST['email'];
  
  mysql_query("UPDATE `users` SET email='".$newEmail."' WHERE (email = '".$oldEmail."')" ) or die("Couldn't execute query.".mysql_error());
  mysql_query( "UPDATE `Users_Search` SET email='".$newEmail."' WHERE (email = '".$oldEmail."')" ) or die("Couldn't execute query.".mysql_error());
  mysql_query( "UPDATE `users_folders` SET user='".$newEmail."' WHERE (user = '".$oldEmail."')" ) or die("Couldn't execute query.".mysql_error());
  mysql_query( "UPDATE `saved_listings` SET user='".$newEmail."', saved_by='".$newEmail."'  WHERE (user = '".$oldEmail."') AND (saved_by = '".$oldEmail."')" ) or die("Couldn't execute query.".mysql_error());
  mysql_query( "UPDATE `saved_listings` SET user='".$newEmail."' WHERE (user = '".$oldEmail."')" ) or die("Couldn't execute query.".mysql_error());

  $_SESSION['email'] = $newEmail;
}

if(isset($_POST['code'])){
  $code = $_POST['code'];
  
  // Add agent to the account
  mysql_query( "UPDATE `users` SET P_agent='".$code."', P_agent_assign_time='".date('U')."' WHERE (email = '".$email."')" ) or die("Couldn't execute query.".mysql_error());
  
  // Assign agent to the buyer's folder
  mysql_query( "UPDATE users_folders SET agent = '".$code."' WHERE (user = '".$email."')" ) or die("Couldn't execute query.".mysql_error());
  
  // Get the folder information
  $result3 = mysql_query( "SELECT name FROM `users_folders` WHERE (user = '".$email."')" ) or die("Couldn't execute query.".mysql_error());
  $row3 = mysql_fetch_array($result3,MYSQL_ASSOC);
  $folder_name = $row3['name'];
  
  // Check if folder has formula associated with it.
  $result4 = mysql_query( "SELECT * FROM `Users_Search` WHERE (email = '".$email."') and (name = '".$folder_name."')" ) or die("Couldn't execute query.".mysql_error());
  $num_rows = mysql_num_rows($result4);
  
  // Check if formula exists if so assign agent to formula.
  if($num_rows > 0){
    mysql_query( "UPDATE Users_Search SET agent = '".$code."' WHERE (email = '".$email."') and (name = '".$folder_name."')" ) or die("Couldn't execute query.".mysql_error());
  }  
  
  // Get agent's information
  $result6 = mysql_query( "SELECT first_name, last_name, phone, email, agent_id FROM `registered_agents` WHERE (agent_id = '".$code."')" ) or die("Couldn't execute query.".mysql_error());
  $row6 = mysql_fetch_array($result6,MYSQL_ASSOC);
  $agent = $row6['email'];
  $afirstname = $row6['first_name'];
  $alastname = $row6['last_name'];
  
  // Get buyer's information
  $result7 = mysql_query( "SELECT first_name, last_name FROM `users` WHERE (email='".$email."')" ) or die("Couldn't execute query.".mysql_error());
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
  
  $_SESSION['agent1'] = $code;
  
  echo json_encode($row6);
}

if(isset($_POST['code2'])){
  $code = $_POST['code2'];
  
  // Get buyer's information
  $result1 = mysql_query( "SELECT first_name, last_name FROM `users` WHERE (email='".$email."')" ) or die("Couldn't execute query.".mysql_error());
  $row1 = mysql_fetch_array($result1,MYSQL_ASSOC);
  $firstname = $row1['first_name'];
  $lastname = $row1['last_name'];
  
  // Add second agent to the acccount
  mysql_query( "UPDATE `users` SET P_agent2='".$code."', P_agent2_assign_time='".date('U')."' WHERE (email = '".$email."')" ) or die("Couldn't execute query.".mysql_error());
    
  // Add the folder
  mysql_query( "INSERT INTO users_folders(`user`,`name`,`agent`,`last_update`) VALUES  ('".$email."','Folder 2','".$code."','".date('U')."')" ) or die("Couldn't execute query.".mysql_error());
  
  // Get the agent's information
  $result6 = mysql_query( "SELECT first_name, last_name, phone, email, agent_id FROM `registered_agents` WHERE (agent_id = '".$code."')" ) or die("Couldn't execute query.".mysql_error());
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
  
  $_SESSION['agent2'] = $code;
  
  echo json_encode($row6);
  
}

if(isset($_POST['delete'])){
  // Get buyer's information
  $result = mysql_query( "SELECT CONCAT(first_name, ' ' ,last_name) as name, P_agent, P_agent2 FROM `users` WHERE (email = '".$email."')" ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);  
  $buyer_name = $row['name'];
  $id = $row['P_agent'];
  $id2 = $row['P_agent2'];
  
  // Get the first agent's information
  $result2 = mysql_query( "SELECT CONCAT(first_name, ' ' ,last_name) as name, email FROM `registered_agents` WHERE (agent_id = '".$id."')" ) or die("Couldn't execute query.".mysql_error());
  $row2 = mysql_fetch_array($result2,MYSQL_ASSOC);  
  $agent = $row2['email'];
  $agent_name = $row2['name'];
  
  // Get folder associated with first agent
  $result3 = mysql_query( "SELECT name FROM `users_folders` WHERE (user = '".$email."') AND (agent = '".$id."')" ) or die("Couldn't execute query.".mysql_error());
  while($row3 = mysql_fetch_array($result3,MYSQL_ASSOC)){
    $name = $row3['name'];
  
    // Check if buyer has second agent
    if($id2 == "" || $id2 == null){
      // buyer only has one agent associated with their account
      // Remove the agent from the listings
      mysql_query( "UPDATE `saved_listings` SET agent='' WHERE (user = '".$email."') AND (agent = '".$agent."')" ) or die("Couldn't execute query.".mysql_error());
      
      // Remove the agent from the formula associated with the folder
      mysql_query( "UPDATE `Users_Search` SET agent='' WHERE (email = '".$email."') AND (name = '".$name."')" ) or die("Couldn't execute query.".mysql_error()); 
  
      // Remove the agent from the folder
      mysql_query( "UPDATE `users_folders` SET agent='' WHERE (user = '".$email."') AND (name = '".$name."') AND (agent = '".$id."')" ) or die("Couldn't execute query.".mysql_error());
      
      // Remove the first agent from the buyer's account
      mysql_query( "UPDATE `users` SET P_agent='', P_agent_assign_time=0 WHERE (email = '".$email."')" ) or die("Couldn't execute query.".mysql_error());
      
      $_SESSION['agent1'] = '';
    }
    else{
      // buyer has a second agent associated with their account
      // Delete the listings inside that folder.
      mysql_query( "DELETE FROM `saved_listings` WHERE (user = '".$email."') AND (folder = '".$name."')" )  or die(mysql_error());
      
      // Delete the buying formula associated with the folder.
      mysql_query( "DELETE FROM `Users_Search` WHERE (email = '".$email."') AND (name = '".$name."')" )  or die(mysql_error());
    
      // Delete the folder
      mysql_query( "DELETE FROM `users_folders` WHERE (user = '".$email."') AND (name = '".$name."') AND (agent = '".$id."')" ) or die("Couldn't execute query.".mysql_error());
      
      // Remove the first agent from the buyer's account and replace with the second agent
      $result = mysql_query( "UPDATE `users` set P_agent=P_agent2, P_agent_assign_time=P_agent2_assign_time, P_agent2='', P_agent2_assign_time=0 WHERE (email = '".$email."')" ) or die("Couldn't execute query.".mysql_error());
      
      // Move all listings with second agent from Folder 2 to Folder 1
      mysql_query( "UPDATE `saved_listings` SET folder='Folder 1' WHERE (user = '".$email."') AND (agent = '".$id2."')" ) or die("Couldn't execute query.".mysql_error());
      
      // Rename formula with second agent from Folder 2 to Folder 1
      mysql_query( "UPDATE `Users_Search` SET name='Folder 1' WHERE (email = '".$email."') AND (agent = '".$id2."')" ) or die("Couldn't execute query.".mysql_error()); 
  
      // Rename folder with second agent from Folder 2 to Folder 1
      mysql_query( "UPDATE `users_folders` SET name='Folder 1' WHERE (user = '".$email."') AND (agent = '".$id2."')" ) or die("Couldn't execute query.".mysql_error());
  
      $_SESSION['agent1'] = $_SESSION['agent2'];
      $_SESSION['agent2'] = '';
    }
  }

  // Send email to first agent that they have been removed from the account.
  $message = "Hello ". $agent_name . ",<br><br>";
  $message .= $buyer_name . " has removed you as their primary agent.\n\n ";
  $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
  $message .= "HomePik.com is licensed by Nice Idea Media";
  
  $mail->addAddress($agent);
  $mail->Subject = 'HomePik.com Lost Buyer';
  $mail->Body = $message;
  $mail->send();
}

if(isset($_POST['delete2'])){
  //Get user information and agent 2 id
  $result = mysql_query( "SELECT CONCAT(first_name, ' ' ,last_name) as name, P_agent2 FROM `users` WHERE (email = '".$email."')" ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $buyer_name = $row['name'];
  $id = $row['P_agent2'];
  
  // Get agent 2's information
  $result2 = mysql_query( "SELECT CONCAT(first_name, ' ' ,last_name) as name, email FROM `registered_agents` WHERE (agent_id = '".$id."')" ) or die("Couldn't execute query.".mysql_error());
  $row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
  $agent = $row2['email'];
  $agent_name = $row2['name'];
  
  // Get the folder associated with that agent.
  $result3 = mysql_query( "SELECT name, agent FROM `users_folders` WHERE (user = '".$email."') AND (agent LIKE '%".$id."%')" ) or die("Couldn't execute query.".mysql_error());
  while($row3 = mysql_fetch_array($result3,MYSQL_ASSOC)){
    $name = $row3['name'];
    $agents = $row3['agent'];
    
    // Delete the listings inside that folder.
    mysql_query( "DELETE FROM `saved_listings` WHERE (user = '".$email."') AND (folder = '".$name."')" )  or die(mysql_error());
    
    // Delete the buying formula associated with the folder.
    mysql_query( "DELETE FROM `Users_Search` WHERE (email = '".$email."') AND (name = '".$name."')" )  or die(mysql_error());
  
    // Delete the folder
    mysql_query( "DELETE FROM `users_folders` WHERE (user = '".$email."') AND (name = '".$name."') AND (agent LIKE '%".$id."%')" ) or die("Couldn't execute query.".mysql_error());
  }
   
  // Remove the agent from the buyer's account
  mysql_query( "UPDATE `users` SET P_agent2='', P_agent2_assign_time=0 WHERE (email = '".$email."')" ) or die("Couldn't execute query.".mysql_error());
  
  // Send email to agent agent that they have been removed from account.
  $message = "Hello ". $agent_name . ",<br><br>";
  $message .= $buyer_name . " has removed you as their primary agent.\n\n ";
  $message .= "<br><br>&copy; Nice Idea Media  All Rights Reserved<br>";
  $message .= "HomePik.com is licensed by Nice Idea Media";
  
  $mail->addAddress($agent);
  $mail->Subject = 'HomePik.com Lost Buyer';
  $mail->Body = $message;
  $mail->send();
  
  $_SESSION['agent2'] = ''; 
}
?>