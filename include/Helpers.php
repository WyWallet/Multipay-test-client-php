<?php

  if( !ini_get('date.timezone') ) {
      date_default_timezone_set('CET');
  }

  function generateHMAC($generatedString) {
  	return substr(base64_encode(sha1(utf8_encode($generatedString),true)),0,14);
  }

  function generateguid($intLength) {
    return substr(strtoupper(md5(uniqid(rand(),true))),0,$intLength);
  }

  function loadSelector($action) {
    $actionList = Array(
      'Payment.php' => 'PAYMENT',
      'Capture.php' => 'CAPTURE',
      'Cancel.php' => 'CANCEL',
      'Refund.php' => 'REFUND',      
      'GetPayment.php' => 'GET PAYMENT',
      'GetTransaction.php' => 'GET TRANSACTION',
      'GetCapture.php' => 'GET CAPTURE',
      'GetCancel.php' => 'GET CANCEL',
      'GetRefund.php' => 'GET REFUND',
      'Ping.php' => 'PING'
    );
    
    $actionSelectorHTML = '<select onchange="location = this.options[this.selectedIndex].value;">';
    $actionOptionsHTML = '';
    
    foreach ($actionList as $key => $value) {
      $actionOptionsHTML .= '<option ' . ($key == $action ? 'SELECTED' : '') . ' value="' . $key . '">' . $value . '</option>';
    }
    
    $actionList[$action] = 'SELECTED';
  
    $actionSelectorHTML = <<< EOD
      <table>
        <tr>
          <td style="width:100px">Function</td>
          <td>
            <select onchange="location = this.options[this.selectedIndex].value;">
              {$actionOptionsHTML}
            </select>
          </td>
        </tr>
      </table>
EOD;
    
    return $actionSelectorHTML;
  }

?>