<?php

require_once('includes/config.php');
require_once('includes/smarty/libs/Smarty.class.php');

$facebook = new Facebook($api_key, $secret);

$uid1 = $_POST['fb_sig_profile_user']; 
$uid2 = $_POST['fb_sig_user'];

$smarty = new Smarty();
$smarty->assign('uid1',$uid1);
$smarty->assign('uid2',$uid2);

$smarty->assign('flight1_cost',100);
$smarty->assign('flight1_departure',100);
$smarty->assign('flight1_arrival',100);
$smarty->assign('flight1_airline',100);

$smarty->assign('flight2_cost',100);
$smarty->assign('flight2_departure',100);
$smarty->assign('flight2_arrival',100);
$smarty->assign('flight2_airline',100);

$smarty->display('apptab.tpl');
?>