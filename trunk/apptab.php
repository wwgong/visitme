<?php

// Includes
require_once('includes/sqlfunctions.php');

// Get facebook stuff
$facebook = new Facebook($api_key, $secret);

$uid1 = $_POST['fb_sig_profile_user'];

$uid1Details = $facebook->api_client->users_getInfo($uid1, 'first_name, last_name, current_location');
$uid1Location = $uid1Details[0]['current_location'];

$uid1Locale = $_POST['fb_sig_locale'];

$uid2 = $_POST['fb_sig_user'];

$sql	= 'SELECT code FROM airports WHERE city = "'.$uid1Location['city'].'"';
$result	= sql_result($sql);
$dest = sql_fetch_obj($result);

$rssURL = 'http://www.kayak.com/h/rss/fare?dest='.$dest->code;
$rss	= fetch_rss($rssURL);

$code	= $rss->items[0]['kyk']['origincode'];

$rssURL2 = 'http://www.kayak.com/h/rss/fare?code='.$dest->code.'&dest='.$code;
$rss2	= fetch_rss($rssURL2);

$uid2Location = $rss2->items[0]['kyk']['destlocation'];
$uid1Location = $rss2->items[0]['kyk']['originlocation'];
// Smarty
$smarty = new Smarty();
$smarty->assign('uid1',$uid1);
$smarty->assign('uid2',$uid2);

$smarty->assign('uid1Location',$uid1Location);
$smarty->assign('uid1AirportCode',$dest->code);
$smarty->assign('uid2Location',$uid2Location);
$smarty->assign('uid2AirportCode',$code);

$smarty->assign('flight1_cost',$rss->items[0]['kyk']['price']);
$smarty->assign('flight1_departure',100);
$smarty->assign('flight1_arrival',100);
$smarty->assign('flight1_airline',100);
$smarty->assign('flight1_description',$rss->items[0]['description']);
$smarty->assign('flight1_buzz',$rss->items[0]['guid']);

$smarty->assign('flight2_cost',$rss2->items[0]['kyk']['price']);
$smarty->assign('flight2_departure',100);
$smarty->assign('flight2_arrival',100);
$smarty->assign('flight2_airline',100);
$smarty->assign('flight2_description',$rss2->items[0]['description']);
$smarty->assign('flight2_buzz',$rss2->items[0]['guid']);

$smarty->display('apptab.tpl');
?>