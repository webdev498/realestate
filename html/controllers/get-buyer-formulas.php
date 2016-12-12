<?php
session_start();
include_once("dbconfig.php");
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

$buyer_email = $_POST['buyer'];  
$formulas = array();
$id = 1;

if(isset($_POST['agentID'])){ $SQL = "SELECT * FROM `Users_Search` WHERE (email = '".$buyer_email."') AND (agent LIKE '%".$_POST['agentID']."%') ORDER BY name ASC"; }
else if($_SESSION['agent']){ $SQL = "SELECT * FROM `Users_Search` WHERE (email = '".$buyer_email."') AND (agent LIKE '%".$_SESSION['agent_id']."%') ORDER BY name ASC"; }
else{ $SQL = "SELECT * FROM `Users_Search` WHERE (email = '".$buyer_email."') ORDER BY name ASC"; }

$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
  $name = $row['name'];
  $agents = $row['agent'];
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
  
  if($agents != ''){
    if(strpos($agents, ',') === false){
      $result2 = mysql_query( "SELECT CONCAT(first_name, ' ', last_name) AS name FROM `registered_agents` WHERE (agent_id = '".$agents."')" ) or die("Couldn't execute query.".mysql_error());
      $row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
      $agents = $row2['name'];
    }
    else{
      $agents = explode(",", $agents);
      
      $result2 = mysql_query( "SELECT CONCAT(first_name, ' ', last_name) AS name FROM `registered_agents` WHERE (agent_id = '".$agents[0]."')" ) or die("Couldn't execute query.".mysql_error());
      $row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
      $agent1 = $row2['name'];
      
      $result3 = mysql_query( "SELECT CONCAT(first_name, ' ', last_name) AS name FROM `registered_agents` WHERE (agent_id = '".$agents[1]."')" ) or die("Couldn't execute query.".mysql_error());
      $row3 = mysql_fetch_array($result3,MYSQL_ASSOC);
      $agent2 = $row3['name'];
      
      $agents = $agent1 . ", " . $agent2;
    }
  }
  else{
    $agents = "No Agents";
  }
  
  switch ($min_price) {
    case "100000": $min_price = '$100K';
        break;
    case "200000": $min_price = '$200K';
        break;
    case "300000": $min_price = '$300K';
        break;
    case "400000": $min_price = '$400K';
        break;
    case "500000": $min_price = '$500K';
        break;
    case "600000": $min_price = '$600K';
        break;
    case "700000": $min_price = '$700K';
        break;
    case "800000": $min_price = '$800K';
        break;
    case "900000": $min_price = '$900K';
        break;
    case "1000000": $min_price = '$1M'; 
        break;
    case "1100000": $min_price = '$1.1M';
        break;
    case "1200000": $min_price = '$1.2M';
        break;
    case "1300000": $min_price = '$1.3M';
        break;
    case "1400000": $min_price = '$1.4M';
        break;
    case "1500000": $min_price = '$1.5M';
        break;
    case "1600000": $min_price = '$1.6M';
        break;
    case "1700000": $min_price = '$1.7M';
        break;
    case "1800000": $min_price = '$1.8M';
        break;
    case "1900000": $min_price = '$1.9M';
        break;
    case "2000000": $min_price = '$2M';
        break;
    case "2250000": $min_price = '$2.25M';
        break; 
    case "2500000": $min_price = '$2.5M';
        break;
    case "2750000": $min_price = '$2.75M';
        break;
    case "3000000": $min_price = '$3M';
        break;
    case "3500000": $min_price = '$3.5M';
        break;
    case "4000000": $min_price = '$4M';
        break;
    case "6000000": $min_price = '$6M';
        break;
    case "8000000": $min_price = '$8M';
        break;
    case "12000000": $min_price = '$12M';
        break;
    case "25000000": $min_price = '$25M';
        break;
    case "50000000": $min_price = '$50M';
        break;
    case "99000000": $min_price = '$99M';
        break;
  };
  
  switch ($max_price) {
    case "100000": $max_price = '$100K';
        break;
    case "200000": $max_price = '$200K';
        break;
    case "300000": $max_price = '$300K';
        break;
    case "400000": $max_price = '$400K';
        break;    
    case "500000": $max_price = '$500K';
        break;
    case "600000": $max_price = '$600K';
        break;
    case "700000": $max_price = '$700K';
        break;
    case "800000": $max_price = '$800K';
        break;
    case "900000": $max_price = '$900K';
        break;
    case "1000000": $max_price = '$1M'; 
        break;
    case "1100000": $max_price = '$1.1M';
        break;
    case "1200000": $max_price = '$1.2M';
        break;
    case "1300000": $max_price = '$1.3M';
        break;
    case "1400000": $max_price = '$1.4M';
        break;
    case "1500000": $max_price = '$1.5M';
        break;
    case "1600000": $max_price = '$1.6M';
        break;
    case "1700000": $max_price = '$1.7M';
        break;
    case "1800000": $max_price = '$1.8M';
        break;
    case "1900000": $max_price = '$1.9M';
        break;
    case "2000000": $max_price = '$2M';
        break;
    case "2250000": $max_price = '$2.25M';
        break; 
    case "2500000": $max_price = '$2.5M';
        break;
    case "2750000": $max_price = '$2.75M';
        break;
    case "3000000": $max_price = '$3M';
        break;
    case "3500000": $max_price = '$3.5M';
        break;
    case "4000000": $max_price = '$4M';
        break;
    case "6000000": $max_price = '$6M';
        break;
    case "8000000": $max_price = '$8M';
        break;
    case "12000000": $max_price = '$12M';
        break;
    case "25000000": $max_price = '$25M';
        break;
    case "50000000": $max_price = '$50M';
        break;
    case "99000000": $max_price = '$99M';
        break;
  };

  switch ($bedrooms) {
    case "0": $bedrooms = "Studio";
        break;
    case "1": $bedrooms = "1 Bedroom";
        break;
    case "2": $bedrooms = "2 Bedrooms";
        break;
    case "3": $bedrooms = "3 Bedrooms";
        break;
    case "4": $bedrooms = "4 Bedrooms";
        break;
    case "5": $bedrooms = "5 Bedrooms";
        break;
    case "6": $bedrooms = "6 Bedrooms";
        break;
    case "7": $bedrooms = "7 Bedrooms";
        break;
    case "8": $bedrooms = "8 Bedrooms";
        break;
  };
  
  switch ($bedroom_area) {
    case "1": $bedroom_area = "S - Any bedroom size is okay.";
        break;
    case "2": $bedroom_area = "M - At least 13ft by 11ft";
        break;
    case "3": $bedroom_area = "L - At least 16ft by 11ft";
        break;
    case "4": $bedroom_area = "XL - At least 19ft by 11ft";
        break;
  };
        
  switch ($living_room) {
    case "1": $living_room = "S - Any living room size is okay.";
        break;
    case "2": $living_room = "M - At least 18ft by 12ft";
        break;
    case "3": $living_room = "L - At least 22ft by 12ft";
        break;
    case "4": $living_room = "XL - At least 27ft by 12ft";
        break;
  };
  
  switch ($loc_grade){
    case "1": $loc_grade = "All locations";
        break; 
    case "2": $loc_grade = "New developing market.";
        break;
    case "3": $loc_grade = "Emerging residential area";
        break;
    case "4": $loc_grade = "Active commercial street";
        break;
    case "5": $loc_grade = "Active commercial street / Residential Area";
        break;
    case "6": $loc_grade = "Quiet residential street";
        break;
    case "7": $loc_grade = "Residential  area/ neighborhood amenities";
        break;
    case "8": $loc_grade = "Residential area/ near local park or river";
        break;
    case "9": $loc_grade = "Residential area close to major park.";
        break;
    case "10": $loc_grade = "Internationally renown/ near major park";
        break;
  };
  
  switch ($build_grade){
    case "1": $build_grade = "All buildings";
        break;
    case "2": $build_grade = "Walkup in fair condition";
        break;
    case "3": $build_grade = "Walkup in good condition.";
        break;
    case "4": $build_grade = "Elevator building in fair condition";
        break;
    case "5": $build_grade = "Elevator building in good condition";
        break;
    case "6": $build_grade = "Doorman building –no amenities";
        break;
    case "7": $build_grade = "Doorman building with amenities";
        break;
    case "8": $build_grade = "Full service building with amenities.";
        break;
    case "9": $build_grade = "Local renown or new construction with full services";
        break;
    case "10": $build_grade = "International renown";
        break;
  };
  
  switch ($view_grade){
    case "1": $view_grade = "All properties";
        break;
    case "2": $view_grade = "Indirect light";
        break;
    case "3": $view_grade = "Interior courtyard or area with moderate light";
        break;
    case "4": $view_grade = "Interior courtyard or area without view but bright";
        break;
    case "5": $view_grade = "Street view or interior garden moderate light";
        break;
    case "6": $view_grade = "Street view or interior garden bright";
        break;
    case "7": $view_grade = "Rooftop views";
        break;
    case "8": $view_grade = "Cityscape views.";
        break;
    case "9": $view_grade = "Cityscape and river or park views.";
        break;
    case "10": $view_grade = "Cityscape and Central Park views";
        break;
  };
  
  $formula = array("id"=>$id, "name"=>$name, "agent"=>$agents, "location_grade"=>$loc_grade, "building_grade"=>$build_grade, "view_grade"=>$view_grade, "neighborhoods"=>$neighborhoods, "properties"=>$properties, "min_price"=>$min_price, "max_price"=>$max_price, "bedrooms"=>$bedrooms, "living_room"=>$living_room, "bedroom_area"=>$bedroom_area, "amenities"=>$amenities);
  array_push($formulas, $formula);
  $id = $id + 1;
}

echo json_encode($formulas);
?>