<?php
session_start();
include_once("functions.php");
include_once("dbconfig.php");
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

$location_grade = 1; // Minimum Location grade from 1 to 10
$neighborhoods = "null"; // Greenwich Village, Midtown, etc.
$prop_type = "null"; // Co-op, Condo, Townhouse, etc.
$building_grade = 1; // Minimum Building grade from 1 to 10
$amens = array("timeshare"); // Doorman, Pool, etc.
$views_grade = 1;  // Minimum Views grade from 1 to 10
$min_floor = 0;  // The lowest acceptable floor, e.g. 8th floor. This feature is not currently available to users, but operational for future versions.
$max_price = 2000000; // Maximum Price
$max_price = str_replace(",", "", $max_price);
$min_price = 100000; // Minimum Price
$min_price = str_replace(",", "", $min_price);
$bedrooms = 0; // Minimum Number of Bedrooms
$living_area = 1; // Minimum Size of Living Room
$bedroom_area = 1; // Minimum Size of Bedroom

// Translate bedroom size grade into square feet
switch ($bedroom_area) {
  case "1": $bedroom_area = "0";
      break;
  case "2": $bedroom_area = "143";
      break;
  case "3": $bedroom_area = "176";
      break;
  case "4": $bedroom_area = "198";
      break;
};

// Is it a studio apartment?
if ($bedrooms < 1) { // Yes it's a studio
  switch ($living_area) { // Translate livign room grade into studio square feet
      case "1": $living_area = "0";
          break;
      case "2": $living_area = "192";
          break;
      case "3": $living_area = "252";
          break;
      case "4": $living_area = "324";
          break;
  };
} else { // No its not a studio
  switch ($living_area) {	// Translate living room grade into living room square feet
      case "1": $living_area = "0";
          break;
      case "2": $living_area = "216";
          break;
      case "3": $living_area = "264";
          break;
      case "4": $living_area = "324";
          break;
  };
};

// SQL QUERY
// select
$all_listing_count = array();
if ((authentication() == 'guest')){
	$from = "FROM vow_data ";
  $where = "WHERE (";
    $where .= "(";
      $where .= "(vow_data.contract = 'COBRK' AND status = 'AVAIL') AND";
      $where .= " EXISTS(";
        $where .= "SELECT * FROM IDX_Listing_Numbers";
        $where .= " WHERE CONCAT('\"',vow_data.RLS_id,'\"') = IDX_Listing_Numbers.RLS_id ";
      $where .= ")";
    $where .= ")";
    $where .= "OR (vow_data.contract <> 'COBRK' AND status = 'AVAIL')";
  $where .= ") AND ";

  //======get public listing count====
  $public_portal_listing_count = get_listing_count($location_grade,$neighborhoods,$prop_type,$building_grade,$amens,$views_grade,$min_floor,$bedrooms,$bedroom_area,$living_area,$living_area,$min_price,$max_price,$select='',$from,$where);
  $all_listing_count['public_listingcount'] = $public_portal_listing_count;

  //======get homepik listing count====
  $select = "SELECT vow_data.* ";
  $from = " FROM vow_data ";
  $where = " WHERE (vow_data.status = 'AVAIL') AND ";

  $homepik_listing_count = get_listing_count($location_grade,$neighborhoods,$prop_type,$building_grade,$amens,$views_grade,$min_floor,$bedrooms,$bedroom_area,$living_area,$living_area,$min_price,$max_price,$select,$from,$where);
  $all_listing_count['homepik_listingcount'] = $homepik_listing_count;

  echo json_encode($all_listing_count);

} else{
  $select = "SELECT vow_data.* ";
  $from = " FROM vow_data ";
  $where = " WHERE (vow_data.status = 'AVAIL') AND ";

  $homepik_listing_count = get_listing_count($location_grade,$neighborhoods,$prop_type,$building_grade,$amens,$views_grade,$min_floor,$bedrooms,$bedroom_area,$living_area,$living_area,$min_price,$max_price,$select,$from,$where);
  $all_listing_count['public_listingcount'] = $homepik_listing_count;
  $all_listing_count['homepik_listingcount'] = '';

   echo json_encode($all_listing_count);
};


function get_listing_count($location_grade,$neighborhoods,$prop_type,$building_grade,$amens,$views_grade,$min_floor,$bedrooms,$bedroom_area,$living_area,$living_area,$min_price,$max_price,$select='',$from,$where)
{
  // location
  $where .= " (vow_data.loc >= " . $location_grade . ")";

  if ($neighborhoods != 'null') {
    $where .= " AND (vow_data.nbrhood = 'none' ";
    foreach ($neighborhoods as $value) {
      if ($value != 'North') {
        $where .=" OR vow_data.nbrhood = '" . $value . "' ";
      } else {
        $where .=" OR vow_data.nbrhood = 'E-North' OR vow_data.nbrhood = 'W-North' ";
      };
    };
    $where .= ") ";
  };

  // COOP or CONDO
  if ($prop_type != 'null') {
    $where .= " AND (vow_data.prop_type = 'none' ";
    foreach ($prop_type as $value) {
      $where .=" OR vow_data.prop_type = '" . $value . "' ";
    };
    $where .= ") ";
  };

  // building
  $where .= " AND (vow_data.bld >= " . $building_grade . ") ";

  // amenities
  echo $amens . "<br><br>";
  if($amens != 'null'){
    foreach ($amens as $value){
      echo $value . "<br><br>";      
      if($value != 'newconstruction' && $value != 'timeshare'){
        if($value != 'outdoor'){
           $where .= " AND (vow_data." . $value . " = '.T.' OR vow_data." . $value . " > 0 )";
        } else {
            $where .= " AND (vow_data.outdoor = '.T.' OR vow_data.outdoor > 0 OR vow_data.roofd = '.T.' OR vow_data.roofd > 0 OR vow_data.garden = '.T.' OR vow_data.garden > 0 OR vow_data.terrace = '.T.' OR vow_data.terrace > 0 OR vow_data.balcony = '.T.' OR vow_data.balcony > 0)";
        }
      }
      else{
        if($value == 'newconstruction'){ $newconstruction = "true"; }
        else if($value == 'timeshare'){ $timeshare = "true"; }
      }
    }
  };

  // views
  $where .= " AND (vow_data.vws >= " . $views_grade . " OR vow_data.vws = 0) ";
  $where .= " AND (vow_data.floor >= " . $min_floor . ") ";

  // size & price
  $where .= " AND (vow_data.bed >= " . $bedrooms . ")";
  $where .= " AND (vow_data.br1_sqf >= " . $bedroom_area . ")";
  $where .= " AND (vow_data.lr_sqf >= " . $living_area . ")";
  if( $timeshare == 'true' ){ $where .= " AND (vow_data.price < 100000)"; }
  else{ $where .= " AND (vow_data.price >= " . $min_price . " AND vow_data.price <= " . $max_price . ")"; }

  echo "nc: " . $newconstruction . "<br><br>";
  
  if($newconstruction == 'true'){
    $from = "FROM `vow_data` LEFT JOIN `Building_file` ON vow_data.address = Building_file.location ";
    $where .= " AND (Building_file.newcon = '1')";
  }

  // calculate the number of rows for the query. We need this for paging the result
  echo "SELECT COUNT(*) AS count " . $from . $where . "<br><br>"; 
}
?>