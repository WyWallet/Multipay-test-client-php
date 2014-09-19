<?php 

if (isset($_GET['paymentId']) && $_GET['paymentId'] != '') {

  if( !ini_get('date.timezone') ) {
      date_default_timezone_set('CET');
  }

  $timestamp = new DateTime('NOW');
  $logTime = $timestamp->format('Y-m-d H:i:s');
  $postbbackLogEntry = "[" . $logTime . "] Postback: OrderId: " . $_GET['orderId'] . ", PaymentId: " . $_GET['paymentId'] .  "\n";
  
  $response = $_SERVER['SERVER_PROTOCOL'] . ' 200 OK';
  $responseLogEntry = "[" . $logTime . "] Response: " . $response . "\r\n";
  
  $file = 'log/postback.log';
  file_put_contents($file, $postbbackLogEntry, FILE_APPEND | LOCK_EX);
  file_put_contents($file, $responseLogEntry, FILE_APPEND | LOCK_EX);

  header($response);

  // PERFORM GET-PAYMENT TO CHECK STATUS OF ORDER
  
  // SAVE STATUS OF ORDER - TAKE APROPRIATE ACTION IN ECOMMERCE SYSTEM
  
  // SEND RESPONSE: HTTP 200 OK
  
  //header($_SERVER['SERVER_PROTOCOL'] . ' OK'); 
}

?>