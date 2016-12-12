<?php
session_start();
include_once("dbconfig.php");
include_once('functions.php');
// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

$email = (isset($_POST['email']) ? $_POST['email'] : $_SESSION['email']);
$agent_id = (isset($_POST['agentID']) ? $_POST['agentID'] : ""); 
$listings = array();
$results= array();
$id = 1;

if($agent_id != ''){
  $buyer_folders = array();
  $day = date('U') - 86400;
  $result = mysql_query( "SELECT * FROM users_folders WHERE (agent LIKE '%".$agent_id."%') AND (last_update > ".$day.") ORDER BY name ASC" ) or die("Couldn't execute query.".mysql_error());
  while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    $row['folderName'] = $row['name'];
    
    $result2 = mysql_query( "SELECT CONCAT(first_name, ' ', last_name) AS name FROM `users` WHERE (email = '".$row['user']."')" ) or die("Couldn't execute query.".mysql_error());
    $row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
    $row['name'] = $row2['name'];
    
    $row['last_update'] = date( "m/d/y", $row['last_update']);
    
    if(strpos($row['agent'], ',') === false){
      $result2 = mysql_query( "SELECT CONCAT(first_name, ' ',last_name) AS name FROM `registered_agents` WHERE (agent_id = '".$row['agent']."')" ) or die("Couldn't execute query.".mysql_error());
      $row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
      $row['agent'] = $row2['name'];
    }
    else{
      $agents = explode(",", $row['agent']);
      
      $result2 = mysql_query( "SELECT CONCAT(first_name, ' ',last_name) AS name FROM `registered_agents` WHERE (agent_id = '".$agents[0]."')" ) or die("Couldn't execute query.".mysql_error());
      $row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
      $agent1 = $row2['name'];
      
      $result3 = mysql_query( "SELECT CONCAT(first_name, ' ',last_name) AS name FROM `registered_agents` WHERE (agent_id = '".$agents[1]."')" ) or die("Couldn't execute query.".mysql_error());
      $row3 = mysql_fetch_array($result3,MYSQL_ASSOC);
      $agent2 = $row3['name'];
      
      $row['agent'] = $agent1 . ", " . $agent2;
    }
    
    $buyer_folders[] = $row;
  }

  $last_update = 0;
  foreach ($buyer_folders as $folder) {
    $folder['listings'] = array();
    $grade_total['loc'] = 0;
    $grade_total['bld'] = 0;
    $grade_total['vws'] = 0;
    $grade_total['vroom_sqf'] = 0;
    $result_total = 0;
    
    $result = mysql_query( "SELECT saved_listings.list_num, saved_listings.user, vow_data.loc, vow_data.bld, vow_data.vws,  vow_data.vroom_sqf, vow_data.floor FROM `saved_listings`, `vow_data` where (saved_listings.list_num = vow_data.list_numb) AND (saved_listings.user = '".$folder['user']."') AND (saved_listings.folder = '".$folder['folderName']."')" ) or die("Couldn't execute query.".mysql_error());
    while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
      $grade['loc'] = $row['loc'];
      $grade['bld'] = $row['bld'];
      $grade['vws'] = $row['vws'];
      $grade['vroom_sqf'] = $row['vroom_sqf'];
      $grade_total['loc'] = $grade_total['loc'] + $grade['loc'];
      $grade_total['bld'] = $grade_total['bld'] + $grade['bld'];
      $grade_total['vws'] = $grade_total['vws'] + $grade['vws'];
      $grade_total['vroom_sqf'] = $grade_total['vroom_sqf'] + $grade['vroom_sqf'];
      $result_total = $result_total + 1;
    };
  
    $grade_average['loc'] = $grade_total['loc'] / $result_total;
    $grade_average['bld'] = $grade_total['bld'] / $result_total;
    $grade_average['vws'] = $grade_total['vws'] / $result_total;
    $grade_average['vroom_sqf'] = $grade_total['vroom_sqf'] / $result_total;
  
    foreach($grade_average as &$value){
        $value = number_format($value, 0, '.', ',');
    };

    $result = mysql_query( "SELECT distinct saved_listings.list_num, saved_listings.user, saved_listings.comments, saved_listings.folder, saved_listings.time, vow_data.price, vow_data.address, vow_data.apt, vow_data.lr, vow_data.br1, vow_data.bed, vow_data.bath, vow_data.maint, vow_data.taxes, vow_data.loc, vow_data.bld, vow_data.vws, vow_data.vroom_sqf, vow_data.status FROM `saved_listings`, `vow_data` where (saved_listings.list_num = vow_data.list_numb) AND (saved_listings.user = '".$folder['user']."') AND (saved_listings.folder = '".$folder['folderName']."')" ) or die("Couldn't execute query.".mysql_error());
    while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
      $date = date( "m/d/y", $row['time']);
      if($date > $last_update){$last_update = $date;}
      $address = ucwords(strtolower($row['address']));
      $price = substr($row['price'], 0, strpos($row['price'], "."));
      $price = number_format($price, 0, '.', ',');
      $monthly = (intval($row['maint']) + intval($row['taxes']));
      $monthly = number_format($monthly, 0, '.', ',');
      $bath = $row['bath'];
      $bath = number_format($bath, 0, '.', ',');
      $loc = $row['loc'];
      $loc = number_format($loc, 0, '.', ',');
      $aloc = relative_to_average($loc,$grade_average['loc'],'gold');
      $aloc = 'http://homepik.com/' . $aloc;
      $bld = $row['bld'];
      $bld = number_format($bld, 0, '.', ',');
      $abld = relative_to_average($bld,$grade_average['bld'],'gold');
      $abld = 'http://homepik.com/' . $abld;
      $vws = $row['vws'];
      $vws = number_format($vws, 0, '.', ',');
      $avws = relative_to_average($vws,$grade_average['vws'],'gold');
      $avws = 'http://homepik.com/' . $avws;
      $vroom_sqf = $row['vroom_sqf'];
      $vroom_sqf = number_format($vroom_sqf, 0, '.', '');
      $vroom_sqf = str_replace(',', '', $vroom_sqf);
      $grade_average['vroom_sqf'] = str_replace(',', '', $grade_average['vroom_sqf']);
      $avroom_sqf = relative_to_average($vroom_sqf,$grade_average['vroom_sqf'],'gold');
      $avroom_sqf = 'http://homepik.com/' . $avroom_sqf;
      $comments = $row['comments'];
      if($comments == ''){$comments = "No comments";}
      $status =  $row['status'];
      if($status == "AVAIL"){$status = "Available";}
      elseif($status == "CONTB"){$status = "Bellmarc Contract";}
      elseif($status == "INCON"){$status = "Non Bellmarc Contract";}
      elseif($status == "OTM"){$status = "Off the Market";}
      elseif($status == "POM"){$status = "Permanently Off Market";}
      elseif($status == "TOM"){$status = "Temporarily Off Market";}
      elseif($status == "SOLD"){$status = "Sold";}
      elseif($status == "RENTD" || $status == "RENTE" || $status == "RENTED"){$status = "Rented";}
      $listing = array("id"=>$id, "last_update"=>$last_update, "listing_num"=>$row['list_num'], "comments"=>$comments,
                       "folder"=>$row['folder'], "date"=>$date, "price"=>$price, "address"=>$address, "apt"=>$row['apt'],
                       "lr"=>$row['lr'], "br"=>$row['br1'], "bed"=>$row['bed'], "bath"=>$bath, "monthly"=>$monthly,
                       "maint"=>$row['maint'], "taxes"=>$row['taxes'], "loc"=>$aloc, "bld"=>$abld, "vws"=>$avws,
                       "space"=>$avroom_sqf, "status"=>$status);

      $folder['listings'][] = $listing;
      $id = $id + 1;
    }
    
    $result = mysql_query( "SELECT * FROM `users` WHERE (email = '".$folder['user']."')" ) or die("Couldn't execute query.".mysql_error());    
    while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
      $agent_code = $row['P_agent'];
      $agent2_code = $row['P_agent2'];
    }
    
    if($agent_code != "" && $agent_code != null){
      $result = mysql_query( "SELECT CONCAT(first_name, ' ',last_name) AS name FROM `registered_agents` WHERE (agent_id = '".$agent_code."')" ) or die("Couldn't execute query.".mysql_error());
      $row = mysql_fetch_array($result,MYSQL_ASSOC);
      $folder['agent1'] = $row['name'];
    }
    else{
      $folder['agent1'] = "";
    }
    
    if($agent2_code != "" && $agent2_code != null){
      $result = mysql_query( "SELECT CONCAT(first_name, ' ',last_name) AS name FROM `registered_agents` WHERE (agent_id = '".$agent2_code."')" ) or die("Couldn't execute query.".mysql_error());
      $row = mysql_fetch_array($result,MYSQL_ASSOC);
      $folder['agent2'] = $row['name'];
    }    
    else{
      $folder['agent2'] = "";
    }

    if ($last_update != 0 && empty($folder['last_update'])) {
      $folder['last_update'] = $last_update;
    }

    $results[] = $folder;
  }
}

echo json_encode($results);
?>
