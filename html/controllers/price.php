<?php
session_start();
include_once("dbconfig.php");
include_once("functions.php");

if((authentication() == 'agent') OR (authentication() == 'user') OR (authentication() == 'guest') OR (authentication() == 'anonymous')){
  if(!isset($_GET['id'])) {
    exit();
  }

  header ("Content-type: image/png");
  $list_numb = $_GET['id'];
  $code = $_GET['code'];
  $session = session_id();
  $code2 = string_encrypt($list_numb, $session);
  $id = $_GET['id'];

  $db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
  mysql_select_db($database) or die("Error connecting to db.");
  
  $select = "price" ;
  $from = "vow_data";
  $where = "(list_numb = '".$id."')";
  $SQL = "SELECT ".$select." FROM ".$from." WHERE ".$where." ";
  $result = mysql_query($SQL) or die(mysql_error()." ".$SQL);
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $string = $row['price'];
  if ($string < 1000000){ 
    $string = '$  '.number_format($string, 0, ',', ',');
  } else {
    $string = '$'.number_format($string, 0, ',', ',');
  };
  $font_size   = 18;
  $width  = $font_size * strlen($string) * 0.7;
  $height = 22;
  $font_file = '../views/fonts/helveticaneue/HelveticaNeueLight.ttf';
  $im = @imagecreate ($width,$height);
  $background_color = imagecolorallocate ($im, 255, 255, 255); //white background
  $text_color = imagecolorallocate ($im, 90, 90,90);//black text
  imagettftext ($im, $font_size, 0, 0, $font_size, $text_color, $font_file, $string);
  imagepng ($im);
}
?>
