<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Copyright 2010 GoPandas
This file is part of Meet Me Half Way ( an extension of VisitME ).

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
<html xmlns:og="http://opengraphprotocol.org/schema/"
      xmlns:fb="http://developers.facebook.com/schema/">
<head>
<title>Meet Me Halfway</title>
    <meta property="og:title" content="Meet Me Halfway" />
    <meta property="og:type" content="city" />
    <meta property="og:url" content="{$host_url}" />
    <meta property="og:image" content="{$host_url}/mmhw.png" />
    <meta property="fb:admins" content="{$fb_admin_id}" />
    <meta property="og:site_name" content="GoPandas" />
    <meta property="og:description" content="Can't think of an interesting place to meet with someone? 'Meet Me Halfway' can help you." />
    <!--
    <meta property="og:email" content="me@example.com" />
    <meta property="og:phone_number" content="000-000-0000" />
    <meta property="og:fax_number" content="+1-000-000-0000" />
    -->


    <meta name="description" content="Can't think of an interesting place to meet with someone? 'Meet Me Halfway' can help you." />
    <meta name="keywords" content="meet me halfway, mmhw, lets meet, flight, fly, kayak, pandas, gopandas, visitme, class 2010, umb, umass, umass boston, umb computer science" />

<link rel="stylesheet" type="text/css" href="style/mmhwStyle.css" />
<link type="text/css" href="style/jquery.ui.datepicker.css" rel="stylesheet" />
<link type="text/css" href="style/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="style/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="style/jquery.autocomplete.css"  rel="stylesheet" />
<link type="text/css" href="style/jquery.ui.progressbar.css"  rel="stylesheet" />

<script type="text/javascript" src="includes/scripts/jquery.js"></script>
<script type="text/javascript" src="includes/scripts/jquery.ui.core.js"></script>
<script type="text/javascript" src="includes/scripts/jquery.ui.widget.js"></script>
<script type="text/javascript" src="includes/scripts/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="includes/scripts/jquery.autocomplete.js"></script>
<script type="text/javascript" src="includes/scripts/jquery.ui.progressbar.js"></script>
<script type="text/javascript" src="includes/scripts/calendar.js"></script>
<script type="text/javascript" src="includes/scripts/section.js"></script>
<script type="text/javascript" src="includes/scripts/search.progressbar.js"></script>

<!-- Google Maps API Javascript V3 -->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
{literal}
  function initialize() {
    var latlng = new google.maps.LatLng({/literal} {$midpoint_latitude} {literal},{/literal} {$midpoint_longitude} {literal});
    var myOptions = {
      zoom: 12,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    var location1 = new google.maps.LatLng({/literal} {$location_1_latitude} {literal},{/literal} {$location_1_longitude} {literal});
    var location2 = new google.maps.LatLng({/literal} {$location_2_latitude} {literal},{/literal} {$location_2_longitude} {literal});
    var geoMidpoint = new google.maps.LatLng({/literal} {$midpoint_latitude} {literal},{/literal} {$midpoint_longitude} {literal});

    var bounds = new google.maps.LatLngBounds();
    bounds.extend(location1);
    bounds.extend(location2);
    bounds.extend(geoMidpoint); 
    {/literal}{if ($meeting_point_longitude != NULL) && ($meeting_point_latitude != NULL)}{literal}
        var meetingPoint = new google.maps.LatLng({/literal} {$meeting_point_latitude} {literal},{/literal} {$meeting_point_longitude} {literal});
        bounds.extend(meetingPoint);

        <!-- Polylines -->
        var flightPlanCoordinates = [
        location1,
        meetingPoint,
        location2
        ];

        var flightPath = new google.maps.Polyline({
        path: flightPlanCoordinates,
        strokeColor: "#ffa500",
        strokeOpacity: 1.0,
        strokeWeight: 2
        });
        flightPath.setMap(map);
    {/literal}{/if}{literal}
    map.fitBounds(bounds);

    var marker1Icon = 'images/marker_1.png';
    var marker2Icon = 'images/marker_2.png';
    var markerGeoMidIcon = 'images/geomid.png';
    var markerMeetIcon = 'images/marker_meet.png';

    <!-- Creating markers and positioning them on the map  -->
    var loc1_marker = new google.maps.Marker({
    position: location1,
    title: 'Location 1',
    icon: marker1Icon,
    map: map,
    zIndex: 3
    });
    var loc2_marker = new google.maps.Marker({
    position: location2,
    title: 'Location 2',
    icon: marker2Icon,
    map: map,
    zIndex: 2
    });
    var mid_marker = new google.maps.Marker({
    position: geoMidpoint,
    title: 'Geographic Midpoint',
    icon: markerGeoMidIcon,
    map: map,
    zIndex: 1
    });
    {/literal}{if ($meeting_point_longitude != NULL) && ($meeting_point_latitude != NULL)}{literal}
        var meet_marker = new google.maps.Marker({
        position: meetingPoint,
        title: 'Location to Meet',
        icon: markerMeetIcon,
        map: map,
        zIndex: 4
        });
    {/literal}{/if}{literal}
  }
{/literal} 
</script>

<!-- AJAX Autocomplete -->
<script type="text/javascript">
{literal}
    $().ready(function() {

	$(".airportAutocomplete").autocomplete("includes/autocomplete.php", {
		selectFirst: true,
                matchSubset: false,
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
<body onload="initialize(); travel_month();">
<div style="position:absolute;top:0px;width:100%;height:25px;margin-left:-9px;border-bottom:1px solid #ffa500;">
    <div style="float:left;padding:0px 0px 0px 20px;">
        <a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php?u={$host_url}&t=Meet Me Halfway">Share</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script> &nbsp;|&nbsp;&nbsp;
    </div>
    <div style="float:left;">
        <iframe src="http://www.facebook.com/widgets/like.php?href={$host_url}"
            scrolling="no" frameborder="0"
            style="border:none; width:450px; height:23px; "></iframe>
    </div>
    <div style="float:right;padding:0px 20px 0px 0px;">
        <a href="http://apps.facebook.com/visitme/" target="_blank">VisitME</a> &nbsp;|&nbsp;
        <a href="http://code.google.com/p/visitme/" target="_blank">Project's Website</a> &nbsp;|&nbsp;
        <a href="http://gopandas.com/" target="_blank">Team's Website</a>
    </div>
</div>
<center>

<br />
<div style="margin-left:auto;margin-right:auto;text-align:center;">
    <img src="images/mmhw.png" alt="Meet Me Half Way" />
    <br />
    <form autocomplete="on" name="input" action="index.php" method="get" >
        <table border="0" cellpadding="1" cellspacing="2" width="500px" class="center">
            <tr>
                <td align="right">Airport 1:&nbsp;</td>
                <td align="left"><input type="text" class="airportAutocomplete" name="loc1" size="50" /></td>
            </tr>
            <tr>
                <td align="right">Airport 2:&nbsp;</td>
                <td align="left"><input type="text" class="airportAutocomplete" name="loc2" size="50"  /></td>
            </tr>
            <tr>
                <td align="right" >Travel Month:&nbsp;</td>
                <td align="left" >{$month_selection}&nbsp; Year:&nbsp;{$year_selection}</td>
            </tr>
            <tr>
                <td align="right">Filter:&nbsp;</td>
                <td align="left">
                                <SELECT NAME="filter">
                                    <OPTION VALUE="0" SELECTED>Best Matched Dates</OPTION>
                                    <OPTION VALUE="1" >Lowest Combined Fares</OPTION>
                                    <OPTION VALUE="2" >Closest to Geographic Midpoint</OPTION>
                                 </SELECT></td>

            </tr>
        </table>
        <br />
        <input type="submit" value="Search" onclick="changeSection('searchProgress');" />
    </form>
</div>
<br />

<div class="inactiveSection" id="searchProgress">
    <div style="margin-top:20px;width:500px;">
        <img src="images/indicator.gif" /> Searching, please wait...</img>
    </div>
   
</div>
<div class="inactiveSection" id="contents">
{if $search}
    <center>
    <div style="height:0px;width:665px;padding:0px;border-top:1px solid #999;" ></div>
    <br />
    <div style="width:660px;height:150px;padding:3px;border:1px solid #999;" >
         <div id="map_canvas" style="width:100%; height:100%" ></div>
    </div>
    </center>
    <br />

    {if ($flight1_cost!=NULL) && ($flight2_cost!=NULL)}

        <table align="center" border="0">
            <caption align="center"><img src="{$host_url}/images/m.gif" /><span style="font-size:29px;font-weight:bold;margin-top:0px;">{$location_mid}</span></caption>
            <tr>
                <th colspan="3" style="border-bottom: solid;border-color: #e2e1e1;font-size: 18px; padding: 5px;"><div style="padding-bottom:0px;float:left;"><img src="{$host_url}/images/1.gif" /></div><div style="margin-top:27px;float:left;">{$location_1}</div></th>
                <th colspan="3"  style="border-bottom: solid;border-color: #e2e1e1;font-size: 18px; padding: 5px;"><div style="padding-bottom:0px;float:left;"><img src="{$host_url}/images/2.gif" /></div><div style="margin-top:27px;float:left;">{$location_2}</div></th>
            </tr>
            <tr>
                <td align="center" width="100"><a style="font-size: 32px;font-weight: bold;text-decoration:none;" href="{$flight1_buzz}" target="_blank">${$flight1_cost}*</a></td>
                <td align="center" width="120"><span style="font-size: 12px;font-weight: bold;">{$location_1_airportCode} <img src="{$host_url}/images/airplane.jpg" alt="airplane" /> {$location_mid_airportCode} <br />  {$location_mid_airportCode} <img src="{$host_url}/images/airplane.jpg" alt="airplane" /> {$location_1_airportCode}</span></td>
                <td align="center" width="100"><img src="{$host_url}/images/hotel.jpg" alt="hotel" /> <a style="font-family:tahoma;font-size:12px;text-decoration:none;" href="http://www.kayak.com/s/search/hotel?crc={$location_mid}&do=y" target="_blank">Hotels</a></td>
                <td align="center" width="100"><a style="font-size: 32px;font-weight: bold;text-decoration:none;" href="{$flight2_buzz}" target="_blank">${$flight2_cost}*</a></td>
                <td align="center" width="120"><span style="font-size: 12px;font-weight: bold;">{$location_2_airportCode} <img src="{$host_url}/images/airplane.jpg" alt="airplane" /> {$location_mid_airportCode} <br />  {$location_mid_airportCode} <img src="{$host_url}/images/airplane.jpg" alt="airplane" /> {$location_2_airportCode}</span></td>
                <td align="center" width="100"><img src="{$host_url}/images/hotel.jpg" alt="hotel" /> <a style="font-family:tahoma;font-size:12px;text-decoration:none;" href="http://www.kayak.com/s/search/hotel?crc={$location_mid}&do=y" target="_blank">Hotels</a></td>
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
             <div style="float:left;color:red;padding-left:10px;width:570px;"> Nearby airport(s) / flight information to nearby airport(s) of the selected mid point at (longitude: {$midpoint_longitude}, latitude: {$midpoint_latitude}) were not found.</div>
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
                    <th colspan="3"  style="border-bottom: solid;border-color: #e2e1e1;font-size: 18px; padding: 5px;"><div style="padding-bottom:0px;float:left;"><img src="{$host_url}/images/1.gif" /></div><div style="margin-top:27px;float:left;">{$location_A}</div></th>
                    <th colspan="3"  style="border-bottom: solid;border-color: #e2e1e1;font-size: 18px; padding: 5px;"><div style="padding-bottom:0px;float:left;"><img src="{$host_url}/images/2.gif" /></div><div style="margin-top:27px;float:left;">{$location_B}</div></th>
                </tr>
                <tr>
                    {if $flightA_cost!= NULL}
                        <td align="center" width="100"><a style="font-size: 32px;font-weight: bold;text-decoration:none;" href="{$flightA_buzz}" target="_blank">${$flightA_cost}*</a></td>
                        <td align="center" width="120"><span style="font-size: 12px;font-weight: bold;">{$location_A_airportCode} <img src="{$host_url}/images/airplane.jpg" alt="airplane" /> {$location_B_airportCode} <br />  {$location_B_airportCode} <img src="{$host_url}/images/airplane.jpg" alt="airplane" /> {$location_A_airportCode}</span></td>
                        <td align="center" width="100"><img src="{$host_url}/images/hotel.jpg" alt="hotel" /> <a style="font-family:tahoma;font-size:12px;text-decoration:none;" href="http://www.kayak.com/s/search/hotel?crc={$location_B}&do=y" target="_blank">Hotels</a></td>
                    {else}
                        <td width="10"></td>
                        <td width="300" align="center">No flights were found.</td>
                        <td width="10"></td>
                    {/if}
                    {if $flightB_cost!= NULL}
                        <td align="center" width="100"><a style="font-size: 32px;font-weight: bold;text-decoration:none;" href="{$flightB_buzz}" target="_blank">${$flightB_cost}*</a></td>
                        <td align="center" width="120"><span style="font-size: 12px;font-weight: bold;">{$location_B_airportCode} <img src="{$host_url}/images/airplane.jpg" alt="airplane" /> {$location_A_airportCode} <br />  {$location_A_airportCode} <img src="{$host_url}/images/airplane.jpg" alt="airplane" /> {$location_B_airportCode}</span></td>
                        <td align="center" width="100"><img src="{$host_url}/images/hotel.jpg" alt="hotel" /> <a style="font-family:tahoma;font-size:12px;text-decoration:none;" href="http://www.kayak.com/s/search/hotel?crc={$location_A}&do=y" target="_blank">Hotels</a></td>
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
        <p style="font-size:10px;width: 640px;text-align:left;">
            *The fares displayed include all taxes and fees for economy class
            travel and were found by Kayak users in the last 48 hours. Seats are
            limited and may not be available on all flights and days. Fares are
            subject to change and may not be available on all flights or dates of
            travel. Some carriers charged additional fees for extra checked bags.
            Please check the carriers' sites.
        </p>
        </div>
     {/if}
     </center>

     <SCRIPT LANGUAGE="JavaScript" TYPE="TEXT/JAVASCRIPT">
     {literal}
     <!--
        changeSection('contents');
     //-->
    {/literal}
    </SCRIPT>
{/if}
</div>

</body>
</html>