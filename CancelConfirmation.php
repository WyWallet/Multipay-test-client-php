<!DOCTYPE html>
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>WyWallet-kassan Test Client</title>
    <meta charset="utf-8" />
    <link href="style.css" rel="stylesheet" />
  </head>
  <body>
    <br>
    <img alt="Wy-kassan Test Tool" src="img/WyWallet_Logo_RGB_s.png">​​
    <br><br>
    <?php
    if (isset($_GET['orderId']) && $_GET['orderId'] != '' && isset($_GET['paymentId']) && $_GET['paymentId'] != '') {
      echo '<p><strong>Order with order id: ' . $_GET['orderId'] . ' has been canceled!</strong><br><br>';
      echo '<em><a href="api-functions/GetPayment.php?uxPaymentId=' . $_GET['paymentId'] . '">Check Payment</a></em></td>';
    } else {
      echo '<p><strong>Order has been canceled!</strong>';
    }
    ?>
  </body>
</html>
