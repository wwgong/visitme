<?php
	require_once('includes/config.php');
        $facebook = new Facebook($api_key, $secret);
	$facebook = new Facebook($api_key, $secret);
	$facebook->require_frame();
	$user = $facebook->require_login();

	$appUserList = $facebook->api_client->friends_getAppUsers();

    $excludeFriendList = NULL;

    foreach($appUserList as $appUserId)
    {
        if($excludeFriendList == NULL)
        {
            $excludeFriendList = $excludeFriendList.$appUserId;
        }
        else
        {
            $excludeFriendList = $excludeFriendList.','.$appUserId;
        }
    }

    $link = htmlentities("<fb:req-choice url=\"http://apps.facebook.com/visitme/\" label=\"Authorize My Application\">");

    $smarty = new Smarty();
    $smarty->assign('excludeFriendList', $excludeFriendList);
    $smarty->assign('link', $link);
    $smarty->assign('host_url',$host_url);
    $smarty->display('invite.tpl');
?>




