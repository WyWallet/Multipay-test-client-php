<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__FILE__) . '/../include/Helpers.php';
require_once dirname(__FILE__) . '/../include/Config.php';

require('HTTP/httpful.phar');

  class Client {

    private $secretKey;
    private $endpoint;

    function createPayment(
      $merchantId,
      $transmissionTime,
      $amount,
      $vat,
      $vatFormat,
      $currency,
      $description,
      $merchantOrderId,
      $isImmediate,
      $environment,
      $secretKey,
      $msisdn = '',
      $useDeliveryAddress = '',
      $returnUrl = '',
      $cancelUrl = '',
      $postbackUrl = ''
    ) {

      /* Set parameters */
      $payment = array(
        'merchantId' => $merchantId,
        'transmissionTime' => $transmissionTime,
        'transaction' => array(
          'merchantOrderId' => $merchantOrderId,
          'isImmediate' => $isImmediate,
          'amount' => $amount,
          'vat' => $vat,
          'vatFormat' => $vatFormat,
          'currency' => $currency,
          'description' => $description,
          'secretKey' => $secretKey
        )
      );

      /* Validate input */
      $parameters = array (
        'merchantId', 'transmissionTime', 'merchantOrderId', 'isImmediate', 'amount', 'vat', 'vatFormat', 'currency', 'description', 'secretKey'
      );

      //$errorList = $this->validateArray($payment, $parameters);
      $errorList = null;

      if ($errorList  == null) {
        /* Set Environment Variables */
        $this->setEnvironment($environment);

        /* Set URI */
        $uri =  $this->endpoint . 'payments/';
        #echo $uri;

        $UTFdescription = utf8_decode($description);
        /* Generate HMAC */
        $concatenatedParameters = $merchantId . $transmissionTime . $merchantOrderId . $isImmediate . $amount . $vat . $vatFormat . $currency . $UTFdescription . $secretKey;
        $hmac = generateHMAC($concatenatedParameters);

        unset($payment['transaction']['secretKey']);

        /* Add optional parameters to payload */
        if (isset($msisdn) && $msisdn != '') {
          $payment['msisdn'] = $msisdn;
        }
        if (isset($useDeliveryAddress) && $useDeliveryAddress != '') {
          $payment['useDeliveryAddress'] = $useDeliveryAddress;
        }

        if (isset($returnUrl) && $returnUrl != '') {
          $payment['returnUrl'] = $returnUrl;
        }

        if (isset($cancelUrl) && $cancelUrl != '') {
          $payment['cancelUrl'] = $cancelUrl;
        }

        if (isset($postbackUrl) && $postbackUrl != '') {
          $payment['postbackUrl'] = $postbackUrl;
        }

        #echo "<pre>";
        #echo json_encode($payment);
        #echo "</pre>";

        /* Add HMAC to header and do request */
        $response = \Httpful\Request::post($uri)
            ->addHeader('hmac', $hmac)        // add hmac to header
            ->sendsJson()                     // Content-Type: JSON
            ->body(json_encode($payment))     // attach payload
            ->send();                         // send request
      } else {
        $response = $errorList;
      }
      #echo $response;
      $responseLog = print_r($response, TRUE);

      #echo 'Input parameters:' . "\r\n" . json_encode($payment) . "\r\n\n" . 'Response:' . "\r\n" . strstr($responseLog, '(');
      return $response;
    }

    function getPayment($paymentId, $environment, $secretKey = '') {

      if (isset($paymentId) && $paymentId != '' && isset($environment) && $environment != '') {
        /* Set Environment Variables */
        $this->setEnvironment($environment);

        /* Set URI */
        $uri =  $this->endpoint . 'payments/' . $paymentId;

        /* Generate HMAC */
        $concatenatedParameters = $paymentId . ($secretKey == '' ? $this->secretKey : $secretKey);
        //echo 'HMAC string: ' . $concatenatedParameters;
        $hmac = generateHMAC($concatenatedParameters);
        //echo 'HMAC: ' . $hmac;

        /* Add HMAC to header and do request */
        $response = \Httpful\Request::get($uri)
            ->addHeader('hmac', $hmac)        // add hmac to header
            ->send();                         // send request
      } else {
        $response = Array('Parameter: paymentId or environment is missing or empty.');
      }

      $responseLog = print_r($response, TRUE);

      //return 'Input parameters:' . "\r\n" . json_encode($paymentId) . "\r\n\n" . 'Response:' . "\r\n" . strstr($responseLog, '(');
      return $response;
    }

    function getTransaction($transactionId, $environment, $secretKey = '') {

      if (isset($transactionId) && $transactionId != '' && isset($environment) && $environment != '') {
        /* Set Environment Variables */
        $this->setEnvironment($environment);

        /* Set URI */
        $uri =  $this->endpoint . 'transactions/' . $transactionId;

        /* Generate HMAC */
        $concatenatedParameters = $transactionId . ($secretKey == '' ? $this->secretKey : $secretKey);
        //echo 'HMAC string: ' . $concatenatedParameters;
        $hmac = generateHMAC($concatenatedParameters);
        //echo 'HMAC: ' . $hmac;

        /* Add HMAC to header and do request */
        $response = \Httpful\Request::get($uri)
            ->addHeader('hmac', $hmac)        // add hmac to header
            ->send();                         // send request
      } else {
        $response = Array('Parameter: TransactionId and/or Environment is missing or empty.');
      }

      $responseLog = print_r($response, TRUE);

      //return 'Input parameters:' . "\r\n" . json_encode($transactionId) . "\r\n\n" . 'Response:' . "\r\n" . strstr($responseLog, '(');
      return $response;
    }

   function capture($transactionId, $amount, $vat, $vatFormat, $isLastCapture, $transmissionTime, $environment, $secretKey = '') {
      /* Set parameters */
      $capture = array(
        'transactionId' => $transactionId,
        'amount' => $amount,
        'vat' => $vat,
        'vatFormat' => $vatFormat,
        'isLastCapture' => $isLastCapture,
        'transmissionTime' => $transmissionTime
      );

      /* Validate input */
      $parameters = array (
        'transactionId', 'amount', 'vat', 'vatFormat', 'isLastCapture', 'transmissionTime'
      );

      $errorList = $this->validateArray($capture, $parameters);

      if ($errorList  == null) {
        /* Set Environment Variables */
        $this->setEnvironment($environment);

        /* Set URI */
        $uri =  $this->endpoint . 'transactions/capture/';

        /* Generate HMAC */
        $concatenatedParameters = $transactionId . $amount . $vat . $vatFormat . $isLastCapture . $transmissionTime . ($secretKey == '' ? $this->secretKey : $secretKey);
        //echo 'HMAC string: ' . $concatenatedParameters;
        $hmac = generateHMAC($concatenatedParameters);
        //echo 'HMAC: ' . $hmac;

        /* Add HMAC to header and do request */
        $response = \Httpful\Request::post($uri)
            ->addHeader('hmac', $hmac)        // add hmac to header
            ->sendsJson()                     // Content-Type: JSON
            ->body(json_encode($capture))     // attach payload
            ->send();                         // send request
      } else {
        $response = $errorList;
      }

      $responseLog = print_r($response, TRUE);

      //return 'Input parameters:' . "\r\n" . json_encode($capture) . "\r\n\n" . 'Response:' . "\r\n" . strstr($responseLog, '(');
      return $response;
    }

    function getCapture($captureId, $environment, $secretKey = '') {

      if (isset($captureId) && $captureId != '' && isset($environment) && $environment != '') {
        /* Set Environment Variables */
        $this->setEnvironment($environment);

        /* Set URI */
        $uri =  $this->endpoint . 'captures/' . $captureId;

        /* Generate HMAC */
        $concatenatedParameters = $captureId . ($secretKey == '' ? $this->secretKey : $secretKey);
        //echo 'HMAC string: ' . $concatenatedParameters;
        $hmac = generateHMAC($concatenatedParameters);
        //echo 'HMAC: ' . $hmac;

        /* Add HMAC to header and do request */
        $response = \Httpful\Request::get($uri)
            ->addHeader('hmac', $hmac)        // add hmac to header
            ->send();                         // send request
      } else {
        $response = Array('Parameter: CaptureId and/or Environment is missing or empty.');
      }

      $responseLog = print_r($response, TRUE);

      //return 'Input parameters:' . "\r\n" . json_encode($captureId) . "\r\n\n" . 'Response:' . "\r\n" . strstr($responseLog, '(');
      return $response;
    }

    function cancel($transactionId, $transmissionTime, $environment, $secretKey = '') {

      /* Set parameters */
      $cancel = array(
        'transactionId' => $transactionId,
        'transmissionTime' => $transmissionTime,
        'environment' => $environment,
        'secretKey' => $secretKey
      );

      /* Validate input */
      $parameters = array (
        'transactionId', 'transmissionTime', 'environment', 'secretKey'
      );

      $errorList = $this->validateArray($cancel, $parameters);
      //$errorList = null;

      if ($errorList  == null) {
        /* Set Environment Variables */
        $this->setEnvironment($environment);

        /* Set URI */
        //$uri =  $this->endpoint . 'transactions/' . $transactionId . '/cancel/';
        $uri =  $this->endpoint . 'transactions/cancel/';

        /* Generate HMAC */
        $concatenatedParameters = $transactionId . $transmissionTime . ($secretKey == '' ? $this->secretKey : $secretKey);
        //echo 'HMAC string: ' . $concatenatedParameters;
        $hmac = generateHMAC($concatenatedParameters);
        //echo 'HMAC: ' . $hmac;
        unset($cancel['secretKey']);
        unset($cancel['environment']);

        /* Add HMAC to header and do request */
        $response = \Httpful\Request::post($uri)
            ->addHeader('hmac', $hmac)        // add hmac to header
            ->sendsJson()                     // Content-Type: JSON
            ->body(json_encode($cancel))     // attach payload
            ->send();                         // send request
      } else {
        $response = $errorList;
      }

      $responseLog = print_r($response, TRUE);

      //return 'Input parameters:' . "\r\n" . json_encode($cancel) . "\r\n\n" . 'Response:' . "\r\n" . strstr($responseLog, '(');
      return $response;
    }

    function getCancel($cancelId, $environment, $secretKey = '') {

      if (isset($cancelId) && $cancelId != '' && isset($environment) && $environment != '') {
        /* Set Environment Variables */
        $this->setEnvironment($environment);

        /* Set URI */
        $uri =  $this->endpoint . 'cancels/' . $captureId;

        /* Generate HMAC */
        $concatenatedParameters = $cancelId . ($secretKey == '' ? $this->secretKey : $secretKey);
        //echo 'HMAC string: ' . $concatenatedParameters;
        $hmac = generateHMAC($concatenatedParameters);
        //echo 'HMAC: ' . $hmac;

        /* Add HMAC to header and do request */
        $response = \Httpful\Request::get($uri)
            ->addHeader('hmac', $hmac)        // add hmac to header
            ->send();                         // send request
      } else {
        $response = Array('Parameter: CancelId and/or Environment is missing or empty.');
      }

      $responseLog = print_r($response, TRUE);

      //return 'Input parameters:' . "\r\n" . json_encode($cancelId) . "\r\n\n" . 'Response:' . "\r\n" . strstr($responseLog, '(');
      return $response;
    }

   function refund($transactionId, $description, $amount, $vat, $vatFormat, $transmissionTime, $environment, $secretKey = '') {

      /* Set parameters */
      $refund = array(
        'description' => $description,
        'transactionId' => $transactionId,
        'amount' => $amount,
        'vat' => $vat,
        'vatFormat' => $vatFormat,
        'transmissionTime' => $transmissionTime
      );

      /* Validate input */
      $parameters = array (
        'description', 'transactionId', 'amount', 'vat', 'vatFormat', 'transmissionTime'
      );

      $errorList = $this->validateArray($refund, $parameters);

      if ($errorList  == null) {
        /* Set Environment Variables */
        $this->setEnvironment($environment);

        /* Set URI */
        $uri =  $this->endpoint . 'transactions/refund/';

        /* Generate HMAC */
        $concatenatedParameters = $description . $transactionId . $amount . $vat . $vatFormat . $transmissionTime . ($secretKey == '' ? $this->secretKey : $secretKey);
        //echo 'HMAC string: ' . $concatenatedParameters;
        $hmac = generateHMAC($concatenatedParameters);
        //echo 'HMAC: ' . $hmac;

        /* Add HMAC to header and do request */
        $response = \Httpful\Request::post($uri)
            ->addHeader('hmac', $hmac)        // add hmac to header
            ->sendsJson()                     // Content-Type: JSON
            ->body(json_encode($refund))     // attach payload
            ->send();                         // send request
      } else {
        $response = $errorList;
      }

      $responseLog = print_r($response, TRUE);

      //return 'Input parameters:' . "\r\n" . json_encode($refund) . "\r\n\n" . 'Response:' . "\r\n" . strstr($responseLog, '(');
      return $response;
    }

    function getRefund($refundId, $environment, $secretKey = '') {

      if (isset($refundId) && $refundId != '' && isset($environment) && $environment != '') {
        /* Set Environment Variables */
        $this->setEnvironment($environment);

        /* Set URI */
        $uri =  $this->endpoint . 'refunds/' . $refundId;

        /* Generate HMAC */
        $concatenatedParameters = $refundId . ($secretKey == '' ? $this->secretKey : $secretKey);
        //echo 'HMAC string: ' . $concatenatedParameters;
        $hmac = generateHMAC($concatenatedParameters);
        //echo 'HMAC: ' . $hmac;

        /* Add HMAC to header and do request */
        $response = \Httpful\Request::get($uri)
            ->addHeader('hmac', $hmac)        // add hmac to header
            ->send();                         // send request
      } else {
        $response = Array('Parameter: RefundId and/or Environment is missing or empty.');
      }

      $responseLog = print_r($response, TRUE);

      //return 'Input parameters:' . "\r\n" . json_encode($refundId) . "\r\n\n" . 'Response:' . "\r\n" . strstr($responseLog, '(');
      return $response;
    }

    function ping($environment) {
      if (isset($environment) && $environment != '') {
        /* Set Environment Variables */
        $this->setEnvironment($environment);

        /* Set URI */
        $uri =  $this->endpoint . 'ping/';


        /* Do request */
        $response = \Httpful\Request::get($uri)
            ->send();                         // send request
      } else {
        $response = Array('Parameter: Environment is missing or empty.');
      }

      //return 'Response:' . "\r\n" . strstr(print_r($response, TRUE), '(');
      return $response;
    }

    private function validateArray($array, $template) {
      $error = null;

      if (!isset($array)) {
        $error[] = 'Please provide a data array';
      }

      $array = $this->array_flatten($array);

      foreach ($template as $key) {
        if (!isset($array[$key]) || $array[$key] == '') {
          $error[] = 'Parameter: ' . $key . ' is missing or empty.';
        }
      }

      return $error;
    }

    private function array_flatten($array, $preserve_keys = 1, &$newArray = Array()) {
      foreach ($array as $key => $child) {
        if (is_array($child)) {
          $newArray =& $this->array_flatten($child, $preserve_keys, $newArray);
        } elseif ($preserve_keys + is_string($key) > 1) {
          $newArray[$key] = $child;
        } else {
          $newArray[] = $child;
        }
      }
      return $newArray;
    }

    private function setEnvironment($environment) {
      if($environment == ENV_DEV) {
        $this->secretKey = SECRET_KEY_DEV;
        $this->endpoint = API_URI_DEV;
      }
      else if($environment == ENV_STAGE) {
        $this->secretKey = SECRET_KEY_STAGE;
        $this->endpoint = API_URI_STAGE;
      }
      else if($environment == ENV_PROD) {
        $this->secretKey = SECRET_KEY_PROD;
        $this->endpoint = API_URI_PROD;
      }
    }
  }
?>
