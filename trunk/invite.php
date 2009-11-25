<?php
    require_once('includes/config.php');
?>

<div style="text-align:right;">
     <img src="http://www.kayak.com/v283/images/logos/kayak-175px-static.png" id="logo" />
</div>

<div style="text-align:center;">
    <fb:tabs>
        <fb:tab-item href='index.php' title='Home' selected='false'/>
        <fb:tab-item href='invite.php' title='Invite Friends' selected='true'/>
		<fb:tab-item href='about.php' title='About' selected='false' />
    </fb:tabs>
</div>

<div style="text-align:center;">
    <?php
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
    ?>

    <fb:fbml>
        <fb:request-form
            action="index.php"
            method="POST"
            invite="true"
            type="VisitME"
            content="Social flight ticketing!<?php echo htmlentities("<fb:req-choice url=\"http://apps.facebook.com/kayakme/\" label=\"Authorize My Application\"") ?>" >
            <fb:multi-friend-selector showborder="false" actiontext="Invite your friends to use VisitME." exclude_ids="<?php echo $excludeFriendList; ?>">>
        </fb:request-form>
    </fb:fbml>
</div>



