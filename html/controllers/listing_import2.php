
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
mysql_query("LOAD DATA INFILE '/www/homepik.com/html/VOW_data.TXT' INTO TABLE vow_upload FIELDS TERMINATED BY '|' LINES TERMINATED BY '\r\n' ;") or die ("MySQL - Query Error - " . MySQL_Error());
$result = mysql_query("SELECT * FROM vow_upload");
while ($row = mysql_fetch_array($result)) 
{
  $list_numb = $row['list_numb'];
  $result2 = mysql_query("SELECT * from vow_upload WHERE (list_numb = '" . $list_numb . "')");
  while ($row2 = mysql_fetch_array($result2)) 
{
  if (!($row2['list_numb']))
  {
    //mysql_query("INSERT INTO `vow_data` (`list_numb`, `prop_type`, `bldname`, `address`, `alt_address`, `xstreet`, `zip`, `bld_year`, `bld_floors`, `bld_units`, `rentsale`, `list_date`, `price`, `apt`, `bed`, `bath`, `lr`, `br1`, `br2`, `br3`, `br4`, `kitchen`, `other_din`, `other_den`, `alcove`, `maids`, `other`, `room_cnt`, `sq_feet`, `maint`, `p_down`, `taxes`, `tax_ded`, `available`, `minterm`, `maxterm`, `agent`, `phone1`, `email1`, `agntphoto1`, `agent2`, `phone2`, `email2`, `agntphoto2`, `title`, `photo1`, `photo2`, `photo3`, `photo4`, `photo5`, `photo6`, `photo7`, `photo8`, `photo9`, photo10`, `flrplan1`, `flrplan2`,  );
    //mysql_query("UPDATE vow_upload SET status='POM', last_change_data WHERE list_numb = '" . $list_numb ."'");
    mysql_query("INSERT INTO `vow_data` SELECT DISTINCT * FROM `vow_upload` WHERE list_numb = '" . $list_numb . "'");
  }
  else if ($row2['last_change_data'] >= $row['last_change_data'])
  {
    mysql_query("INSERT INTO `vow_data` (`last_change_data`) VALUES ('" . $row['last_change_data'] . "')");
  }
  else
  {
    
  }
  
 $result3 = mysql_query("SELECT * from vow_data WHERE (list_numb = '" . $list_numb . "')");
while ($row3 = mysql_fetch_array($result3)) 
{
  if (($row3['list_numb']) && !($row2['list_numb']) && ($row3['status'] == 'AVAIL') )
  {
    mysql_query("UPDATE vow_data SET status='POM', last_change_date='" . $currentDate . "' WHERE list_numb = '" . $list_numb ."'");
  }
} 
}

}


echo "Imported VOW_data successfully\n<br />";

mysql_query("TRUNCATE TABLE Agent_Import") or die ("MySQL - Query Error - " . MySQL_Error());
mysql_query("LOAD DATA INFILE '/www/homepik.com/html/slsp.TXT' INTO TABLE Agent_Import FIELDS TERMINATED BY '|' LINES TERMINATED BY '\r\n' ;") or die ("MySQL - Query Error - " . MySQL_Error());


echo "Imported Agent_Import successfully\n<br />";

mysql_query("TRUNCATE TABLE IDX_Listing_Numbers") or die ("MySQL - Query Error - " . MySQL_Error());
mysql_query("LOAD DATA INFILE '/www/homepik.com/html/IDX_Listing_Numbers.csv' IGNORE INTO TABLE IDX_Listing_Numbers  FIELDS TERMINATED BY ','  LINES TERMINATED BY '\r\n' ;") or die ("MySQL - Query Error - " . MySQL_Error());
echo "Imported IDX_Listing_Numbers.csv successfully\n<br />";

mysql_query("TRUNCATE TABLE office") or die ("MySQL - Query Error - " . MySQL_Error());  
mysql_query("LOAD DATA INFILE '/www/homepik.com/html/office.TXT' IGNORE INTO TABLE office FIELDS TERMINATED BY '|' LINES TERMINATED BY '\r\n' ;") or die ("MySQL - Query Error - " . MySQL_Error());
echo "Imported offices.txt successfully\n<br />";

mysql_query("TRUNCATE TABLE Building_file") or die ("MySQL - Query Error - " . MySQL_Error());  
mysql_query("LOAD DATA INFILE '/www/homepik.com/html/bldfile.TXT' IGNORE INTO TABLE Building_file FIELDS TERMINATED BY '|'  LINES TERMINATED BY '\r\n';") or die ("MySQL - Query Error - " . MySQL_Error());
echo "Imported bldfile.txt successfully\n<br />";

mysql_query("TRUNCATE TABLE rental") or die ("MySQL - Query Error - " . MySQL_Error());
mysql_query("LOAD DATA INFILE '/www/homepik.com/html/rentals-one_table.csv' INTO TABLE rental fields terminated by ';' enclosed by '\"' LINES TERMINATED BY '\r\r\n'  IGNORE 1 LINES;") or die ("MySQL - Query Error - " . MySQL_Error());
echo "Imported rental-one successfully\n<br />";

mysql_query("LOAD DATA INFILE '/www/homepik.com/html/rentals-studio_table.csv' INTO TABLE rental fields terminated by ';' enclosed by '\"' LINES TERMINATED BY '\r\r\n'  IGNORE 1 LINES;") or die ("MySQL - Query Error - " . MySQL_Error());
echo "Imported rental-studio successfully\n<br />";

mysql_query("LOAD DATA INFILE '/www/homepik.com/html/rentals-other_table.csv' INTO TABLE rental fields terminated by ';' enclosed by '\"' LINES TERMINATED BY '\r\r\n'  IGNORE 1 LINES;") or die ("MySQL - Query Error - " . MySQL_Error());
echo "Imported rental-other successfully\n<br />";

mysql_close($conn);
?>
</body>
</html>
