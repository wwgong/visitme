<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

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

<link rel="stylesheet" type="text/css" href="{$host_url}/style/about.css" />

<div style="text-align: right;">
     <img src="{$host_url}/images/visitme_logo_s.jpg" id="logo" />
</div>

<div class="center">
    <fb:tabs>
        <fb:tab-item href='index.php' title='Home' selected='false'/>
        <fb:tab-item href='invite.php' title='Invite Friends' selected='false'/>
	<fb:tab-item href='about.php' title='About' selected='true' />
    </fb:tabs>
</div>

<br /><br /><br /><br /><br /><br /><br /><br /><br />

<div class="designBox center">
    <table>
        <tr>
            <th  rowspan="3"><span class="visitMe">VisitME</span></th>
            <td><span class="param">&nbsp;&nbsp;Version:</span> {$version}</td>
        </tr>
        <tr>
            <td><span class="param">&nbsp;&nbsp;Project's website:</span> <a href="http://code.google.com/p/visitme/" target="showframe">VisitME</a></td>
       </tr>
       <tr>
            <td><span class="param">&nbsp;&nbsp;Team's website:</span> <a href="http://gopandas.com/" target="showframe">http://gopandas.com/</a></td>
       </tr>
    </table>
</div>
