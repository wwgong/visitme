<?php
/*
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
*/

// Authors: H. Volckmer, V. Gadge, B. Moy, W. Gong, C. Pham, K. Nguyen
// Site: http://code.google.com/p/visitme

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

	if ($_POST['apptab_location'])
	{
		echo "Hey, you finally found us! - Visit Me Team <br/><br/>H. Volckmer, V. Gadge, B. Moy, W. Gong, C. Pham, K. Nguyen";
		die();
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
		while($row = mysql_fetch_assoc($RecordSet))
		{
			foreach($row as $key => $value)
			{				
				if(trim($key)==trim($FilterField)&&trim($value)==trim($FilterCriteria))
				{
					array_push($ReturnArray,$row);
				}
			}
		}
		return $ReturnArray;
	}
	
	function get_geocode_xml($location)
	{
		$url = "http://maps.google.com/maps/geo?q=".$location."&output=xml";
		$url = str_replace(" ","%20",$url);
		//echo "$url<br/>";
		
		// create a new cURL resource
		$ch = curl_init();

		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// grab URL and pass it to the browser
		curl_exec($ch);
		$output = curl_multi_getcontent($ch);
		// close cURL resource, and free up system resources
		curl_close($ch);
		
		$rss	= fetch_rss($url);
		//print_r($rss);
		
		return $output;
	}
	
	function get_lola_xml($location)
	{
		$xml = get_geocode_xml($location);

		//echo "<br/>Output:".strstr($output,"coordinates")."<br/>";
		
		// Get x, y of city
		$x = 0;
		$y = 0;
		
		$output = strstr($output,"coordinates");
		$output = substr(strstr($output,"["),2);
		$x = substr($output, 0, strpos($output, ","));
		
		$y = substr(strstr($output,", "),2);
		$y = substr($y, 0, strpos($y, ","));
		
		return array($x, $y);
	}
	
	// Takes a location string, returns x and y for that location
	function get_lola($location)
	{
		$url = "http://maps.google.com/maps/geo?q=".$location;
		$url = str_replace(" ","%20",$url);
		//echo "$url<br/>";
		
		// create a new cURL resource
		$ch = curl_init();

		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// grab URL and pass it to the browser
		curl_exec($ch);
		$output = curl_multi_getcontent($ch);
		// close cURL resource, and free up system resources
		curl_close($ch);

		//echo "<br/>Output:".strstr($output,"coordinates")."<br/>";
		
		// Get x, y of city
		$x = 0;
		$y = 0;
		$city = "";
		$state = "";
		$country = "";
		
		$l_output = $output;
		$l_output = substr(strstr($l_output,"address\": \""),11);
		$l_output = substr($l_output,0,strpos($l_output, "\""));
		
		$commas = substr_count($l_output,",");
		if ($commas == 0)
		{
			// e.g. Hong Kong
			$city = $l_output;
			
			$l_output = $output;
			$l_output = substr(strstr($l_output,"CountryName\" : \""),16);
			$l_output = substr($l_output,0,strpos($l_output, "\""));
			
			$country = $l_output;
			
			// Exceptions
			if ($country == "Hong Kong")
			{
				$country = "China";
			}
		}
		else if ($commas == 1)
		{
			// e.g. Beijing, China
			$city = substr($l_output,0,strpos($l_output, ","));
			$country = substr($l_output,strlen($city) + 2);
		}
		else
		{
			// e.g. Boston, MA, USA
			$city = substr($l_output,0,strpos($l_output, ","));
			$l_output = substr($l_output,strlen($city) + 2);
			$state = substr($l_output,0,strpos($l_output, ","));
			$country = substr($l_output,strlen($state) + 2);
			
			// Exceptions
			if ($country == "USA")
			{
				$country = "United States";
			}
		}
		
		$c_output = $output;
		$c_output = strstr($c_output,"coordinates");
		$c_output = substr(strstr($c_output,"["),2);
		$x = substr($c_output, 0, strpos($c_output, ","));
		
		$y = substr(strstr($c_output,", "),2);
		$y = substr($y, 0, strpos($y, ","));
		
		return array($x, $y, $city, $state, $country);
	}
	
	function get_airport_codes($lola, $radius = 1)
	{		
		//if ($state != "")
		//{
		//	$state = " and state = '".$state."' ";
		//}
		//echo "X:$x Y:$y<br/>";
		
		// SELECT a.code FROM airports a, country c WHERE a.country = c.code and c.name = 'India' and x between 71.8692711 and 73.8692711 and y between 18.1130192 and 20.1130192 
		if ($lola[4] != "")
		{
			$sql = "SELECT a.code FROM airports a, country c WHERE a.country = c.code and c.name = '".$lola[4].
					"' and x != 0 and y != 0 and x between ".($lola[0] - $radius)." and ".($lola[0] + $radius)." and y between ".($lola[1] - $radius)." and ".($lola[1] + $radius);
		}
		else
		{
			$sql = "SELECT a.code FROM airports a".
					" WHERE x != 0 and y != 0 and x between ".($lola[0] - $radius)." and ".($lola[0] + $radius)." and y between ".($lola[1] - $radius)." and ".($lola[1] + $radius);
		}
		//echo "<br/>X: $x Y: $y<br/>SQL: $sql<br/>";
		
		$result	= sql_result($sql);
	
		$count = 0;
		$codes = array();
		while ($dest = sql_fetch_obj($result))
		{
			$codes[$count] = $dest->code;
			$count++;
		}
		
		//print_r($codes);
		
		return $codes;
	}
	
	function get_state_code($state)
	{
		$sql = "SELECT code FROM states WHERE state = '".$state."'";
		$result = sql_result($sql);
		
		return sql_fetch_obj($result)->code;
	}
	
	function get_country_code($country)
	{
		$sql = "SELECT code FROM country WHERE name = '".$country."'";
		$result = sql_result($sql);
		
		return sql_fetch_obj($result)->code;
	}
?>