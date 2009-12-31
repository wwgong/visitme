<link rel="stylesheet" type="text/css" href="http://www.umbcs.org/gopandas/visitme/style/styleMsg.css" />
<br /><br /><br /><br />

<div class="textAlignCenter">
    <fb:tabs>
        <fb:tab-item href='index.php' title='Home' selected='true'/>
         <fb:tab-item href='invite.php' title='Invite Friends' selected='false'/>
	<fb:tab-item href='about.php' title='About' selected='false' />
    </fb:tabs>

    <br />
    <img src="{$host_url}images/logo.jpg" alt="logo" />
</div>

{if $userLocation == NULL && $targetCity == NULL}
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

<br /><br />
 
{if $uid2 != null}
	<center>
	<table>
	<tr>
		<td rowspan="2"><div style="height:100px;overflow:hidden;"><div style="clip:rect(0px 100px 100px 0px);">
			<fb:profile-pic uid="{$uid2}" linked="true" size="small" />
		</div></div></td>
		
		{if not $dest_airport_exists}
			<td>
			<div class="textAlignCenter">
				<span class="warning">Flight information is unavailable!</span> 
				<span>&nbsp; Unable to find the destination airport at  <fb:name uid="{$uid2}" />'s current location: {$targetCity}, {$targetState}, {$targetCountry}.</span>
			</div>
			</td>
		{elseif $targetLocation == NULL}
			<td><div class="textAlignCenter">
				<span class="warning">Flight information is unavailable!</span>
				<span>&nbsp; <fb:name uid="{$uid2}" />'s profile does not have current location set.</span>
			</div></td>
		{elseif not $nearby}
			<td>You can visit <fb:name uid="{$uid2}"/> in {$targetLocation} ({$targetAirportCode}) for...
				<br/><font color="#00AA00">{$flight1_description}</font>
			</td>
			<td><a style="font-size:26px" href="{$flight1_buzz}" target="_blank"><b>${$flight1_cost}*</b></a>
			</td>
		{else}
			<td>You are close enough to drive to <fb:name uid="{$uid2}"/>. Would you like to <a href="http://www.kayak.com/cars">rent a car</a>?</td>
		{/if}
	</tr>

	<tr>
		{if not $dest_airport_exists}
			<td></td>
		{elseif $targetLocation == NULL}
			<td></td>
		{elseif not $nearby}
			<td><fb:name uid="{$uid2}"/> can visit you in {$userLocation} ({$userAirportCode}) for...
				<br/><font color="#00AA00">{$flight2_description}</font>
			</td>
			<td><a style="font-size:26px" href="{$flight2_buzz}" target="_blank"><b>${$flight2_cost}*</b></a>
			</td>
		{else}
			<td></td>
		{/if}
	</tr>
	</table></center>

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