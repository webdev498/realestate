<html>
<head>
<title>Create Buyer Formula Link</title>
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
  $SQL2 = "SELECT * FROM `Users_Search` WHERE email = '".$row["email"]."'";
  $result2 = mysql_query( $SQL2 ) or die("Couldn't execute query.".mysql_error());
  $num_formulas = mysql_num_rows($result2);
  
  //echo $row['first_name'] . " " . $row['last_name'] . " has $num_formulas <br/>";
  
  if($num_formulas == 1){
    if($row['P_agent'] != "" && $row['P_agent'] != null){
      $SQL3 = "UPDATE `Users_Search` SET `name`='Folder 1',`agent`='".$row['P_agent']."' WHERE email = '".$row['email']."'";
      $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());
    }
    else{
      $SQL3 = "UPDATE `Users_Search` SET `name`='Folder 1',`agent`='' WHERE email = '".$row['email']."'";
      $result3 = mysql_query( $SQL3 ) or die("Couldn't execute query.".mysql_error());
    }
  }
}

echo "Buyer formula link successfully\n<br />";

mysql_close($conn);
?>
</body>
</html>
