<html>
<head>
<title>Import Listings</title>
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
mysql_query("TRUNCATE TABLE rental") or die ("MySQL - Query Error - " . MySQL_Error());
mysql_query("LOAD DATA INFILE '/home/webadmin/homepik.com/html/rentals-one_table.csv' INTO TABLE rental fields terminated by ';' enclosed by '\"' LINES TERMINATED BY '\r\r\n'  IGNORE 1 LINES;") or die ("MySQL - Query Error - " . MySQL_Error());

echo "Imported rental-one successfully\n<br />";

mysql_query("LOAD DATA INFILE '/home/webadmin/homepik.com/html/rentals-studio_table.csv' INTO TABLE rental fields terminated by ';' enclosed by '\"' LINES TERMINATED BY '\r\r\n'  IGNORE 1 LINES;") or die ("MySQL - Query Error - " . MySQL_Error());

echo "Imported rental-studio successfully\n<br />";

mysql_query("LOAD DATA INFILE '/home/webadmin/homepik.com/html/rentals-other_table.csv' INTO TABLE rental fields terminated by ';' enclosed by '\"' LINES TERMINATED BY '\r\r\n'  IGNORE 1 LINES;") or die ("MySQL - Query Error - " . MySQL_Error());

echo "Imported rental-other successfully\n<br />";

mysql_close($conn);
?>
</body>
</html>
