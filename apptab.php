<?php
/***********************************************************************************************
 *    Project's Name: VisitME
 *            School: University of Massachusetts Boston
 *        Department: Computer Science
 * Project's website: http://code.google.com/p/visitme/
 *             Class: CS682 - Software Development Laboratory I
 * ---------------------------------------------------------------------------------------------
 * Status    Date        Authors          Comments
 * ---------------------------------------------------------------------------------------------
 * Created   11/07/2009  Henrik Volckmer  Created application tab page using PHP, Smarty and
 *                       Weiwei Gong      HTML template. Works for getting people to and from
 *                       Vasudev Gadge    cities in the airport database.
 * *********************************************************************************************/

// Includes
require_once('includes/common.php');

$smarty = new Smarty();
$smarty->assign("host_url",$host_url);
$smarty->assign("app_name",$app_name);
$smarty->assign("zipcode",$_POST['zipcode']);
$user = $_POST['fb_sig_profile_user'];
$smarty->assign("uid",$user);
$facebook = new Facebook($api_key, $secret);
$user_details = $facebook->api_client->users_getInfo($user, 'first_name, last_name, current_location, hometown_location');
//$user_location = $user_details[0]['current_location'];

$visitor = $_POST['fb_sig_user'];
if ($user_location = NULL)
{
	//$user_location = $user_details[0]['hometown_location'];
}

if ($debug)
{
	$user_locale = $_POST['fb_sig_locale'];
	echo "<br/>User id: $user<br/>User locale: $user_locale<br/><br/>Visitor: $visitor";
}

$visitor_ip = $_SERVER['REMOTE_ADDR'];

if ($debug)
{
	echo "<br/>Visitor ip: $visitor_ip";
}

$smarty->display("apptab.tpl");
?>