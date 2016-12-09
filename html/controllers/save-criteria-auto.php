<?php
session_start();
include_once("dbconfig.php");
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

$email = $_SESSION['email'];

if(!isset($_POST['folder'])){
  $SQL = "SELECT * FROM `users_folders` WHERE (user = '".$email."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $name = $row['name'];
  $agent = $row['agent'];
  
  $neighborhoods = array();
  $prop_type = array();

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
  $results = mysql_query("INSERT INTO Users_Search(`email`,`name`, `agent`, `location-grade`, `building_grade`, `views_grade`, `min_floor`, `max_price`, `min_price`, `bedrooms`, `living_area`, `bedroom_area`, `neighborhoods`, `property-type`, `ammenities`) VALUES  ('".$email."','".$name."','".$agent."','".$loc_grade."','".$build_grade."','".$view_grade."','".$min_floor."','".$max_price."','".$min_price."','".$bedrooms."','".$living_area."','".$bedroom_area."','".$neigh."','".$properties."','".$amen."')")  or die(mysql_error());
}
else{
  $folders = $_POST['folder'];
  $isArray = is_array($folders);
    
  if(!$isArray){
    $SQL = "SELECT * FROM `users_folders` WHERE (user = '".$email."') AND (name = '".$folders."')";
    $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $agent = $row['agent'];
    
    $neighborhoods = array();
    $prop_type = array();
  
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
        case "12": $a = "Time Shares";
            break;
        case "13": $a = "New Construction";
            break;
      };
    }
    
    $neigh = implode(", ", $neighborhoods);
    $properties = implode(", ", $prop_type);
    $amen = implode(", ", $amenities);
    
    $SQL = "SELECT * FROM `Users_Search` WHERE (email = '".$email."') AND (name = '".$folders."')";
    $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
    $num_rows = mysql_num_rows($result);
        
    if($num_rows < 1){
      $results = mysql_query("INSERT INTO Users_Search(`email`,`name`, `agent`, `location-grade`, `building_grade`, `views_grade`, `min_floor`, `max_price`, `min_price`, `bedrooms`, `living_area`, `bedroom_area`, `neighborhoods`, `property-type`, `ammenities`) VALUES  ('".$email."','".$folders."','".$agent."','".$loc_grade."','".$build_grade."','".$view_grade."','".$min_floor."','".$max_price."','".$min_price."','".$bedrooms."','".$living_area."','".$bedroom_area."','".$neigh."','".$properties."','".$amen."')")  or die(mysql_error());
    }
    else{
      $results = mysql_query("UPDATE Users_Search SET `agent` = '".$agent."', `location-grade` = '".$loc_grade."', `building_grade` = '".$build_grade."', `views_grade` = '".$view_grade."', `min_floor` = '".$min_floor."', `max_price` = '".$max_price."', `min_price` = '".$min_price."', `bedrooms` = '".$bedrooms."', `living_area` = '".$living_area."', `bedroom_area` = '".$bedroom_area."', `neighborhoods` = '".$neigh."', `property-type` = '".$properties."', `ammenities` = '".$amen."' WHERE (email = '".$email."') AND (name = '".$folders."')")  or die(mysql_error());
    }
  }
  else{
    foreach ($folders as $folder) {
      $SQL = "SELECT * FROM `users_folders` WHERE (user = '".$email."') AND (name = '".$folder."')";
      $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
      $row = mysql_fetch_array($result,MYSQL_ASSOC);
      $agent = $row['agent'];
      
      $neighborhoods = array();
      $prop_type = array();
    
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
      
      $SQL = "SELECT * FROM `Users_Search` WHERE (email = '".$email."') AND (name = '".$folder."')";
      $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
      $num_rows = mysql_num_rows($result);
      
      if($num_rows < 1){
        $results = mysql_query("INSERT INTO Users_Search(`email`,`name`, `agent`, `location-grade`, `building_grade`, `views_grade`, `min_floor`, `max_price`, `min_price`, `bedrooms`, `living_area`, `bedroom_area`, `neighborhoods`, `property-type`, `ammenities`) VALUES  ('".$email."','".$folder."','".$agent."','".$loc_grade."','".$build_grade."','".$view_grade."','".$min_floor."','".$max_price."','".$min_price."','".$bedrooms."','".$living_area."','".$bedroom_area."','".$neigh."','".$properties."','".$amen."')")  or die(mysql_error());
      }
      else{
        $results = mysql_query("UPDATE Users_Search SET `agent` = '".$agent."', `location-grade` = '".$loc_grade."', `building_grade` = '".$build_grade."', `views_grade` = '".$view_grade."', `min_floor` = '".$min_floor."', `max_price` = '".$max_price."', `min_price` = '".$min_price."', `bedrooms` = '".$bedrooms."', `living_area` = '".$living_area."', `bedroom_area` = '".$bedroom_area."', `neighborhoods` = '".$neigh."', `property-type` = '".$properties."', `ammenities` = '".$amen."' WHERE (email = '".$email."') AND (name = '".$folder."')")  or die(mysql_error());
      }
    }
  }
}
?>