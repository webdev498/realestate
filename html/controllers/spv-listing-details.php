<?php

if($list_num != ''){
  // get listing data
  $SQL = "SELECT address, zip, apt, price, bed, bath, prop_type, nbrhood, loc, bld, vws, vroom_sqf, floor, maint, taxes, fireplace, roofd, garden, doorman, elevator, terrace, balcony FROM vow_data WHERE (list_numb = '" . $list_num . "') ";
  $result = mysql_query($SQL) or die(mysql_error()." ".$SQL);
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  
  $address = $row['address'];
  $address = str_replace("&", " & ", $address);
  $address = ucwords(strtolower($address));
  $zip = $row['zip'];
  $apt = $row['apt'];
  $price = (isset($listing_price) ? preg_replace("/[^0-9]/", "", $listing_price) : $row['price'] );
  $price_uf = $price;
  $minPriceRange = $price - ($price * ($interest / 100));
  $minPriceRange = number_format($minPriceRange, 0, '.', ',');
  $maxPriceRange = $price + ($price * ($interest / 100));
  $maxPriceRange = number_format($maxPriceRange, 0, '.', ',');
  $price = number_format($price, 0, '.', ',');
  $bed = (isset($min_bed) ? $min_bed : $row['bed']);
  $bath = (isset($min_bath) ? $min_bath : $row['bath']);
  $bath = number_format($bath, 0, '.', ',');
  $property = $row['prop_type'];
  $property = number_format($property, 0, '.', ',');
  switch ($property){
    case "1": $property = "Coop"; break;
    case "2": $property = "Condo"; break;
    case "4": $property = "House/Townhouse"; break;
    case "5": $property = "Condop"; break;
  };
  $nbrhood = $row['nbrhood'];
  $nhood = $row['nbrhood'];
  switch ($nbrhood){
    case "North": $nbrhood = "Far Uptown"; break;
    case "Eastside": $nbrhood = "Upper East Side"; break;
    case "Westside": $nbrhood = "Upper West Side"; break;
    case "SMG": $nbrhood = "Midtown West"; break;
    case "Chelsea": $nbrhood = "Midtown East"; break;
    case "Village": $nbrhood = "Greenwich Village"; break;
    case "Lower": $nbrhood = "Downtown"; break;
  };
  $loc = (isset($min_loc) ? $min_loc : $row['loc']);
  $bld = (isset($min_bld) ? $min_bld : $row['bld']);
  $vws = (isset($min_vws) ? $min_vws : $row['vws']);
  $space = (isset($min_space) ? preg_replace("/[^0-9]/", "", floor($min_space)) : $row['vroom_sqf']);
  $space_uf = $space;
  $space = number_format($space, 0, '.', ',');
  $floor = (isset($min_floor) ? $min_floor : $row['floor']);
  $floor = number_format($floor, 0, '.', ',');
  $monthly = (isset($min_maint) ? preg_replace("/[^0-9]/", "", $min_maint) : $row['maint'] + $row['taxes']);
  $monthly_uf = $monthly;
  $monthly = number_format($monthly, 0, '.', ',');  
  
  if($location == "Local"){
    if($listings == "Active"){ $where = "WHERE (nbrhood = '" . $nhood . "') AND (status = 'AVAIL')"; }
    elseif($listings == "Historical"){ $where = "WHERE (nbrhood = '" . $nhood . "') AND (status != 'AVAIL')"; }
    else{ $where = "WHERE (nbrhood = '" . $nhood . "')"; }
  }
  elseif($location == "Zip"){
    if($listings == "Active"){ $where = "WHERE (zip = '" . $zip . "') AND (status = 'AVAIL')"; }
    elseif($listings == "Historical"){ $where = "WHERE (zip = '" . $zip . "') AND (status != 'AVAIL')"; }
    else{ $where = "WHERE (zip = '" . $zip . "')"; }
  }
  else{
    if($listings == "Active"){ $where = "WHERE (status = 'AVAIL')"; }
    elseif($listings == "Historical"){ $where = "WHERE (status != 'AVAIL')"; }
    else{ $where = "WHERE ((status = 'AVAIL') OR (status != 'AVAIL'))"; }
  }
  
  // Additional Filters
  $additional_filters = "";
  $where2 = "";
  if($condo_only == "yes"){
    $additional_filters .= "Listing activity in the past 12 months<span class='lineSeperator'>|</span> Condos only";
    $where2 .= " AND (prop_type = '2')";
  }
  if($min_bld != $row['bld'] && $min_bld > 0){
    if($additional_filters == ""){ $additional_filters .= "Listing activity in the past 12 months<span class='lineSeperator'>|</span> <span class='titleText'>BLDG</span> >= $min_bld"; }
    else{ $additional_filters .= "<span class='lineSeperator'>|</span> <span class='titleText'>BLDG</span> >= $min_bld"; }
    $where2 .= " AND (bld >= '".$min_bld."')";
  }
  if($min_loc != $row['loc'] && $min_loc > 0){
    if($additional_filters == ""){ $additional_filters .= "Listing activity in the past 12 months<span class='lineSeperator'>|</span> <span class='titleText'>LOC</span> >= $min_loc"; }
    else{ $additional_filters .= "<span class='lineSeperator'>|</span> <span class='titleText'>LOC</span> >= $min_loc"; }
    $where2 .= " AND (loc >= '".$min_loc."')";
  }
  if($min_vws != $row['vws'] && $min_vws > 0){
    if($additional_filters == ""){ $additional_filters .= "Listing activity in the past 12 months<span class='lineSeperator'>|</span> <span class='titleText'>VIEW</span> >= $min_vws"; }
    else{ $additional_filters .= "<span class='lineSeperator'>|</span> <span class='titleText'>VIEW</span> >= $min_vws"; }
    $where2 .= " AND (vws >= '".$min_vws."')";
  }
  if($min_floor != $row['floor'] && $min_floor > 0){
    if(substr($min_floor, -1) == "1" && $min_floor != "11"){ $ending = "st"; }
    else if(substr($min_floor, -1) == "2" && $min_floor != "12"){ $ending = "nd"; }
    else if(substr($min_floor, -1) == "3" && $min_floor != "13"){ $ending = "rd"; }
    else{ $ending = "th"; }
    if($additional_filters == ""){ $additional_filters .= "Listing activity in the past 12 months<span class='lineSeperator'>|</span> <span class='titleText'>on or above</span> ".$min_floor.$ending." floor"; }
    else{ $additional_filters .= "<span class='lineSeperator'>|</span> <span class='titleText'>on or above</span> ".$min_floor."th floor"; }
    $where2 .= " AND (floor >= '".$min_floor."')";
  }
  if($min_space != number_format($row['vroom_sqf'], 0, '.', ',') && $min_space > 0){ $where2 .= " AND (vroom_sqf >= '".$min_space."')"; }
  if($min_maint != number_format(($row['maint'] + $row['taxes']), 0, '.', ',') && $min_maint > 0){ $where2 .= " AND ( (`maint` + `taxes`) >= '".$min_maint."')"; }
  /* if($max_maint > 0){ $where2 .= " AND ( (`maint` + `taxes`) <= '".$max_maint."')"; } */
  if(in_array("fireplace", $amenity)){ $where2 .= " AND (fireplace = '1')"; }
  if(in_array("elevator", $amenity)){ $where2 .= " AND (elevator = '1')"; }
  if(in_array("balcony", $amenity)){ $where2 .= " AND (balcony = '1')"; }
  if(in_array("terrace", $amenity)){ $where2 .= " AND (terrace = '1')"; }
  if(in_array("setBack", $amenity)){ $where2 .= " AND (terrace = '1')"; }
  if(in_array("garden", $amenity)){ $where2 .= " AND (garden = '1' OR roofd = '1')"; }
  
  //EVALUATION DETAILS SECTION
  // NUMBER OF LISTINGS NO FILTER: This gets the number of listings without filters returned the search results
  $result = mysql_query("SELECT COUNT(*) AS num_listings FROM vow_data " . $where ." AND (list_numb != '". $list_num ."')");
  $row2 = mysql_fetch_array($result, MYSQL_ASSOC);
  $num_listings_no_filter = $row2['num_listings'];
  
  // NUMBER OF LISTINGS FILTER: This gets the number of listings with filters returned the search results
  $result = mysql_query("SELECT COUNT(*) AS num_listings FROM vow_data " . $where . $where2 . " AND (list_numb != '". $list_num ."')");
  $row2 = mysql_fetch_array($result, MYSQL_ASSOC);
  $num_listings_filtered = $row2['num_listings'];
  
  // PRICE AVERAGE: This gets the average price of all returned the search results
  $result = mysql_query("SELECT avg(price) AS avg_price FROM vow_data " . $where . $where2);
  $row2 = mysql_fetch_array($result, MYSQL_ASSOC);
  $avg_price = $row2['avg_price'];
  
  // LOCATION AVERAGE: This gets the average location grade of all returned the search results
  $result = mysql_query("SELECT avg(loc) AS avg_location FROM vow_data " . $where . $where2 . " AND loc > 0");
  $row2 = mysql_fetch_array($result, MYSQL_ASSOC);
  $avg_location = number_format($row2['avg_location'], 0, '.', ',');
  
  // BUILDING AVERAGE: This gets the average building grade of all returned the search results
  $result = mysql_query("SELECT avg(bld) AS avg_building FROM vow_data " . $where . $where2 . " AND bld > 0");
  $row2 = mysql_fetch_array($result, MYSQL_ASSOC);
  $avg_building = number_format($row2['avg_building'], 0, '.', ',');
  
  // VIEWS AVERAGE: This gets the average views grade of all returned the search results
  $result = mysql_query("SELECT avg(vws) AS avg_views FROM vow_data " . $where . $where2 . " AND vws > 0");
  $row2 = mysql_fetch_array($result, MYSQL_ASSOC);
  $avg_views = number_format($row2['avg_views'], 0, '.', ',');
  
  // FLOOR AVERAGE: This gets the average floor grade of all returned the search results
  $result = mysql_query("SELECT avg(floor) AS avg_floor FROM vow_data " . $where . $where2 . " AND floor > 0");
  $row2 = mysql_fetch_array($result, MYSQL_ASSOC);
  $avg_floor = number_format($row2['avg_floor'], 0, '.', ',');
  
  // SPACE AVERAGE: This gets the average space of all returned the search results
  $result = mysql_query("SELECT avg(vroom_sqf) AS avg_space FROM vow_data " . $where . $where2 . " AND vroom_sqf > 0");
  $row2 = mysql_fetch_array($result, MYSQL_ASSOC);
  $avg_space_uf = $row2['avg_space'];
  $avg_space = number_format($row2['avg_space'], 0, '.', ',');
  
  // MONTHLY AVERAGE: This gets the average monthly charge of all returned the search results
  $result = mysql_query("SELECT SUM(maint+taxes)/COUNT(*) AS avg_monthly FROM vow_data " . $where . $where2);
  $row2 = mysql_fetch_array($result, MYSQL_ASSOC);
  $avg_monthly_uf = $row2['avg_monthly'];
  $avg_monthly = number_format($row2['avg_monthly'], 0, '.', ',');
  
  // LOCATION EVALUATION AND ADJUSTMENT
  $location_diff = (($avg_location - $loc) * -1);
  if(abs($location_diff) == 2){ $location_interest = 15; }
  elseif(abs($location_diff) == 3){ $location_interest = 25; }
  elseif(abs($location_diff) >= 4){ $location_interest = 40; }
  else{ $location_interest = 0; }
  $loc_adjust = $price_uf * ($location_interest / 100);
  if($location_diff > 1){
    $location_eval = "Location is " . abs($location_diff) . " grades higher than average, increasing the value by " . $location_interest . "%";
    $location_adjustment = "$" . number_format($loc_adjust, 0, '.', ',');
  }
  elseif($location_diff < -1){
    $location_eval = "Location is " . abs($location_diff) . " grades lower than average, decreasing the value by " . $location_interest . "%";
    $location_adjustment = "-$" . number_format($loc_adjust, 0, '.', ',');
    $loc_adjust = $loc_adjust * -1;
  }
  else{
    $location_eval = "No material difference in location grade. No change in value.";
    $location_adjustment = "$0";
  }
  
  //BUILDING EVALUATION AND ADJUSTMENT
  $building_diff = (($avg_building - $bld) * -1);
  if(abs($building_diff) == 2){ $building_interest = 15; }
  elseif(abs($building_diff) == 3){ $building_interest = 25; }
  elseif(abs($building_diff) >= 4){ $building_interest = 40; }
  else{ $building_interest = 0; }
  $bld_adjust = $price_uf * ($building_interest / 100);
  if($building_diff > 1){
    $building_eval = "Building is " . abs($building_diff) . " grades higher than average, increasing the value by " . $building_interest . "%";
    $building_adjustment = "$" . number_format($bld_adjust, 0, '.', ',');
  }
  elseif($building_diff < -1){
    $building_eval = "Building is " . abs($building_diff) . " grades lower than average, decreasing the value by " . $building_interest . "%";
    $building_adjustment = "-$" . number_format($bld_adjust, 0, '.', ',');
    $bld_adjust = $bld_adjust * -1;
  }
  else{
    $building_eval = "No material difference in building grade. No change in value.";
    $building_adjustment = "$0";
  }
  
  //VIEW EVALUATION AND ADJUSTMENT
  $views_diff = (($avg_views - $vws) * -1);
  if(abs($views_diff) == 2){ $views_interest = 15; }
  elseif(abs($views_diff) == 3){ $views_interest = 25; }
  elseif(abs($views_diff) >= 4){ $views_interest = 40; }
  else{ $views_interest = 0; }
  $vws_adjust = $price_uf * ($views_interest / 100);
  if($views_diff > 1){
    $views_eval = "View is " . abs($views_diff) . " grades higher than average, increasing the value by " . $views_interest . "%";
    $views_adjustment = "$" . number_format($vws_adjust, 0, '.', ',');
  }
  elseif($views_diff < -1){
    $views_eval = "View is " . abs($views_diff) . " grades lower than average, decreasing the value by " . $views_interest . "%";
    $views_adjustment = "-$" . number_format($vws_adjust, 0, '.', ',');
    $vws_adjust = $vws_adjust * -1;
  }
  else{
    $views_eval = "No material difference in view grade. No change in value.";
    $views_adjustment = "$0";
  }
  
  //FLOOR EVALUATION AND ADJUSTMENT
  $floor_diff = (($avg_floor - $floor) * -1);
  $floor_adjust_per = $price_uf * 0.005;
  $floor_adjust = $floor_adjust_per * abs($floor_diff);
  if($floor_diff >= 0){
    $floor_eval = "Floor is " . abs($floor_diff) . " higher than average. Calculated at ($" . number_format($floor_adjust_per, 0, '.', ',') . ") per floor";
  }
  elseif($floor_diff < 0){
    $floor_eval = "Floor is " . abs($floor_diff) . " lower than average. Calculated at (-$" . number_format($floor_adjust_per, 0, '.', ',') . ") per floor";
    $floor_adjust = $floor_adjust * -1;
  }
  
  /* Add doorman discount */
  if($bld > 5 && ($floor == 1 || $floor == 2)){
    if($floor == 1){
      $floor_eval .= ". First floor subject to $35,000 discount";
      $floor_adjust = $floor_adjust - 35000;
    }
    elseif($floor == 2){
      $floor_eval .= ". Second floor subject to $25,000 discount";
      $floor_adjust = $floor_adjust - 25000;
    }
  }
  
  /* Add walkup discount */
  if($row['elevator'] == "0"){
    if($floor == 3){
      $floor_eval .= ". Third floor unit in walk-up building, 20% discount";
      $floor_adjust = $floor_adjust - ($price * 0.2);
    }
    elseif($floor == 4){
      $floor_eval .= ". Fourth floor unit in walk-up building, 25% discount";
      $floor_adjust = $floor_adjust - ($price * 0.25);
    }
    elseif($floor == 5){
      $floor_eval .= ". Fifth floor unit in walk-up building, 30% discount";
      $floor_adjust = $floor_adjust - ($price * 0.3);
    }
  }
  
  /* Format Floor Adjustment */
  if($floor_adjust >= 0){ $floor_adjustment = "$" . number_format($floor_adjust, 0, '.', ','); }
  elseif($floor_adjust < 0){ $floor_adjustment = "-$" . number_format($floor_adjust, 0, '.', ','); }
  
  //SPACE EVALUATION AND ADJUSTMENT
  $space_diff = (($avg_space_uf - $space_uf) * -1);
  $avg_per_sq_ft = ($avg_space_uf > 0 ? ($avg_price / $avg_space_uf) : 0);
  $space_adjust = round($avg_per_sq_ft) * round(abs($space_diff));
  if($space_diff >= 0){
    $space_eval = "Value Rooms are $" . number_format($avg_per_sq_ft, 0, '.', ',') . " higher than the average price per sq. foot x " . number_format($space_diff, 0, '.', ',') . " sq. feet.";
    $space_diff = number_format($space_diff, 0, '.', ',');
    $space_adjustment = "$" . number_format($space_adjust, 0, '.', ',');
    $space_adjust = $space_adjust * -1;
  }
  elseif($space_diff < 0){
    $space_eval = "Value Rooms are $" . number_format($avg_per_sq_ft, 0, '.', ',') . " lower than the average price per sq. foot x " . number_format(abs($space_diff), 0, '.', ','). " sq. feet.";
    $space_diff = number_format($space_diff, 0, '.', ',');
    $space_adjustment = "-$" . number_format($space_adjust, 0, '.', ',');
    }
  
  //MONTHLY EVALUATION AND ADJUSTMENT
  $monthly_diff = (($avg_monthly_uf - $monthly_uf) * -1);
  $monthly_equation = "$" . number_format(abs($monthly_diff), 0, '.', ',') . "&nbsp; x&nbsp; 12&nbsp; =&nbsp; " . number_format((abs($monthly_diff) * 12), 0, '.', ',') . "&nbsp; /&nbsp; 5.000%";
  $monthly_adjust = (abs($monthly_diff) * 12) / (5/100);
  if($monthly_diff >= 0){
    $monthly_diff = "$" . number_format($monthly_diff, 0, '.', ',');
    $monthly_adjustment = "$" . number_format($monthly_adjust, 0, '.', ',');
  }
  elseif($monthly_diff < 0){
    $monthly_diff = "-$" . number_format(abs($monthly_diff), 0, '.', ',');
    $monthly_adjustment = "-$" . number_format($monthly_adjust, 0, '.', ',');
    $monthly_adjust = $monthly_adjust * -1;
  }
  
  //AMENITIES EVALUATION AND ADJUSTMENT
  $amenities = "";
  $amenities_adjustment = 0;
  if($row['fireplace'] == '1'){
    $amenities .= "Fireplace";
    if($bed < 2){ $amenities_adjustment = $amenities_adjustment + 25000; }
    elseif($bed >= 2){ $amenities_adjustment = $amenities_adjustment + 50000; }
  }
  /*
  if($row['terrace'] == '1'){
    if($amenities != ""){ $amenities .= ", Balcony/Terrace"; }
    else{ $amenities .= "Balcony/Terrace"; }
  }
  if($row['balcony'] == '1'){
    if($amenities == ""){ $amenities .= "Balcony/Terrace"; }
    elseif($amenity['terrace'] == '0'){ $amenities .= ", Balcony/Terrace"; }
  }
  */
  
  // FLOOR/GENERAL EVALUATION AND ADJUSTMENT
  switch($condition){
    case "Level 1":
      $condition_eval = "Complete Rehabilitation Required, value decreased by 20%.";
      $condition_interest = 20;
      $condition_status = "discount";
      break;
    case "Level 2":
      $condition_eval = "Obsolete Components in Need of Replacement, value decreased by 10%.";
      $condition_interest = 10;
      $condition_status = "discount";
      break;
    case "Level 3":
      $condition_eval = "Premium Quality High Luxury";
      $condition_interest = 0;
      $condition_status = "none";
      break;
    case "Level 4":
      $condition_eval = "High Quality Decorator Improvements, value increased by 5%.";
      $condition_interest = 5;
      $condition_status = "premium";
      break;
    case "Level 5":
      $condition_eval = "Decorator Showcase. Triple Min Condition, value increased by 20%.";
      $condition_interest = 10;
      $condition_status = "premium";
      break;
  }
  
  $condition_adjust = $price_uf * ($condition_interest / 100);  
  if($condition_status == "discount"){
    $condition_adjustment = "-$" . number_format($condition_adjust, 0, '.', ',');
    $condition_adjust = $condition_adjust * -1;
  }
  elseif($condition_status == "premium"){    
    $condition_adjustment = "$" . number_format($condition_adjust, 0, '.', ',');
  }
  else{
    $condition_adjustment = "$0";
  }
  
  //TOTAL ADJUSTMENTS
  $total_prop_adjustment = $loc_adjust + $bld_adjust + $vws_adjust + $floor_adjust + $monthly_adjust + $space_adjust + $amenities_adjustment + $condition_adjust;
  $prop_val_adjustment = $price_uf + $total_prop_adjustment;
  $total_prop_adjustment = number_format(abs($total_prop_adjustment), 0, '.', ',');
  $prop_val_adjustment = number_format(abs($prop_val_adjustment), 0, '.', ',');
  
  
  // MARKET PLACE NEGOTIABILITY SECTION
  
  //INVENTORY EVALUATION AND ADJUSTMENTS
  if($num_listings_filtered <= 40){
    $inventory_eval = "$num_listings_filtered comparable units; a normal supply of properties, no additional adjustment is needed.";
    $inventory_adjust = 0;
    $inventory_adjustment = "$" . number_format($inventory_adjust, 0, '.', ',');
  }
  else{
    $inventory_eval = "$num_listings_filtered comparable units; an abundance of properties requires a downward adjustment of 10%";
    $inventory_adjust = $price_uf * (10 / 100);
    $inventory_adjustment = "-$" . number_format($inventory_adjust, 0, '.', ',');
    $inventory_adjust = $inventory_adjust * -1;
  }
  
  //DEMAND EVALUATION AND ADJUSTMENTS
  switch($demand){
    case "Level 1":
      $demand_eval = "Appreciating market, value discount is 3%.";
      $demand_discount = 3;
      break;
    case "Level 2":
      $demand_eval = "A stable market, value discount is 5%.";
      $demand_discount = 5;
      break;
    case "Level 3":
      $demand_eval = "A slightly declining market, value discount is 7%.";
      $demand_discount = 7;
      break;
    case "Level 4":
      $demand_eval = "A dramatically declining market, value discount is 10%.";
      $demand_discount = 10;
      break;
  }
  
  $demand_adjust = $price_uf * ($demand_discount / 100);  
  $demand_adjustment = "-$" . number_format($demand_adjust, 0, '.', ',');
  $demand_adjust = $demand_adjust * -1;
  
  $total_adjustment = $inventory_adjust + $demand_adjust;
  $val_adjustment = $price_uf + $total_adjustment;
  $total_adjustment = number_format(abs($total_adjustment), 0, '.', ',');
  $val_adjustment = number_format(abs($val_adjustment), 0, '.', ',');
  
  $compare_listings = array();
  $result = mysql_query("SELECT price, address, apt, loc, bld, vws, vroom_sqf, floor, bed, maint, taxes, virtual, burnst, fireplace, garage, pool, roofd, garden, den, din, laundry, ac, security, doorman, wheelchair, elevator, healthclub, utilities, pets, furnished, prewar, loft, terrace, balcony, outdoor, wash_dry, nofee FROM vow_data " . $where . $where2 . " AND (list_numb != '". $list_num ."')") or die(mysql_error());
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
    $c_address = $row['address'];
    $c_address = str_replace("&", " & ", $c_address);
    $c_address = ucwords(strtolower($c_address));
    
    if($c_address == $address){ $same_build = "<img src='/images/check.png' height='10'/>"; }
    else{ $same_build = "&nbsp;"; }
    
    $c_price = $row['price'];
    $c_price = number_format($c_price, 0, '.', ',');
    $c_loc = number_format($row['loc'], 0, '.', ',');
    if($c_loc > $loc){ $c_loc = "+"; }
    elseif($c_loc < $loc){ $c_loc = "-"; }
    else{ $c_loc = "="; }
    $c_bld = number_format($row['bld'], 0, '.', ',');
    if($c_bld > $bld){ $c_bld = "+"; }
    elseif($c_bld < $bld){ $c_bld = "-"; }
    else{ $c_bld = "="; }
    $c_vws = number_format($row['vws'], 0, '.', ',');
    if($c_vws > $vws){ $c_vws = "+"; }
    elseif($c_vws < $vws){ $c_vws = "-"; }
    else{ $c_vws = "="; }
    $c_space = number_format($row['vroom_sqf'], 0, '.', ',');
    $c_floor = number_format($row['floor'], 0, '.', ',');
    if($c_floor > $floor){ $c_floor = "+"; }
    elseif($c_floor < $floor){ $c_floor = "-"; }
    else{ $c_floor = "="; }
    $c_monthly = ($row['maint'] + $row['taxes']);
    $c_monthly = number_format($c_monthly, 0, '.', ',');
    
    // Amenities
    $amenity_count = 0;
    $c_amenities = "";
    if($row['prewar'] == "1" && $amenity_count < 6){ $c_amenities .= "PREW&nbsp;&nbsp;&nbsp;&nbsp;"; $amenity_count++; }
    if($row['ac'] == "1" && $amenity_count < 6){ $c_amenities .= "COND&nbsp;&nbsp;&nbsp;&nbsp;"; $amenity_count++; }
    if($row['doorman'] == "1" && $amenity_count < 6){ $c_amenities .= "DRMN&nbsp;&nbsp;&nbsp;&nbsp;"; $amenity_count++; }
    if($row['garage'] == "1" && $amenity_count < 6){ $c_amenities .= "GRGE&nbsp;&nbsp;&nbsp;&nbsp;"; $amenity_count++; }
    if(($row['laundry'] == "1" || $row['wash_dry'] == "1") && $amenity_count < 6){ $c_amenities .= "LNDR&nbsp;&nbsp;&nbsp;&nbsp;"; $amenity_count++; }
    if($row['pets'] == "1" && $amenity_count < 6){ $c_amenities .= "PETS&nbsp;&nbsp;&nbsp;&nbsp;"; $amenity_count++; }
    if($row['pool'] == "1" && $amenity_count < 6){ $c_amenities .= "POOL&nbsp;&nbsp;&nbsp;&nbsp;"; $amenity_count++; }
    if($row['roofd'] == "1" && $amenity_count < 6){ $c_amenities .= "RFDK&nbsp;&nbsp;&nbsp;&nbsp;"; $amenity_count++; }
    if($row['garden'] == "1" && $amenity_count < 6){ $c_amenities .= "GRDN&nbsp;&nbsp;&nbsp;&nbsp;"; $amenity_count++; }
    if($row['outdoor'] == "1" && $amenity_count < 6){ $c_amenities .= "OTDR&nbsp;&nbsp;&nbsp;&nbsp;"; $amenity_count++; }
    if($row['terrace'] == "1" && $amenity_count < 6){ $c_amenities .= "TRCE&nbsp;&nbsp;&nbsp;&nbsp;"; $amenity_count++; }    
    if($row['balcony'] == "1" && $amenity_count < 6){ $c_amenities .= "BLCY&nbsp;&nbsp;&nbsp;&nbsp;"; $amenity_count++; }
    if($row['security'] == "1" && $amenity_count < 6){ $c_amenities .= "SECR&nbsp;&nbsp;&nbsp;&nbsp;"; $amenity_count++; }
    if($row['elevator'] == "1" && $amenity_count < 6){ $c_amenities .= "ELVR&nbsp;&nbsp;&nbsp;&nbsp;"; $amenity_count++; }
    if($row['wheelchair'] == "1" && $amenity_count < 6){ $c_amenities .= "WLCR&nbsp;&nbsp;&nbsp;&nbsp;"; $amenity_count++; }
    if($row['healthclub'] == "1" && $amenity_count < 6){ $c_amenities .= "HLCB&nbsp;&nbsp;&nbsp;&nbsp;"; $amenity_count++; }
    if($row['fireplace'] == "1" && $amenity_count < 6){ $c_amenities .= "FRPC&nbsp;&nbsp;&nbsp;&nbsp;"; $amenity_count++; }
    
    if($amenity_count == 0){ $c_amenities .= "&nbsp;"; }
    
    $listing = array("price"=>$c_price, "address"=>$c_address, "apt"=>$row['apt'], "bed"=>$row['bed'],
                      "monthly"=>$c_monthly, "loc"=>$c_loc, "bld"=>$c_bld, "vws"=>$c_vws, "space"=>$c_space,
                      "floor"=>$c_floor, "amenities"=>$c_amenities, "same_build"=>$same_build
                    );
    $compare_listings[] = $listing;
  }
  
}
?>