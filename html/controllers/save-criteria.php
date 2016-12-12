<?php
session_start();
include_once("dbconfig.php");
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if(isset($_SESSION['user'])){
  $email = $_SESSION['email'];
  $role = 'user';
}

if(isset($_POST['email'])){ $email = $_POST['email']; }

$oldName = $_POST['oldname'];

$SQL = "SELECT * FROM `Users_Search` WHERE (email = '".$email."') AND (name = '".$oldName."')";
$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
$row = mysql_fetch_array($result,MYSQL_ASSOC);
$num_rows = mysql_num_rows($result);

if($num_rows >= 1){
  $neighborhoods = array();
  $prop_type = array();

  $searchName = $_POST['name'];
  if(isset($_POST['agents'])){ $agents = $_POST['agents']; } else{ $agents = $row['agent']; }
  $loc_grade = $_POST['location'];
  $build_grade = $_POST['building'];
  $view_grade = $_POST['view'];
  $min_floor = $_POST['floor'];
  $min_price = $_POST['min_price'];
  $max_price = $_POST['max_price'];
  $bedrooms = $_POST['bedrooms'];
  $bedroom_area = $_POST['bedroom_area'];
  $living_area = $_POST['living_area'];
  $neighborhoods = $_POST['neighborhoods'];
  $prop_type = $_POST['prop_type'];
  $amenities = $_POST['amenities'];
  
  foreach($neighborhoods as &$n){
    switch ($n) {
      case "North": $n = "Far Uptown";
          break;
      case "Westside": $n = "Upper West Side";
          break;
      case "Eastside": $n = "Upper East Side";
          break;
      case "Chelsea": $n = "Midtown West";
          break;
      case "SMG": $n = "Midtown East";
          break;
      case "Village": $n = "East/West Village";
          break;
      case "Lower": $n = "Downtown";
          break;
    };
  }
  
  foreach($prop_type as &$p){
    switch ($p) {
      case "1": $p = "Coop";
          break;
      case "2": $p = "Condo";
          break;
      case "4": $p = "House/Townhouse";
          break;
      case "5": $p = "Condop";
          break;
    };
  }
  
  foreach($amenities as &$a){
    switch ($a) {
      case "1": $a = "Garage";
          break;
      case "2": $a = "Pool";
          break;
      case "3": $a = "Laundry";
          break;
      case "4": $a = "Doorman";
          break;
      case "5": $a = "Elevator";
          break;
      case "6": $a = "Pets";
          break;
      case "7": $a = "Fireplace";
          break;
      case "8": $a = "Healthclub";
          break;
      case "9": $a = "Prewar";
          break;
      case "10": $a = "Outdoor";
          break;
      case "11": $a = "Wheelchair";
          break;
    };
  }
  
  $neigh = implode(", ", $neighborhoods);
  $properties = implode(", ", $prop_type);
  $amen = implode(", ", $amenities);
  $results = mysql_query("UPDATE Users_Search SET `email` = '".$email."', `name` = '".$searchName."', `agent` = '".$agents."', `location-grade` = '".$loc_grade."', `building_grade` = '".$build_grade."', `views_grade` = '".$view_grade."', `min_floor` = '".$min_floor."', `max_price` = '".$max_price."', `min_price` = '".$min_price."', `bedrooms` = '".$bedrooms."', `living_area` = '".$living_area."', `bedroom_area` = '".$bedroom_area."', `neighborhoods` = '".$neigh."', `property-type` = '".$properties."', `ammenities` = '".$amen."' WHERE (email = '".$email."') AND (name = '".$oldName."')")  or die(mysql_error());
} else{
  
  $searchName = $_POST['name'];
  
  $SQL = "SELECT * FROM `Users_Search` WHERE (email = '".$email."') AND (name = '".$searchName."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $num_rows = mysql_num_rows($result);
  
  if($num_rows >= 1){
    $neighborhoods = array();
    $prop_type = array();
    $searchName = $_POST['name'];
    if(isset($_POST['agents'])){ $agents = $_POST['agents']; }else{$agents = $row['agent']; }
    $loc_grade = $_POST['location'];
    $build_grade = $_POST['building'];
    $view_grade = $_POST['view'];
    $min_floor = $_POST['floor'];
    $min_price = $_POST['min_price'];
    $max_price = $_POST['max_price'];
    $bedrooms = $_POST['bedrooms'];
    $bedroom_area = $_POST['bedroom_area'];
    $living_area = $_POST['living_area'];
    $neighborhoods = $_POST['neighborhoods'];
    $prop_type = $_POST['prop_type'];
    $amenities = $_POST['amenities'];
    
    foreach($neighborhoods as &$n){
      switch ($n) {
        case "North": $n = "Far Uptown";
            break;
        case "Westside": $n = "Upper West Side";
            break;
        case "Eastside": $n = "Upper East Side";
            break;
        case "Chelsea": $n = "Midtown West";
            break;
        case "SMG": $n = "Midtown East";
            break;
        case "Village": $n = "East/West Village";
            break;
        case "Lower": $n = "Downtown";
            break;
      };
    }
    
    foreach($prop_type as &$p){
      switch ($p) {
        case "1": $p = "Coop";
            break;
        case "2": $p = "Condo";
            break;
        case "4": $p = "House/Townhouse";
            break;
        case "5": $p = "Condop";
            break;
      };
    }
    
    foreach($amenities as &$a){
      switch ($a) {
        case "1": $a = "Garage";
            break;
        case "2": $a = "Pool";
            break;
        case "3": $a = "Laundry";
            break;
        case "4": $a = "Doorman";
            break;
        case "5": $a = "Elevator";
            break;
        case "6": $a = "Pets";
            break;
        case "7": $a = "Fireplace";
            break;
        case "8": $a = "Healthclub";
            break;
        case "9": $a = "Prewar";
            break;
        case "10": $a = "Outdoor";
            break;
        case "11": $a = "Wheelchair";
            break;
      };
    }
    
    $neigh = implode(", ", $neighborhoods);
    $properties = implode(", ", $prop_type);
    $amen = implode(", ", $amenities);
    $results = mysql_query("UPDATE Users_Search SET `email` = '".$email."', `name` = '".$searchName."', `agent` = '".$agents."', `location-grade` = '".$loc_grade."', `building_grade` = '".$build_grade."', `views_grade` = '".$view_grade."', `min_floor` = '".$min_floor."', `max_price` = '".$max_price."', `min_price` = '".$min_price."', `bedrooms` = '".$bedrooms."', `living_area` = '".$living_area."', `bedroom_area` = '".$bedroom_area."', `neighborhoods` = '".$neigh."', `property-type` = '".$properties."', `ammenities` = '".$amen."' WHERE (email = '".$email."') AND (name = '".$searchName."')")  or die(mysql_error());
  }
  else{
    $neighborhoods = array();
    $prop_type = array();
    $agents = $_POST['agents'];
    $loc_grade = $_POST['location'];
    $build_grade = $_POST['building'];
    $view_grade = $_POST['view'];
    $min_floor = $_POST['floor'];
    $min_price = $_POST['min_price'];
    $max_price = $_POST['max_price'];
    $bedrooms = $_POST['bedrooms'];
    $bedroom_area = $_POST['bedroom_area'];
    $living_area = $_POST['living_area'];
    $neighborhoods = $_POST['neighborhoods'];
    $prop_type = $_POST['prop_type'];
    $amenities = $_POST['amenities'];
    
    foreach($neighborhoods as &$n){
      switch ($n) {
        case "North": $n = "Far Uptown";
            break;
        case "Westside": $n = "Upper West Side";
            break;
        case "Eastside": $n = "Upper East Side";
            break;
        case "Chelsea": $n = "Midtown West";
            break;
        case "SMG": $n = "Midtown East";
            break;
        case "Village": $n = "East/West Village";
            break;
        case "Lower": $n = "Downtown";
            break;
      };
    }
    
    foreach($prop_type as &$p){
      switch ($p) {
        case "1": $p = "Coop";
            break;
        case "2": $p = "Condo";
            break;
        case "4": $p = "House/Townhouse";
            break;
        case "5": $p = "Condop";
            break;
      };
    }
    
    foreach($amenities as &$a){
      switch ($a) {
        case "1": $a = "Garage";
            break;
        case "2": $a = "Pool";
            break;
        case "3": $a = "Laundry";
            break;
        case "4": $a = "Doorman";
            break;
        case "5": $a = "Elevator";
            break;
        case "6": $a = "Pets";
            break;
        case "7": $a = "Fireplace";
            break;
        case "8": $a = "Healthclub";
            break;
        case "9": $a = "Prewar";
            break;
        case "10": $a = "Outdoor";
            break;
        case "11": $a = "Wheelchair";
            break;
      };
    }
    
    $neigh = implode(", ", $neighborhoods);
    $properties = implode(", ", $prop_type);
    $amen = implode(", ", $amenities);
    
    if($_SESSION['agent']){
      $SQL = "SELECT agent_id FROM `registered_agents` WHERE (email = '".$_SESSION['email']."')";
      $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
      $row = mysql_fetch_array($result,MYSQL_ASSOC);
      $agent_id = $row['agent_id'];
      
      if($searchName == ""){
        $SQL = "SELECT name FROM `users_folders` WHERE (user = '".$email."') AND (agent = '".$agent_id."')";
        $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
        $row = mysql_fetch_array($result,MYSQL_ASSOC);
        $searchName = $row['name'];
      }
      else{
        $results = mysql_query("INSERT INTO Users_Search(`email`,`name`, `agent`, `location-grade`, `building_grade`, `views_grade`, `min_floor`, `max_price`, `min_price`, `bedrooms`, `living_area`, `bedroom_area`, `neighborhoods`, `property-type`, `ammenities`) VALUES  ('".$email."','".$searchName."','".$agent_id."','".$loc_grade."','".$build_grade."','".$view_grade."','".$min_floor."','".$max_price."','".$min_price."','".$bedrooms."','".$living_area."','".$bedroom_area."','".$neigh."','".$properties."','".$amen."')")  or die(mysql_error());
      }
    }
    else{
      $results = mysql_query("INSERT INTO Users_Search(`email`,`name`, `agent`, `location-grade`, `building_grade`, `views_grade`, `min_floor`, `max_price`, `min_price`, `bedrooms`, `living_area`, `bedroom_area`, `neighborhoods`, `property-type`, `ammenities`) VALUES  ('".$email."','".$searchName."','".$agents."','".$loc_grade."','".$build_grade."','".$view_grade."','".$min_floor."','".$max_price."','".$min_price."','".$bedrooms."','".$living_area."','".$bedroom_area."','".$neigh."','".$properties."','".$amen."')")  or die(mysql_error());
    }
  }
}
?>