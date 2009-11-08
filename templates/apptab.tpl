<link rel="stylesheet" type="text/css" href="http://www.umbcs.org/gopandas/visitme/style/apptab.css" />
<div id="main">
	<img src="http://www.kayak.com/v283/images/logos/kayak-175px-static.png" id="logo" /><br/>
	<div>You can visit <fb:name uid="{$uid1}"/> in {$uid1Location} ({$uid1AirportCode}) for...
		<br/>
		<div id="price"><a style="font-size:20px" href="{$flight1_buzz}">${$flight1_cost}</a> </div>
	</div>
	
	<fb:name uid="{$uid1}"/> can visit you in {$uid2Location} ({$uid2AirportCode}) for...
	<br/>
	<div id="prive"><a style="font-size:20px" href="{$flight2_buzz}">${$flight2_cost}</a> </div>
</div>