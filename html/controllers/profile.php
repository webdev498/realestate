<?php
session_start();
include_once("dbconfig.php");
include_once('functions.php');

// LOAD SMARTY TEMPLATE ENGINE
include_once('../classes/smarty/SmartyBC.class.php');
$smarty = new SmartyBC();
$smarty->setTemplateDir('../views');
$smarty->setCompileDir('../views/compiled');
$tplvar = array();

if ((authentication() == 'agent') OR (authentication() == 'user') OR (authentication() == 'guest')) {
  $list_numb = $_GET['list_numb']; // Listing number
  $code = $_GET['code']; // Encrypted listing number for the URL which changes with each session to prevent the listing # from being made public (VOW RULES)
  $session = session_id();
  $code2 = string_encrypt($list_numb, $session);
  if($code == $code2) { // Make sure the code in the listing url was created for the current session before displaying anything (VOW RULES)
    $db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
    mysql_select_db($database) or die("Error connecting to db.");
    $limit = limit();
    if ($limit == 'clear') {
      $select = "list_numb, zip, price, address, nbrhood, apt, loc, bld, vws, vroom_sqf, floor, bed, bath, nbrhood, mkt_desc, photo1, photo2, photo3, photo4, photo5, photo6, photo7, photo8, photo9, photo10, br1, lr, maint, taxes, br2, br3, br4, kitchen, other_din, other_den, alcove, maids, owner, broker, contact, contact_email, contact_phone, contract";
      $select .= ", virtual, burnst, fireplace, garage, pool, roofd, garden, den, din, laundry, ac, security, doorman, wheelchair, elevator, healthclub, utilities, pets, furnished, prewar, loft, terrace, balcony, outdoor, wash_dry, nofee, agent_id_1, RLS_id";
      $from = "vow_data";
      $where = "(list_numb = '" . $list_numb . "') AND (status = 'AVAIL') ";
      $SQL = "SELECT " . $select . " FROM " . $from . " WHERE " . $where . " ";
      $result = mysql_query($SQL) or die(mysql_error() . " " . $SQL);
      $row = mysql_fetch_array($result, MYSQL_ASSOC);
      $select1 = "SELECT
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
                    when name = 'hgrny' then 'Hecht Group'
                    else name
                  end) as name

                  FROM(

                    SELECT  list_numb,contact_email, broker, owner, contract, SUBSTRING_INDEX(email,'.',1) as name ,status
                    FROM  (SELECT  list_numb,contact_email, broker, owner, contract, SUBSTRING_INDEX(contact_email,'@',-1) AS email,status  FROM  `vow_data` as T1 )
                    AS T2) as T3  where  list_numb = '" . $list_numb . "' AND status = 'AVAIL'";
      $result1 = mysql_query($select1) or die(mysql_error() . " " . $select1);
      $row1 = mysql_fetch_array($result1, MYSQL_ASSOC);

      // Set the template variables
      $tplvar['list_num'] = $list_numb;
      $tplvar['zip'] = $row['zip'];
      $tplvar['assigned'] = isset($_SESSION['assigned'])? $_SESSION['assigned']: ''; // If the user is a buyer, the agent the buyer has been assigned to is displayed on all listings
      $tplvar['code2'] = $code2; // for the listing url
      $tplvar['auth'] = authentication();
      $tplvar['role'] = $_SESSION['role'];
      $tplvar['price'] = $row['price'];
      $tplvar['price'] = number_format($tplvar['price'], 0, '.', ', ');
      $tplvar['loc'] = $row['loc'];
      $tplvar['loc'] = number_format($tplvar['loc'], 0, '.', ', ');
      $tplvar['bld'] = $row['bld'];
      $tplvar['bld'] = number_format($tplvar['bld'], 0, '.', ', ');
      $tplvar['vws'] = $row['vws'];
      $tplvar['vws'] = number_format($tplvar['vws'], 0, '.', ', ');
      $tplvar['spac'] = number_format($row['vroom_sqf'],2);
      $tplvar['bed'] = $row['bed'];
      $tplvar['bath'] = $row['bath'];
      $tplvar['bath'] = number_format($tplvar['bath'], 0, '.', ', ');
      $tplvar['address'] = $row['address'];
      $tplvar['contract'] = $row['contract'];
      $tplvar['contract'] = str_replace("BEXCL", "Bellmarc", $tplvar['contract']);
      $tplvar['contract'] = str_replace("OPEN", "the owner (open listing)", $tplvar['contract']);
      $tplvar['broker'] = $row1['name'];
      $tplvar['broker'] = str_replace("Bellmarc Exc", "Bellmarc", $tplvar['broker']);
      $tplvar['broker'] = str_replace("rebny", "", $tplvar['broker']);
      $tplvar['broker'] = ucwords(strtolower($tplvar['broker']));
      $tplvar['owner'] = $row['owner'];
      $tplvar['owner'] = str_replace("Bellmarc Exc", "Bellmarc", $tplvar['owner']);
      $tplvar['owner'] = str_replace("rebny", "", $tplvar['owner']);
      $tplvar['owner'] = ucwords(strtolower($tplvar['owner']));
      $tplvar['contact'] = $row['contact'];
      $tplvar['contact_email'] = $row['contact_email'];
      $tplvar['contact_phone'] = $row['contact_phone'];
      $tplvar['photo1'] = $row['photo1'];
      if ($tplvar['photo1'] == '') { $tplvar['photo1'] = 'http://www.homepik.com/images/nopicture3.png'; };
      $tplvar['photo2'] = $row['photo2'];
      $tplvar['photo3'] = $row['photo3'];
      $tplvar['photo4'] = $row['photo4'];
      $tplvar['photo5'] = $row['photo5'];
      $tplvar['photo6'] = $row['photo6'];
      $tplvar['photo7'] = $row['photo7'];
      $tplvar['photo8'] = $row['photo8'];
      $tplvar['photo9'] = $row['photo9'];
      $tplvar['photo10'] = $row['photo10'];

      for($i=1; $i<11; $i++){
        if(strpos($tplvar['photo'.$i], "JPG")){ $tplvar['photo'.$i] = strtolower($tplvar['photo'.$i]); }
        if(strpos($tplvar['photo'.$i], "GIF")){ $tplvar['photo'.$i] = strtolower($tplvar['photo'.$i]); }
        if(strpos($tplvar['photo'.$i], "PNG")){ $tplvar['photo'.$i] = strtolower($tplvar['photo'.$i]); }
      }

      $str = 'floor';
      $matches = array();
      $photos = array($tplvar['photo1'], $tplvar['photo2'], $tplvar['photo3'], $tplvar['photo4'], $tplvar['photo5'], $tplvar['photo6'], $tplvar['photo7'], $tplvar['photo8'], $tplvar['photo9'], $tplvar['photo10']);
      foreach($photos as $a) { if(stripos($a, $str) === false){ array_push($matches, $a); } }

      $tplvar['default'] = $matches[0];
      if ($tplvar['default'] == ""){ $tplvar['default'] = $photos[0]; };

      // if it's a floor plan, look for a second photo to use instead
      $stristr = stristr($tplvar['photo1'], 'floor');
      if ($stristr != false) {
        if ($tplvar['photo2'] != '') {
          $tplvar['photo1'] = $tplvar['photo2'];
          $tplvar['photo2'] = $row['photo1'];
        };
      };
      $tplvar['mkt_desc'] = $row['mkt_desc'];
     	$start = strpos($tplvar['mkt_desc'], "Please call");

     	if($start == 0){ $start = strpos($tplvar['mkt_desc'], "Please contact"); }
      if($start == 0){ $start = strpos($tplvar['mkt_desc'], "Contact"); }
      if($start == 0){ $start = strpos($tplvar['mkt_desc'], "contact"); }
      if($start == 0){ $start = strpos($tplvar['mkt_desc'], "Call"); }

      if($start != false){
      	$contactInfo = substr($tplvar['mkt_desc'], $start);
      	$chunk = substr($contactInfo, 0);
    		$tplvar['mkt_desc'] = str_replace($chunk, '', $tplvar['mkt_desc']);
      }

      $tplvar['mkt_desc'] = str_replace("Courtesy of Stribling", "", $tplvar['mkt_desc']);

      $images = array();
      if($tplvar['photo1'] != ""){ array_push($images, $tplvar['photo1']); }
      if($tplvar['photo2'] != ""){ array_push($images, $tplvar['photo2']); }
      if($tplvar['photo3'] != ""){ array_push($images, $tplvar['photo3']); }
      if($tplvar['photo4'] != ""){ array_push($images, $tplvar['photo4']); }
      if($tplvar['photo5'] != ""){ array_push($images, $tplvar['photo5']); }
      if($tplvar['photo6'] != ""){ array_push($images, $tplvar['photo6']); }
      if($tplvar['photo7'] != ""){ array_push($images, $tplvar['photo7']); }
      if($tplvar['photo8'] != ""){ array_push($images, $tplvar['photo8']); }
      if($tplvar['photo9'] != ""){ array_push($images, $tplvar['photo9']); }
      if($tplvar['photo10'] != ""){ array_push($images, $tplvar['photo10']); }

      $tplvar['floor'] = $row['floor'];
      $tplvar['floor'] = @number_format($floor, 0, '.', ',');
      $tplvar['bedroom1'] = $row['br1'];
      $tplvar['bedroom1'] = str_replace("X", "' x ", $tplvar['bedroom1']);
      if($tplvar['bedroom1'] != ''){ $tplvar['bedroom1'] = $tplvar['bedroom1'] . "'"; }
      $tplvar['living_room'] = $row['lr'];
      $tplvar['living_room'] = str_replace("X", "' x ", $tplvar['living_room']);
      if($tplvar['living_room'] != ''){ $tplvar['living_room'] = $tplvar['living_room'] . "'"; }
      $tplvar['bedroom2'] = $row['br2'];
      $tplvar['bedroom2'] = str_replace("X", "' x ", $tplvar['bedroom2']);
      if($tplvar['bedroom2'] != ''){ $tplvar['bedroom2'] = $tplvar['bedroom2'] . "'"; }
      $tplvar['bedroom3'] = $row['br3'];
      $tplvar['bedroom3'] = str_replace("X", "' x ", $tplvar['bedroom3']);
      if($tplvar['bedroom3'] != ''){ $tplvar['bedroom3'] = $tplvar['bedroom3'] . "'"; }
      $tplvar['bedroom4'] = $row['br4'];
      $tplvar['bedroom4'] = str_replace("X", "' x ", $tplvar['bedroom4']);
      if($tplvar['bedroom4'] != ''){ $tplvar['bedroom4'] = $tplvar['bedroom4'] . "'"; }
      $tplvar['kitchen'] = $row['kitchen'];
      $tplvar['kitchen'] = str_replace("X", "' x ", $tplvar['kitchen']);
      if($tplvar['kitchen'] != ''){ $tplvar['kitchen'] = $tplvar['kitchen'] . "'"; }
      $tplvar['dining_room'] = $row['other_din'];
      $tplvar['dining_room'] = str_replace("X", "' x ", $tplvar['dining_room']);
      if($tplvar['dining_room'] != ''){ $tplvar['dining_room'] = $tplvar['dining_room'] . "'"; }
      $tplvar['den'] = $row['other_den'];
      $tplvar['den'] = str_replace("X", "' x ", $tplvar['den']);
      if($tplvar['den'] != ''){ $tplvar['den'] = $tplvar['den'] . "'"; }
      $tplvar['alcove'] = $row['alcove'];
      $tplvar['alcove'] = str_replace("X", "' x ", $tplvar['alcove']);
      if($tplvar['alcove'] != ''){ $tplvar['alcove'] = $tplvar['alcove'] . "'"; }
      $tplvar['maids_room'] = $row['maids'];
      $tplvar['maids_room'] = str_replace("X", "' x ", $tplvar['maids_room']);
      if($tplvar['maids_room'] != ''){ $tplvar['maids_room'] = $tplvar['maids_room'] . "'"; }
      $tplvar['address'] = str_replace("&", " & ", $tplvar['address']);
      $tplvar['address'] = ucwords(strtolower($tplvar['address']));
      $tplvar['apt'] = $row['apt'];
      $tplvar['maint'] = number_format($row['maint'], 0, '.', ',');
      $tplvar['taxes'] = number_format($row['taxes'], 0, '.', ',');
      $tplvar['monthly'] = ($row['maint'] + $row['taxes']);
      $tplvar['monthly'] = number_format($tplvar['monthly'], 0, '.', ',');
      $nbrhood = $row['nbrhood'];
      switch ($nbrhood) {
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
      $tplvar['nbrhood'] = $nbrhood;
      $tplvar['agent_id_1'] = $row['agent_id_1'];
      $tplvar['agent_id_2'] = isset($row['agent_id_2'])? $row['agent_id_2'] : '';
      $tplvar['agent_id_3'] = isset($row['agent_id_3'])? $row['agent_id_3'] : '';
      $tplvar['agent_id_4'] = isset($row['agent_id_4'])? $row['agent_id_4'] : '';
      $tplvar['contract'] = $row['contract'];
      $tplvar['RLS_id'] = $row['RLS_id'];
      // Amenities
      $tplvar['amenities']['Wood Burning Stove'] = $row['burnst'];
      $tplvar['amenities']['Fireplace'] = $row['fireplace'];
      $tplvar['amenities']['Garage'] = $row['garage'];
      $tplvar['amenities']['Pool'] = $row['pool'];
      $tplvar['amenities']['Roof Deck'] = $row['roofd'];
      $tplvar['amenities']['Garden'] = $row['garden'];
      $tplvar['amenities']['Den'] = $row['den'];
      $tplvar['amenities']['Laundry'] = $row['laundry'];
      $tplvar['amenities']['Air Conditioning'] = $row['ac'];
      $tplvar['amenities']['Security'] = $row['security'];
      $tplvar['amenities']['Doorman'] = $row['doorman'];
      $tplvar['amenities']['Wheelchair'] = $row['wheelchair'];
      $tplvar['amenities']['Elevator'] = $row['elevator'];
      $tplvar['amenities']['Healthclub'] = $row['healthclub'];
      $tplvar['amenities']['Utilities'] = $row['utilities'];
      $tplvar['amenities']['Pets Allowed'] = $row['pets'];
      $tplvar['amenities']['Furnished'] = $row['furnished'];
      $tplvar['amenities']['Pre-War'] = $row['prewar'];
      $tplvar['amenities']['Loft'] = $row['loft'];
      $tplvar['amenities']['Terrace'] = $row['terrace'];
      $tplvar['amenities']['Balcony'] = $row['balcony'];
      $tplvar['amenities']['Outdoor Area'] = $row['outdoor'];
      $tplvar['amenities']['Washer/Dryer'] = $row['wash_dry'];
      $tplvar['amenities']['No Fee'] = $row['nofee'];

      $amenity_icons = "";
      $tplvar['amenities_html'] = '<table class="amenitiesList"><colgroup><col width="250"/><col width="250"/></colgroup><tbody><tr>'; // for each amenity print name here
      $num_amens = 0;

      foreach ($tplvar['amenities'] as $key => $value) {
        if ($value == '.T.' || $value > 0) {
          $num_amens = $num_amens + 1;
          if($num_amens % 2 == 0){ $tplvar['amenities_html'] .= "<td>" . $key . "</td></tr><tr>"; }
          else{ $tplvar['amenities_html'] .= "<td>" . $key . "</td>"; }
        };
      };

      $tplvar['amenities_html'] .= "</tr></tbody></table>";

      // Decide which agent's contact info to show (show office manager based on neighborhood unless the user is a buyer who has been assigned to an agent)
      if($tplvar['role'] == 'buyer'){
        $SQL = "SELECT * FROM users WHERE (email = '" . $_SESSION['email'] . "')";
        $result = mysql_query($SQL) or die(mysql_error() . " " . $SQL);
        $row = mysql_fetch_assoc($result);
        $tplvar['P_agent'] = $row['P_agent'];
        $tplvar['P_agent2'] = $row['P_agent2'];
        
        if ($tplvar['P_agent'] == ''){
          $tplvar['error'] = 'No primary agent';
          $tplvar['agent-txt'] = "<span>Select</span> as your primary agent</div>";
        }
        else{
          $tplvar['agent_id_1'] = $tplvar['P_agent'];
          $tplvar['agent-txt'] = "<span class='remove-primary'>Remove</span> My Primary Agent";
          $tplvar['agent_id_2'] = $tplvar['P_agent2'];
          $tplvar['agent-txt2'] = "<span class='remove-primary'>Remove</span> My Primary Agent";
        }
      }
      
      $SQL = "SELECT active FROM `registered_agents` WHERE (agent_id = '".$tplvar['agent_id_1']."')";
      $result = mysql_query($SQL) or die(mysql_error() . " " . $SQL);
      $row = mysql_fetch_assoc($result);
      $active = $row['active'];

      if((!isset($active)) || $active == "N"){ $tplvar['agent_id_1'] = ''; }

      if ($tplvar['agent_id_1'] == '') {
        $agentNums = array();
        $agentCount = 0;
        $SQL = "SELECT location, agent_1, agent_2, agent_3, agent_4 FROM Building_file WHERE (location = '" . $tplvar['address'] . "'); ";
        $result = mysql_query($SQL) or die(mysql_error() . " " . $SQL);
        $row = mysql_fetch_assoc($result);
        $tplvar['build_location'] = $row['location'];
        $tplvar['agent_1_v2'] = $row['agent_1'];
        $tplvar['agent_2_v2'] = $row['agent_2'];
        $tplvar['agent_3_v2'] = $row['agent_3'];
        $tplvar['agent_4_v2'] = $row['agent_4'];

        if ($tplvar['agent_1_v2'] != ''){
          $SQL = "SELECT active FROM `registered_agents` WHERE (agent_id = '" . $tplvar['agent_1_v2'] . "'); ";
          $result = mysql_query($SQL) or die(mysql_error() . " " . $SQL);
          $row = mysql_fetch_assoc($result);
          $active = $row['active'];

          if($active == "Y"){
            $agentNums[$agentCount] = $tplvar['agent_1_v2'];
            $agentCount++;
            $tplvar['error'] .= 'Agent 1 added';
          }
        }
        elseif ($tplvar['agent_2_v2'] != ''){
          $SQL = "SELECT active FROM `registered_agents` WHERE (agent_id = '" . $tplvar['agent_2_v2'] . "'); ";
          $result = mysql_query($SQL) or die(mysql_error() . " " . $SQL);
          $row = mysql_fetch_assoc($result);
          $active = $row['active'];

          if($active == "Y"){
            $agentNums[$agentCount] = $tplvar['agent_2_v2'];
            $agentCount++;
            $tplvar['error'] .= 'Agent 2 added';
          }
        }
        elseif ($tplvar['agent_3_v2'] != ''){
          $SQL = "SELECT active FROM `registered_agents` WHERE (agent_id = '" . $tplvar['agent_3_v2'] . "'); ";
          $result = mysql_query($SQL) or die(mysql_error() . " " . $SQL);
          $row = mysql_fetch_assoc($result);
          $active = $row['active'];

          if($active == "Y"){
            $agentNums[$agentCount] = $tplvar['agent_3_v2'];
            $agentCount++;
            $tplvar['error'] .= 'Agent 3 added';
          }
        }
        elseif ($tplvar['agent_4_v2'] != ''){
          $SQL = "SELECT active FROM `registered_agents` WHERE (agent_id = '" . $tplvar['agent_4_v2'] . "'); ";
          $result = mysql_query($SQL) or die(mysql_error() . " " . $SQL);
          $row = mysql_fetch_assoc($result);
          $active = $row['active'];

          if($active == "Y"){
            $agentNums[$agentCount] = $tplvar['agent_4_v2'];
            $agentCount++;
            $tplvar['error'] .= 'Agent 4 added';
          }
        }

        if ($agentCount > 0){
          shuffle($agentNums);
          $agent_num = $agentNums[0];
          if (strlen($agent_num) == 3){
            $tplvar['agent_id_1'] = $agent_num;
            $tplvar['error'] .= 'Nothing here';
            $tplvar['agentCount'] = $agentCount;
          }
          else{
            $SQL = "SELECT office FROM zip_to_office WHERE (zip = '" . $tplvar['zip'] . "')";
            $result = mysql_query($SQL) or die(mysql_error() . " " . $SQL);
            $row = mysql_fetch_assoc($result);
            $tplvar['office'] = $row['office'];
            if($tplvar['office'] == "SS"){ $tplvar['office'] = "BSS"; }
            if($tplvar['office'] == "GC"){ $tplvar['office'] = "CG"; }
            $tplvar['error'] .= 'Got an office';

            //Get code and manager id from office where code is office from zip_to_office
            $SQL = "SELECT code, mgr_id FROM office WHERE (code = '" . $tplvar['office'] . "')";
            $result = mysql_query($SQL) or die(mysql_error() . " " . $SQL);
            $row = mysql_fetch_assoc($result);
            $tplvar['mgr_id'] = isset($row['mgr_id']) ? $row['mgr_id'] : '';
            $tplvar['error'] .= 'Got an id';
            
            $SQL = "SELECT active FROM `registered_agents` WHERE (agent_id = '".$tplvar['mgr_id']."')";
            $result = mysql_query($SQL) or die(mysql_error() . " " . $SQL);
            $row = mysql_fetch_assoc($result);
            $active = $row['active'];

            if($active == "Y"){ $tplvar['agent_id_1'] = $tplvar['mgr_id']; }
            else{ $tplvar['agent_id_1'] = "NB"; }              
          }
        }
        else{
          // Get office from zip_to_office where zip is zip of building
          $SQL = "SELECT office FROM zip_to_office WHERE (zip = '" . $tplvar['zip'] . "')";
          $result = mysql_query($SQL) or die(mysql_error() . " " . $SQL);
          $row = mysql_fetch_assoc($result);
          $tplvar['office'] = $row['office'];
          if($tplvar['office'] == "SS"){ $tplvar['office'] = "BSS"; }
          if($tplvar['office'] == "GC"){ $tplvar['office'] = "CG"; }
          $tplvar['error'] .= 'Got an office';

          //Get code and manager id from office where code is office from zip_to_office
          $SQL = "SELECT code, mgr_id FROM office WHERE (code = '" . $tplvar['office'] . "')";
          $result = mysql_query($SQL) or die(mysql_error() . " " . $SQL);
          $row = mysql_fetch_assoc($result);
          $tplvar['mgr_id'] = isset($row['mgr_id']) ? $row['mgr_id'] : '';          
          $tplvar['error'] .= 'Got an id';
          
          $SQL = "SELECT active FROM `registered_agents` WHERE (agent_id = '".$tplvar['mgr_id']."')";
          $result = mysql_query($SQL) or die(mysql_error() . " " . $SQL);
          $row = mysql_fetch_assoc($result);
          $active = $row['active'];

          if($active == "Y"){ $tplvar['agent_id_1'] = $tplvar['mgr_id']; }
          else{ $tplvar['agent_id_1'] = "NB"; }             
        }
      }

      $SQL = "SELECT first_name, last_name, title, agent_id, phone, email, bio FROM `registered_agents` WHERE (agent_id = '" . $tplvar['agent_id_1'] . "'); ";
      $result = mysql_query($SQL) or die(mysql_error() . " " . $SQL);
      $row = mysql_fetch_array($result, MYSQL_ASSOC);
      $tplvar['agent_id'] = $row['id'];
      $tplvar['agent_firstname'] = $row['first_name'];
      $tplvar['agent_lastname'] = $row['last_name'];
      $tplvar['agent_phone'] = $row['phone'];
      $tplvar['agent_email'] = $row['email'];
      $tplvar['agent_title'] = $row['title'];
      $tplvar['agent_bio_1'] = $row['bio'];
      $tplvar['agent_title'] = str_replace('Executive', 'Exec.', $tplvar['agent_title']);

      if($tplvar['agent_title'] == "Sales Director"){ $tplvar['agent_title'] = "Licensed Real Estate Sales Director"; }
      elseif($tplvar['agent_title'] == "Sales Manager"){ $tplvar['agent_title'] = "Licensed Real Estate Sales Manager"; }
      elseif($tplvar['agent_title'] == "Associate Broker"){ $tplvar['agent_title'] = "Licensed Associate Real Estate Broker"; }
      elseif($tplvar['agent_title'] == "Salesperson"){ $tplvar['agent_title'] = "Licensed Real Estate Salesperson"; }

      if($tplvar['agent_id_1'] == "NB"){ $tplvar['agent_email'] = "nbinder@homepik.com"; }

      $SQL = "SELECT first_name, last_name, title, agent_id, phone, email, bio FROM `registered_agents` WHERE (agent_id = '" . $tplvar['agent_id_2'] . "'); ";
      $result = mysql_query($SQL) or die(mysql_error() . " " . $SQL);
      $row = mysql_fetch_array($result, MYSQL_ASSOC);
      $tplvar['agent2_id'] = $row['id'];
      $tplvar['agent2_firstname'] = $row['first_name'];
      $tplvar['agent2_lastname'] = $row['last_name'];
      $tplvar['agent2_phone'] = $row['phone'];
      $tplvar['agent2_email'] = $row['email'];
      $tplvar['agent2_title'] = $row['title'];
      $tplvar['agent_bio_2'] = $row['bio'];
      $tplvar['agent2_title'] = str_replace('Executive', 'Exec.', $tplvar['agent_title']);

      if($tplvar['agent_title'] == "Sales Director"){ $tplvar['agent_title'] = "Licensed Real Estate Sales Director"; }
      elseif($tplvar['agent2_title'] == "Sales Manager"){ $tplvar['agent2_title'] = "Licensed Real Estate Sales Manager"; }
      elseif($tplvar['agent2_title'] == "Associate Broker"){ $tplvar['agent2_title'] = "Licensed Associate Real Estate Broker"; }
      elseif($tplvar['agent2_title'] == "Salesperson"){ $tplvar['agent2_title'] = "Licensed Real Estate Salesperson"; }
      
      if($tplvar['agent_id_2'] == "NB"){ $tplvar['agent2_email'] = "nbinder@homepik.com"; }

      // DETERMINE IF IT'S AN IDX LISTING
      $SQL = "SELECT * FROM IDX_Listing_Numbers WHERE `RLS_id` = CONCAT('\"','" . $tplvar['RLS_id'] . "','\"')";
      $result = mysql_query($SQL) or die(mysql_error() . " " . $SQL);
      $row = mysql_fetch_array($result, MYSQL_ASSOC);
      $tplvar['RLS_id_match'] = $row['RLS_id'];

      // DISPLAY WHICH FOLDERS THE LISTING HAS BEEN SAVED TO
      $tplvar['saved_to'] = '';
      $count= 0;
      if($_SESSION['agent']){
        foreach ($_SESSION['saved_listings'] as $key => $value) {
          if ($value[1] == $list_numb) {
            if ($value[0] == $_SESSION['email']) { $value[0] = 'Agent Folder'; }
            else{
              $SQL = "SELECT first_name, last_name FROM `users` where (email = '" . $value[0] . "')";
              $result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
              $row = mysql_fetch_array($result, MYSQL_ASSOC);
              $value[0] = $row['first_name'] . " " . $row['last_name'];
            }
            $tplvar['saved_to'] .= '<tr id="savedTo"><th>Saved to: </th><td>' . $value[0] . '</td></tr>';
            $count++;
          }
          $tplvar['count'] = $count;
        }
      }

      if($tplvar['broker'] == 'info@realplusonline.com'){ $tplvar['broker'] = "Real Plus Online"; }
      elseif($tplvar['broker'] == 'takumi@realtymx.com'){ $tplvar['broker'] = "Realty MX"; }

      if(!$tplvar['contact_phone']){ $tplvar['extraPhone'] = "<br>"; }
      else{ $tplvar['extraPhone'] = "or {$tplvar['contact_phone']} <br/>"; }


      // HTML TEMPLATE
      $templatehead = $smarty->fetch('headReduced.tpl.php', array('tplvar' => $tplvar));
      $template = $smarty->fetch('profile.tpl.php', array('tplvar' => $tplvar));
      print $template; // display the templated profile page

      // JAVASCRIPT TEMPLATE
      $jstemplate = $smarty->fetch('profile.tpl.js', array('tplvar' => $tplvar));
      // Display the templated javascript for the profile page
      echo "<div id='tobeInjected' style='display:none'>". $jstemplate ."</div>";

      //Record viewed listing to the database
      if(isset($_SESSION['agent']) || isset($_SESSION['buyer'])){
        $from = (isset($_SESSION['email'])) ? $_SESSION['email'] : '';
        $role = (isset($_SESSION['agent'])) ? 'agent' : 'user';
        $time = date('U');
  
        $con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
        $db = mysql_select_db('sp', $con) or die(mysql_error());
        $res = mysql_query("INSERT INTO viewed_listings(`user`,`list_num`, `role`, `time`) VALUES  ('" . $_SESSION['email'] . "','" . $list_numb . "','" . $role . "','" . $time . "')") or die(mysql_error());
      }
    }
  }
};

?>
