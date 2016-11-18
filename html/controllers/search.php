<?php
session_start();
include_once("dbconfig.php");
include_once('functions.php');
include("basicHead.php");
include_once('../classes/smarty/Smarty.class.php'); // LOAD SMARTY TEMPLATE ENGINE
$smarty = new Smarty();
$smarty->setTemplateDir('../views');
$smarty->setCompileDir('../views/compiled');
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());
$_SESSION['user'] = 'true';
if (!isset($_SESSION['email'])){
  $_SESSION['email'] = 'guest@email.com';
  $_SESSION['role'] = 'guest';
}
$name = explode('@', $_SESSION['email']);
$name = $name[0];
// If the user has been rate limited because of too many requests, cut them off (VOW RULE)
$limit = limit();
if ($limit != 'clear') { limit(); }
else {
  if(isset($_SESSION['agent'])){
    // GET THE LIST_NUM OF ALL SAVED LISTINGS SO WE CAN MARK THEM WITH AS SAVED IN THE LISTING DETAILS
    $saved_listings = array();
    $SQL = "SELECT agent_id FROM `registered_agents` WHERE email = '".$_SESSION['email']."'";
    $rs = mysql_query($SQL);
    $row = mysql_fetch_array($rs);
    $id = $row['agent_id'];

    if(isset($id)){ 
      $SQL = "SELECT email FROM users WHERE P_agent = '".$id."'";
      $rs = mysql_query($SQL);
      while ($row = mysql_fetch_array($rs)) {
        $sql = "(SELECT user, list_num FROM saved_listings WHERE user = '".$row['email']."' AND saved_by='" . $_SESSION['email'] . "')";
        $rs2 = mysql_query($sql);
        while ($row2 = mysql_fetch_array($rs2)) {
          $saved_listings[] = $row2;
        }
      }
    }
    $sql = "(SELECT user, list_num FROM queued_listings WHERE saved_by='" . $_SESSION['email'] . "')";
    $rs2 = mysql_query($sql);
    while ($row2 = mysql_fetch_array($rs2)) {
      $saved_listings[] = $row2;
    }
    $_SESSION['saved_listings'] = $saved_listings;    
  }

  if(isset($_SESSION['agent'])){
    $SQL = "SELECT first_name, last_name, agent_id FROM `registered_agents` where (email = '" . $_SESSION['email'] . "')";
    $result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    $name = $row['first_name'] . " " . $row['last_name'];
    $agentID = $row['agent_id'];
    $num_searches = 0;
  }
  elseif($_SESSION['user'] && $_SESSION['email'] != "guest@email.com"){
    $SQL = "SELECT first_name, last_name FROM `users` where (email = '" . $_SESSION['email'] . "')";
    $result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    $name = $row['first_name'] . " " . $row['last_name'];
    $agentID = "";

    $SQL2 = "SELECT COUNT(*) AS count FROM `Users_Search` where (email = '" . $_SESSION['email'] . "')";
    $result2 = mysql_query($SQL2) or die("Couldn't execute query." . mysql_error());
    $row2 = mysql_fetch_array($result2, MYSQL_ASSOC);
    $num_searches = $row2['count'];
    
    if(isset($_SESSION['justRegisteredSaveFormula'])){
      $justReg = $_SESSION['justRegisteredSaveFormula'];
      unset($_SESSION['justRegisteredSaveFormula']);
    }
    else{ $justReg = 'false'; }    
  }
  else{
    $name = "Guest";
    $num_searches = 0;
    $agentID = "";
    if(isset($_SESSION['guestID']) && !empty($_SESSION['guestID'])){
      $_SESSION['guestID'] = $_SESSION['guestID']; //Use guest ID
    }
    else{      
      $_SESSION['guestID'] = session_id(); //Set guest ID
      $time = date('U');
      $res = mysql_query("INSERT INTO users_folders(`user`,`name`,`last_update`) VALUES  ('".$_SESSION['guestID']."','Guest Folder','".$time."')")  or die(mysql_error());
    }
  }
	//SMARTY TEMPLATES
	// Pass variables to the smarty templates
	$tplvar['auth'] = authentication();
	$tplvar['useragent'] = useragent();
	$tplvar['name'] = $name;
	$tplvar['buyers'] = $buyers;
	$tplvar['role'] = $_SESSION['role'];
	$tplvar['email'] = $_SESSION['email'];
	$tplvar['guestID'] = $_SESSION['guestID'];
  $tplvar['agentID'] = $agentID;
  $tplvar['numSearches'] = $num_searches;
  $tplvar['justRegSaveForm'] = $justReg;
	$tplvar['loadSaved'] = isset($_SESSION['loadSaved'])? $_SESSION['loadSaved']: false;
	$templatehead = $smarty->fetch('headReducedOld.tpl.php', array('tplvar' => $tplvar));
	$templatebody = $smarty->fetch('search.tpl.php', array('tplvar' => $tplvar));
	// Display the templated search page
	print $templatehead;
  include_javascript("/js/search-react.js", "text/babel");
  include_javascript("/js/search.js");
  include_css("/views/css/buying-formula-popup.css");
  include_css("/views/css/master_c-menu---search-field.css");
  include_css("/views/css/search.css");
	print $templatebody;
};
?>
<?php include_once("analyticstracking.php") ?>
<?php include_once('autoLogout.php'); ?>

</body>
</html>
