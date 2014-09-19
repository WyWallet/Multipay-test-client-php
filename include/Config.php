<?php

# Connection settings
define("ENV_DEV", "DEV");
define("ENV_STAGE", "STAGE");
define("ENV_PROD", "PROD");
define("DEFAULT_ENV", "STAGE");
define("SECRET_KEY_DEV", "SECRETKEY");
define("SECRET_KEY_STAGE", "SECRETKEY");
define("SECRET_KEY_PROD", "SECRETKEY");
define("MERCHANT_ID_DEV","MERCHANTID");
define("MERCHANT_ID_STAGE","MERCHANTID");
define("MERCHANT_ID_PROD","MERCHANTID");
define("API_URI_DEV", "https://wywallet-dev.cybercomhosting.com/wywallet-cashier/api/");
define("API_URI_STAGE", "https://cashier-stage.wywallet.se/api/");
define("API_URI_PROD", "https://multipay.wywallet.se/api/");
define("SITE_URL", "https://test.wywallet.se/Multipay-test-client/");
define("DEFAULT_ACTION", "api-functions/Payment.php");

# General settings
define("PC_GUIDLENGTH",20);

# Setup environment for the project
$environment = DEFAULT_ENV;

switch ($environment) {
    case "DEV":
      $merchantId = MERCHANT_ID_DEV;
      $secretKey = SECRET_KEY_DEV;
      break;
    case "STAGE":
      $merchantId = MERCHANT_ID_STAGE;
      $secretKey = SECRET_KEY_STAGE;
      break;
    case "PROD":
      $merchantId = MERCHANT_ID_PROD;
      $secretKey = SECRET_KEY_PROD;
      break;
}
?>
