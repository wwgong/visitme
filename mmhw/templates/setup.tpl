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

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>VisitME</title>
	<link rel="stylesheet" type="text/css" href="{$host_url}/style/setup.css" />
</head>
<body id="loginBg">
     {if $verify_credential}
	<div class="blockCenter textCenter" id="loginPos">
            <img src="images/visitme_logo.jpg" alt="VisitME Logo" />
            <br /><br />
            <div class="bold">DATABASE SETUP</div>
            <br />
            <form name="credential" action="setup.php" method="post">
                <table cellpadding="2" border="0" cellspacing="2"  class="blockCenter">
                    <tr>
                        <td align="right" class="bold">DB Username:</td>
                        <td><input type="text" name="user" class="login" /></td>
                    </tr>

                    <tr>
                        <td align="right" class="bold">DB Password:</td>
                        <td><input type="password" name="pwd" class="login" /></td>
                    </tr>
                </table>
                <br /><br />

                <div>
                     <input type="submit" value="Submit" name="submit"  />
                </div>
            </form>
	</div>
      {/if}
</body>
</html>
