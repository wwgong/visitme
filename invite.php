<?php
/*
Copyright 2010 GoPandas
This file is part of VisitME.

VisitME is free software: you can redistribute it and/or modify it
under the terms of the GNU General Public License as published by the
Free Software Foundation, either version 3 of the License, or (at your
option) any later version.

VisitME is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
for more details.

You should have received a copy of the GNU General Public License
along with VisitME. If not, see http://www.gnu.org/licenses/.
*/

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




