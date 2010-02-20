<link rel="stylesheet" type="text/css" href="http://www.umbcs.org/gopandas/visitme/style/styleMsg.css" />
<script><!--
function sharePrompt(message,farelink,origin,destination)
{literal}{{/literal}
	farelink = farelink.replace("ai=","ai=sm");
	farelink = farelink.replace("p=","p=facebook/app_visitme/buzz");
	var actionLinks = [{literal}{{/literal} "text": "Visit Me", "href": "http://apps.facebook.com/visitme"{literal}}{/literal}];
	var attachment = {literal}{{/literal}
	'name': 'Low fares were just found at KAYAK from ' + origin + " to " + destination + ".",
	'href': farelink,
	'description': 'Visit Me is an application that searches KAYAK to find the best flight deals for travel between you and your friends.',
	{literal}}{/literal}; 
	Facebook.streamPublish(message, attachment, actionLinks, {$uid2});
	return false;
{literal}}{/literal}
//-->
</script>
<br /><br /><br /><br />

<div style="margin-left:auto;margin-right:auto;width:628px;height:116px;background-repeat:no-repeat;background-image:url('{$host_url}images/header.jpg');"></div>

<div style="margin-left:auto;margin-right:auto;margin-bottom:0px;width:600px;border-style:solid;border-width: 0px 14px 0px 14px;border-color:#000000;">
	<center>
	<div style="padding-top:{if $result == null}160px{else}40px{/if};width:520px;{if $result == null}height:200px;{/if}">

		<div class="textAlignCenter">
			<form action="{$host_url}" id="searchForm" method="post">
				One:<input type="text" name="origin_code"/><br/>
				Two:<input type="text" name="dest_code"/><br/>
				<input type="submit" value="Go!" />
			</form>
		</div>
	</div>
	</center>
	<br /><br />
	{if $uid2 != null}
	<span style="padding-left:40px;font-size:20px;"><fb:name uid="{$uid2}"/></span>
	<br/><br/>
	<span style="padding-left:40px;"><b><fb:name uid="{$uid2}" linked="false"/> can visit <fb:name uid="{$uid1}" linked="false"/> for...</b></span>
	<center>
	<table width="540" style="border-width:1px;border-style:solid;border-color:#DDDDDD;">
		<tr>
			<td width="64"><div style="height:64px;overflow:hidden;"><div style="clip:rect(0px 64px 64px 0px);">
				<fb:profile-pic uid="{$uid2}" linked="true" size="thumb" width="64px" />
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
					<span class="warning">Flight information is unavailable!</span><br>
					<span><fb:name uid="{$uid2}" />'s profile does not have current location set.</span>
				</div></td>
			{elseif not $nearby}
				<td width="100" style="border-width:0px 1px 0px 0px;border-style:solid;border-color:#DDDDDD;">
					<center>{if $flight2_cost != NULL}<b style="font-size:23px">${$flight2_cost}</b><sup>^</sup>{else}No flights found.<br/>{/if}
					<br/><a href="http://www.kayak.com/h/farealert?o={$targetAirportCode}&d={$userAirportCode}&ns=N&ai=sm&p=facebook/app_visitme/buzz" target="_blank"><img src="{$host_url}images/search-exp.gif" /></a></center>
				</td>
				<td width="120">
					<table>
						<tr><td><font size="4">{$targetAirportCode}</font></td><td><img src="{$host_url}images/a-arrow-icon.gif" /></td><td><font size="4">{$userAirportCode}</font></td></tr>
						<tr><td><font size="4">{$userAirportCode}</font></td><td><img src="{$host_url}images/a-arrow-icon.gif" /></td><td><font size="4">{$targetAirportCode}</font></td></tr>
					</table>
				</td>
				<td width="106">
					<a href="#" onclick="sharePrompt('Someone found a flight on KAYAK for ${$flight2_cost}^ - you should visit me!', '{$flight2_buzz}','{$targetCity}','{$userCity}');" >Share with<br/><fb:name uid="{$uid2}">!</a>
				</td>
				<td><div style="width:140px; height:62px; margin:0px; padding:0px;"><a href="http://www.kayak.com/s/search/hotel?crc={$userCity},{if $userStateCode != NULL}{$userStateCode},{/if}{$userCountryCode}&do=y&ai=sm&p=facebook/app_visitme/hotel" style="font-family:tahoma;font-size:10px;" target="_blank">
						<img src="http://maps.google.com/maps/api/staticmap?center={$userLat},{$userLong}&zoom=6&size=140x50&sensor=false&key=ABQIAAAAGCpRfrtYUM--SFuCea71tRT9zg3T6UOROa1ThBwls6lJyYcFbhQUX43Uc1mOijTZgEzKCLkpv3IbCA"/></a>
					<br/><a href="http://www.kayak.com/s/search/hotel?crc={$userCity},{if $userStateCode != NULL}{$userStateCode},{/if}{$userCountryCode}&do=y&ai=sm&p=facebook/app_visitme/hotel" style="font-family:tahoma;font-size:10px;" target="_blank">Hotels in {$userCity}</a></div>
				</td>

			{else}
				<td><center>You are close enough to drive to <fb:name uid="{$uid2}"/>. Would you like to <a href="http://www.kayak.com/in?ai=sm&p=facebook/app_visitme/car&url=/cars" target="_blank">rent a car</a>?</center></td>
			{/if}
		</tr>
	</table>
	</center>
	<br/>
	<span style="padding-left:40px;"><b><fb:name uid="{$uid1}" linked="false" capitalize="true"/> can visit <fb:name uid="{$uid2}" linked="false"/> for...</b></span>
	<center>
	<table width="540" style="border-width:1px;border-style:solid;border-color:#DDDDDD;">
		<tr>
			<td width="64"><div style="height:64px;overflow:hidden;"><div style="clip:rect(0px 64px 64px 0px);">
				<fb:profile-pic uid="{$uid1}" linked="true" size="thumb" width="64px" />
			</div></div></td>
			
			{if not $dest_airport_exists}
				<td></td>
			{elseif $targetLocation == NULL}
				<td></td>
			{elseif not $nearby}
				<td width="100" style="border-width:0px 1px 0px 0px;border-style:solid;border-color:#DDDDDD;">
					<center><b style="font-size:23px">${$flight1_cost}</b><sup>^</sup>
					<br/><a href="{$flight1_buzz}&ai=sm&p=facebook/app_visitme/buzz" target="_blank"><img src="{$host_url}images/search-exp.gif" /></a></center>
				</td>
				<td width="120">
					<table>
						<tr><td><font size="4">{$userAirportCode}</font></td><td><img src="{$host_url}images/a-arrow-icon.gif" /></td><td><font size="4">{$targetAirportCode}</font></td></tr>
						<tr><td><font size="4">{$targetAirportCode}</font></td><td><img src="{$host_url}images/a-arrow-icon.gif" /></td><td><font size="4">{$userAirportCode}</font></td></tr>
					</table>
				</td>
				<td width="106">
					<a href="#" onclick="sharePrompt('Someone found a flight on KAYAK for ${$flight1_cost}^ - I want to visit you!','{$flight1_buzz}','{$userCity}','{$targetCity}');" >Share with<br/><fb:name uid="{$uid2}">!</a>
				</td>
				<td>
					<div style="width:140px; height:62px; margin:0px; padding:0px;"><a href="http://www.kayak.com/s/search/hotel?crc={$targetCity},{if $targetStateCode != NULL}{$targetStateCode},{/if}{$targetCountryCode}&do=y&ai=sm&p=facebook/app_visitme/hotel" style="font-family:tahoma;font-size:10px;" target="_blank">
						<img src="http://maps.google.com/maps/api/staticmap?center={$targetLat},{$targetLong}&zoom=6&size=140x50&sensor=false&key=ABQIAAAAGCpRfrtYUM--SFuCea71tRT9zg3T6UOROa1ThBwls6lJyYcFbhQUX43Uc1mOijTZgEzKCLkpv3IbCA"/></a>
					<br/><a href="http://www.kayak.com/s/search/hotel?crc={$targetCity},{if $targetStateCode != NULL}{$targetStateCode},{/if}{$targetCountryCode}&do=y&ai=sm&p=facebook/app_visitme/hotel" style="font-family:tahoma;font-size:10px;" target="_blank">Hotels in {$targetCity}</a></div>
				</td>			
			{else}
				<td><center>You are close enough to drive to <fb:name uid="{$uid2}"/>. Would you like to <a href="http://www.kayak.com/in?ai=sm&p=facebook/app_visitme/car&url=/cars" target="_blank">rent a car</a>?</center></td>
			{/if}
		</tr>
	</table>
	
	</center>

	{if not $nearby}
	<div style="background-color:#e6dfd5;margin:19px 0px 0px 0px;padding:8px 8px 8px 8px;font-size:10px;font-family:tahoma;">
		^The fares displayed include all taxes and fees for economy class travel and were found by Kayak users in the last 72 hours.
		Seats are limited and may not be available on all flights and days. Fares are subject to change and may not be available on all flights or dates of travel. 
		Some carriers charge additional fees for extra checked bags. Please check the carriers' sites.
	</div>
	{else}
	<div style="height:30px;"></div>
	{/if}
{/if}
</div>
<div style="margin-left:auto;margin-right:auto;width:628px;height:16px;background-repeat:no-repeat;background-image:url('{$host_url}images/footer.jpg');"></div>
<script>
{literal}
var latlng = new google.maps.LatLng(-34.397, 150.644);
var myOptions = {
  zoom: 8,
  center: latlng,
  mapTypeId: google.maps.MapTypeId.ROADMAP
};
var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
{/literal}
</script>