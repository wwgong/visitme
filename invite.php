
<div style="text-align:right;">
     <img src="http://www.kayak.com/v283/images/logos/kayak-175px-static.png" id="logo" />
</div>

<div style="text-align:center;">
    <fb:tabs>
        <fb:tab-item href='index.php' title='Home' selected='false'/>
        <fb:tab-item href='invite.php' title='Invite Friends' selected='true'/>
	<fb:tab-item href='about.php' title='About' selected='false' />
    </fb:tabs>

    <fb:fbml>
        <fb:request-form
            action="index.php"
            method="POST"
            invite="true"
            type="VisitME"
            content="Social flight ticketing!<?php echo htmlentities("<fb:req-choice url=\"http://apps.facebook.com/kayakme/\" label=\"Authorize My Application\"") ?>" >
            <fb:multi-friend-selector showborder="false" actiontext="Invite your friends to use VisitME.">
        </fb:request-form>
    </fb:fbml>
</div>



