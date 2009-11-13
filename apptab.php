<?php

// Includes
require_once('includes/sqlfunctions.php');

// Get facebook stuff
$facebook = new Facebook($api_key, $secret);

$uid1 = $_POST['fb_sig_profile_user'];

$uid1Details = $facebook->api_client->users_getInfo($uid1, 'first_name, last_name, current_location');
$uid1Location = $uid1Details[0]['current_location'];

$savedUid1Location = $uid1Location; /* Save the uid1Location's value in $savedUid1Location variable for later use... */

$uid1Locale = $_POST['fb_sig_locale'];

$uid2 = $_POST['fb_sig_user'];

$sql	= 'SELECT code FROM airports WHERE city = "'.$uid1Location['city'].'"';
$result	= sql_result($sql);
$dest = sql_fetch_obj($result);

$dest_codes = get_airport_codes($uid1Location['city']);

$rssURL = 'http://www.kayak.com/h/rss/fare?dest=';
for ($i = 0; $i < sizeof($dest_codes) - 1; $i++)
{
	$rssURL = $rssURL.$dest_codes[$i].",";
}
$rssURL = $rssURL.$dest_codes[sizeof($dest_codes) - 1];
$rss	= fetch_rss($rssURL);

$code	= $rss->items[0]['kyk']['origincode'];
$dest_code = $rss->items[0]['kyk']['destcode'];

$rssURL2 = 'http://www.kayak.com/h/rss/fare?code='.$dest_code.'&dest='.$code;
$rss2	= fetch_rss($rssURL2);

$uid2Location = $rss2->items[0]['kyk']['destlocation'];
$uid1Location = $rss2->items[0]['kyk']['originlocation'];
// Smarty
$smarty = new Smarty();
$smarty->assign('uid1',$uid2); // In app tab, uid1 is profile owner (friend's); uid2 is profile visitor (you) which are in reversed order, compared to canvas
$smarty->assign('uid2',$uid1);

$smarty->assign('uid1Location',$uid1Location);
$smarty->assign('uid1AirportCode',$dest_code);
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

$smarty->assign('targetCity', $savedUid1Location['city']);
$smarty->assign('targetState', $savedUid1Location['state']);
$smarty->assign('targetCountry', $savedUid1Location['country']);

$smarty->display('apptab.tpl');

/////////////
if($debug){
    echo "code: (".$code.") dest_code: (".$dest_code.") rssURL: (".$rssURL.") flight1_buzz: (".$rss->items[0]['guid'].")";
    echo " flight1_description: (".$rss->items[0]['description'].") rss2URL: (".$rss2URL.")<br /><br />";
}

if((sizeof($dest_codes) < 1) && ($savedUid1Location != NULL))
{
     $smarty->display('noDestAirportMsg.tpl');
}
else if($savedUid1Location == NULL)
{
    $smarty->display('noDestLocationMsg.tpl');
}
else if($code != $dest_code)
{
    $smarty->display('youToFriendMsg.tpl');
    $smarty->display('friendToYouMsg.tpl');
}
else if(sizeof($dest_codes) >= 1)
{
    $smarty->display('noFlightInfoMsg.tpl');
}
?>