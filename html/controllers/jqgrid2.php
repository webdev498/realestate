<?php

// JQGRID2.PHP IS ALWAYS REQUESTED BY THE JQGRID2 FUNCTION SEARCH.JS

session_start();
include('functions.php');
include("dbconfig.php");

$user_email = $_SESSION['email'];
// OLD SEARCH

$saved = $_REQUEST['saved'];

if ($saved != 'true') {

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

  if(isset($_GET['address'])){
    $address = $_GET['address'];
  }
  else{
    $address = $_REQUEST['address'];
  }

  $address = explode(" ", $address);
  foreach($address as &$a){
    $a = $a . "%";
  }
  $address[0] = "%" . $address[0];
  $address = join(" ", $address);
  $where = "(address LIKE '".$address."') AND (address NOT LIKE '%.0%')";
};

$amens = $_REQUEST['amenities'];
if($amens != 'null'){
  foreach ($amens as $value){
	  if($value != "newconstruction" && $value != "timeshare"){
			if($value != 'outdoor'){
				  $where .= " AND (" . $value . " = '.T.' OR " . $value . " > 0 )";
			} else {
				  $where .= " AND (outdoor = '.T.' OR outdoor > 0 OR roofd = '.T.' OR roofd > 0 OR garden = '.T.' OR garden > 0 OR terrace = '.T.' OR terrace > 0 OR balcony = '.T.' OR balcony > 0)";
			}
	  }
	  else{
      if($value == 'newconstruction'){ $newconstruction = 'true'; }
      else if($value == 'timeshare'){ $timeshare = 'true'; }
	  }
  }
};

$where .= "AND (status  = 'AVAIL')";

if($newconstruction == 'true'){
  $select = "SELECT vow_data.* ";
  $from = "FROM `vow_data` LEFT JOIN `Building_file` ON vow_data.address = Building_file.location ";
  $where .= " AND (Building_file.newcon = '1')";
}
else{
	  $from = "FROM `vow_data`";
}

// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

// SQL QUERY
// Guests are only allowed to view non COBRK listings or COBRK listings that are in IDX table
if ((authentication() == 'guest')){
  $where .= " AND (";
    $where .= "(";
      $where .= "(vow_data.contract = 'COBRK' AND status = 'AVAIL') AND";
      $where .= " EXISTS(";
        $where .= "SELECT * FROM IDX_Listing_Numbers";
        $where .= " WHERE CONCAT('\"',vow_data.RLS_id,'\"') = IDX_Listing_Numbers.RLS_id";
      $where .= ")";
    $where .= ")";
    $where .= "OR (vow_data.contract <> 'COBRK' AND status = 'AVAIL') ";
  $where .= ") ";
  $SQL = "SELECT * " . $from . " WHERE " . $where;
} else{
	$SQL = "SELECT vow_data.* " . $from . " WHERE " . $where;
};

$result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
$count = mysql_num_rows($result);

// if the index is not provided, use the first column (price) as the default
if (!$sidx)
  $sidx = 1;

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
$result2 = mysql_query("SELECT avg(loc) AS avg_location " . $from . " WHERE $where AND vow_data.loc > 0");
$row = mysql_fetch_array($result2, MYSQL_ASSOC);
$avg_location = number_format($row['avg_location'], 0, '.', ',');

// BUILDING AVERAGE: This gets the average building grade of all returned the search results
$result2 = mysql_query("SELECT avg(bld) AS avg_building " . $from . " WHERE $where AND vow_data.bld > 0");
$row = mysql_fetch_array($result2, MYSQL_ASSOC);
$avg_building = number_format($row['avg_building'], 0, '.', ',');

// VIEWS AVERAGE: This gets the average views grade of all returned the search results
$result2 = mysql_query("SELECT avg(vws) AS avg_views " . $from . " WHERE $where AND vow_data.loc > 0");
$row = mysql_fetch_array($result2, MYSQL_ASSOC);
$avg_views = number_format($row['avg_views'], 0, '.', ',');

// SPACE AVERAGE: This gets the average space  of all returned the search results
$result2 = mysql_query("SELECT avg(vroom_sqf) AS avg_space " . $from . " $where AND vow_data.vroom_sqf > 0");
$row = mysql_fetch_array($result2, MYSQL_ASSOC);
$avg_space = $row['avg_space'];

// SEARCH RESULTLS: the actual query for the grid data
$SQL = "SELECT vow_data.* " . $from . " WHERE $where ORDER BY $sidx $sord LIMIT $start , $limit";
$result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());

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
  $loc = relative_to_average($loc, $avg_location); // check if the location  grade for this listing is higher, lower, or equal to the average for the search results
  $bld = $row['bld'];
  $bld = number_format($bld, 0, '.', ',');
  $bld = relative_to_average($bld, $avg_building);  // check if the building grade for this listing is higher, lower, or equal to the average for the search results
  $vws = $row['vws'];
  $vws = number_format($vws, 0, '.', ',');
  $vws = relative_to_average($vws, $avg_views);  // check if the views grade for this listing is higher, lower, or equal to the average for the search results
  $spac = $row['vroom_sqf'];
  $spac = relative_to_average($spac, $avg_space);  // check if the space for this listing is higher, lower, or equal to the average for the search results
  $bed = $row['bed'];
  $bath = $row['bath'];
  $bath = number_format($bath, 0, '.', ',');
  $address = $row['address'];

  if($address == "7.00000000"){
    continue;
  }

  $photo1 = $row['photo1'];
  if ($photo1 == '') {
    $photo1 = 'http://www.homepik.com/images/nopicture3.png';
  }
  $photo2 = $row['photo2'];

  // if it's a bellmarc building photo, add url location
  $stristr = stristr($photo1, 'http');
  if ($stristr === false) {
    $photo1 = 'http://www.bellmarc.com/pictures/building/' . $photo1 . '.bmp';
  }
  // if the first photos is a floor plan, look for a second photo to use instead
  $stristr = stristr($photo1, 'floor');
  if ($stristr != false) {
    if ($photo2 != '') {
      $photo1 = $photo2;
    };
  };
  $stristr = stristr($photo1, 'plan');
  if ($stristr != false) {
    if ($photo2 != '') {
      $photo1 = $photo2;
    };
  };

  if(strpos($photo1, "JPG")){
    $photo1 = strtolower($photo1);
  }

  if(strpos($photo1, "GIF")){
    $photo1 = strtolower($photo1);
  }

  if(strpos($photo1, "PNG")){
    $photo1 = strtolower($photo1);
  }

  $onerror = 'http://homepik.com/images/nopicture3.png';
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
  else if($_SESSION['buyer']){ $sql = "SELECT * FROM saved_listings WHERE user = '" . $email . "' AND list_num = '" . $list_numb . "'";  }
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
        case "outdoor": $amenity_name = "Outdoor";
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
  if($role =='guest'){ $class = 'save-listing-guest'; }
  else if($role=='buyer'){ $class = 'save-listing-buyer'; }
  else if($role=='agent'){ $class = 'save-listing-agent'; }
  $iconOk = '';
  $iconHeartOk = '';
  $checkTooltip = 'view';
  $heartTooltip = 'save';
  if(in_array($list_numb, $viewedListingsByMe)){ $iconOk = 'color-blue'; $checkTooltip = 'viewed'; }
  if(in_array($list_numb, $savedListingsByMe)){ $iconHeartOk = 'color-blue'; $heartTooltip = 'saved'; }

  $s .= "<row id='" . $list_numb . "'>";
  $s .= "<cell><![CDATA[
      	<p id='abc' title=''><span class='icon-ok ".$iconOk."' data-toggle='tooltip' data-placement='right' title='".$checkTooltip."'></span></br>
	    <span data-text='save' class='icon-heart ".$class." ".$iconHeartOk." heart-list-".$list_numb."' data-toggle='tooltip' data-placement='right' title='".$heartTooltip."'></span></p>
	    <img class='picture' src='" . $photo1 . "' onError='this.onerror=null;this.src=&#39;http://homepik.com/images/nopicture3.png&#39;;' alt='Listing Photo' />
      	<div class='open-list-detail' contextmenu='customMenu' title=''>
      	<div class='address'>" . $address . " <span class='details' style='color:#a0a0a0;'>" . $apt . "</span></div>
      	<div class='code' style='display:none;'>" . $code . "</div>
      	<div class='saved' style='display:none;'>" . $saved . "</div>
              <div class='details' style='margin:3px 0 0 0;'>
      	<div>" . $nbrhood . "</div>
      	<div>" . $building_address . "</div>
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
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Best' class='quality' alt='Best Location Match' src='http://homepik.com/" . $loc . "'/>]]></cell>";
  } elseif( strpos($loc, "silver") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Better' class='quality' alt='Better Location Match' src='http://homepik.com/" . $loc . "'/>]]></cell>";
  } elseif( strpos($loc, "bronze") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Satisfactory' class='quality' alt='Satisfactory Location Match' src='http://homepik.com/" . $loc . "'/>]]></cell>";
  } else{
    $s .= "<cell><![CDATA[<img class='quality' alt='Location Match'  src='http://homepik.com/" . $loc . "'/>]]></cell>";
  }

  if( strpos($bld, "gold") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Best' class='quality' alt='Best Building Match' src='http://homepik.com/" . $bld . "'/>]]></cell>";
  } elseif( strpos($bld, "silver") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Better' class='quality' alt='Better Building Match' src='http://homepik.com/" . $bld . "'/>]]></cell>";
  } elseif( strpos($bld, "bronze") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Satisfactory' class='quality' alt='Satisfactory Building Match' src='http://homepik.com/" . $bld . "'/>]]></cell>";
  } else{
    $s .= "<cell><![CDATA[<img class='quality' alt='Building Match' src='http://homepik.com/" . $bld . "'/>]]></cell>";
  }

  if( strpos($vws, "gold") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Best' class='quality' alt='Best Views Match' src='http://homepik.com/" . $vws . "'/>]]></cell>";
  } elseif( strpos($vws, "silver") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Better' class='quality' alt='Better Views Match' src='http://homepik.com/" . $vws . "'/>]]></cell>";
  } elseif( strpos($vws, "bronze") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Satisfactory' class='quality' alt='Satisfactory Views Match' src='http://homepik.com/" . $vws . "'/>]]></cell>";
  } else{
    $s .= "<cell><![CDATA[<img class='quality' alt='Views Match' src='http://homepik.com/" . $vws . "'/>]]></cell>";
  }

  if( strpos($spac, "gold") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Best' alt='Best Space Match' class='quality' src='http://homepik.com/" . $spac . "'/>]]></cell>";
  } elseif( strpos($spac, "silver") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Better' class='quality' alt='Better Space Match' src='http://homepik.com/" . $spac . "'/>]]></cell>";
  } elseif( strpos($spac, "bronze") !== false ){
    $s .= "<cell><![CDATA[<img data-toggle='tooltip' title='Satisfactory' class='quality' alt='Satisfactory Space Match' src='http://homepik.com/" . $spac . "'/>]]></cell>";
  } else{
    $s .= "<cell><![CDATA[<img class='quality' alt='Space Match' src='http://homepik.com/" . $spac . "'/>]]></cell>";
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