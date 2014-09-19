<?php
session_start();

  if (isset($_GET['paymentId']) && $_GET['paymentId'] != '') {

    require_once 'backend/Client.php';
    require_once 'include/Config.php';
    $client = new Client();

    if(isset($_SESSION['key']) && isset($_SESSION['env'])) {
      $secretKey = $_SESSION['key'];
      $environment = $_SESSION['env'];
      session_unset();
      session_destroy();
    }

    $responseObject = $client->getPayment($_GET['paymentId'], $environment, $secretKey);
    $logOutput = print_r($responseObject, true);

    if (is_object($responseObject) && !empty($responseObject->body->transaction) && $responseObject->body->id == $_GET['paymentId']) {
      $message = "Payment has been successfully completed.";
      $orderId = $_GET['orderId'];
      $paymentId = $_GET['paymentId'];
      $transactionId = $responseObject->body->transaction->id;
      $status = $responseObject->body->transaction->status;
      $a2m = $responseObject->body->deliveryAddress;
      $amount = $responseObject->body->transaction->amount;
      $vat = $responseObject->body->transaction->vat;
      $vatFormat = $responseObject->body->transaction->vatFormat;
    } else {
      $message = "Payment Complete! No Order ID specified, please update configuration in Merchant Admin.";
    }
  } else {
    $logOutput = 'Incorrect return parameters - unknown error';
  }
?>
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

    <p><strong><?php echo $message;?></strong>
    <table>
      <tr>
        <td>Order ID:</td>
        <td><?php echo $orderId;?></td>
      </tr>
      <tr>
        <td>Payment ID:</td>
        <td><?php echo $paymentId;?></td>
      </tr>
      <tr>
        <td>Transaction ID:</td>
        <td><?php echo $transactionId;?></td>
      </tr>
      <tr>
        <td>Status:</td>
        <td><?php echo $status;?></td>
      </tr>
    </table>

    <table>
      <tr>
        <td>
          <?php if (empty($a2m)) { ?>
          Address 2 Merchant:<br><pre><?php print_r($a2m);?></pre>
          <?php } ?>
        </td>
      </tr>
    </table>

    <table>
      <tr>
        <td><em><a href="api-functions/Payment.php">Place a new order</a></em></td>
        <?php if ($status == 'PENDING') { ?>
        <td style="width:50px; text-align:center;">|</td>
        <td><em><a href="api-functions/Capture.php?uxTransactionId=<?php echo $transactionId?>&uxAmount=<?php echo $amount?>&uxVat=<?php echo $vat?>&uxVatFormat=<?php echo $vatFormat?>">Capture transaction</a></em></td>
        <td style="width:50px; text-align:center;">|</td>
        <td><em><a href="api-functions/Cancel.php?uxTransactionId=<?php echo $transactionId?>">Cancel transaction</a></em></td>
        <?php }?>
        <?php if ($status == 'COMPLETED') { ?>
        <td style="width:50px; text-align:center;">|</td>
        <td><em><a href="api-functions/Refund.php?uxTransactionId=<?php echo $transactionId?>">Refund transaction</a></em></td>
        <?php }?>
      </tr>
    </table>

    <p><strong>LOG:</strong></p>
    <textarea style="resize:none;" rows="20" cols="80" readonly><?php print $logOutput;?></textarea>
  </body>
</html>
