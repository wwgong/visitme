<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>VisitME</title>
	<link rel="stylesheet" type="text/css" href="{$host_url}/style/setup.css" />
</head>
<body id="loginBg">
	<div class="blockCenter textCenter" id="loginPos">
          {if $prompt_input}
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
         {/if}
	</div>
</body>
</html>
