<?php 

require_once('includes/config.php');
require_once('includes/smarty/libs/Smarty.class.php');

$facebook = new Facebook($api_key, $secret);
$facebook->require_frame();
$user = $facebook->require_login();

$box = '<script type="text/javascript">function update_user_box() {
	var user_box = document.getElementById("blah");
	
	user_box.innerHTML = "ajax"; } </script>
	<a onclick="update_user_box();" href="#">blah</a>
	<div id="blah"></div>';

//$facebook->api_client->profile_setFBML(NULL, $user, 'profile', NULL, NULL, 'profile_main');
$user_details = $facebook->api_client->users_getInfo($user, 'last_name, first_name');
$facebook->api_client->profile_setFBML(NULL, $user, 'profile', NULL, NULL, $box);

$smarty = new Smarty();

$smarty->assign('name', $user_details[0]['first_name']);

$smarty->display('canvas.tpl');
?>