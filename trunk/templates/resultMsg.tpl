<!--
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
-->

<center>
<table>
<tr>
	<td rowspan="2"><div style="height:100px"><div style="clip:rect(0px 100px 100px 0px);">
		<fb:profile-pic uid="{$uid2}" linked="true" size="small" />
	</div></div></td>
	
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