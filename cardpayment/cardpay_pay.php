<?php

$token = $_GET['t'];

session_start();
$mid = $_SESSION['mid'];
$price = $_SESSION['price'];
$orderid = $_SESSION['orderID'];
$firstname = $_SESSION['firstname'];
$lastname = $_SESSION['lastname'];
$address1 = $_SESSION['address1'];
$address2 = $_SESSION['address2'];
$city = $_SESSION['city'];
$state = $_SESSION['state'];
$postcode = $_SESSION['postcode'];
$orders = $_SESSION['orders'];
$pass = $_SESSION['pass'];


$url = 'https://api.na.bambora.com/v1/payments';
$pass = base64_encode($mid.":".$pass);
$postData =array(
  'order_number' => '',
  'amount' => $price,
  'payment_method' => 'token',
  'token' => array(
    'name' => $firstname.$lastname,
    'code' => $token,
    'complete' => 'true'
  ),
    'billing' => array(
      'postal_code' => $postcode
    )

);
//MzAwMjA3MTYyOkUwMTYxOTgwRTcxNTQ4ZjM4QmQ3MDhCMzNGMzMyM2Q3
$context = stream_context_create(array(
  'http' => array(
      'method' => 'POST',
//      'header' => "Authorization: Passcode". base64_encode('E0161980E71548f38Bd708B33F3323d7')."\r\n".
//        'header' => "Authorization: Passcode MzAwMjA3MTYyOkUwMTYxOTgwRTcxNTQ4ZjM4QmQ3MDhCMzNGMzMyM2Q3\r\n".
    'header' => "Authorization: Passcode ".$pass. "\r\n".
                  "Content-Type: application/json\r\n",
//                  "Sub-Merchant-id: ".$mid."\r\n",
      'content' => json_encode($postData)
  )
));

$response = file_get_contents($url, FALSE, $context);

if($response === FALSE){
  echo '<script type="text/javascript">
           window.location.replace("https://demo.alphapay.ca/test/card_error.php");
      </script>';
      die('Error');
  }


  $responseData = json_decode($response, TRUE);


  print_r('Order id:'.$responseData['id']."\r\n");
  print_r("PAYMENT SUCCESS");
  header('Location: '.$orderid);
  die();

 ?>
