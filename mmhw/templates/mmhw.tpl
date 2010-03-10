
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<link rel="stylesheet" type="text/css" href="{$host_url}/style/mmhwStyle.css" />
<br />

<div class="center">
    <h1>Meet Me Half Way</h1>
    <br />
    <div class="center"><span class="bold">Format:&nbsp;</span>airport code</div>
    <br /><br />
    <form name="input" action="index.php" method="get">
        Location 1: <input type="text" name="loc1" id="ajxloc_1" />
        Location 2: <input type="text" name="loc2" id="ajxloc_2" />
        <input type="submit" value="Submit" />
    </form>
</div>
<br /><br /><br />

{if $search}
    <div class="center">
        {if $midpoint_1_sel}
            <img style="background-color:green;padding:5px;" src="{$map_1_url}" />
            <img style="padding:5px;" src="{$map_2_url}" />
        {elseif $midpoint_2_sel}
            <img style="padding:5px;" src="{$map_1_url}" />
            <img style="background-color:green;padding:5px;" src="{$map_2_url}" />
        {else}
            <img style="padding:5px;" src="{$map_1_url}" />
            <img style="padding:5px;" src="{$map_2_url}" />
        {/if}
    </div>

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
        
        <div style="text-align:left;font-size:14px;font-weight:bold;width:640px;margin-left:auto;margin-right:auto;">
             <span style="float:left;font-weight:bold;">Status:</span> &nbsp;
             <div style="float:left;color:red;padding-left:10px;width:580px;"> Nearby airport(s) / flight information to nearby airport(s) of the selected mid point at (longitude: {$midpoint_longitude}, latitude: {$midpoint_latitude}) were not found.</div>
        </div>
        <br /><br />
        {if ($flightA_cost!=NULL) || ($flightB_cost!=NULL)}
            <p style="text-align:center;color:green;font-size:14px;font-weight:bold;width:640px;margin-left:auto;margin-right:auto;">
                Would you like to fly to your friend's city instead?
            </p>
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
        <p style="font-size:10px;width: 640px;margin-left:auto; margin-right:auto;">
            *The fares displayed include all taxes and fees for economy class
            travel and were found by Kayak users in the last 48 hours. Seats are
            limited and may not be available on all flights and days. Fares are
            subject to change and may not be available on all flights or dates of
            travel. Some carriers charged additional fees for extra checked bags.
            Please check the carriers' sites.
        </p>
     {/if}

{/if}

