<fb:if-section-not-added section="profile">  
	Display our box on your profile!<br />  
	<fb:add-section-button section="profile" /> 
</fb:if-section-not-added>

<center>
    <img src="http://irock.comlu.com/logo.JPG" width="342" height="109" alt="logo" />

    <form action="http://apps.facebook.com/kayakme/" id="searchForm" method="post">
        Search friend: <fb:friend-selector uid="#request.userID#" name="uid" idname="friend_sel" />
        <input type="submit" value="Go!" />
    </form>

	<br/><br/>
<table>
		<tr>
			<td>You can visit <fb:name uid="{$uid2}"/> in {$uid1Location.city} ({$uid1AirportCode}) for...
			<br/><font color="#00AA00">{$flight1_description}</font>
			</td>
			<td><a style="font-size:26px" href="{$flight1_buzz}" target="_blank"><b>${$flight1_cost}</b></a>
			</td>
		</tr>
		
		<tr>
			<td><fb:name uid="{$uid2}"/> can visit you in {$uid2Location.city} ({$uid2AirportCode}) for...
			<br/><font color="#00AA00">{$flight2_description}</font>
			</td>
			<td><a style="font-size:26px" href="{$flight2_buzz}" target="_blank"><b>${$flight2_cost}</b></a>
			</td>
		</tr>
</table>

</center>