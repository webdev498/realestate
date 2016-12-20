<?php
session_start();
include_once("dbconfig.php");
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

$user = (isset($_SESSION['buyer']) || isset($_SESSION['agent']) ? $_SESSION['email'] : $_SESSION['guestID'] );
$list_num = $_POST['listing'];

if($list_num != ''){
  // get listing data
  $select = "list_numb, zip, price, address, nbrhood, apt, loc, bld, vws, vroom_sqf, floor, bed, bath, nbrhood, mkt_desc, photo1, photo2, photo3, photo4, photo5, photo6, photo7, photo8, photo9, photo10, br1, lr, maint, taxes, br2, br3, br4, kitchen, other_din, other_den, alcove, maids" ;
  $select .= ", virtual, burnst, fireplace, garage, pool, roofd, garden, den, din, laundry, ac, security, doorman, wheelchair, elevator, healthclub, utilities, pets, furnished, prewar, loft, terrace, balcony, outdoor, wash_dry, nofee, agent_id_1, agent_id_2, agent_id_3, agent_id_4, contract";
  $select .= ", contact, contact_email, contact_phone, RLS_id";
  $from = "vow_data";
  $where = "(list_numb = '" . $list_num . "') ";
  $SQL = "SELECT " . $select . " FROM " . $from . " WHERE " . $where . " ";
  $result = mysql_query($SQL) or die(mysql_error()." ".$SQL);
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  
  $select1="SELECT
  ( case
  when name = 'bhsusa' then  'Brown Harris Stevens'
  when name = 'nestseekers' then  'Nest Seekers International'
  when name = 'corcoran' then  'The Corcoran International'
  when name = 'tngny' then 'The Nassimi International'
  when name = 'anchornyc' then  'Anchor Associates  '
  when name = 'plazagroupnyc' then  'Plaza Real Estate Group  '
  when name = 'manhattanhouse' then  'The Corcoran Group '
  when name = 'luxornyc' then  'Luxor Homes & Investment Realty, LLC '
  when name = 'djkresidential' then  'DJK Residential'
  when name = 'opgny' then  'Oxford Property Group '
  when name = 'peterashe' then  'Peter-Ashe '
  when name = 'halstead' then  'Halstead Property, LLC '
  when name = 'broadwayrealty' then  'A & I Broadway '
  when name = 'ccrny' then  'City Connections Realty '
  when name = 'trumpic' then  'Trump International Realty '
  when name = 'elegran' then  'Elegran Real Estate and Development '
  when name = 'fenwickkeats' then  'Fenwick Keats Real Estate '
  when name = 'bsdequities' then  'BSD Equities '
  when name = 'hgrny' then 'Hecht Group '
  else name
  end) as name
  
  FROM(
  SELECT  list_numb,contact_email, broker, owner, contract, SUBSTRING_INDEX(email,'.',1) as name
  
  FROM  (SELECT  list_numb,contact_email, broker, owner, contract, SUBSTRING_INDEX(contact_email,'@',-1) AS email  FROM  `vow_data` as T1 )
  
  AS T2) as T3  where  list_numb = '" . $list_num . "' ";
      
  $result1 = mysql_query($select1) or die(mysql_error() . " " . $select1);
  $row1 = mysql_fetch_array($result1, MYSQL_ASSOC);
  
  $role = $_SESSION['role'];
  $RLS_id = $row['RLS_id'];
  $zip = $row['zip'];
  $price = $row['price'];
  $price = number_format($price, 0, '.', ',');
  $loc = $row['loc'];
  $loc = number_format($loc, 0, '.', ',');
  $bld = $row['bld'];
  $bld = number_format($bld, 0, '.', ',');
  $vws = $row['vws'];
  $vws = number_format($vws, 0, '.', ',');
  $space = $row['vroom_sqf'];
  $space = number_format($space, 0, '.', ',');
  $footage = $space * 1.3;
  $bed = $row['bed'];
  $bath = $row['bath'];
  $bath = number_format($bath, 0, '.', ',');
  $address = $row['address'];
  $contract = $row['contract'];
  $broker = $row1['name'];
  $broker = str_replace("Bellmarc Exc", "Bellmarc", $broker);
  $broker = str_replace("rebny", "", $broker);
  $broker = ucwords(strtolower($broker));
  $owner = $row['owner'];
  $owner = str_replace("Bellmarc Exc", "Bellmarc", $owner);
  $owner = str_replace("rebny", "", $owner);
  $owner = ucwords(strtolower($owner));
  $contact = $row['contact'];
  $contact_email = $row['contact_email'];
  $contact_phone = $row['contact_phone'];
  $photo1 = $row['photo1'];
  if ($photo1 == ''){$photo1 = 'http://www.homepik.com/images/nopicture3.png';};
  $photo2 = $row['photo2'];
  $photo3 = $row['photo3'];
  $photo4 = $row['photo4'];
  $photo5 = $row['photo5'];
  $photo6 = $row['photo6'];
  $photo7 = $row['photo7'];
  $photo8 = $row['photo8'];
  $photo9 = $row['photo9'];
  $photo10 = $row['photo10'];
  $numPhotos = 0;
  $photos = array();
  
  for($i=1; $i<11; $i++){
    if(strpos(${'photo'.$i}, "JPG")){ ${'photo'.$i} = strtolower(${'photo'.$i}); }
    if(strpos(${'photo'.$i}, "GIF")){ ${'photo'.$i} = strtolower(${'photo'.$i}); }
    if(strpos(${'photo'.$i}, "PNG")){ ${'photo'.$i} = strtolower(${'photo'.$i}); }
    if(${'photo'.$i} != ''){ 
      array_push($photos, ${'photo'.$i});
      $numPhotos = $numPhotos + 1;
    }
  }
  
  $stristr = stristr($photo1,'http'); if($stristr === false) { $photo1 = 'http://www.bellmarc.com/pictures/building/'.$photo1.'.bmp';} // if it's a bellmarc building photo, add url location
  $stristr = stristr($photo1,'floor'); if($stristr != false) { if($photo2 != ''){ $photo1 = $photo2; $photo2 = $row['photo1'];}; }; // if it's a floor plan, look for a second photo to use instead
  
  $mkt_desc = $row['mkt_desc'];
  $start = strpos($mkt_desc, "Please call");

  if($start == 0){ $start = strpos($mkt_desc, "Please contact"); }
  if($start == 0){ $start = strpos($mkt_desc, "Contact"); }
  if($start == 0){ $start = strpos($mkt_desc, "contact"); }
  if($start == 0){ $start = strpos($mkt_desc, "Call"); }

  if($start != false){
    $contactInfo = substr($mkt_desc, $start);
    $chunk = substr($contactInfo, 0);
    $mkt_desc = str_replace($chunk, '', $mkt_desc);
  }

  $mkt_desc = str_replace("Courtesy of Stribling", "", $mkt_desc);
  
  $floor = $row['floor'];
  $floor = number_format($floor, 0, '.', ',');
  $bedroom1 = $row['br1'];
  $bedroom1 = str_replace("X","' x ",$bedroom1);
  $living_room = $row['lr'];
  $living_room = str_replace("X","' x ",$living_room);
  $bedroom2 = $row['br2'];
  $bedroom2 = str_replace("X","' x ",$bedroom2);
  $bedroom3 = $row['br3'];
  $bedroom3 = str_replace("X","' x ",$bedroom3);
  $bedroom4 = $row['br4'];
  $bedroom4 = str_replace("X","' x ",$bedroom4);
  $kitchen = $row['kitchen'];
  $kitchen = str_replace("X","' x ",$kitchen);
  $dining_room = $row['other_din'];
  $dining_room = str_replace("X","' x ",$dining_room);
  $den = $row['other_den'];
  $den = str_replace("X","' x ",$den);
  $alcove = $row['alcove'];
  $alcove = str_replace("X","' x ",$alcove);
  $maids_room = $row['maids'];
  $maids_room = str_replace("X","' x ",$maids_room);
  $address = str_replace("&", " & ", $address);
  $address = ucwords(strtolower($address));
  $apt = $row['apt'];
  $monthly = ($row['maint'] + $row['taxes']);
  $monthly = number_format($monthly, 0, '.', ',');
  $maint = $row['maint'];
  $maint = number_format($maint, 0, '.', ',');
  $taxes = $row['taxes'];
  $taxes = number_format($taxes, 0, '.', ',');
  $nbrhood = $row['nbrhood'];
  switch ($nbrhood){
    case "North": $nbrhood = "Far Uptown"; break;
    case "Eastside": $nbrhood = "Upper East Side"; break;
    case "Westside": $nbrhood = "Upper West Side"; break;
    case "SMG": $nbrhood = "Midtown West"; break;
    case "Chelsea": $nbrhood = "Midtown East"; break;
    case "Village": $nbrhood = "Greenwich Village"; break;
    case "Lower": $nbrhood = "Downtown"; break;
  };
  $agent_id_1=$row['agent_id_1'];
  $agent_id_2=$row['agent_id_2'];
  $agent_id_3=$row['agent_id_3'];
  $agent_id_4=$row['agent_id_4'];
  
  // Amenities
  $amenities['virtual']= $row['virtual'];
  $amenities['burnst']= $row['burnst'];
  $amenities['fireplace']= $row['fireplace'];
  $amenities['garage']= $row['garage'];
  $amenities['pool']= $row['pool'];
  $amenities['roofd']= $row['roofd'];
  $amenities['garden']= $row['garden'];
  $amenities['den']= $row['den'];
  $amenities['din']= $row['din'];
  $amenities['laundry']= $row['laundry'];
  $amenities['ac']= $row['ac'];
  $amenities['security']= $row['security'];
  $amenities['doorman']= $row['doorman'];
  $amenities['wheelchair']= $row['wheelchair'];
  $amenities['elevator']= $row['elevator'];
  $amenities['healthclub']= $row['healthclub'];
  $amenities['utilities']= $row['utilities'];
  $amenities['pets']= $row['pets'];
  $amenities['furnished']= $row['furnished'];
  $amenities['prewar']= $row['prewar'];
  $amenities['loft']= $row['loft'];
  $amenities['terrace']= $row['terrace'];
  $amenities['balcony']= $row['balcony'];
  $amenities['outdoor']= $row['outdoor'];
  $amenities['wash_dry']= $row['wash_dry'];
  $amenities['nofee']= $row['nofee'];
  
  if($role == 'buyer'){
    $result = mysql_query( "SELECT * FROM users WHERE (email = '" . $user . "')" ) or die(mysql_error() . " " . $SQL);
    $row = mysql_fetch_assoc($result);
    $pAgent = $row['P_agent'];
    $pAgent2 = $row['P_agent2'];

    $agent_id_1 = $pAgent;
  }
  
  $result = mysql_query( "SELECT active FROM `registered_agents` WHERE (agent_id = '".$agent_id_1."')" ) or die(mysql_error() . " " . $SQL);
  $row = mysql_fetch_assoc($result);
  $active = $row['active'];

  if((!isset($active)) || $active == "N"){ $agent_id_1 = ''; }
  
  if ($agent_id_1 == '' || $agent_id_1 == null) {
    $agentNums = array();
    $agentCount = 0;
    $result = mysql_query( "SELECT location, agent_1, agent_2, agent_3, agent_4 FROM Building_file WHERE (location = '" . $address . "'); " ) or die(mysql_error() . " " . $SQL);
    $row = mysql_fetch_assoc($result);
    $buildLocation = $row['location'];
    $agent_1_v2 = $row['agent_1'];
    $agent_2_v2 = $row['agent_2'];
    $agent_3_v2 = $row['agent_3'];
    $agent_4_v2 = $row['agent_4'];

    if ($agent_1_v2 != ''){
      $result = mysql_query( "SELECT active FROM `registered_agents` WHERE (agent_id = '" . $agent_1_v2 . "'); " ) or die(mysql_error() . " " . $SQL);
      $row = mysql_fetch_assoc($result);
      $active = $row['active'];
      
      if($active == "Y"){
        $agentNums[$agentCount] = $agent_1_v2;
        $agentCount++;
      }
    }
    elseif ($agent_2_v2 != ''){
      $result = mysql_query( "SELECT active FROM `registered_agents` WHERE (agent_id = '" . $agent_2_v2 . "'); " ) or die(mysql_error() . " " . $SQL);
      $row = mysql_fetch_assoc($result);
      $active = $row['active'];
      
      if($active == "Y"){
        $agentNums[$agentCount] = $agent_2_v2;
        $agentCount++;
      }
    }
    elseif ($agent_3_v2 != ''){
      $result = mysql_query( "SELECT active FROM `registered_agents` WHERE (agent_id = '" . $agent_3_v2 . "'); " ) or die(mysql_error() . " " . $SQL);
      $row = mysql_fetch_assoc($result);
      $active = $row['active'];
      
      if($active == "Y"){
        $agentNums[$agentCount] = $agent_3_v2;
        $agentCount++;
      }
    }
    elseif ($agent_4_v2 != ''){
      $result = mysql_query( "SELECT active FROM `registered_agents` WHERE (agent_id = '" . $agent_4_v2 . "'); " ) or die(mysql_error() . " " . $SQL);
      $row = mysql_fetch_assoc($result);
      $active = $row['active'];
      
      if($active == "Y"){
        $agentNums[$agentCount] = $agent_4_v2;
        $agentCount++;
      }
    }

    if ($agentCount > 0){
      shuffle($agentNums);
      $agent_num = $agentNums[0];
      if (strlen($agent_num) == 3){ $agent_id_1 = $agent_num; }
      else{
        $result = mysql_query( "SELECT office FROM zip_to_office WHERE (zip = '" . $zip . "')" ) or die(mysql_error() . " " . $SQL);
        $row = mysql_fetch_assoc($result);
        $office = $row['office'];
        if($office == 'SS'){ $office = 'BSS'; }
        if($office == 'GC'){ $office = 'CG'; }

        //Get code and manager id from office where code is office from zip_to_office
        $result = mysql_query( "SELECT code, mgr_id FROM office WHERE (code = '" . $office . "')" ) or die(mysql_error() . " " . $SQL);
        $row = mysql_fetch_assoc($result);
        $mgr_id = $row['mgr_id'];
        
        $result = mysql_query( "SELECT active FROM `registered_agents` WHERE (agent_id = '".$mgr_id."')" ) or die(mysql_error() . " " . $SQL);
        $row = mysql_fetch_assoc($result);
        $active = $row['active'];

        if($active == "Y"){ $agent_id_1 = $mgr_id; }
        else{ $agent_id_1 = "NB"; }  
      }
    }
    else
    {
      // Get office from zip_to_office where zip is zip of building
      $result = mysql_query( "SELECT office FROM zip_to_office WHERE (zip = '" . $zip . "')" ) or die(mysql_error() . " " . $SQL);
      $row = mysql_fetch_assoc($result);
      $office = $row['office'];
      if($office == "SS"){ $office = "BSS"; }
      if ($office == 'GC'){ $office = 'CG'; }

      //Get code and manager id from office where code is office from zip_to_office
      $result = mysql_query( "SELECT code, mgr_id FROM office WHERE (code = '" . $office . "')" ) or die(mysql_error() . " " . $SQL);
      $row = mysql_fetch_assoc($result);
      $mgr_id = $row['mgr_id'];

      $result = mysql_query( "SELECT active FROM `registered_agents` WHERE (agent_id = '".$mgr_id."')" ) or die(mysql_error() . " " . $SQL);
      $row = mysql_fetch_assoc($result);
      $active = $row['active'];

      if($active == "Y"){ $agent_id_1 = $mgr_id; }
      else{ $agent_id_1 = "NB"; }  
    }
  }
  
  if($agent_id_1 != '') {
    $result = mysql_query( "SELECT first_name, last_name, title, agent_id, phone, email, bio FROM `registered_agents` WHERE (agent_id = '".$agent_id_1."'); " ) or die(mysql_error()." ".$SQL);
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $agent_id = $row['agent_id'];
    $agent_firstname = $row['first_name'];
    $agent_lastname = $row['last_name'];
    $agent_cellphone = $row['phone'];
    $agent_ext = "";
    $agent_email = $row['email'];
    $agent_title = $row['title'];
    $agent_title = str_replace('Executive', 'Exec.', $agent_title);
    $agent_bio = $row['bio'];
    if($agent_title == "Sales Director"){ $agent_title = "Licensed Real Estate Sales Director"; }
    elseif($agent_title == "Sales Manager"){ $agent_title = "Licensed Real Estate Sales Manager"; }
    elseif($agent_title == "Associate Broker"){ $agent_title = "Licensed Associate Real Estate Broker"; }
    elseif($agent_title == "Salesperson"){ $agent_title = "Licensed Real Estate Salesperson"; }

    if($agent_id == "NB"){ $agent_email = "nbinder@homepik.com"; }
 
    $agent_photo = "http://www.bellmarc.com/pictures/agent/medium/" . $agent_id . ".jpg";
  }
  
  //If the user has a P_agent2, get the info
  if(isset($pAgent2) && $pAgent2 != "" && $pAgent2 != null){
    $result = mysql_query( "SELECT first_name, last_name, title, agent_id, phone, email, bio FROM `registered_agents` WHERE (agent_id = '".$pAgent2."'); " ) or die(mysql_error()." ".$SQL);
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $agent2_id = $row['agent_id'];  
    $agent2_firstname = $row['first_name'];
    $agent2_lastname = $row['last_name'];
    $agent2_title = $row['title'];
    $agent2_title = str_replace('Executive', 'Exec.', $agent2_title);
    $agent2_cellphone = $row['phone'];
    $agent2_ext = "";
    $agent2_email = $row['email'];
    $agent2_bio = $row['bio'];
    if($agent_title == "Sales Director"){ $tplvar['agent_title'] = "Licensed Real Estate Sales Director"; }
    elseif($agent2_title == "Sales Manager"){ $agent2_title = "Licensed Real Estate Sales Manager"; }
    elseif($agent2_title == "Associate Broker"){ $agent2_title = "Licensed Associate Real Estate Broker"; }
    elseif($agent2_title == "Salesperson"){ $agent2_title = "Licensed Real Estate Salesperson"; }

    if($agent_id == "NB"){ $agent_email = "nbinder@homepik.com"; }
    
    $agent2_photo = "http://www.bellmarc.com/pictures/agent/medium/" . $agent2_id . ".jpg";
  }
  
  $url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  $parse=parse_url($url, PHP_URL_QUERY);
  $priceImg = "price.php?id=$list_num&code=$list_num";
  
  $folders = array();
  $result = mysql_query( "SELECT name, id, user FROM users_folders WHERE (user = '".$user."'); " ) or die(mysql_error()." ".$SQL);
  while ($row = mysql_fetch_assoc($result)) {
    $folder = array("id"=>$row['id'], "name"=>$row['name']);
    array_push($folders, $folder);
  }
  
  // DETERMINE IF IT'S AN IDX LISTING
  $result = mysql_query( "SELECT * FROM IDX_Listing_Numbers WHERE `RLS_id` = CONCAT('\"','" . $RLS_id . "','\"')" ) or die(mysql_error() . " " . $SQL);
  $row = mysql_fetch_array($result, MYSQL_ASSOC);
  $rlsMatch = $row['RLS_id'];
  
  //CHECK IF LISTING IS ALREADY SAVED
  $result = mysql_query( "SELECT * FROM saved_listings WHERE user = '".$user."' AND list_num = '" . $list_num . "'" ) or die(mysql_error()." ".$SQL);
  $num = mysql_num_rows($result);
  if($num > 0){ $saved = true; } else{ $saved = false; }
  
  $details = array("folders"=>$folders, "broker"=>$broker, "contract"=>$contract, "contact"=>$contact,
                   "contact_email"=>$contact_email, "contact_phone"=>$contact_phone, "RLS_id"=>$RLS_id,
                   "price"=>$price, "loc"=>$loc, "bld"=>$bld, "vws"=>$vws, "space"=>$space, "footage"=>$footage,
                   "bed"=>$bed, "bath"=>$bath, "address"=>$address, "zip"=>$zip, "numPhotos"=>$numPhotos, "photos"=>$photos,
                   "photo1"=>$photo1, "photo2"=>$photo2, "photo3"=>$photo3, "photo4"=>$photo4, "photo5"=>$photo5,
                   "photo6"=>$photo6, "photo7"=>$photo7, "photo8"=>$photo8, "photo9"=>$photo9, "photo10"=>$photo10,
                   "numPhotos"=>$numPhotos, "agent_id_1"=>$agent_id_1, "agent_id_2"=>$agent_id_2, "agent_id_3"=>$agent_id_3,
                   "agent_id_4"=>$agent_id_4, "mkt_desc"=>$mkt_desc, "floor"=>$floor, "bedroom1"=>$bedroom1,
                   "bedroom2"=>$bedroom2, "bedroom3"=>$bedroom3, "bedroom4"=>$bedroom4, "living_room"=>$living_room,
                   "kitchen"=>$kitchen, "dining_room"=>$dining_room, "den"=>$den, "alcove"=>$alcove, "maids_room"=>$maids_room,
                   "apt"=>$apt, "contract"=>$contract, "monthly"=>$monthly, "maint"=>$maint, "taxes"=>$taxes, "nbrhood"=>$nbrhood,
                   "amenities"=>$amenities, "pAgent"=>$pAgent, "pAgent2"=>$pAgent2,
                   "agent_firstname"=>$agent_firstname, "agent_lastname"=>$agent_lastname, "agent_id"=>$agent_id,
                   "agent_title"=>$agent_title, "agent_cellphone"=>$agent_cellphone, "agent_ext"=>$agent_ext,
                   "agent_email"=>$agent_email, "agent_bio"=>$agent_bio, "agent_photo"=>$agent_photo,
                   "agent2_firstname"=>$agent2_firstname, "agent2_lastname"=>$agent2_lastname, "agent2_id"=>$agent2_id,
                   "agent2_title"=>$agent2_title, "agent2_cellphone"=>$agent2_cellphone, "agent2_ext"=>$agent2_ext,
                   "agent2_email"=>$agent2_email, "agent2_bio"=>$agent2_bio, "agent2_photo"=>$agent2_photo, "rlsMatch"=>$rlsMatch, "saved"=>$saved);
  
  echo json_encode($details);
}
?>