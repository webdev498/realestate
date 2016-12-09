<?php
function protect($string){
	$string = trim(strip_tags(addslashes($string)));
	return $string;
}

function useragent() {
  $useragent = $_SERVER['HTTP_USER_AGENT'];
  if(strchr($useragent,"iphone")) return 'touch';
  if(strchr($useragent,"Android")) return 'touch';
  if(strchr($useragent,"iPad")) return 'touch';
  if(strchr($useragent,"ipad")) return 'touch';
  if(strchr($useragent,"ios")) return 'touch';
  if(strchr($useragent,"iOS")) return 'touch';
  if(strchr($useragent,"IOS")) return 'touch';
  if(strchr($useragent,"WebKit")) return 'webkit';
  if(strchr($useragent,"Blackberry")) return 'Blackberry';
}


// Averages function for search
function relative_to_average($grade,$average, $color){
	if ($color == 'gold'){
		if ($grade == 0) { $grade = 'images/nodata.png'; }
		elseif ($grade > $average) { $grade = 'images/gold2.png'; }
		elseif ($grade == $average) { $grade = 'images/silver2.png'; }
		elseif ($grade < $average) { $grade = 'images/bronze2.png'; }
	  return $grade;
	}
	else {
		if ($grade == 0) { $grade = 'images/nodata.png'; }
		elseif ($grade > $average) { $grade = 'images/gold.png'; }
		elseif ($grade == $average) { $grade = 'images/silver.png'; }
		elseif ($grade < $average) { $grade = 'images/bronze.png'; }
		return $grade;
	}
};

// SHA512 encryption with nonce and key for storing passwords, concealing listing numbers, etc.
$site_key = 'gygkgk456765^&*$%#%hgghjkg67596069^&(%&*$rtghgfgdf43#$%^&%677808ghgjkgy&h';
function string_encrypt($string, $nonce) {
  return hash_hmac('sha512', $string . $nonce, $site_key);
}


// Drupal password encryption (for authenticating Bellmarc agents only)
function drupal_pass_check($string) {
  return md5($string);
};

// Authentication
function authentication(){
  if (isset($_SESSION['agent'])){
	  $status = 'agent';
	  $logged_in = 'true';
    return 'agent';
  }
  elseif (isset($_SESSION['user'])){
    if($_SESSION['role'] == 'guest'){
      $status = 'guest';
	    $logged_in = 'true';
      return 'guest';
    } else{
      $status = 'user';
	    $logged_in = 'true';
      return 'user';
    }
  }
  else {
    return 'anonymous';
    $status = 'none';
  }
};

// Rate limit to prevent scraping
function limit(){
  $id = isset($_SESSION['id'])? $_SESSION['id'] :'';
  $email = isset($_SESSION['email'])? $_SESSION['email'] : '';
  $time = date('U');
  $one_hour_ago = $time - 36000;
  $five_minutes_ago = $time - 300;
  $ipaddr = $_SERVER['REMOTE_ADDR'];

  $res = mysql_query("SELECT * FROM users WHERE (id = '".$id."' OR banned = '".$ipaddr."')");
  $num2 = mysql_num_rows($res);
  $row = mysql_fetch_assoc($res);

  $blocked = $row['banned'];
  $strikes = $row['strikes'];
  if ($strikes >= 3){
    $status = 'banned';
  } elseif ($blocked == 'delayed'){
    $status = 'blocked';
  } elseif ($blocked == 'blocked'){
    $status = 'blocked';
  } else {
    $res = mysql_query("SELECT * FROM viewed_listings WHERE (time > ".$five_minutes_ago.") AND (user = '".$id."')");
    $num1 = mysql_num_rows($res);

    $res = mysql_query("SELECT * FROM search_history WHERE (time > ".$five_minutes_ago.") AND (user = '".$email."')");
    $num2 = mysql_num_rows($res);

    $res = mysql_query("SELECT * FROM viewed_listings WHERE (time > ".$one_hour_ago.") AND (user = '".$id."')");
    $num1b = mysql_num_rows($res);

    $res = mysql_query("SELECT * FROM search_history WHERE (time > ".$one_hour_ago.") AND (user = '".$email."')");
    $num2b = mysql_num_rows($res);

    $num = $num1 + $num2;
    $numb = $num1b + $num2b;
    if (($num > 225) || ($numb > 2000)){
      $strikes = $strikes + 1;

      mysql_query("UPDATE users SET banned = '".$ipaddr."',  strikes = '".$strikes."' WHERE id = '".$id."' ");

      if ($strikes < 3){
        $status = 'blocked';
      } else {
        $status = 'banned';
      }
    } else {
      $status = 'clear';
    }
  };

  if ($status == 'delayed'){
    header('Location: delay.php');
    session_destroy();
  } elseif ($status == 'blocked'){
    session_destroy();
    header('Location: blocked.php');
  } elseif ($status == 'banned') {
    header('Location: banned.php');
  } else {
    return $status;
  }
};

function urlmtime($url) {
   $parsed_url = parse_url($url);
   $path = $parsed_url['path'];

   if ($path[0] == "/") { $filename = $_SERVER['DOCUMENT_ROOT'] . "/" . $path; }
	 else { $filename = $path; }

   if(!file_exists($filename)) {
		// If not a file then use the current time
    $lastModified = date('YmdHis');
   }
	 else { $lastModified = date('YmdHis', filemtime($filename)); }

   if (strpos($url, '?') === false) { $url .= '?ts=' . $lastModified; }
	 else { $url .= '&ts=' . $lastModified; }

   return $url;
}

function include_css($css_url, $media='all') {
  echo '<link rel="stylesheet" type="text/css" media="' . $media . '" href="' . urlmtime($css_url) . '">'."\n";
}

function include_javascript($javascript_url, $type='text/javascript') {
  echo '<script type="'.$type.'" src="' . urlmtime($javascript_url) . '"></script>'."\n";
}

function generateRandomString() {
	$length = rand(5, 15);
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i=0; $i<$length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}
?>
