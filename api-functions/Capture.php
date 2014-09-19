<?php
require_once '../include/Helpers.php';
require_once '../include/Config.php';
require_once '../backend/Client.php';

$logOutput = 'Log will be displayed here.';

if (!isset($_GET["uxIsLastCapture"])) {
  $isLastCapture = 'false';
}

if (isset($_GET["uxBtnCapture"]) && $_GET["uxBtnCapture"] != "") {
  $transactionId = $_GET["uxTransactionId"];
  $isLastCapture = isset($_GET["uxIsLastCapture"]) ? $_GET["uxIsLastCapture"] : $isLastCapture;
  $amount = $_GET["uxAmount"];
  $vat = $_GET["uxVat"];
  $vatFormat = $_GET["uxVatFormat"];
  $transmissionTime = $_GET["uxTransmissionTime"] = isset($_GET["uxTransmissionTime"]) && $_GET["uxTransmissionTime"] != '' ? $_GET["uxTransmissionTime"] : gmdate("Y-m-d H:i:s");

  $client = new Client();
  $responseObject = $client->capture($transactionId, $amount, $vat, $vatFormat, $isLastCapture, $transmissionTime, $environment, $secretKey);

  $logOutput = print_r($responseObject, true);
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
                <td style="width:100px">Amount</td>
                <td>
                  <input type="text" size="6" name="uxAmount" value="<?php print isset($_GET["uxAmount"]) ? $_GET["uxAmount"] : 1;?>">
                </td>
              </tr>
              <tr>
                <td style="width:100px">Vat</td>
                <td>
                  <input size="6" type="text" name="uxVat" value="<?php print isset($_GET["uxVat"]) ? $_GET["uxVat"] : 0;?>">
                </td>
              </tr>
              <tr>
                <td style="width:100px">Vat Format</td>
                <td>
                  <select name="uxVatFormat" id="vatFormat">                   
                    <option <?php print isset($_GET["uxVatFormat"]) && $_GET["uxVatFormat"] == 'AMOUNT' ? 'SELECTED' : '';?> value="AMOUNT">AMOUNT</option>
                    <option <?php print isset($_GET["uxVatFormat"]) && $_GET["uxVatFormat"] == 'PERCENT' ? 'SELECTED' : '';?> value="PERCENT">PERCENT</option>
                  </select>
                </td>
              </tr>
            </table>
          </td>
          <td>
            <table>
              <tr>
                <td style="width:100px">TransactionId</td>
                <td>
                  <input type="text" size="38" name="uxTransactionId" value="<?php print isset($_GET["uxTransactionId"]) ? $_GET["uxTransactionId"] : '';?>">
                </td>
              </tr>
              <tr>
                <td style="width:100px">TransmissionTime</td>
                <td>
                  <input type="text" size="38" name="uxTransmissionTime" value="<?php print isset($_GET["uxTransmissionTime"]) ? $_GET["uxTransmissionTime"] : '';?>">
                </td>
              </tr>
              <tr>
                <td style="width:100px">IsLastCapture</td>
                <td>
                  <input type="checkbox" <?php print $isLastCapture == 'true' ? 'checked' : '';?> name="uxIsLastCapture" value="true">
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
            <input type="submit" name="uxBtnCapture" value="Capture">
          </td>
        </tr>
      </table>
    </form>
    
    <textarea style="resize:none;" rows="20" cols="80" readonly><?php print $logOutput;?></textarea>
    
    <script type="text/javascript">
      <?php print isset($standAloneSnippet) ? $standAloneSnippet : '';?>
    </script>
  </body>
</html>