<?php
require_once '../include/Helpers.php';
require_once '../include/Config.php';
require_once '../backend/Client.php';

$logOutput = 'Log will be displayed here.';

if (isset($_GET["uxBtnCheck"]) && $_GET["uxBtnCheck"] != "") {
  $cancelId = $_GET["uxCancelId"];

  $client = new Client();
  $responseObject = $client->getTransaction($cancelId, $environment, $secretKey);

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
                  <input type="text" size="12" name="uxCancelId" value="<?php print isset($_GET["uxCancelId"]) ? $_GET["uxCancelId"] : '';?>">
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
            <input type="submit" name="uxBtnCheck" value="Check">
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