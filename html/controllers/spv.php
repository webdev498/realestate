<?php
require_once("/dompdf/autoload.inc.php");
include_once("dbconfig.php");

// reference the Dompdf namespace
use Dompdf\Dompdf;

// connect to the MySQL database server
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if(isset($_POST['submit'])){
  
  $list_num = $_REQUEST['listing'];
  $listing_price = $_REQUEST['price'];
  $min_maint = $_REQUEST['min_maint'];
  $min_bed = $_REQUEST['min_bed'];
  $min_bath = $_REQUEST['min_bath'];
  $min_floor = $_REQUEST['min_floor'];
  $listings = $_REQUEST['listing_type'];
  $location = $_REQUEST['listing_location'];
  $condition = $_REQUEST['condition'];
  $demand = $_REQUEST['demand'];
  $interest = $_REQUEST['interest'];
  $condo_only = (isset($_REQUEST['condos_only']) ? $_REQUEST['condos_only'] : "no");
  $min_bld = $_REQUEST['min_bld'];
  $min_loc = $_REQUEST['min_loc'];
  $min_vws = $_REQUEST['min_view'];
  $min_space = $_REQUEST['min_space'];
  $amenity = array();
  (isset($_REQUEST['amenities']) ? $amenity = $_REQUEST['amenities'] : array_push($amenity, "none"));
  $outdoor_amenity = $_REQUEST['outdoors_amenity'];
  array_push($amenity, $outdoor_amenity);
  
  include_once('spv-listing-details.php');
      
  switch($location){
    case "Local": $location = "local area"; break;
    case "Zip": $location = "with zip code " . $zip; break;
    case "All": $location = "all of Manhattan"; break;
  }
      
  $htmlString = '';
  ob_start();
  include('spv-template.php');
  $htmlString .= ob_get_clean();
  
  ini_set('memory_limit', '-1');
  
  // instantiate and use the dompdf class
  $dompdf = new Dompdf();
  $dompdf->loadHtml($htmlString);
  
  // Render the HTML as PDF
  $dompdf->render();
  
  // Output the generated PDF to Browser
  //$dompdf->stream("Selection_Portfolio_Valuation"); // Gives option to open in Adobe or save to computer
  $dompdf->stream("Selection_Portfolio_Valuation", array('Attachment'=>0)); // Open in tab
}
?>