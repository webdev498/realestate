<html>
<head>
<title>Create Agent Folder</title>
</head>
<body>
<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '$abc456g8';
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully<br />';

mysql_select_db( 'sp' );

$SQL = "SELECT * FROM `Agent_Import` WHERE title != '' AND  title NOT LIKE '%NON-Agent%' AND title != 'Property Management'";
$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
  $hasFolder = false;
  $SQL2 = "SELECT * FROM `users_folders` WHERE user = '".$row["e_mail"]."'";
  $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
  while($row2 = mysql_fetch_array($result2,MYSQL_ASSOC)) {
    if($row2['name'] == "Agent Folder"){
      $hasFolder = true;
    }
  }
  
  if($hasFolder == false){
    $SQL3 = "INSERT INTO users_folders(`user`,`name`,`agent`,`last_update`) VALUES  ('".$row['e_mail']."','Agent Folder','','".date('U')."')";
    $result3 = mysql_query( $SQL3 ) or die(mysql_error());
  }
}

echo "Agent folder creation successfully\n<br />";

mysql_close($conn);
?>
</body>
</html>
