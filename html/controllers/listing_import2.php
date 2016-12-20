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
$currentDate = date("m-d-Y");

mysql_select_db( 'sp' );

mysql_query("TRUNCATE TABLE vow_upload") or die ("MySQL - Query Error - " . MySQL_Error());
mysql_query("LOAD DATA INFILE '/var/www/webadmin/data/www/homepik.com/html/VOW_data.TXT' INTO TABLE vow_upload FIELDS TERMINATED BY '|' LINES TERMINATED BY '\r\n' ;") or die ("MySQL - Query Error - " . MySQL_Error());
$result = mysql_query("SELECT * FROM vow_upload");
while ($row = mysql_fetch_array($result)){
  $list_numb = $row['list_numb'];

  $result2 = mysql_query("SELECT EXISTS(SELECT 'list_numb' FROM vow_data WHERE (list_numb = '" . $list_numb . "')) AS num") or die (MySQL_Error());
  $row2 = mysql_fetch_array($result2);

  if($row2['num'] == '0'){ mysql_query("INSERT INTO `vow_data` SELECT DISTINCT * FROM `vow_upload` WHERE list_numb = '" . $list_numb . "'") or die (MySQL_Error()); }
  else{ mysql_query("UPDATE vow_data SET status='".$row['status']."', last_change_date='".$currentDate."' WHERE list_numb = '". $list_numb ."'") or die(MySQL_Error()); }
}

//$result3 = mysql_query("SELECT list_numb, status FROM vow_data WHERE NOT EXISTS( SELECT list_numb FROM vow_upload WHERE vow_data.list_numb = vow_upload.list_numb");
$result3 = mysql_query("SELECT vow_data.list_numb, vow_data.status FROM vow_data LEFT OUTER JOIN vow_upload ON(vow_data.list_numb = vow_upload.list_numb) WHERE vow_upload.list_numb is NULL AND vow_data.status = 'AVAIL'") or die (MySQL_Error());
while($row3 = mysql_fetch_array($result3)){
   if($row3['status'] == 'AVAIL'){ mysql_query("UPDATE vow_data SET status='POM', last_change_date='". $currentDate."' WHERE list_numb = '". $row3['list_numb'] ."'"); }
}

mysql_query("UPDATE `vow_data` SET loc = '1.00000000000000000000' WHERE status='AVAIL' AND loc = 0") or die(mysql_error());
mysql_query("UPDATE `vow_data` SET bld = '1.00000000000000000000' WHERE status='AVAIL' AND bld = 0") or die(mysql_error());
mysql_query("UPDATE `vow_data` SET vws = '1.00000000000000000000' WHERE status='AVAIL' AND vws = 0") or die(mysql_error());
echo "Imported VOW_data successfully\n<br />";


mysql_query("TRUNCATE TABLE Agent_Import") or die ("MySQL - Query Error - " . MySQL_Error());
mysql_query("LOAD DATA INFILE '/var/www/webadmin/data/www/homepik.com/html/slsp.TXT' INTO TABLE Agent_Import FIELDS TERMINATED BY '|' LINES TERMINATED BY '\r\n' ;") or die ("MySQL - Query Error - " . MySQL_Error());
echo "Imported Agent_Import successfully\n<br />";


mysql_query("TRUNCATE TABLE IDX_Listing_Numbers") or die ("MySQL - Query Error -" . MySQL_Error());
mysql_query("LOAD DATA INFILE '/var/www/webadmin/data/www/homepik.com/html/IDX_Listing_Numbers.csv' IGNORE INTO TABLE IDX_Listing_Numbers  FIELDS TERMINATED BY ','  LINES TERMINATED BY '\r\n' ;") or die ("MySQL - Query Error - " . MySQL_Error());
echo "Imported IDX_Listing_Numbers.csv successfully\n<br />";


mysql_query("TRUNCATE TABLE office") or die ("MySQL - Query Error - " . MySQL_Error());
mysql_query("LOAD DATA INFILE '/var/www/webadmin/data/www/homepik.com/html/office.TXT' IGNORE INTO TABLE office FIELDS TERMINATED BY '|' LINES TERMINATED BY '\r\n' ;") or die ("MySQL - Query Error - " . MySQL_Error());
echo "Imported offices.txt successfully\n<br />";


mysql_query("TRUNCATE TABLE Building_file") or die ("MySQL - Query Error - " . MySQL_Error());
mysql_query("LOAD DATA INFILE '/var/www/webadmin/data/www/homepik.com/html/bldfile.TXT' IGNORE INTO TABLE Building_file FIELDS TERMINATED BY '|'  LINES TERMINATED BY '\r\n';") or die ("MySQL - Query Error - " . MySQL_Error());
echo "Imported bldfile.txt successfully\n<br />";


mysql_query("TRUNCATE TABLE rental") or die ("MySQL - Query Error - " . MySQL_Error());
mysql_query("LOAD DATA INFILE '/var/www/webadmin/data/www/homepik.com/html/rentals-one_table.csv' INTO TABLE rental fields terminated by ';' enclosed by '\"' LINES TERMINATED BY '\r\r\n'  IGNORE 1 LINES;") or die ("MySQL - Query Error - " . MySQL_Error());
echo "Imported rental-one successfully\n<br />";


mysql_query("LOAD DATA INFILE '/var/www/webadmin/data/www/homepik.com/html/rentals-studio_table.csv' INTO TABLE rental fields terminated by ';' enclosed by '\"' LINES TERMINATED BY '\r\r\n'  IGNORE 1 LINES;") or die ("MySQL - Query Error - " . MySQL_Error());
echo "Imported rental-studio successfully\n<br />";


mysql_query("LOAD DATA INFILE '/var/www/webadmin/data/www/homepik.com/html/rentals-other_table.csv' INTO TABLE rental fields terminated by ';' enclosed by '\"' LINES TERMINATED BY '\r\r\n'  IGNORE 1 LINES;") or die ("MySQL - Query Error - " .MySQL_Error());
echo "Imported rental-other successfully\n<br />";

mysql_close($conn);
?>
</body>
</html>
