<html>
<head>
<title>Create Buyer Folder</title>
</head>
<body>
<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '$abc456g8';
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
if(! $conn ){ die('Could not connect: ' . mysql_error()); }
echo 'Connected successfully<br />';

mysql_select_db( 'sp' );

$SQL = "SELECT * FROM `users` WHERE (first_name  != '') AND (last_name != '') ORDER BY last_name ASC";
$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
  $hasFolder = false;
  $SQL2 = "SELECT * FROM `users_folders` WHERE user = '".$row["email"]."'";
  $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
  while($row2 = mysql_fetch_array($result2,MYSQL_ASSOC)) {
    $hasFolder = true;
    break;
  }
  
  if($hasFolder == false){
    if(($row['P_agent'] == "" || $row['P_agent'] == null) && ($row['P_agent2'] == "" || $row['P_agent2'] == null)){
      //echo $row["first_name"] . " " . $row["last_name"] . " NO AGENTS<br />";
      $SQL3 = "INSERT INTO users_folders(`user`,`name`,`agent`,`last_update`) VALUES  ('".$row['email']."','Folder 1','','".date('U')."')";
      $result3 = mysql_query( $SQL3 ) or die(mysql_error());
    }
    if($row['P_agent'] != "" && $row['P_agent'] != null){
      //echo $row["first_name"] . " " . $row["last_name"] . " FIRST AGENT<br />";
      $SQL4 = "INSERT INTO users_folders(`user`,`name`,`agent`,`last_update`) VALUES  ('".$row['email']."','Folder 1','".$row['P_agent']."','".date('U')."')";
      $result4 = mysql_query( $SQL4 ) or die(mysql_error());
    }
    if($row['P_agent2'] != "" && $row['P_agent2'] != null){
      //echo $row["first_name"] . " " . $row["last_name"] . " SECOND AGENT<br />";
      $SQL5 = "INSERT INTO users_folders(`user`,`name`,`agent`,`last_update`) VALUES  ('".$row['email']."','Folder 2','".$row['P_agent2']."','".date('U')."')";
      $result5 = mysql_query( $SQL5 ) or die(mysql_error());
    }
  }
}

echo "Buyer folder creation successfully\n<br />";

mysql_close($conn);
?>
</body>
</html>
