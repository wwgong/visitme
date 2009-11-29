<?php
/***********************************************************************************************
 *    Project's Name: VisitME
 *            School: University of Massachusetts Boston
 *        Department: Computer Science
 * Project's website: http://code.google.com/p/visitme/
 *             Class: CS682 - Software Development Laboratory I
 * ---------------------------------------------------------------------------------------------
 * Status    Date        Authors         Comments
 * ---------------------------------------------------------------------------------------------
 * Created   11/06/2009  Brian Moy       Successfully run on Facebook.
 * Modified  11/08/2009  Brian Moy       Able to extract users' info, query Kayak RSS Feeds,
 *                                       and print out info on canvas.
 * Modified  11/09/2009  Henrik Volckmer Merged code with application tab code format, RSS
 *                       Vasudev Gadge   handling, and database queries.
 * Modified  11/20/2009  Brian Moy       Added tabs, friends-invitation function, and detect
 *                                       and prompt unset user's current location msg on canvas.
 * Modified  11/24/2009  Henrik Volckmer Single result template.
 * *********************************************************************************************/

require_once('includes/sqlfunctions.php');

$facebook = new Facebook($api_key, $secret);
$facebook->require_frame();
$user = $facebook->require_login();

// Create Smarty object
$smarty = new Smarty();
$smarty->assign('app_name',$app_name);
$smarty->assign('host_url',$host_url);
$smarty->assign('version', $version);
$smarty->assign('uid1',$user);

// Logic
$userDetails = $facebook->api_client->users_getInfo($user, 'last_name, first_name, current_location, hometown_location');
$userLocation = $userDetails[0]['current_location'];
if ($userLocation == NULL)
{
	$userLocation = $userDetails[0]['hometown_location'];
}
$facebook->api_client->profile_setFBML(NULL, $user, 'profile', NULL, NULL, 'deprecated');

$targetedFriendId = $_POST['friend_sel'];
if ($targetedFriendId != NULL)
{
	$targetUserInfo		= $facebook->api_client->users_getInfo($targetedFriendId, 'last_name, first_name, current_location, hometown_location, work_history');
	$targetFirstName	= $targetUserInfo[0]['first_name'];
	$targetLastName		= $targetUserInfo[0]['last_name'];
	
	// Try to get the target's location
	$targetLocation		= $targetUserInfo[0]['current_location'];
	if ($targetLocation == NULL)
	{
		$targetLocation = $targetUserInfo[0]['hometown_location'];
	}
	/*if ($targetLocation == NULL)
	{
		echo "Work history: ";
		print_r($targetUserInfo[0]['work_history'][0]);
		$targetLocation = $targetUserInfo[0]['work_history'][0]['location'];
	}*/
	
    $smarty->assign('uid2',$targetedFriendId);

	if ($targetLocation['city'] != NULL) 
	{
		// Get origin codes
		$orig_codes = array();
		$orig_info = false;
		if ($userLocation != NULL)
		{
			$orig_codes = get_airport_codes($userLocation['city'], $userLocation['state'], $userLocation['country']);
			$orig_info = true;
		}
		
		// Get destination codes
		$dest_codes = get_airport_codes($targetLocation['city'], $targetLocation['state'], $targetLocation['country']);

		// Create URL string
		$rssURL = 'http://www.kayak.com/h/rss/fare?dest=';
		for ($i = 0; $i < sizeof($dest_codes) - 1; $i++)
		{
			$rssURL = $rssURL.$dest_codes[$i].",";
		}
		$rssURL = $rssURL.$dest_codes[sizeof($dest_codes) - 1];
		if (false)
		{
			$rssURL = $rssURL.'&code=';
			for ($i = 0; $i < sizeof($orig_codes) - 1; $i++)
			{
				$rssURL = $rssURL.$orig_codes[$i].",";
			}
			$rssURL = $rssURL.$orig_codes[sizeof($orig_codes) - 1];
		}
		
		// Get RSS Feed
		$rss	= fetch_rss($rssURL);
		$origin_code	= $rss->items[0]['kyk']['origincode'];
		$dest_code	= $rss->items[0]['kyk']['destcode'];

		$rssURL2 = 'http://www.kayak.com/h/rss/fare?code='.$dest_code.'&dest='.$origin_code;
		$rss2	= fetch_rss($rssURL2);

		//$smarty->assign('uid1Location',$rss2->items[0]['kyk']['originlocation']);
		$smarty->assign('targetLocation',$targetLocation['city']);
		$smarty->assign('targetAirportCode',$rss2->items[0]['kyk']['origincode']);
		$smarty->assign('userLocation',$rss->items[0]['kyk']['originlocation']);
		$smarty->assign('userAirportCode',$rss->items[0]['kyk']['origincode']);

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

		$smarty->assign('targetCity', $targetLocation['city']);
		$smarty->assign('targetState', $targetLocation['state']);
		$smarty->assign('targetCountry', $targetLocation['country']);
	}
}

// Debug output
if ($debug)
{
	echo $targetedFriendId." Name: (".$targetFirstName.") City: (".$targetLocation['city'].") State: (".$targetLocation['state'].") Country: (".$targetLocation['country'].")\n";
	echo $rssURL;
        echo "<br />Origin Code: (".$origin_code.") Destination Code: (".$dest_code.")";
}

// Smarty

$smarty->assign('name', $user_details[0]['first_name']);
$smarty->assign('originCodes', $originCodes);

$smarty->display('canvas.tpl');
if($userLocation == NULL)
{
   $smarty->display('noUserLocationMsg.tpl');
}
else
{
    echo "<br /><br />";
}
$smarty->display('searchForm.tpl');
 

if ($targetedFriendId != NULL)
{
    if((sizeof($dest_codes) < 1) && ($targetLocation != NULL))
    {
        $smarty->display('noDestAirportMsg.tpl');
    }
    else if($targetLocation == NULL)
    {
        $smarty->display('noDestLocationMsg.tpl');
    }
    else if($origin_code != $dest_code)
    {
        $smarty->display('resultMsg.tpl');
    }
    else
    {
        $smarty->display('noFlightInfoMsg.tpl');
    }
}

?>

