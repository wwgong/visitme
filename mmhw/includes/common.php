<?php

require_once("sqlfunctions.php");

/** Takes in an origin code and an array of destination codes
 *
 *	This function provides 1->N location fares
 */
function get_fares_code_to_city($origin_code, $dest_codes, $travel_month)
{
    // Create URL string
    $rssURL = 'http://www.kayak.com/h/rss/fare?dest=';

    if(sizeof($dest_codes) == 1)
    {
        $rssURL = $rssURL.$dest_codes;
    }
    else
    {
        for ($i = 0; $i < sizeof($dest_codes); $i++)
	{
            $rssURL = $rssURL.$dest_codes[$i].",";
	}
        $rssURL = substr($rssURL, 0, -1);
    }
   
    $rssURL = $rssURL.'&code='.$origin_code;

    if($travel_month != NULL)
    {
        $rssURL = $rssURL.'&tm='.$travel_month;
    }
  
    // Get RSS feed
    $rss = fetch_rss($rssURL);

    if($debug)
    {
	echo "<br/>Fares url: $rssURL<br/>";
	print_r($rss);
	echo "<br/><br/>";
    }

    return $rss;
}

?>