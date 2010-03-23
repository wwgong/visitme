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


<div style="text-align:right;">
     <img src="{$host_url}/images/visitme_logo_s.jpg" id="logo" />
</div>

<div style="text-align:center;">
    <fb:tabs>
        <fb:tab-item href='index.php' title='Home' selected='false'/>
        <fb:tab-item href='invite.php' title='Invite Friends' selected='true'/>
	<fb:tab-item href='about.php' title='About' selected='false' />
    </fb:tabs>

    <fb:fbml>
        <fb:request-form
            action="index.php"
            method="POST"
            invite="true"
            type="VisitME"
            content="Social flight ticketing!{$link}"
            <fb:multi-friend-selector showborder="false" actiontext="Invite your friends to use VisitME." exclude_ids={$excludeFriendList}>>
        </fb:request-form>
    </fb:fbml>
</div>
