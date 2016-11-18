<?php
session_start();
include("dbconfig.php");

// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

$email = $_POST['email'];
$name = $_POST['name'];
$_SESSION['buyerSave'] = $email;

if($name != "false"){   
  $SQL = "SELECT * FROM `Users_Search` WHERE (email = '".$email."') AND (name = '".$name."')";
}
else{
  $SQL = "SELECT * FROM `Users_Search` WHERE (email = '".$email."')";
}

$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());

while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
  $name = $row['name'];
  $loc_grade = $row['location-grade'];
  $build_grade = $row['building_grade'];
  $view_grade = $row['views_grade'];
  $neighborhoods = $row['neighborhoods'];
  $properties = $row['property-type'];
  $min_floor = $row['min_floor'];
  $min_price = $row['min_price'];
  $max_price = $row['max_price'];
  $bedrooms = $row['bedrooms'];
  $living_room = $row['living_area'];
  $bedroom_area = $row['bedroom_area'];
  $amenities = $row['ammenities'];
}

switch ($min_price) {
  case "0": $min_price = '0';
      break;
  case "100000": $min_price = '1';
      break;
  case "200000": $min_price = '2';
      break;
  case "300000": $min_price = '3';
      break;
  case "400000": $min_price = '4';
      break;
  case "500000": $min_price = '5';
      break;
  case "600000": $min_price = '6';
      break;
  case "700000": $min_price = '7';
      break;
  case "800000": $min_price = '8';
      break;
  case "900000": $min_price = '9';
      break;
  case "1000000": $min_price = '10'; 
      break;
  case "1100000": $min_price = '11';
      break;
  case "1200000": $min_price = '12';
      break;
  case "1300000": $min_price = '13';
      break;
  case "1400000": $min_price = '14';
      break;
  case "1500000": $min_price = '15';
      break;
  case "1600000": $min_price = '16';
      break;
  case "1700000": $min_price = '17';
      break;
  case "1800000": $min_price = '18';
      break;
  case "1900000": $min_price = '19';
      break;
  case "2000000": $min_price = '20';
      break;
  case "2250000": $min_price = '21';
      break; 
  case "2500000": $min_price = '22';
      break;
  case "2750000": $min_price = '23';
      break;
  case "3000000": $min_price = '24';
      break;
  case "3500000": $min_price = '25';
      break;
  case "4000000": $min_price = '26';
      break;
  case "6000000": $min_price = '27';
      break;
  case "8000000": $min_price = '28';
      break;
  case "12000000": $min_price = '29';
      break;
  case "25000000": $min_price = '30';
      break;
  case "50000000": $min_price = '31';
      break;
  case "99000000": $min_price = '32';
      break;
};
      
switch ($max_price) {
  case "100000": $max_price = '1';
      break;
  case "200000": $max_price = '2';
      break;
  case "300000": $max_price = '3';
      break;
  case "400000": $max_price = '4';
      break;
  case "500000": $max_price = '5';
      break;
  case "600000": $max_price = '6';
      break;
  case "700000": $max_price = '7';
      break;
  case "800000": $max_price = '8';
      break;
  case "900000": $max_price = '9';
      break;
  case "1000000": $max_price = '10'; 
      break;
  case "1100000": $max_price = '11';
      break;
  case "1200000": $max_price = '12';
      break;
  case "1300000": $max_price = '13';
      break;
  case "1400000": $max_price = '14';
      break;
  case "1500000": $max_price = '15';
      break;
  case "1600000": $max_price = '16';
      break;
  case "1700000": $max_price = '17';
      break;
  case "1800000": $max_price = '18';
      break;
  case "1900000": $max_price = '19';
      break;
  case "2000000": $max_price = '20';
      break;
  case "2250000": $max_price = '21';
      break; 
  case "2500000": $max_price = '22';
      break;
  case "2750000": $max_price = '23';
      break;
  case "3000000": $max_price = '24';
      break;
  case "3500000": $max_price = '25';
      break;
  case "4000000": $max_price = '26';
      break;
  case "6000000": $max_price = '27';
      break;
  case "8000000": $max_price = '28';
      break;
  case "12000000": $max_price = '29';
      break;
  case "25000000": $max_price = '30';
      break;
  case "50000000": $max_price = '31';
      break;
  case "99000000": $max_price = '32';
      break;
};

$neighborhoods = explode(", ", $neighborhoods);

foreach($neighborhoods as &$n){
  switch ($n) {
    case "Far Uptown": $n = "North";
        break;
    case "Upper West": $n = "Westside";
        break;
    case "Upper West Side": $n = "Westside";
        break;
    case "Upper East": $n = "Eastside";
        break;
    case "Upper East Side": $n = "Eastside";
        break;
    case "Midtown West": $n = "Chelsea";
        break;
    case "Midtown East": $n = "SMG";
        break;
    case "Greenwich Village": $n = "Village";
        break;
    case "East/West Village": $n = "Village";
        break;
    case "Downtown": $n = "Lower";
        break;
  };
}

$properties = explode(", ", $properties);

$amenities = explode(", ", $amenities);

foreach($amenities as &$a){
  strtolower($a);
}

$criteria = array("name"=>$name, "location_grade"=>$loc_grade, "building_grade"=>$build_grade, "view_grade"=>$view_grade, "floor"=>$min_floor, "bedrooms"=>$bedrooms, "min_price"=>$min_price, "max_price"=>$max_price, "living_area"=>$living_room, "bedroom_area"=>$bedroom_area, "neighborhoods" =>$neighborhoods, "prop_type"=>$properties, "amenities"=>$amenities);

echo json_encode($criteria);

?>