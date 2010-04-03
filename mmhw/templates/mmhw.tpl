
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link rel="stylesheet" type="text/css" href="style/mmhwStyle.css" />
<link type="text/css" href="style/jquery.ui.datepicker.css" rel="stylesheet" />
<link type="text/css" href="style/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="style/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="style/jquery.autocomplete.css"  rel="stylesheet" />

<script type="text/javascript" src="includes/scripts/jquery.js"></script>
<script type="text/javascript" src="includes/scripts/jquery.ui.core.js"></script>
<script type="text/javascript" src="includes/scripts/jquery.ui.widget.js"></script>
<script type="text/javascript" src="includes/scripts/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="includes/scripts/jquery.autocomplete.js"></script>

<!-- Google Maps API Javascript V3 -->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
{literal}
  function initialize() {
    var latlng = new google.maps.LatLng({/literal} {$midpoint_latitude} {literal},{/literal} {$midpoint_longitude} {literal});
    var myOptions = {
      zoom: 1,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    var location1 = new google.maps.LatLng({/literal} {$location_1_latitude} {literal},{/literal} {$location_1_longitude} {literal});
    var location2 = new google.maps.LatLng({/literal} {$location_2_latitude} {literal},{/literal} {$location_2_longitude} {literal});
    var midpoint = new google.maps.LatLng({/literal} {$midpoint_latitude} {literal},{/literal} {$midpoint_longitude} {literal});
    var bounds = new google.maps.LatLngBounds();
    bounds.extend(location1);
    bounds.extend(location2);
    bounds.extend(midpoint);
    map.fitBounds(bounds);

    var marker_1_icon = 'images/marker_1.png';
    var marker_2_icon = '{/literal}{$host_url}{literal}/images/marker_2.png';
    var marker_mid_icon = '{/literal}{$host_url}{literal}/images/marker_mid.png';

    <!-- Creating a marker and positioning it on the map  -->
    var loc1_marker = new google.maps.Marker({
    position: location1,
    title: 'Location 1',
    icon: marker_1_icon,
    map: map
    });
    var loc2_marker = new google.maps.Marker({
    position: location2,
    title: 'Location 2',
    icon: marker_2_icon,
    map: map
    });
    var mid_marker = new google.maps.Marker({
    position: midpoint,
    title: 'Midpoint',
    icon: marker_mid_icon,
    map: map
    });
  }
{/literal} 
</script>

<!-- JQuery Datepicker -->
<script type="text/javascript">
{literal}
	$(function() {
            $('#datepicker').datepicker({
                dateFormat: "mm/yy",
                minDate: '+0D',
                maxDate: '+1Y',
		changeMonth: true,
		changeYear: true
            });
	});
{/literal}
</script>

<!-- AJAX Autocomplete -->
<script type="text/javascript">
{literal}
    $().ready(function() {

	$(".airportAutocomplete").autocomplete("includes/autocomplete.php", {
		selectFirst: false,
		<!-- mustMatch: true,    Note: Having issue with some valid values get cleared out-->
                minChars: 3,
                delay: 700,

	});

	$(".airportAutocomplete").result(function(event, data, formatted) {
		if (data)
                    $(this).parent().next().find("input").val(data[1]);
	});

	$("#clear").click(function() {
		$(":input").unautocomplete();
	});
    });
{/literal}
</script>
</head>
<body onload="initialize()">
<br />
<center>

<div style="margin-left:auto;margin-right:auto;text-align:center;">
    <h1>Meet Me Half Way</h1>
    <br />
    <form autocomplete="on" name="input" action="index.php" method="get" >
        <table border="0" cellpadding="1" cellspacing="1" width="410px" class="center">
            <tr>
                <td align="right">Airport 1:&nbsp;</td>
                <td><input type="text" class="airportAutocomplete" name="loc1" size="45"  /></td>
            </tr>
            <tr>
                <td align="right">Airport 2:&nbsp;</td>
                <td><input type="text" class="airportAutocomplete" name="loc2" size="45"  /></td>
            </tr>
            <tr>
                <td align="right">Travel Month:&nbsp;</td>
                <td><input type="text" name="tm" size="45" id="datepicker" /></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td align="left">&nbsp;&nbsp;mm/yyyy</td>
            </tr>
        </table>
        <br />
        <input type="submit" value="Submit" />
    </form>
</div>
<br />

{if $search}
    <center>
    <div style="height:0px;width:665px;padding:0px;border-top:2px solid #999;" ></div>
    <br />
    <div style="width:660px;height:150px;padding:3px;border:1px solid #999;" >
         <div id="map_canvas" style="width:100%; height:100%" ></div>
    </div>
    </center>
    <br />

    {if ($flight1_cost!=NULL) && ($flight2_cost!=NULL)}

        <table align="center" border="0">
            <caption align="center"><img src="{$host_url}/images/m.gif" /><span style="font-size:29px;font-weight:bold;margin-top:0px;">{$location_mid_1}</span></caption>
            <tr>
                <th colspan="3" style="border-bottom: solid;border-color: #e2e1e1;font-size: 18px; padding: 5px;"><div style="padding-bottom:0px;float:left;"><img src="{$host_url}/images/1.gif" /></div><div style="margin-top:27px;float:left;">{$location_1}</div></th>
                <th colspan="3"  style="border-bottom: solid;border-color: #e2e1e1;font-size: 18px; padding: 5px;"><div style="padding-bottom:0px;float:left;"><img src="{$host_url}/images/2.gif" /></div><div style="margin-top:27px;float:left;">{$location_2}</div></th>
            </tr>
            <tr>
                <td align="center" width="100"><a style="font-size: 32px;font-weight: bold;text-decoration:none;" href="{$flight1_buzz}" target="_blank">${$flight1_cost}*</a></td>
                <td align="center" width="120"><span style="font-size: 12px;font-weight: bold;">{$location1AirportCode} <img src="{$host_url}/images/airplane.jpg" alt="airplane" /> {$mid1AirportCode} <br />  {$mid1AirportCode} <img src="{$host_url}/images/airplane.jpg" alt="airplane" /> {$location1AirportCode}</span></td>
                <td align="center" width="100"><img src="{$host_url}/images/hotel.jpg" alt="hotel" /> <a style="font-family:tahoma;font-size:12px;text-decoration:none;" href="http://www.kayak.com/s/search/hotel?crc={$location_mid_1}&do=y" target="_blank">Hotels</a></td>
                <td align="center" width="100"><a style="font-size: 32px;font-weight: bold;text-decoration:none;" href="{$flight2_buzz}" target="_blank">${$flight2_cost}*</a></td>
                <td align="center" width="120"><span style="font-size: 12px;font-weight: bold;">{$location2AirportCode} <img src="{$host_url}/images/airplane.jpg" alt="airplane" /> {$mid2AirportCode} <br />  {$mid2AirportCode} <img src="{$host_url}/images/airplane.jpg" alt="airplane" /> {$location2AirportCode}</span></td>
                <td align="center" width="100"><img src="{$host_url}/images/hotel.jpg" alt="hotel" /> <a style="font-family:tahoma;font-size:12px;text-decoration:none;" href="http://www.kayak.com/s/search/hotel?crc={$location_mid_2}&do=y" target="_blank">Hotels</a></td>
            </tr>
            <tr>
                <td colspan="3" height="8" style=" font-size: 10px;padding: 5px;background-color: #e2e1e1;">{$flight1_description}</td>
                <td colspan="3" height="8" style=" font-size: 10px;padding: 5px;background-color: #e2e1e1;">{$flight2_description}</td>
            </tr>
        </table>
     {elseif $nearby}
        <div style="text-align:center;">The two locations are close enough to drive to. Would you like to <a href="http://www.kayak.com/cars" target="showframe">rent a car</a>?</div>

     {else}
        <div class="center">
        <div style="text-align:left;font-size:14px;font-weight:bold;width:640px;margin-left:auto;margin-right:auto;">
             <span style="float:left;font-weight:bold;">Status:</span> &nbsp;
             <div style="float:left;color:red;padding-left:10px;width:580px;"> Nearby airport(s) / flight information to nearby airport(s) of the selected mid point at (longitude: {$midpoint_longitude}, latitude: {$midpoint_latitude}) were not found.</div>
        </div>
        </div>
        <br /><br />
        {if ($flightA_cost!=NULL) || ($flightB_cost!=NULL)}
            <div class="center">
            <p style="text-align:center;color:green;font-size:14px;font-weight:bold;width:640px;margin-left:auto;margin-right:auto;">
                Would you like to fly to your friend's city instead?
            </p>
            </div>
            <table align="center">
                <tr>
                    <th colspan="3"  style="border-bottom: solid;border-color: #e2e1e1;font-size: 18px; padding: 5px;"><div style="padding-bottom:0px;float:left;"><img src="{$host_url}/images/1.gif" /></div><div style="margin-top:27px;float:left;">{$location_1}</div></th>
                    <th colspan="3"  style="border-bottom: solid;border-color: #e2e1e1;font-size: 18px; padding: 5px;"><div style="padding-bottom:0px;float:left;"><img src="{$host_url}/images/2.gif" /></div><div style="margin-top:27px;float:left;">{$location_2}</div></th>
                </tr>
                <tr>
                    {if $flightA_cost!= NULL}
                        <td align="center" width="100"><a style="font-size: 32px;font-weight: bold;text-decoration:none;" href="{$flightA_buzz}" target="_blank">${$flightA_cost}*</a></td>
                        <td align="center" width="120"><span style="font-size: 12px;font-weight: bold;">{$location1AirportCode} <img src="{$host_url}/images/airplane.jpg" alt="airplane" /> {$location2AirportCode} <br />  {$location2AirportCode} <img src="{$host_url}/images/airplane.jpg" alt="airplane" /> {$location1AirportCode}</span></td>
                        <td align="center" width="100"><img src="{$host_url}/images/hotel.jpg" alt="hotel" /> <a style="font-family:tahoma;font-size:12px;text-decoration:none;" href="http://www.kayak.com/s/search/hotel?crc={$location_2}&do=y" target="_blank">Hotels</a></td>
                    {else}
                        <td width="10"></td>
                        <td width="300" align="center">No flights were found.</td>
                        <td width="10"></td>
                    {/if}
                    {if $flightB_cost!= NULL}
                        <td align="center" width="100"><a style="font-size: 32px;font-weight: bold;text-decoration:none;" href="{$flightB_buzz}" target="_blank">${$flightB_cost}*</a></td>
                        <td align="center" width="120"><span style="font-size: 12px;font-weight: bold;">{$location2AirportCode} <img src="{$host_url}/images/airplane.jpg" alt="airplane" /> {$location1AirportCode} <br />  {$location1AirportCode} <img src="{$host_url}/images/airplane.jpg" alt="airplane" /> {$location2AirportCode}</span></td>
                        <td align="center" width="100"><img src="{$host_url}/images/hotel.jpg" alt="hotel" /> <a style="font-family:tahoma;font-size:12px;text-decoration:none;" href="http://www.kayak.com/s/search/hotel?crc={$location_1}&do=y" target="_blank">Hotels</a></td>
                    {else}
                        <td width="10"></td>
                        <td width="300" align="center">No flights were found.</td>
                        <td width="10"></td>
                    {/if}
                </tr>
                <tr>
                    <td colspan="3" height="8" style=" font-size: 10px;padding: 5px;background-color: #e2e1e1;">{$flightA_description}</td>
                    <td colspan="3" height="8" style=" font-size: 10px;padding: 5px;background-color: #e2e1e1;">{$flightB_description}</td>
                </tr>
            </table>
        {/if}

     {/if}

     {if (($flight1_cost!=NULL) && ($flight2_cost!=NULL)) || ($flightA_cost!=NULL) || ($flightB_cost!=NULL)}
        <br />
        <p style="font-size:10px;width: 640px;">
            *The fares displayed include all taxes and fees for economy class
            travel and were found by Kayak users in the last 48 hours. Seats are
            limited and may not be available on all flights and days. Fares are
            subject to change and may not be available on all flights or dates of
            travel. Some carriers charged additional fees for extra checked bags.
            Please check the carriers' sites.
        </p>
        </div>
     {/if}

{/if}
</center>

</body>
</html>