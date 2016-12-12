<?php
session_start();
include_once("dbconfig.php");
include_once('functions.php');
include_once("basicHead.php");
include_once('../classes/smarty/Smarty.class.php'); // LOAD SMARTY TEMPLATE ENGINE
$smarty = new Smarty();
$smarty->setTemplateDir('../views');
$smarty->setCompileDir('../views/compiled');
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());
$_SESSION['user'] = 'true';
if(!isset($_SESSION['email'])){
  $_SESSION['email'] = 'guest@email.com';
  $_SESSION['role'] = 'guest';
}
$name = explode('@', $_SESSION['email']);
$name = $name[0];
// If the user has been rate limited because of too many requests, cut them off (VOW RULE)
$limit = limit();
if($limit != 'clear') { limit(); }
else {
  if(isset($_SESSION['agent'])){
    $saved_listings = array();
    $name = $_SESSION['firstname'] . " " . $_SESSION['lastname'];
    $agentID = $_SESSION['agent_id'];
    $num_searches = 0;

    // GET THE LIST_NUM OF ALL SAVED LISTINGS SO WE CAN MARK THEM WITH AS SAVED IN THE LISTING DETAILS
    if(isset($agentID)){ 
      $rs = mysql_query("SELECT email FROM users WHERE P_agent = '".$agentID."'");
      while ($row = mysql_fetch_array($rs)) {
        $rs2 = mysql_query("(SELECT user, list_num FROM saved_listings WHERE user = '".$row['email']."' AND saved_by='" . $_SESSION['email'] . "')");
        while ($row2 = mysql_fetch_array($rs2)) {
          $saved_listings[] = $row2;
        }
      }
    }
    
    $rs2 = mysql_query("(SELECT user, list_num FROM queued_listings WHERE saved_by='" . $_SESSION['email'] . "')");
    while ($row2 = mysql_fetch_array($rs2)) {
      $saved_listings[] = $row2;
    }
    $_SESSION['saved_listings'] = $saved_listings;    
  }
  elseif(isset($_SESSION['buyer'])){
    $name = $_SESSION['firstname'] . " " . $_SESSION['lastname'];
    $agentID = "";

    $result2 = mysql_query( "SELECT COUNT(*) AS count FROM `Users_Search` where (email = '" . $_SESSION['email'] . "')" ) or die("Couldn't execute query." . mysql_error());
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
	$tplvar['messages'] = $_SESSION['unreadMessages'];
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
