
<?php
/***********************************************************************************************
 *    Project Name: VisitME
 *    Project website: http://code.google.com/p/visitme/
 * *********************************************************************************************/

$time		= microtime(true);
$time_query	= 0;
require_once('includes/common.php');

$facebook = new Facebook($api_key, $secret);
$facebook->require_frame();
$user = $facebook->require_login();

// Create Smarty object
$smarty = new Smarty();
$smarty->assign('app_name',$app_name);
$smarty->assign('host_url',$host_url);
$smarty->assign('version', $version);
$smarty->assign('google_map_api_key', $google_map_api_key);
$smarty->assign('uid1',$user);

// Logic
$userDetails = array();
$userLocation = array();
while ($userDetails == NULL)
{
	$userDetails = $facebook->api_client->users_getInfo($user, 'last_name, first_name, current_location, hometown_location');
	usleep(50000); // Wait 50ms if $userDetails doesn't initialize
}
$userLocation = $userDetails[0]['current_location'];
	
if ($_POST['apptab_location'] != NULL)
{
	$userLocation = mysql_real_escape_string($_POST['apptab_location']);
}
else if ($userLocation == NULL)
{
	$userLocation = $userDetails[0]['hometown_location'];
}

$smarty->assign('search',$userLocation == NULL);

$facebook->api_client->profile_setFBML(NULL, $user, 'profile', NULL, NULL, 'deprecated');

// flag for whether user and friend are closeby.
$nearby = -1;	// -1 = default, 0 = far, 1 = nearby

$apptab = $_POST['apptab_location'];
if ($apptab == NULL)
{
	$targetedFriendId = $_POST['friend_sel'];
}
else
{
	$targetedFriendId = $_POST['fb_sig_profile'];
}
if ($targetedFriendId != NULL || $apptab != NULL)
{
	$targetUserInfo = array();
	while ($targetUserInfo == NULL)
	{
		$targetUserInfo		= $facebook->api_client->users_getInfo($targetedFriendId, 'last_name, first_name, current_location, hometown_location, work_history');
		usleep(50000); // Wait 50ms if $targetUserInfo doesn't initialize
	}
	$targetFirstName	= $targetUserInfo[0]['first_name'];
	$targetLastName		= $targetUserInfo[0]['last_name'];

	$smarty->assign("userFirstName",$userDetails[0]['first_name']);
	$smarty->assign("targetFirstName",$targetUserDetails[0]['first_name']);
	
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
		$composite = "";
		if ($_POST['apptab_location'] == NULL)
		{
			$composite = $userLocation['city'];
			if ($userLocation['state'] != NULL)
			{
				$composite = $composite.",".$userLocation['state'];
			}
			$composite = $composite.",".$userLocation['country'];
		}
		else
		{
			$composite = $userLocation;
		}
		$xml = get_geocode_xml($composite);
		$userLola = get_lola($composite);
		
		$smarty->assign('userHotel', str_replace(",", "/", $composite));
		
		if ($debug)
		{
			echo "$composite <br/>";
		}
		
		$composite = $targetLocation['city'];
		if ($targetLocation['country'] == "USA" && $targetLocation['state'] != NULL)
		{
			$composite = $composite.",".$targetLocation['state'];
		}
		$composite = $composite.",".$targetLocation['country'];

		$smarty->assign('targetHotel', str_replace(",", "/", $composite));

		$targetLola = get_lola($composite);
		
		$smarty->assign('targetLong',$targetLola[0]);
		$smarty->assign('targetLat',$targetLola[1]);
		
		$smarty->assign('userLong',$userLola[0]);
		$smarty->assign('userLat',$userLola[1]);
		
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
		
		$smarty->assign('targetLocation',$targetLocation['city']);
		$smarty->assign('targetCity', $targetLocation['city']);
		$smarty->assign('targetState', $targetLocation['state']);
                
		if ($targetLocation['country'] == "United States")
		{
			$smarty->assign('targetStateCode', get_state_code($targetLocation['state']));
		}
		$smarty->assign('targetCountry', $targetLocation['country']);
		$smarty->assign('targetCountryCode', get_country_code($targetLocation['country']));


		if ($_POST['apptab_location'] == NULL)
		{
			$smarty->assign('userCity', $userLocation['city']);
			$smarty->assign('userState', $userLocation['state']);
			$smarty->assign('userStateCode',get_state_code($userLocation['state']));
			$smarty->assign('userCountry', $userLocation['country']);
			$smarty->assign('userCountryCode', get_country_code($userLocation['country']));
		}
		else
		{
			$smarty->assign('userCity', $userLola[2]);
			$smarty->assign('userState', $userLocation['state']);
			$smarty->assign('userStateCode',$userLola[3]);
			$smarty->assign('userCountry', $userLola[4]);
			$smarty->assign('userCountryCode', get_country_code($userLola[4]));
		}
		
		if ($distance > 2 * $radius)
		{
			$nearby = 0;
			
			// Get origin codes
			$orig_codes = array();
			$orig_info = false;
			if ($userLocation != NULL)
			{
				if ($_POST['apptab_location'] == NULL)
				{
					$orig_codes = get_airport_codes($userLola, $radius);
				}
				else
				{
					$orig_codes = get_airport_codes($userLola);
				}
				$orig_info = true;
			}
			
			// Get destination codes
			$dest_codes = get_airport_codes($targetLola, $radius);
	
			$time_queries = 0;
			$fares = array();
			if (sizeof($orig_codes) > 0)
			{
				foreach ($orig_codes as $code)
				{
					$time_delta = microtime(true);
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
					$time_delta = microtime(true) - $time_delta;
					$time_queries += $time_delta;
					if ($time_queries >= 8)
					{
						break;
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
			$smarty->assign('targetAirportCode',$fares->items[0]['kyk']['destcode']);
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
		else	// User is within 2x the radius
		{
			$nearby = 1;
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

// Prep
$dest_airport_exists = !(!$nearby && (sizeof($fares) < 1) && ($targetLocation != NULL));

// Smarty
$smarty->assign('name', $user_details[0]['first_name']);
$smarty->assign('originCodes', $originCodes);
$smarty->assign('nearby', $nearby);

$smarty->assign('dest_airport_exists', $dest_airport_exists);

$smarty->display('canvas.tpl');

$time = (microtime(true) - $time);
//echo "<br/><span style='padding-left:120px;'>Generated in ".substr($time,0,5)."s. Queries in ".substr($time_queries,0,5)."s.</span>";
?>
