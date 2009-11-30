<center>
<table>
<tr>
	<td rowspan="2"><fb:profile-pic uid="{$uid2}" linked="true" height="60"/></td>
	{if not $nearby}
	<td>You can visit <fb:name uid="{$uid2}"/> in {$targetLocation} ({$targetAirportCode}) for...
		<br/><font color="#00AA00">{$flight1_description}</font>
	</td>
	<td><a style="font-size:26px" href="{$flight1_buzz}" target="_blank"><b>${$flight1_cost}</b></a>
	</td>
	{else}
	<td>You are close enough to drive to <fb:name uid="{$uid2}"/>.</td>
	{/if}
</tr>

<tr>
	{if not $nearby}
	<td><fb:name uid="{$uid2}"/> can visit you in {$userLocation} ({$userAirportCode}) for...
		<br/><font color="#00AA00">{$flight2_description}</font>
	</td>
	<td><a style="font-size:26px" href="{$flight2_buzz}" target="_blank"><b>${$flight2_cost}</b></a>
	</td>
	{else}
	<td></td>
	{/if}
</tr>
</table>
</center>