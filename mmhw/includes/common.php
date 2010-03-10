<?php

require_once("sqlfunctions.php");

/** Takes in an origin code and an array of destination codes
 *
 *	This function provides 1->N location fares
 */
function get_fares_code_to_city($origin_code, $dest_codes, $debug)
{
	// Create URL string
	$rssURL = 'http://www.kayak.com/h/rss/fare?dest=';
	for ($i = 0; $i < sizeof($dest_codes) - 1; $i++)
	{
		$rssURL = $rssURL.$dest_codes[$i].",";
	}
	$rssURL = $rssURL.$dest_codes[sizeof($dest_codes) - 1];
	$rssURL = $rssURL.'&code='.$origin_code;

	$rssURL = $rssURL.'&tm=201008';

	// Get RSS feed
	$rss	= fetch_rss($rssURL);

	if($debug)
	{
		//echo "<br/>Fares url: $rssURL<br/>";
		//print_r($rss);
		//echo "<br/><br/>";
	}

	return $rss;
}


?>