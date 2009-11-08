<?php
// Author: Surya Nyayapati
// email: surenrao@cs.umb.edu
require_once("config.php");

//odbc errors
//http://msdn.microsoft.com/library/default.asp?url=/library/en-us/trblsql/tr_err_odbc_5stz.asp

	if(DATABASE == "mysql")
	{
		$db_con = mysql_connect($db_host, $db_user, $db_pass) or error_log(date("F j, Y, g:i a") ."\n". $_SERVER["PATH_INFO"] ."\n\t" .  mysql_error() ."\n\n",3,"sql_error.log");// and die("Not connected : " . mysql_error());
		mysql_select_db($db_db,$db_con) or error_log(date("F j, Y, g:i a") ."\n". $_SERVER["PATH_INFO"] ."\n\t" .  mysql_error() ."\n\n",3,"sql_error.log");// and die("Database not Selected  " . mysql_error());

		//To also use the LDAP database set "$LDAP = true" before including sqlfunctions.inc
		if(@$LDAP)
		{
			$db_con2 = mysql_connect($db_host2, $db_user2, $db_pass2) or die("Not connected : " . mysql_error());
			mysql_select_db($db_db2,$db_con2) or die("Database not Selected  " . mysql_error());
		}

	}
	else if(DATABASE == "odbc")
	{
		$db_con = @odbc_connect($db_host, $db_user, $db_pass) or error_log(date("F j, Y, g:i a") ."\n". $_SERVER["PATH_INFO"] ."\n\t" .  @odbc_error() ." Could Not Connect to ODBC Database!\n\n",3,"sql_error.log") and die("Could Not Connect to ODBC Database!");
	}

	function sql_result($query,$conn=NULL)
	{
		if(DATABASE == "mysql")
		{
			if(is_null($conn))
				$qryRslt = @mysql_query($query,$GLOBALS["db_con"]);
			else
				$qryRslt = @mysql_query($query,$conn);

			if(!$qryRslt)
			{
				echo $query;
			
				error_log(date("F j, Y, g:i a") ."\t$query\n". $_SERVER["PATH_INFO"] ."\n\t" .  @mysql_error() ."\n\n",3,"sql_error.log");
				die("<br><font color=\"red\">Due to some technical problem, your Transaction was <b>NOT</b> successful</font>\n");
			}
			return $qryRslt;
		}
		else if(DATABASE == "odbc")
		{
			if(is_null($conn))
				$qryRslt = @odbc_exec($GLOBALS["db_con"],$query);
			else
				$qryRslt = @odbc_exec($conn,$query);

			if(!$qryRslt)
			{
				error_log(date("F j, Y, g:i a") ."\t$query\n". $_SERVER["PATH_INFO"] ."\n\t" .  @odbc_error() ."\n\n",3,"sql_error.log");
				die("<br><font color=\"red\">Due to some technical problem, your Transaction was <b>NOT</b> successful</font>\n");
			}
			return $qryRslt;
		}
	}

	function sql_fetch_obj($result)
	{
		if(DATABASE == "mysql")
		{
			return @mysql_fetch_object($result);
		}
		else if(DATABASE == "odbc")
		{
			return @odbc_fetch_object($result);
		}
	}
	function sql_fetch_array($result)
	{
		if(DATABASE == "mysql")
		{
			return @mysql_fetch_array($result);
		}
		else if(DATABASE == "odbc")
		{
			return @odbc_fetch_array($result);
		}
	}
	
	
	function Filter($RecordSet,$FilterField,$FilterCriteria)
	{
		$ReturnArray = array();
		while($row = mysql_fetch_assoc($RecordSet)){
			foreach($row as $key => $value){				
				if(trim($key)==trim($FilterField)&&trim($value)==trim($FilterCriteria)){
					array_push($ReturnArray,$row);
				}
        }
    }
return $ReturnArray;
}
	
	
	
?>