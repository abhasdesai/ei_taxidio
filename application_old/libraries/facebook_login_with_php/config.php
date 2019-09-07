<?php

include_once("inc/facebook.php"); //include facebook SDK

######### Facebook API Configuration ##########
$appId = '1148040295308859'; //Facebook App ID
$appSecret = '983a7b5250a93430cc5e4f0347f8c486'; // Facebook App Secret
$homeurl = 'http://beta.taxidio.com/fblogin';  //return to home
$fbPermissions = 'email';  //Required facebook permissions

//Call Facebook API
$facebook = new Facebook(array(
  'appId'  => $appId,
  'secret' => $appSecret

));
$fbuser = $facebook->getUser();

?>
