<?php
session_start();

require_once '../include/Helpers.php';
require_once '../include/Config.php';
require_once '../backend/Client.php';

$logOutput = 'Log will be displayed here.';

if (isset($_GET["uxBtnPay"]) && $_GET["uxBtnPay"] != "") {
  $transmissionTime = $_GET["uxTransmissionTime"] = isset($_GET["uxTransmissionTime"]) && $_GET["uxTransmissionTime"] != '' ? $_GET["uxTransmissionTime"] : gmdate("Y-m-d H:i:s");
  $merchantOrderId = $_GET["uxMerchantOrderId"];
  $amount = $_GET["uxAmount"];
  $vat = $_GET["uxVat"];
  $vatFormat = $_GET["uxVatFormat"];
  $currency = $_GET["uxCurrency"];
  $msisdn = $_GET["uxMSISDN"] = isset($_GET["uxMSISDN"]) && $_GET["uxMSISDN"] != '' ? $_GET["uxMSISDN"] : '';
  $description = $_GET["uxDescription"];
  $isImmediate = $_GET["uxImmediate"];
  $useDeliveryAddress = $_GET["uxUseDeliveryAddress"] = isset($_GET["uxUseDeliveryAddress"]) && $_GET["uxUseDeliveryAddress"] != '' ? $_GET["uxUseDeliveryAddress"] : '';
  $returnUrl = $_GET["uxReturnUrl"];
  $cancelUrl = $_GET["uxCancelUrl"];
  $postbackUrl = $_GET["uxpostbackUrl"];


  $client = new Client();
  $responseObject = $client->createPayment($merchantId, $transmissionTime, $amount, $vat, $vatFormat, $currency, $description, $merchantOrderId, $isImmediate, $environment, $secretKey, $msisdn, $useDeliveryAddress, $returnUrl, $cancelUrl, $postbackUrl);

  if (is_object($responseObject) && !empty($responseObject->body->transaction) && $responseObject->body->transaction->merchantOrderId == $merchantOrderId) {

    $height = 375;//$_GET["iframewidth"] >= 485 ? 252 : 340;
    $width = $_GET["iframewidth"] >= 300 ? $_GET["iframewidth"] : 300;
    $standAloneLogoHeightMargin = 110;

    if ($_GET["uxMode"] == "Window") {
      $link = '';
      foreach ($responseObject->body->links as $value) {
        if ($value->rel == 'cashier-standalone') {
          $link = $value->href;
          break;
        }
      }
      $standAloneSnippet = 'window.open("' . $link. '", "WyWallet-Kassan", "width=' . $width . ', height=' . ($height + $standAloneLogoHeightMargin) . '");';
      $standAloneLink = $link;
    } else if ($_GET["uxMode"] == "Iframe") {
      $link = '';
      foreach ($responseObject->body->links as $value) {
        if ($value->rel == 'cashier-iframe') {
          $link = $value->href;
          break;
        }
      }
      $iframeSnippet = '<iframe src="' . $link .
      '" id="ww-kassan-iframe" name="ww-kassan-iframe" class="loaded" frameborder="0" scrolling="no"
      style="width: ' . $width . 'px; min-height:' . $height . 'px;
      -webkit-transition: min-height 0.15s; transition: min-height 0.15s;"></iframe>';
    }
    $logOutput = print_r($responseObject->body, true);
  } else {
    $logOutput = print_r($responseObject, true);
  }
}

?>
<!DOCTYPE html>
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>WyWallet-kassan Test Client</title>
    <meta charset="utf-8" />
    <link href="style.css" rel="stylesheet" />
  </head>
  <body style="width:770px">
    <br>
    <img alt="Wy-kassan Test Tool" src="../img/WyWallet_Logo_RGB_s.png">​​
    <br><br>

    <?php echo loadSelector(basename($_SERVER['SCRIPT_NAME'])); ?>

    <form method="get" action="Payment.php">
      <table>
        <tr>
          <td style="width:305px">
            <table>
              <tr>
                <td style="width:150px">Amount</td>
                <td>
                  <input size="12" type="text" name="uxAmount" value="<?php print isset($_GET["uxAmount"]) ? $_GET["uxAmount"] : 1;?>">
                </td>
              </tr>
              <tr>
                <td style="width:150px">Vat</td>
                <td>
                  <input size="12" type="text" name="uxVat" value="<?php print isset($_GET["uxVat"]) ? $_GET["uxVat"] : 0;?>">
                </td>
              </tr>
              <tr>
                <td style="width:150px">Vat Format</td>
                <td>
                  <select name="uxVatFormat" id="vatFormat">
                    <option <?php print isset($_GET["uxVatFormat"]) && $_GET["uxVatFormat"] == 'AMOUNT' ? 'SELECTED' : '';?> value="AMOUNT">AMOUNT</option>
                    <option <?php print isset($_GET["uxVatFormat"]) && $_GET["uxVatFormat"] == 'PERCENT' ? 'SELECTED' : '';?> value="PERCENT">PERCENT</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td style="width:150px">Currency</td>
                <td>
                  <input size="12" type="text" name="uxCurrency" value="<?php print isset($_GET["uxCurrency"]) ? $_GET["uxCurrency"] : 'SEK';?>">
                </td>
              </tr>
              <tr>
                <td style="width:150px">isImmediate</td>
                <td>
                  <input <?php print isset($_GET["uxImmediate"]) ? $_GET["uxImmediate"] == 'true' ? 'checked' : '' : 'checked';?> type="radio" size="4" name="uxImmediate" value="true">
                  true&nbsp;&nbsp;&nbsp;
                  <input <?php print isset($_GET["uxImmediate"]) && $_GET["uxImmediate"] == 'false' ? 'checked' : '';?> type="radio" size="4" name="uxImmediate" value="false">
                  false
                </td>
              </tr>
              <tr>
                <td style="width:150px">UseDeliveryAddress (optional)</td>
                <td>
                  <input <?php print isset($_GET["uxUseDeliveryAddress"]) && $_GET["uxUseDeliveryAddress"] == 'true' ? 'checked' : '';?> type="radio" size="4" name="uxUseDeliveryAddress" value="true">
                  true&nbsp;&nbsp;&nbsp;
                  <input <?php print isset($_GET["uxUseDeliveryAddress"]) && $_GET["uxUseDeliveryAddress"] == 'false' ? 'checked' : '';?> type="radio" size="4" name="uxUseDeliveryAddress" value="false">
                  false
                </td>
              </tr>
              <tr>
                <td style="width:150px">MSISDN (optional)</td>
                <td>
                  <input size="12" type="text" name="uxMSISDN" value="<?php print isset($_GET["uxMSISDN"]) ? $_GET["uxMSISDN"] : '';?>">
                </td>
              </tr>
              <tr>
                <td>
                  <a href="<?php echo basename($_SERVER['SCRIPT_NAME']);?>">Reset</a>
                </td>
                <td></td>
              </tr>
            </table>
          </td>
          <td>
            <table>
              <tr>
                <td style="width:150px">Description</td>
                <td>
                  <input size="38" type="text" name="uxDescription" value="<?php print isset($_GET["uxDescription"]) ? $_GET["uxDescription"] : 'WW_TEST_' . date("Y-m-d_H:i:s");?>">
                </td>
              </tr>
              <tr>
                <td style="width:150px">OrderId</td>
                <td>
                  <input size="38" type="text" name="uxMerchantOrderId" value="<?php print isset($_GET["uxMerchantOrderId"]) ? $_GET["uxMerchantOrderId"] : generateguid(20);?>">
                </td>
              </tr>
              <tr>
                <td style="width:150px">TransmissionTime</td>
                <td>
                  <input size="38" type="text" name="uxTransmissionTime" value="<?php print isset($_GET["uxTransmissionTime"]) ? $_GET["uxTransmissionTime"] : '';?>">
                </td>
              </tr>
              <tr>
                <td style="width:150px">Cancel URL (optional)</td>
                <td>
                  <input size="38" type="text" name="uxCancelUrl" value="<?php print isset($_GET["uxCancelUrl"]) ? $_GET["uxCancelUrl"] : '';?>">
                </td>
              </tr>
              <tr>
                <td style="width:150px">Return URL (optional)</td>
                <td>
                  <input size="38" type="text" name="uxReturnUrl" value="<?php print isset($_GET["uxReturnUrl"]) ? $_GET["uxReturnUrl"] : '';?>">
                </td>
              </tr>
              <tr>
                <td style="width:150px">PostBack URL (optional)</td>
                <td>
                  <input size="38" type="text" name="uxpostbackUrl" value="<?php print isset($_GET["uxpostbackUrl"]) ? $_GET["uxpostbackUrl"] : '';?>">
                </td>
              </tr>

              <tr>
                <td style="width:150px">Mode</td>
                <td>
                  <input <?php print isset($_GET["uxMode"]) ? $_GET["uxMode"] == 'Window' ? 'checked' : '' : 'checked';?> type="radio" name="uxMode" value="Window">Window&nbsp;&nbsp;&nbsp;
                  <input <?php print isset($_GET["uxMode"]) && $_GET["uxMode"] == 'Iframe' ? 'checked' : '';?>  type="radio" name="uxMode" value="Iframe">Iframe&nbsp;&nbsp;&nbsp;&nbsp;Width&nbsp;
                  <input size="4" type="text" name="iframewidth" value="<?php print isset($_GET["iframewidth"]) ? $_GET["iframewidth"] : 450;?>">px
                </td>
              </tr>
              <tr>
                <td style="width:150px"></td>
                <td colspan="2" align="right">
                  <?php if (isset($standAloneLink)) {?>
                    <a href="<?php echo $standAloneLink;?>" target="_blank">Press here if window did not open</a>&nbsp;&nbsp;&nbsp;<?php } ?>
                  <input type="submit" name="uxBtnPay" value="Pay">
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </form>

    <?php print isset($iframeSnippet) ? $iframeSnippet : '';?><br><br>

    <textarea style="resize:none;" rows="20" cols="80" readonly><?php print $logOutput;?></textarea>

    <script type="text/javascript">
      <?php print isset($standAloneSnippet) ? $standAloneSnippet : '';?>
    </script>
  </body>
</html>
