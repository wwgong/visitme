<?php 
/*******************************************************************************************
 *    Project's Name: VisitME
 *            School: University of Massachusetts Boston
 *        Department: Computer Science
 * Project's website: http://code.google.com/p/visitme/
 *             Class: CS682 - Software Development Laboratory I
 * -----------------------------------------------------------------------------------------
 * Status    Date        Authors       Comments
 * -----------------------------------------------------------------------------------------
 * Created   11/06/2009  Brian Moy       Successfully run on Facebook
 * Modified  11/08/2009  Brian Moy       Able to extract users' info, query Kayak RSS Feeds,
 *                                       and print out info on canvas
 * Modified  11/09/2009  Henrik Volckmer Merged code with application tab code format, RSS
 *                       Vasudev Gadge   handling, and database queries.
 *
 * *****************************************************************************************/
 
require_once('includes/sqlfunctions.php');

$facebook = new Facebook($api_key, $secret);
$facebook->require_frame();
$user = $facebook->require_login();

// Create Smarty object
$smarty = new Smarty();

// Logic
$userDetails = $facebook->api_client->users_getInfo($user, 'last_name, first_name, current_location');
$userCurrentLocation = $userDetails[0]['current_location'];

$facebook->api_client->profile_setFBML(NULL, $user, 'profile', NULL, NULL, 'deprecated');

$targetedFriendId = $_POST['friend_sel'];
if ($targetedFriendId != "")
{
	$targetUserInfo		= $facebook->api_client->users_getInfo($targetedFriendId, 'last_name, first_name, current_location');
	$targetFirstName	= $targetUserInfo[0]['first_name'];
	$targetLastName		= $targetUserInfo[0]['last_name'];
	$targetCurrentLocation	= $targetUserInfo[0]['current_location'];

	// Debug output
	if ($debug)
	{
		echo $targetedFriendId." Name: (".$targetFirstName.") City: (".$targetCurrentLocation['city'].") State: (".$targetCurrentLocation['state'].") Country: (".$targetCurrentLocation['country'].")\n";
	}
	
	if ($userCurrentLocation['city'] != "" && $targetCurrentLocation['city'] != "")
	{
		// Get origin code
		$sql	= 'SELECT code FROM airports WHERE city = "'.$userCurrentLocation['city'].'"';
		$result	= sql_result($sql);
		$origin = sql_fetch_obj($result);

		// Get destination code
		$sql	= 'SELECT code FROM airports WHERE city = "'.$targetCurrentLocation['city'].'"';
		$result	= sql_result($sql);
		$dest = sql_fetch_obj($result);
		
		$dest_codes = get_airport_codes($targetCurrentLocation['city']);
		
		$rssURL = 'http://www.kayak.com/h/rss/fare?dest=';
		for ($i = 0; $i < sizeof($dest_codes) - 1; $i++)
		{
			$rssURL = $rssURL.$dest_codes[$i].",";
		}
		$rssURL = $rssURL.$dest_codes[sizeof($dest_codes) - 1];
		
		echo $rssURL;
		$rss	= fetch_rss($rssURL);
		
		$origin_code	= $rss->items[0]['kyk']['origincode'];
		$dest_code	= $rss->items[0]['kyk']['destcode'];
		
		$rssURL2 = 'http://www.kayak.com/h/rss/fare?code='.$dest_code.'&dest='.$origin_code;
		$rss2	= fetch_rss($rssURL2);

		$smarty->assign('uid1',$user);
		$smarty->assign('uid2',$targetedFriendId);

		$smarty->assign('uid1Location',$rss2->items[0]['kyk']['originlocation']);
		$smarty->assign('uid1AirportCode',$rss2->items[0]['kyk']['origincode']);
		$smarty->assign('uid2Location',$rss->items[0]['kyk']['originlocation']);
		$smarty->assign('uid2AirportCode',$rss->items[0]['kyk']['origincode']);

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

		$smarty->assign('sameCity', $targetCurrentLocation['city']);
		$smarty->assign('sameState', $targetCurrentLocation['state']);
		$smarty->assign('sameCountry', $targetCurrentLocation['country']);
	}	
}

//$originCodes = get_airport_codes($userCurrentLocation['city']);

// Smarty

$smarty->assign('name', $user_details[0]['first_name']);
$smarty->assign('originCodes', $originCodes);
$smarty->display('canvas.tpl');

if ($targetedFriendId != "")
{
    if($origin_code != $dest_code)
    {
        $smarty->display('youToFriendMsg.tpl');
        $smarty->display('friendToYouMsg.tpl');
    }
    else
    {   
        $smarty->display('sameLocationMsg.tpl');
    }
}
?>