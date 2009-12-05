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
 * Modified  11/26/2009  Henrik Volckmer Coding marathon, see
 *                       Weiwei Gong     http://code.google.com/p/visitme/wiki/DevelopmentHistory
 *                       Vasudev Gadge
 * *********************************************************************************************/

require_once('includes/common.php');

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

// flag for whether user and friend are closeby.
$nearby = true;

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
		// Check distance between user and friend
		$composite = $userLocation['city'];
		if ($userLocation['state'] != NULL)
		{
			$composite = $composite.",".$userLocation['state'];
		}
		$composite = $composite.",".$userLocation['country'];
		$userLola = get_lola($composite);
		
		if ($debug)
		{
			echo "$composite <br/>";
		}
		
		$composite = $targetLocation['city'];
		if ($targetLocation['state'] != NULL)
		{
			$composite = $composite.",".$targetLocation['state'];
		}
		$composite = $composite.",".$targetLocation['country'];
		$targetLola = get_lola($composite);
		
		if ($debug)
		{
			echo "$composite <br/>";
		}
		
		$distance = sqrt(pow($userLola[0] - $targetLola[0], 2) + pow($userLola[1] - $targetLola[1], 2));
		
		if ($debug)
		{
			echo "Distance: $distance";
			print_r($userLola);
			print_r($targetLola);
		}
		
		$smarty->assign('targetCity', $targetLocation['city']);
		$smarty->assign('targetState', $targetLocation['state']);
		$smarty->assign('targetCountry', $targetLocation['country']);
			
		if ($distance > 2 * $radius)
		{
			$nearby = false;
			
			// Get origin codes
			$orig_codes = array();
			$orig_info = false;
			if ($userLocation != NULL)
			{
				$orig_codes = get_airport_codes($userLocation['city'], $userLocation['state'], $userLocation['country'], $radius);
				$orig_info = true;
			}
			
			// Get destination codes
			$dest_codes = get_airport_codes($targetLocation['city'], $targetLocation['state'], $targetLocation['country'], $radius);
	
			$fares = array();
			if (sizeof($orig_codes) > 0)
			{
				foreach ($orig_codes as $code)
				{
					$rss = get_fares_code_to_city($code,$dest_codes,$debug);
					if ($debug)
					{
						//echo "<br/>".sizeof($rss->items)."<br/>";
						//print_r($rss);
					}
					if (sizeof($rss->items) > 0)
					{
						if (sizeof($fares->items) < 1 || $rss->items[0]['kyk']['price'] < $fares->items[0]['kyk']['price'])
						{
							$fares = $rss;
						}
					}
				}
			}
			if ($debug)
			{
				echo "RSS values: ".sizeof($fares);
				
				print_r($fares);
				echo "<br/><br/><br/>";
				echo $_SERVER['HTTP_X_FB_USER_REMOTE_ADDR'];
				echo "<br/><br/><br/>";
				
			}
			
			$origin_code	= $fares->items[0]['kyk']['origincode'];
			$dest_code	= $fares->items[0]['kyk']['destcode'];

			if ($debug)
			{
				echo "<br/><br/><br/>";
				print_r($orig_codes);
				echo "<br/><br/><br/>";
				print_r($dest_codes);
				echo "<br/><br/><br/>";
			}
			$rssURL2 = 'http://www.kayak.com/h/rss/fare?code='.$dest_code.'&dest='.$origin_code;
			$rss2	= fetch_rss($rssURL2);

			//$smarty->assign('uid1Location',$rss2->items[0]['kyk']['originlocation']);
			$smarty->assign('targetLocation',$targetLocation['city']);
			$smarty->assign('targetAirportCode',$rss2->items[0]['kyk']['origincode']);
			$smarty->assign('userLocation',$fares->items[0]['kyk']['originlocation']);
			$smarty->assign('userAirportCode',$fares->items[0]['kyk']['origincode']);

			$smarty->assign('flight1_cost',$fares->items[0]['kyk']['price']);
			$smarty->assign('flight1_departure',100);
			$smarty->assign('flight1_arrival',100);
			$smarty->assign('flight1_airline',100);
			$smarty->assign('flight1_description',$fares->items[0]['description']);
			$smarty->assign('flight1_buzz',$fares->items[0]['guid']);

			$smarty->assign('flight2_cost',$rss2->items[0]['kyk']['price']);
			$smarty->assign('flight2_departure',100);
			$smarty->assign('flight2_arrival',100);
			$smarty->assign('flight2_airline',100);
			$smarty->assign('flight2_description',$rss2->items[0]['description']);
			$smarty->assign('flight2_buzz',$rss2->items[0]['guid']);
		}
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
$smarty->assign('nearby', $nearby);
		
$smarty->display('canvas.tpl');
if($userLocation == NULL)
{
	$smarty->display('noUserLocationMsg.tpl');
}
else
{
	$smarty->display('searchForm.tpl');
}


if ($targetedFriendId != NULL)
{
	if ($nearby)
	{
		$smarty->display('resultMsg.tpl');
	}
	else if((sizeof($fares) < 1) && ($targetLocation != NULL))
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
