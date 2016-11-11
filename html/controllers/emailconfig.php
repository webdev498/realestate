<?php
  if(strpos($_SERVER['REQUEST_URI'], '/users/') !== false) {
    require_once('../../PHPMailer/PHPMailerAutoload.php');
  }else{
    require_once('../PHPMailer/PHPMailerAutoload.php');
  }
 
  $mail = new PHPMailer;
  //$mail->SMTPDebug = 3;                               // Enable verbose debug output
  $mail->IsSMTP();                                      // Set mailer to use SMTP
  $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
  $mail->SMTPAuth = true;                               // Enable SMTP authentication
  $mail->Username = 'no-reply@homepik.com';              // SMTP username
  $mail->Password = 'HomePik67';                        // SMTP password
  $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
  $mail->Port = 465;                                    // TCP port to connect to
  $mail->isHTML(true);                                  // Set email format to HTML
  
  $mail->setFrom('no-reply@homepik.com', 'HomePik.com');
?>