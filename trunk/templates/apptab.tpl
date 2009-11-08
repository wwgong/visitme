<link rel="stylesheet" type="text/css" href="http://www.umbcs.org/gopandas/visitme/style/apptab.css" />
<div id="main">
	<img src="http://www.kayak.com/v283/images/logos/kayak-175px-static.png" id="logo" /><br/>
	<table>
		<tr>
			<td>You can visit <fb:name uid="{$uid1}"/> in {$uid1Location} ({$uid1AirportCode}) for...
			<br/><font color="#00AA00">{$flight1_description}</font>
			</td>
			<td><a style="font-size:26px" href="{$flight1_buzz}" target="_blank"><b>${$flight1_cost}</b></a>
			</td>
		</tr>
		
		<tr>
			<td><fb:name uid="{$uid1}"/> can visit you in {$uid2Location} ({$uid2AirportCode}) for...
			<br/><font color="#00AA00">{$flight2_description}</font>
			</td>
			<td><a style="font-size:26px" href="{$flight2_buzz}" target="_blank"><b>${$flight2_cost}</b></a>
			</td>
		</tr>
	</table>
</div>