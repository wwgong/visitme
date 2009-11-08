<?php 

require_once('includes/config.php');
require_once('includes/smarty/libs/Smarty.class.php');

$facebook = new Facebook($api_key, $secret);
$facebook->require_frame();
$user = $facebook->require_login();

$facebook->api_client->profile_setFBML(NULL, $user, 'profile', NULL, NULL, 'box_content');
$user_details = $facebook->api_client->users_getInfo($user, 'last_name, first_name');

$smarty = new Smarty();

$smarty->assign('name', $user_details[0]['first_name']);

$smarty->display('canvas.tpl');
?>