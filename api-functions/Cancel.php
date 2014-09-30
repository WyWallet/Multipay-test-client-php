<?php
require_once '../include/Helpers.php';
require_once '../include/Config.php';
require_once '../backend/Client.php';

$logOutput = 'Log will be displayed here.';

if (isset($_GET["uxBtnCancel"]) && $_GET["uxBtnCancel"] != "") {
  $transactionId = $_GET["uxTransactionId"];
  $transmissionTime = $_GET["uxTransmissionTime"] = isset($_GET["uxTransmissionTime"]) && $_GET["uxTransmissionTime"] != '' ? $_GET["uxTransmissionTime"] : gmdate("Y-m-d H:i:s");

  $client = new Client();
  $responseObject = $client->cancel($transactionId, $transmissionTime, $environment, $secretKey);

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
                <td style="width:100px">TransactionId</td>
                <td>
                  <input type="text" size="38" name="uxTransactionId" value="<?php print isset($_GET["uxTransactionId"]) ? $_GET["uxTransactionId"] : '';?>">
                </td>
              </tr>
              <tr>
                <td style="width:150px">TransmissionTime</td>
                <td>
                  <input size="38" type="text" name="uxTransmissionTime" value="<?php print isset($_GET["uxTransmissionTime"]) ? $_GET["uxTransmissionTime"] : '';?>">
                </td>
              </tr>
            </table>
          </td>
          <td>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="left">
            <a href="<?php echo basename($_SERVER['SCRIPT_NAME']);?>">Reset</a>
          </td>
          <td colspan="2" align="right">
            <input type="submit" name="uxBtnCancel" value="Cancel Transaction">
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
