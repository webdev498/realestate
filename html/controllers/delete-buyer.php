<html>
<head>
<title>Delete a Buyer</title>
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

$email = "";

echo $email . "<br />";

// Remove Saved Listings
mysql_query("DELETE FROM `saved_listings` WHERE (user = '".$email."')");

// Remove Saved Folder
mysql_query("DELETE FROM `users_folders` WHERE (user = '".$email."')");

//Remove Saved Formula
mysql_query("DELETE FROM `Users_Search` WHERE (email = '".$email."')");

// Remove Buyer Account
mysql_query("DELETE FROM `users` WHERE (email = '".$email."')");


echo "Buyer deletion successfully\n<br />";


mysql_close($conn);
?>
</body>
</html>
