<?php

require_once('includes/config.php');
require_once('includes/smarty/libs/Smarty.class.php');

$facebook = new Facebook($api_key, $secret);
//$facebook->require_frame();
//$user = $facebook->require_login();
$user = $_POST['fb_sig_profile_user']; 

//$blah = $facebook->api_client->profile_setFBML(NULL, $user, 'profile', NULL, NULL, 'profile_main');
$facebook->api_client->profile_setFBML(NULL, $user, 'profile', NULL, NULL, 'profile_main');
//echo "Hello, <fb:name/>";
echo $blah;
?>