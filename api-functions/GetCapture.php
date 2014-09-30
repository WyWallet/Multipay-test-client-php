<?php
require_once '../include/Helpers.php';
require_once '../include/Config.php';
require_once '../backend/Client.php';

$logOutput = 'Log will be displayed here.';

if (isset($_GET["uxBtnCheck"]) && $_GET["uxBtnCheck"] != "") {
  $merchantId = $_GET["uxMerchantId"];
  $captureId = $_GET["uxCaptureId"];

  $client = new Client();
  $responseObject = $client->getCapture($captureId, $environment, $secretKey);

  $logOutput = print_r($responseObject, true);

/*
  if (is_object($responseObject) && !empty($responseObject->body->transaction) && $responseObject->body->transaction->merchantOrderId == $merchantOrderId) {
    if ($_GET["uxMode"] == "Window") {
      $standAloneSnippet = 'window.open("' . $responseObject->body->links[1]->href . '");';
    } else if ($_GET["uxMode"] == "Iframe") {
      $iframeSnippet = '<iframe src="' . $responseObject->body->links[1]->href .
      '" id="ww-kassan-iframe" name="ww-kassan-iframe" class="loaded" frameborder="0" scrolling="no" style="width: 100%; min-height:' . 
      $_GET["iframeheight"] . 'px; -webkit-transition: min-height 0.15s; transition: min-height 0.15s;"></iframe>';
    }

    //print '<br><a target="_blank" href="' . $responseObject->body->links[1]->href . '">Stand alone</a>';
    //print '<br><a target="_blank" href="iframe.php?iframeurl=' . $responseObject -> body -> links[1] -> href . '&width=' . $_GET["iframewidth"] . '&height=' . $_GET["iframeheight"] . '">Iframe</a>';
    
    $logOutput = print_r($responseObject->body, true);
  } else {
    $logOutput = print_r($responseObject, true);
    
    print "<br><br>ERROR!<br><br>";
    print "<pre>";
    print_r($responseObject);
    print "</pre>";
    */
}

?>
<!DOCTYPE html>
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>WyWallet-kassan Test Client</title>
    <meta charset="utf-8" />
    <link href="style.css" rel="stylesheet" />
  </head>
  <body style="width:840px">
    <br>
    <img alt="Wy-kassan Test Tool" src="../img/WyWallet_Logo_RGB_s.png">​​
    <br><br>
    
    <?php echo loadSelector(basename($_SERVER['SCRIPT_NAME'])); ?>
    
    <form method="get" action="<?php echo basename($_SERVER['SCRIPT_NAME']);?>">
      <table>
        <tr>
          <td style="width:235px">
            <table>
              <tr>
                <td style="width:100px">MerchantId</td>
                <td>
                  <input size="12" type="text" name="uxMerchantId" id="merchantId" value="<?php print isset($_GET["uxMerchantId"]) ? $_GET["uxMerchantId"] : $merchantId;?>">
                </td>
              </tr>
              <tr>
                <td style="width:100px">TransactionId</td>
                <td>
                  <input type="text" size="12" name="uxCaptureId" value="<?php print isset($_GET["uxCaptureId"]) ? $_GET["uxCaptureId"] : '';?>">
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="left">
            <a href="<?php echo basename($_SERVER['SCRIPT_NAME']);?>">Reset</a>
          </td>
          <td colspan="2" align="right">
            <input type="submit" name="uxBtnCheck" value="Check">
          </td>
        </tr>
      </table>
    </form>
    
    <textarea style="resize:none;" rows="20" cols="80" readonly><?php print $logOutput;?></textarea>
    
    <script type="text/javascript">
      function changeEnvironment(target) {
        if (target == "<?php echo ENV_DEV;?>") {
           document.getElementById("merchantId").value = "<?php echo $merchantId = MERCHANT_ID_DEV;?>";
           document.getElementById("secretKey").value = "<?php echo $secretKey = SECRET_KEY_DEV;?>";
        } else {
          document.getElementById("merchantId").value = "<?php echo $merchantId = MERCHANT_ID_STAGE;?>";
          document.getElementById("secretKey").value = "<?php echo $secretKey = SECRET_KEY_STAGE;?>";
        }
      }
      <?php print isset($standAloneSnippet) ? $standAloneSnippet : '';?>
    </script>
  </body>
</html>