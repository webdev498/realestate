<?php
// JQGRID.PHP IS ALWAYS REQUESTED BY THE JQGRID FUNCTION SEARCH.JS
session_start();
include_once('functions.php');
include_once("dbconfig.php");

$user_email = $_SESSION['email'];
$saved = isset($_REQUEST['saved'])? $_REQUEST['saved']: '';
$curDate = date("Y-m-d");
$getOldDate = @strtotime("-3year", $curDate);
$oldDate = date("Y-m-d", $getOldDate);

if ($saved != 'true') {

  // NEW SEARCH
  // ALL OF THE SEARCH PARAMETERS COME FROM THE JQGRID() FUNCTION IN SEARCH.JS
  // to the url parameter are added 4 parameters as described in colModel
  // we should get these parameters to construct the needed query
  // Get the requested page of search results (e.g. page 1, page 2, page 3, etc). By default grid sets this to 1.
  $page = $_REQUEST['page'];

  // UNCOMMENT THIS WHEN TURNING INTO A VOW IN ORDER TO PASS REBNY APPROVAL -- LIMIT to 240 results in compliance with VOW rules. 240 results fits on 8 pages, so we won't let the user view any further pages.
  /*REBNY RULE
  	if ($page > 8) {
      $page = 8;
    };
  REBNY RULE*/

  // get how many rows we want to have into the grid - rowNum parameter in the grid
  $limit = $_REQUEST['rows'];

  // get index row - i.e. user click to sort by price, location, views, or space. At first time sortname parameter (price) -
  // after that the index from colModel
  $sidx = $_REQUEST['sidx'];

  // is sorting order ascending or descending
  if ($sidx == 'price') {
    $sord = $_REQUEST['sord'];
  } else {
    $sord = 'desc';
  }
	$sord = $_REQUEST['sord'];

  $location_grade = $_REQUEST['location_grade']; // Minimum Location grade from 1 to 10
  $neighborhoods = $_REQUEST['neighborhoods']; // Greenwich Village, Midtown, etc.
  $prop_type = $_REQUEST['prop_type']; // Co-op, Condo, Townhouse, etc.
  $building_grade = $_REQUEST['building_grade']; // Minimum Building grade from 1 to 10
	$amens = $_REQUEST['amenities']; // Doorman, Pool, etc.
  $views_grade = $_REQUEST['views_grade'];  // Minimum Views grade from 1 to 10
  $min_floor = $_REQUEST['min_floor'];  // The lowest acceptable floor, e.g. 8th floor. This feature is not currently available to users, but operational for future versions.
  $max_price = $_REQUEST['max_price']; // Maximum Price
  $max_price = str_replace(",", "", $max_price);
  $min_price = $_REQUEST['min_price']; // Minimum Price
  $min_price = str_replace(",", "", $min_price);
  $bedrooms = $_REQUEST['bedrooms']; // Minimum Number of Bedrooms
  $living_area = $_REQUEST['living_area']; // Minimum Size of Living Room
  $bedroom_area = $_REQUEST['bedroom_area']; // Minimum Size of Bedroom
};

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
if ((authentication() == 'guest')){
	$select = "SELECT vow_data.* ";
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

} else{
  $select = "SELECT vow_data.* ";
  $from = " FROM vow_data ";
  $where = " WHERE (vow_data.status = 'AVAIL') AND ";
};

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
if($amens != 'null'){
  foreach ($amens as $value){
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
$where .= " AND (vow_data.price >= " . $min_price . " AND vow_data.price <= " . $max_price . ")";

if($newconstruction == 'true'){
  $select = "SELECT vow_data.* ";
  $from = "FROM `vow_data` LEFT JOIN `Building_file` ON vow_data.address = Building_file.location ";
  $where .= " AND (Building_file.newcon = '1')";
}

// if the index is not provided, use the first column (price) as the default
if (!$sidx)
  $sidx = 1;

// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

// calculate the number of rows for the query. We need this for paging the result
$result = mysql_query("SELECT COUNT(*) AS count " . $from . $where);
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$count = $row['count'];

// calculate the total pages for the query
if ($count > 0 && $limit > 0) {
  $total_pages = ceil($count / $limit);
} else {
  $total_pages = 0;
}

// if for some reason the requested page is greater than the total
// set the requested page to total page
if ($page > $total_pages)
  $page = $total_pages;

// calculate the starting position of the rows
$start = $limit * $page - $limit;

// if for some reasons start position is negative set it to 0
// typical case is that the user type 0 for the requested page
if ($start < 0)
  $start = 0;

// LOCATION AVERAGE: This gets the average location grade of all returned the search results
$result = mysql_query("SELECT avg(loc) AS avg_location " . $from . $where . " AND loc > 0");
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$avg_location = number_format($row['avg_location'], 0, '.', ',');

// BUILDING AVERAGE: This gets the average building grade of all returned the search results
$result = mysql_query("SELECT avg(bld) AS avg_building " . $from . $where . " AND bld > 0");
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$avg_building = number_format($row['avg_building'], 0, '.', ',');

// VIEWS AVERAGE: This gets the average views grade of all returned the search results
$result = mysql_query("SELECT avg(vws) AS avg_views " . $from . $where . " AND loc > 0");
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$avg_views = number_format($row['avg_views'], 0, '.', ',');

// SPACE AVERAGE: This gets the average space  of all returned the search results
$result = mysql_query("SELECT avg(vroom_sqf) AS avg_space " . $from . $where . " AND vroom_sqf > 0");
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$avg_space = $row['avg_space'];

// SEARCH RESULTS: the actual query for the grid data
$SQL = $select . $from . $where . " ORDER BY $sidx $sord LIMIT $start , $limit";
$result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
$test = $SQL;



/*get viewed listings for user*/
$viewedListingsByMe = array();
if( $_SESSION['role'] !='guest' ){

  $cc1 = mysql_query("SELECT distinct list_num FROM `viewed_listings` WHERE user='".$_SESSION['email']."'");
  while ($cc2 = mysql_fetch_array($cc1, MYSQL_ASSOC)){

     array_push($viewedListingsByMe, $cc2['list_num']);
  }
}
/*get saved listings for user*/
$savedListingsByMe = array();

if($_SESSION['agent']){ $cc1 = mysql_query("SELECT distinct list_num FROM `queued_listings` WHERE user='".$_SESSION['email']."'"); }
else if ($_SESSION['guestID']){ $cc1 = mysql_query("SELECT distinct list_num FROM `saved_listings` WHERE user='".$_SESSION['guestID']."'"); }
else{ $cc1 = mysql_query("SELECT distinct list_num FROM `saved_listings` WHERE user='".$_SESSION['email']."'"); }

while ($cc2 = mysql_fetch_array($cc1, MYSQL_ASSOC)){
   array_push($savedListingsByMe, $cc2['list_num']);
}
// we should set the appropriate header information. Do not forget this.
header("Content-type: text/xml;charset=utf-8");

$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .= "<rows>";
$s .= "<page>" . $page . "</page>";
$s .= "<total>" . $total_pages . "</total>";
$s .= "<average_loc>" . $avg_location . "</average_loc>";
$s .= "<average_building>" . $avg_building . "</average_building>";
$s .= "<average_views>" . $avg_views . "</average_views>";
$s .= "<average_space>" . $avg_space . "</average_space>";
$s .= "<records>" . $count . "</records>";

// be sure to put text data in CDATA
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  $price = $row['price'];
  $price = number_format($price, 0, '.', ',');
  $loc = $row['loc'];
  $loc = number_format($loc, 0, '.', ',');
  $loc = relative_to_average($loc, $avg_location,''); // check if the location  grade for this listing is higher, lower, or equal to the average for the search results
  $bld = $row['bld'];
  $bld = number_format($bld, 0, '.', ',');
  $bld = relative_to_average($bld, $avg_building,'');  // check if the building grade for this listing is higher, lower, or equal to the average for the search results
  $vws = $row['vws'];
  $vws = number_format($vws, 0, '.', ',');
  $vws = relative_to_average($vws, $avg_views,'');  // check if the views grade for this listing is higher, lower, or equal to the average for the search results
  $spac = $row['vroom_sqf'];
  $spac = relative_to_average($spac, $avg_space,'');  // check if the space for this listing is higher, lower, or equal to the average for the search results
  $bed = $row['bed'];
  $bath = $row['bath'];
  $bath = number_format($bath, 0, '.', ',');
  $address = $row['address'];
  $ad = $row['address'];
  $contract = $row['contract'];
  $photo1 = $row['photo1'];
  if ($photo1 == '') {
    $photo1 = 'http://www.homepik.com/images/nopicture3.png';
  };
  $photo2 = $row['photo2'];
  $photo3 = $row['photo3'];
  $photo4 = $row['photo4'];
  $photo5 = $row['photo5'];
  $photo6 = $row['photo6'];
  $photo7 = $row['photo7'];
  $photo8 = $row['photo8'];
  $photo9 = $row['photo9'];
  $photo10 = $row['photo10'];

  //Get all images, Put into array, Search for search terms, Pick first term of array
  $str = 'floor';
  $num = 0;
  $photos = array($photo1, $photo2, $photo3, $photo4, $photo5, $photo6, $photo7, $photo8, $photo9, $photo10);
  foreach($photos as $a) {
    if(stripos($a, $str) === false){
      $matches[$num] = $a;
      $num++;
    }
  }

  $resultPhoto = $matches[0];
  if ($resultPhoto == ""){
    $resultPhoto = $photo1;
  };

  if(strpos($resultPhoto, "JPG")){
    $resultPhoto = strtolower($resultPhoto);
  }

  if(strpos($resultPhoto, "GIF")){
    $resultPhoto = strtolower($resultPhoto);
  }

  if(strpos($resultPhoto, "PNG")){
    $resultPhoto = strtolower($resultPhoto);
  }

  // if it's a bellmarc building photo, add url location
  $stristr = stristr($photo1, 'http');
  if ($stristr === false) {
    $photo1 = 'http://www.bellmarc.com/pictures/building/' . $photo1 . '.bmp';
  }
  $onerror = 'http://www.homepik.com/images/nopicture3.png';

  $floor = $row['floor']; // what floor is it on?
  $floor = number_format($floor, 0, '.', ',');
  $bedroom1 = $row['br1'];
  $bedroom1 = str_replace("X", "' x ", $bedroom1);
  $living_room = $row['lr'];
  $living_room = str_replace("X", "' x ", $living_room);
  $address = str_replace("&", " & ", $address);
  $address = ucwords(strtolower($address));
  $monthly = ($row['maint'] + $row['taxes']); // monthly cost is maintanence plus taxes
  $monthly = number_format($monthly, 0, '.', ',');
  $nbrhood = $row['nbrhood'];
  $apt = str_replace('twnhs','ths',$row['apt']);

  switch ($nbrhood) { // Translate Bellmarc neighborhood codes to standard names
    case "North": $nbrhood = "Far Uptown";
        break;
    case "Eastside": $nbrhood = "Upper East";
        break;
    case "Westside": $nbrhood = "Upper West";
        break;
    case "SMG": $nbrhood = "Midtown East";
        break;
    case "Chelsea": $nbrhood = "Midtown West";
        break;
    case "Village": $nbrhood = "Greenwich Village";
        break;
    case "Lower": $nbrhood = "Downtown";
        break;
  };
  $prop_type = $row['prop_type'];
  $email = $_SESSION['email'];
  $list_numb = $row['list_numb'];
  $session = session_id();
  $code = string_encrypt($list_numb, $session);
  $list_numb = $row['list_numb'];
  if($_SESSION['agent']){ $sql = "SELECT * FROM queued_listings WHERE user = '" . $email . "' AND list_num = '" . $list_numb . "'"; }
  if($_SESSION['buyer']){ $sql = "SELECT * FROM saved_listings WHERE user = '" . $email . "' AND list_num = '" . $list_numb . "'"; }
  else{ $sql = "SELECT * FROM saved_listings WHERE user = '" . $_SESSION['guestID'] . "' AND list_num = '" . $list_numb . "'"; }
  $rs = mysql_query($sql);
  $num = mysql_num_rows($rs);
  if ($num >= 1){ $saved = "saved"; }
  else{ $saved = "save"; }

  $amenities['virtual'] = $row['virtual'];
  $amenities['burnst'] = $row['burnst'];
  $amenities['fireplace'] = $row['fireplace'];
  $amenities['garage'] = $row['garage'];
  $amenities['pool'] = $row['pool'];
  $amenities['roofd'] = $row['roofd'];
  $amenities['garden'] = $row['garden'];
  $amenities['den'] = $row['den'];
  $amenities['din'] = $row['din'];
  $amenities['laundry'] = $row['laundry'];
  $amenities['ac'] = $row['ac'];
  $amenities['security'] = $row['security'];
  $amenities['doorman'] = $row['doorman'];
  $amenities['wheelchair'] = $row['wheelchair'];
  $amenities['elevator'] = $row['elevator'];
  $amenities['healthclub'] = $row['healthclub'];
  $amenities['utilities'] = $row['utilities'];
  $amenities['pets'] = $row['pets'];
  $amenities['furnished'] = $row['furnished'];
  $amenities['prewar'] = $row['prewar'];
  $amenities['loft'] = $row['loft'];
  $amenities['terrace'] = $row['terrace'];
  $amenities['balcony'] = $row['balcony'];
  $amenities['outdoor'] = $row['outdoor'];
  $amenities['wash_dry'] = $row['wash_dry'];
  $amenities['nofee'] = $row['nofee'];
  //$amenities['newCon'] = "F";
  $amenity_icons = "";
  $amenity_names = "";
  $amenity_number = 1;

  foreach ($amenities as $key => $value) {
    $amenity = $key;
    if (($value == '.T.' || $value > 0) && $amenity != "den" && $amenity != "din" && $amenity != "utilities" && $amenity != "loft") {
      switch ($amenity) {
        case "burnst": $amenity_name = "Wood Burning Stove";
            break;
        case "fireplace": $amenity = "fireplace";
            $amenity_name = "Fireplace";
            break;
        case "garage": $amenity_name = "Garage";
            break;
        case "pool": $amenity_name = "Pool";
            break;
        case "roofd": $amenity = "outdoor";
        	$amenity_name = "Outdoor Area";
            break;
        case "garden": $amenity = "outdoor";
            $amenity_name = "Outdoor Area";
            break;
        case "den": $amenity_name = "Den";
            break;
        case "din": $amenity_name = "Dining Room";
            break;
        case "laundry": $amenity_name = "Laundry";
            break;
        case "ac": $amenity_name = "Air Conditioning";
            break;
        case "security": $amenity_name = "Security Guard";
            break;
        case "doorman": $amenity_name = "Doorman";
            break;
        case "wheelchair": $amenity_name = "Wheelchair";
            break;
        case "elevator": $amenity_name = "Elevator";
            break;
        case "healthclub": $amenity_name = "Health Club";
            break;
        case "utilities": $amenity_name = "Utilities Included";
            break;
        case "furnished": $amenity_name = "Furnished";
            break;
        case "prewar": $amenity_name = "Pre-War";
            break;
        case "loft": $amenity_name = "Loft";
            break;
        case "terrace": $amenity = "outdoor";
            $amenity_name = "Outdoor Area";
            break;
        case "balcony": $amenity = "outdoor";
            $amenity_name = "Outdoor Area";
            break;
        case "outdoor": $amenity_name = "Outdoor Area";
            break;
        case "wash_dry": $amenity_name = "Washer/Dryer";
            break;
        case "pets": $amenity_name = "Pets";
            break;
      };
      if ($amenity_number <= 9) {
        if($amenity == "outdoor"){
          if(strpos($amenity_icons, "outdoor") === false){
            $amenity_icons .= "<img src='http://www.homepik.com/images/amenities/" . $amenity . ".png' title='".$amenity_name."' name='" . $amenity_number . "' class='amenity_icons'/>";
            $amenity_names .= $amenity_name . "<br/>";
            $amenity_number = $amenity_number + 1;
          }
        }
        else if($amenity == "burnst"){ /* Do nothing */ }
        else{
          $amenity_icons .= "<img src='http://www.homepik.com/images/amenities/" . $amenity . ".png' title='".$amenity_name."' name='" . $amenity_number . "' class='amenity_icons'/>";
          $amenity_names .= $amenity_name . "<br/>";
          $amenity_number = $amenity_number + 1;
        }
      } else if ($amenity_number <= 10) {
        $amenity_icons .= "<br/><span class='moreamenities'>MORE</span>";
        $amenity_names .= $amenity_name . "<br/>";
        $amenity_number = $amenity_number + 1;
      };
    };
  };

  $SQL4 = "SELECT newcon FROM Building_file WHERE (location = '".$ad."')";
  $result4 = mysql_query($SQL4) or die("Couldn't execute query." . mysql_error());
  $row4 = mysql_fetch_array($result4, MYSQL_ASSOC);
  if($row4['newcon'] == '1'){
	  if ($amenity_number <= 9) {
        $amenity_icons .= "<img src='http://homepik.com/images/amenities/newconstruction.png' name='" . $amenity_number . "' class='amenity_icons'/>";
      } else if ($amenity_number <= 10) {
        $amenity_icons .= "<br/><span class='moreamenities'>MORE</span>";
      };
	  $amenity_names .= "New Construction <br/>";
      $amenity_number = $amenity_number + 1;
  }

  switch ($prop_type) {
    case 0 : $prop_type = '';
        break;
    case 1 : $prop_type = 'Co-op';
        break;
    case 2 : $prop_type = 'Condo';
        break;
    case 3 : $prop_type = 'Condo';
        break;
    case 4 : $prop_type = 'House';
        break;
    case 5 : $prop_type = 'Cond-op';
        break;
  }

  $role = $_SESSION['role'];
  if($role == 'guest'){
    $class = 'save-listing-guest';
  } else if($role == 'buyer'){
    $class = 'save-listing-buyer';
  }
  else if($role == 'agent'){
    $class = 'save-listing-agent';
  }

  $iconOk = '';
  $iconHeartOk = '';
  $checkTooltip = 'view';
  $heartTooltip = 'save';

  if(in_array($list_numb, $viewedListingsByMe)){ $iconOk = 'color-blue'; $checkTooltip = 'viewed'; }
  if(in_array($list_numb, $savedListingsByMe)){ $iconHeartOk = 'color-blue'; $heartTooltip = 'saved'; }

  $s .= "<row id='" . $list_numb . "'>";
  $s .= "<cell><![CDATA[
    <p id='abc' title=''><span class='icon-ok ".$iconOk."' data-toggle='tooltip' data-placement='right' title='".$checkTooltip."'></span></br>
      <span data-text='save' class='icon-heart ".$class." ".$iconHeartOk." heart-list-".$list_numb."' data-list-numb='".$list_numb."' data-toggle='tooltip' data-placement='right' title='".$heartTooltip."'></span></p>
      <img title='' class='picture  open-list-detail' src='" . $resultPhoto . "' onError='this.onerror=null;this.src=&#39;images/nopicture3.png&#39;;' alt='Listing Photo' />
      <div class='open-list-detail' contextmenu='customMenu' title=''>
      <div class='address'>" . $address . " <span class='details' style='color:#a0a0a0;'>" . $apt . "</span></div>
      <div class='code' style='display:none;'>" . $code . "</div>
      <div class='saved' style='display:none;'>" . $saved . "</div>
            <div class='details' style='margin:3px 0 0 0;'>
      <div>" . $nbrhood . "</div>
      <div>" . $bed . " BRs <span style='color:#a0a0a0;'>&nbsp; " . $bath . " baths</span</div>
      <div style='margin:6px 0 0 0;color:#a0a0a0;'>Master Bed: " . $bedroom1 . "'</div>
      <div style='color:#a0a0a0;'>Living Room: " . $living_room . "'</div>
      </div>
      </div>
      ]]></cell>";
  $s .= "<cell><![CDATA[
    <div style='font-size: 18px;'>$ ".$price."</div>
    <div style='margin:12px 0 0 0;color:#a0a0a0;' class='details'><div style='margin:0px 0px 8px 0px;'>" . $prop_type . "</div><div style='font-size:0.95em;'>Monthly Charges<div>$" . $monthly . "/mo</div>
    ]]></cell>";
  $s .= "<cell><![CDATA[<div id='amenity_icons' title='" . $amenity_names . "'>" . $amenity_icons . "</div>]]></cell>";

  if( strpos($loc, "gold") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Best' class='quality view-bubble-grades' style='cursor: pointer' alt='Best Location Match' src='http://homepik.com/" . $loc . "'/>]]></cell>";
  } elseif( strpos($loc, "silver") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Better' class='quality view-bubble-grades' style='cursor: pointer' alt='Better Location Match' src='http://homepik.com/" . $loc . "'/>]]></cell>";
  } elseif( strpos($loc, "bronze") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Satisfactory' class='quality view-bubble-grades' style='cursor: pointer' alt='Satisfactory Location Match' src='http://homepik.com/" . $loc . "'/>]]></cell>";
  } else{
    $s .= "<cell><![CDATA[<img class='quality view-bubble-grades' style='cursor: pointer' alt='Location Match' src='http://homepik.com/" . $loc . "'/>]]></cell>";
  }

  if( strpos($bld, "gold") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Best' alt='Best Building Match' class='quality view-bubble-grades' style='cursor: pointer' src='http://homepik.com/" . $bld . "'/>]]></cell>";
  } elseif( strpos($bld, "silver") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Better' alt='Better Building Match' class='quality view-bubble-grades' style='cursor: pointer' src='http://homepik.com/" . $bld . "'/>]]></cell>";
  } elseif( strpos($bld, "bronze") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Satisfactory' alt='Satisfactory Building Match' class='quality view-bubble-grades' style='cursor: pointer' src='http://homepik.com/" . $bld . "'/>]]></cell>";
  } else{
    $s .= "<cell><![CDATA[<img class='quality view-bubble-grades' style='cursor: pointer' alt='Building Match' src='http://homepik.com/" . $bld . "'/>]]></cell>";
  }

  if( strpos($vws, "gold") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Best' alt='Best Views Match' class='quality view-bubble-grades' style='cursor: pointer' src='http://homepik.com/" . $vws . "'/>]]></cell>";
  } elseif( strpos($vws, "silver") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Better' alt='Better Views Match' class='quality view-bubble-grades' style='cursor: pointer' src='http://homepik.com/" . $vws . "'/>]]></cell>";
  } elseif( strpos($vws, "bronze") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Satisfactory' alt='Satisfactory Views Match' class='quality view-bubble-grades' style='cursor: pointer' src='http://homepik.com/" . $vws . "'/>]]></cell>";
  } else{
    $s .= "<cell><![CDATA[<img class='quality view-bubble-grades' style='cursor: pointer' alt='Views Match' src='" . $vws . "'/>]]></cell>";
  }

  if( strpos($spac, "gold") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Best' class='quality view-bubble-grades' style='cursor: pointer' alt='Best Space Match' src='http://homepik.com/" . $spac . "'/>]]></cell>";
  } elseif( strpos($spac, "silver") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Better' class='quality view-bubble-grades' style='cursor: pointer' alt='Better Space Match' src='http://homepik.com/" . $spac . "'/>]]></cell>";
  } elseif( strpos($spac, "bronze") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Satisfactory' class='quality view-bubble-grades' style='cursor: pointer' alt='Satisfactory Space Match' src='http://homepik.com/" . $spac . "'/>]]></cell>";
  } else{
    $s .= "<cell><![CDATA[<img class='quality view-bubble-grades' style='cursor: pointer' alt='Space Match' src='http://homepik.com/" . $spac . "'/>]]></cell>";
  }

  $s .= "</row>";
}

$s .= "</rows>";
if ((authentication() == 'agent') OR (authentication() == 'user') OR (authentication() == 'anonymous') OR (authentication() == 'guest')) {
  // Rate limit to prevent scraping
  $limit = limit();
  if ($limit != 'clear') {
    limit();
  } else {
    // return the search results
    print $s;
  };
  $time = date('U');
  $result = mysql_query("INSERT INTO `sp`.`search_history` (`user`,`time`, `page`, `limit`, `six`, `sod`, `location-grade`, `neighborhoods`, `building_grade`, `views_grade`, `min_floor`, `max_price`, `min_price`, `bedrooms`, `living_area`, `bedroom_area`) VALUES  ('" . $user_email . "','" . $time . "','" . $page . "','" . $limit . "','" . $sidx . "','" . $sord . "','" . $location_grade . "','" . serialize($neighborhoods) . "','" . $building_grade . "','" . $views_grade . "','" . $min_floor . "','" . $max_price . "','" . $min_price . "','" . $bedrooms . "','" . $living_area . "','" . $bedroom_area . "')") or die(mysql_error());
};

?>
