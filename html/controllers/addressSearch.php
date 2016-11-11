<?
session_start();
include_once("dbconfig.php");
include_once('functions.php');
include("basicHead.php");
// LOAD SMARTY TEMPLATE ENGINE
include_once('../classes/smarty/SmartyBC.class.php');
$smarty = new SmartyBC();
$smarty->setTemplateDir('../views');
$smarty->setCompileDir('../views/compiled');
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());
$_SESSION['user'] = 'true';
if (!$_SESSION['email']){
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
    // GET THE LIST_NUM OF ALL SAVED LISTINGS (BOTH SAVED TO USERS AND OPEN LISTINGS) SO WE CAN MARK THEM WITH A SAVED ICON IN SEARCH RESULTS
    $SQL = "SELECT id FROM Agent_Import WHERE e_mail = '".$_SESSION['email']."'";
    $rs = mysql_query($SQL);
    while ($row = mysql_fetch_array($rs)) {
      $id = $row['id'];
    }
  
    $SQL = "SELECT email FROM users WHERE P_agent = '".$id."'";
    $rs = mysql_query($SQL);
    while ($row = mysql_fetch_array($rs)) {
      $sql = "(SELECT user, list_num FROM saved_listings WHERE user = '".$row['email']."' AND saved_by='" . $_SESSION['email'] . "')";
      $rs2 = mysql_query($sql);
      while ($row2 = mysql_fetch_array($rs2)) {
        $saved_listings[] = $row2;
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
    $SQL = "SELECT firstname, lastname, id FROM `Agent_Import` where (e_mail = '" . $_SESSION['email'] . "')";
    $result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    $name = $row['firstname'] . " " . $row['lastname'];
    $agentID = $row['id'];    
  }
  elseif($_SESSION['user'] && $_SESSION['email'] != "guest@email.com"){
    $SQL = "SELECT first_name, last_name FROM `users` where (email = '" . $_SESSION['email'] . "')";
    $result = mysql_query($SQL) or die("Couldn't execute query." . mysql_error());
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    $name = $row['first_name'] . " " . $row['last_name'];
    $agentID = "";
  }
  else{
    $name = "Guest";
    if(isset($_SESSION['guestID']) && !empty($_SESSION['guestID'])){      
      $_SESSION['guestID'] = $_SESSION['guestID']; //Use guest ID
    }
    else{ 
      $_SESSION['guestID'] = session_id(); //Set guest ID
      $time = date('U');
      $res = mysql_query("INSERT INTO users_folders(`user`,`name`,`last_update`) VALUES  ('".$_SESSION['guestID']."','Guest Folder','".$time."')")  or die(mysql_error());
    }
    $agentID = "";
  }
	$_SESSION['pageVisit'] = "true";
	//SMARTY TEMPLATES
	// Pass variables to the smarty templates
	$tplvar['auth'] = authentication();
	$tplvar['useragent'] = useragent();
	$tplvar['name'] = $name;
	$tplvar['buyers'] = $buyers;
	$tplvar['role'] = $_SESSION['role'];
  $tplvar['agentID'] = $agentID;
	$tplvar['email'] = $_SESSION['email'];  
	$tplvar['guestID'] = $_SESSION['guestID'];
	$tplvar['loadSaved'] = $_SESSION['loadSaved']; $_SESSION['loadSaved'] = false;
  $templatehead = $smarty->fetch('headReduced.tpl.php', array('tplvar' => $tplvar));
  $templatebody = $smarty->fetch('addressSearch.tpl.php', array('tplvar' => $tplvar));
	// Display the templated search page
	print $templatehead;
  include_javascript("/js/addressSearch-react.js", "text/babel");
  include_javascript("/js/search.js");
  include_css("/views/css/master_a-header-only.css");
  include_css("/views/css/master_c-menu---search-field.css");
  include_css("/views/css/addressSearch.css");
  include_css("/views/css/buyer-profile-edit.css");
	print $templatebody;
};
?>
<?php include_once('autoLogout.php'); ?>
</body>
</html>