<?php
session_start();
include("dbconfig.php");
include("functions.php");
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());

$id = $_SESSION['id'];
$email = $_SESSION['email'];
$ipaddr = $_SERVER['REMOTE_ADDR'];

if($_POST['captcha']){
   require_once('../classes/captcha/recaptchalib.php');
  $privatekey = "6Lco78USAAAAAB5Vjh51ym6JpCux7jwVm2GeRwM_";
  $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

  if (!$resp->is_valid) {
    // What happens when the CAPTCHA was entered incorrectly
    $html = "The reCAPTCHA wasn't entered correctly. Go back and try it again.";
  } else {

    $html = "Please wait while we direct you to the search page.";
      mysql_query("UPDATE users SET banned = '' WHERE (id = '".$id."' OR banned = '".$ipaddr."')") or die(mysql_error ());
      header('Location: index.php');
  }
} else {

    

  $html = "Please type the words below to return to the login page:
           <br/><br/>

      <form method='post' action='blocked.php'>";

          require_once('../classes/captcha/recaptchalib.php');
          $publickey = '6Lco78USAAAAADXTPFYzKgWFBLIiIMY8SmSvKR_V'; // you got this from the signup page
          $html .= recaptcha_get_html($publickey);
      
     $html .= "<input type='hidden' name='captcha' value='true'/>
<input type='submit' value='Continue' style='border:1px solid #aaa; border-radius: 5px; -mox-border-radius:5px; color:#000;background:#fff;padding:8px;margin:8px 0 0 0;font-weight:bold;font-size:0.8em;'/>
      </form>";
};
  ?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
<? include 'head.php'; ?>
    <style type="text/css">
    body {
    color: #606060;
    font-family: 'Lato', sans-serif;
    font-size: 1em;
    letter-spacing: 0.02em;
    margin: 0 auto 20px;
    width: 880px;
}
    input.grade_desc {
    height:18px;
}
    #border {
    margin:auto;
    width:550px
}
label.error { float: none; color: red; padding: 0 0 0 0.5em; }

    </style>

    </head>
<body>
    <div id="header">
    <div style="width: 960px;margin: auto;font-family: Bank Gothic, BankGothic;position: relative;">
<img alt="selection portfolio" src="https://homepik.com/logo.png" id="logo" alt="Homepik Logo"/>

    </div>
    </div>
  <div id="wrapper">
    <div id="main" style="position:relative;">

    <?=$html?>

    </div>
  </div>
</body>
</html>