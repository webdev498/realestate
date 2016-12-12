 #!/usr/bin/php -q
<?php
  include_once("dbconfig.php");
  include_once("emailconfig.php");
  $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
  $db = mysql_select_db('sp', $con) or die(mysql_error());

  //Listen to incoming e-mails
  $sock = fopen ("php://stdin", 'r');
  $email = '';
  
  //Read e-mail into buffer
  while (!feof($sock)){
    $email .= fread($sock, 1024);
  }
  
  //Close socket
  fclose($sock);

  //Parse "subject"
  $subject1 = explode ("\nSubject: ", $email);
  $subject2 = explode ("\n", $subject1[1]);
  $subject = $subject2[0];
  
  //Parse "to"
  $to1 = explode ("\nTo: ", $email);
  $to2 = explode ("\n", $to1[1]);
  $to = str_replace ('>', '', str_replace('<', '', $to2[0]));
  
  $message1 = explode ("\n\n", $email);
  
  $start = count ($message1) - 3;
  
  if ($start < 1){
    $start = 1;
  }
  
  //Parse "message"
  $message2 = explode ("\n\n", $message1[$start]);
  $message = $message2[0];
  
  //Parse "from"
  $from1 = explode ("\nFrom: ", $email);
  $from2 = explode ("\n", $from1[1]);
  
  if(strpos ($from2[0], '<') !== false){
    $from3 = explode ('<', $from2[0]);
    $from4 = explode ('>', $from3[1]);
    $from = $from4[0];
  }
  else{
    $from = $from2[0];
  }
  
  $SQL = "SELECT * FROM `email_redirect` WHERE ();"
  
?> 