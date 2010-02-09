<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<link rel="stylesheet" type="text/css" href="http://www.umbcs.org/gopandas/visitme/style/styleMsg.css" />
<br /><br /><br /><br />

<center>
<div class="textAlignCenter">
    <fb:tabs>
        <fb:tab-item href='index.php' title='Home' selected='true'/>
         <fb:tab-item href='invite.php' title='Invite Friends' selected='false'/>
	<fb:tab-item href='about.php' title='About' selected='false' />
    </fb:tabs>

    <br />
    <img src="{$host_url}images/visitme_logo.jpg" alt="visitme_logo" />
    <br /><br />
</div>

{if $search}
	<div class="textAlignCenter">
		<span class="warning">Your current location or hometown is not set yet. Your friend(s) will not be able to find you.</span> <br />
		<a href="http://www.facebook.com/profile.php?ref=profile&id={$uid1}#/profile.php?v=info&ref=profile&id={$uid1}" target="showframe">
			Please set your current location or hometown under "Info" tab, "Contact Information" section...
		</a>
	</div>
{else}
	<div class="textAlignCenter">
		<form action="http://apps.facebook.com/{$app_name}/" id="searchForm" method="post">
			Search friend: <fb:friend-selector uid="#request.userID#" name="uid" idname="friend_sel" />
			<input type="submit" value="Go!" />
		</form>
	</div>
{/if}

</center>
<br /><br />
 
{if $uid2 != null}
	<center>
	<table>
        <tr>
                 <div style="width:524px; height:92px; margin:0px; padding:0px;">
                    <img src="http://maps.google.com/maps/api/staticmap?size=524x92&markers=color:blue|label:O|{$userLat},{$userLong}&markers=color:green|label:D|{$targetLat},{$targetLong}&path=color:orange|weight:3|{$userLat},{$userLong}|{$targetLat},{$targetLong}&sensor=false&key={$google_map_api_key}"/>
                </div>
	</tr>
       <br /><br />
        <tr>
            <table style="border-width:1px;border-style:solid;border-color:#e2e1e1;"  width="520"  height="92">
            <caption style="text-align:left;font-weight:bold">You can visit <fb:name uid="{$uid2}"/> in {$targetLocation} for...</caption>
            <tr>
            <td>
                <fb:profile-pic uid="{$uid1}" linked="true" size="normal" width="90" height="90" />
            </td>
            <td>
                {if not $dest_airport_exists}
			
			<div class="textAlignCenter">
				<span class="warning">Flight information is unavailable!</span>
				<span>&nbsp; Unable to find the destination airport at  <fb:name uid="{$uid2}" />'s current location: {$targetCity}, {$targetState}, {$targetCountry}.</span>
			</div>
			
                {elseif $targetLocation == NULL}
			<div class="textAlignCenter">
				<span class="warning">Flight information is unavailable!</span>
				<span>&nbsp; <fb:name uid="{$uid2}" />'s profile does not have current location set.</span>
			</div>
		{elseif not $nearby}
			
                             <table width="418" height="90" cellpadding="2" style="border-collapse:collapse;border:0px;">
                               <tr>
                                 <td width="120">&nbsp; &nbsp;<a style="font-size:26px" href="{$flight1_buzz}" target="_blank"><b>${$flight1_cost}*</b></a> </td>
                                 <td width="150"><span style="font-size:14px;font-weight:bold">{$userAirportCode} <img src="{$host_url}images/airplane.jpg" alt="airplane" /> {$targetAirportCode} <br />  {$targetAirportCode} <img src="{$host_url}images/airplane.jpg" alt="airplane" /> {$userAirportCode} </span></td>
                                 <td><img src="{$host_url}images/hotel.jpg" alt="hotel" /> <a href="http://www.kayak.com/s/search/hotel?crc={$targetCity},{if $targetStateCode != NULL}{$targetStateCode},{/if}{$targetCountryCode}&do=y" style="font-family:tahoma;font-size:12px;" target="_blank">Hotels in {$targetCity}</a></td>
                               </tr>
                               <tr>
                                 <td colspan="3" height="8" style="padding:5px;border:0px;background-color:#e2e1e1;">{$flight1_description}</td>
                               </tr>
                               <tr>
                                 <td colspan="3" style="padding-top:5px">
                                      <fb:share-button class='meta'>
                                        <meta name='title' content='Round-trip fare from {$userAirportCode} to {$targetAirportCode} is ${$flight1_cost}.'/>
                                        <meta name='description' content="{$app_name} is a Facebook application that helps you to find the lowest fare to fly to/from your friend's city." />
                                        <link rel='target_url' href='http://apps.facebook.com/{$app_name}/'/>
                                      </fb:share-button>
                                 </td>
                               </tr>
                             </table>
			
			
			
		{else}
			You are close enough to drive to <fb:name uid="{$uid2}"/>. Would you like to <a href="http://www.kayak.com/cars">rent a car</a>?
		{/if}
                    
             </td>
             </tr>
             </table>
         </tr>

         <tr height="15"></tr>

         <tr>
            <table style="border-width:1px;border-style:solid;border-color:#e2e1e1;"  width="520"  height="92">
            <caption style="text-align:left;font-weight:bold"><fb:name uid="{$uid2}"/> can visit you in {$userLocation} for...</caption>
            <tr>
             <td>
                <fb:profile-pic uid="{$uid2}" linked="true" size="normal" width="90" height="90" />
             </td>
             <td>
                {if not $dest_airport_exists}
			<div class="textAlignCenter">
				<span class="warning">Flight information is unavailable!</span>
				<span>&nbsp; Unable to find the destination airport at  <fb:name uid="{$uid2}" />'s current location: {$targetCity}, {$targetState}, {$targetCountry}.</span>
			</div>
		{elseif $targetLocation == NULL}
			<div class="textAlignCenter">
				<span class="warning">Flight information is unavailable!</span>
				<span>&nbsp; <fb:name uid="{$uid2}" />'s profile does not have current location set.</span>
			</div>
		{elseif not $nearby}
			<table width="418" height="90" cellpadding="2" style="border-collapse:collapse;border:0px;">
                               <tr>
                                 <td width="120">&nbsp; &nbsp;<a style="font-size:26px" href="{$flight1_buzz}" target="_blank"><b>${$flight2_cost}*</b></a> </td>
                                 <td width="150"><span style="font-size:14px;font-weight:bold">{$targetAirportCode} <img src="{$host_url}images/airplane.jpg" alt="airplane" /> {$userAirportCode} <br /> {$userAirportCode} <img src="{$host_url}images/airplane.jpg" alt="airplane" /> {$targetAirportCode} </span></td>
                                 <td><img src="{$host_url}images/hotel.jpg" alt="hotel" /> <a href="http://www.kayak.com/s/search/hotel?crc={$userCity},{if $userStateCode != NULL}{$userStateCode},{/if}{$userCountryCode}&do=y" style="font-family:tahoma;font-size:12px;" target="_blank">Hotels in {$userCity}</a></td>
                               </tr>
                               <tr>
                                 <td colspan="3" height="8" style="padding:5px;margin:0px;border:0px;background-color:#e2e1e1;">{$flight2_description}</td>
                               </tr>
                               <tr>

                               </tr>
                               <tr>
                                 <td colspan="3" style="padding-top:5px;">
                                     <fb:share-button class='meta'>
                                        <meta name='title' content='Round-trip fare from {$targetAirportCode} to {$userAirportCode} is ${$flight2_cost}.'/>
                                        <meta name='description' content="{$app_name} is a Facebook application that helps you to find the lowest fare to fly to/from your friend's city." />
                                        <link rel='target_url' href='http://apps.facebook.com/{$app_name}/'/>
                                     </fb:share-button>
                                </td>
                               </tr>
                         </table>
                              
		{else}
			
		{/if}
       
             </td>
            </tr>
            </table>
        </tr>
	

       
	</table></center>

        <br /><br />

	{if not $nearby}
		<p align="center"/>
		<font style="font-size:10px">*The fares displayed include all taxes and fees for economy class
		travel and were found by Kayak users in the last 48 hours. Seats are
		limited and may not be available on all flights and days. Fares are
		subject to change and may not be available on all flights or dates of
		travel. Some carriers charged additional fees for extra checked bags.
		Please check the carriers' sites.</font></p>
	{/if}
{/if}