<html>
<head>
<title>Update Buyer Folder Name</title>
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
  if(($row['P_agent'] == '' || $row['P_agent'] == null) && ($row['P_agent2'] == '' || $row['P_agent2'] == null)){
    mysql_query("INSERT INTO users_folders(`user`,`name`,`agent`,`last_update`) VALUES ('".$row['email']."','Folder 1','','".date('U')."')");
  }
  else{
    if($row['P_agent'] != '' && $row['P_agent'] != null){
      mysql_query("INSERT INTO users_folders(`user`,`name`,`agent`,`last_update`) VALUES ('".$row['email']."','Folder 1','".$row['P_agent']."','".date('U')."')");
    }
    
    if($row['P_agent2'] != '' && $row['P_agent2'] != null){
      mysql_query("INSERT INTO users_folders(`user`,`name`,`agent`,`last_update`) VALUES ('".$row['email']."','Folder 2','".$row['P_agent2']."','".date('U')."')");
    }
  }
  //$SQL2 = "SELECT * FROM `users_folders` WHERE user = '".$row["email"]."'";
  //$result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
  //while($row2 = mysql_fetch_array($result2,MYSQL_ASSOC)) {
  //  if($row2['name'] == $row['last_name']){
  //    $SQL3 = "UPDATE `users_folders` SET `name`='Folder 1' WHERE user = '".$row['email']."'";
  //    $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());
  //    
  //    $SQL4 = "UPDATE `saved_listings` SET `folder`='Folder 1' WHERE user = '".$row['email']."'";
  //    $result4 = mysql_query( $SQL4 ) or die("Couldn't execute query.".mysql_error());
  //  }
  //  else if($row2['name'] == ($row['last_name']." (2)")){
  //    $SQL3 = "UPDATE `users_folders` SET `name`='Folder 2' WHERE user = '".$row['email']."'";
  //    $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());
  //    
  //    $SQL4 = "UPDATE `saved_listings` SET `folder`='Folder 2' WHERE user = '".$row['email']."'";
  //    $result4 = mysql_query( $SQL4 ) or die("Couldn't execute query.".mysql_error());
  //  }
  //}
}

echo "Buyer folder name update successfully\n<br />";

mysql_close($conn);
?>
</body>
</html>
