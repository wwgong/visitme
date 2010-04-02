
<?php
   /***********************************************************************************************
    *    Project Name: Meet Me Halfway
    *    Project website: http://code.google.com/p/visitme/
    ***********************************************************************************************/
    require_once('includes/common.php');
    require_once('includes/midpoint.php');
    require_once('includes/point.php');
    require_once('includes/distance.php');
    require_once('includes/util.php');
    require_once('includes/result.php');
    require_once('includes/rssparser.php');

    $smarty = new Smarty();
    $smarty->assign('host_url', $host_url);
    $smarty->assign('version', $version);

    $location1_input = $_GET['loc1'];
    $location2_input = $_GET['loc2'];
    $travel_month_input = $_GET['tm'];

    $search = false;
    $nearby = true; // By default
    $travel_month = null;

    if(($location1_input != NULL) && ($location2_input != NULL) && ($travel_month_input != NULL))
    {
        //Form data
        $location1_input = mysql_real_escape_string($location1_input);
        $location2_input = mysql_real_escape_string($location2_input);
        $travel_month_input = mysql_real_escape_string($travel_month_input);

        $util_obj = new Util();
        $location1_airport_code = $util_obj->parse_airport_code($location1_input);
        $location2_airport_code = $util_obj->parse_airport_code($location2_input);
        $travel_month = $util_obj->format_travel_month_input($travel_month_input);

        if($debug)
        {
            echo "<br />Location1 code: ".$location1_airport_code."; Location2 code: ".$location2_airport_code."; Travel month: ".$travel_month_input."<br />";
        }

        $location_1_lola = get_lola_airport($location1_airport_code);
	$location_2_lola = get_lola_airport($location2_airport_code);

        // If either origin or destination lola isn't available
        if (!(is_array($location_1_lola) && is_array($location_2_lola)))
        {
            die();
        }

        $search = true;

        // Location 1 & 2
        $loc_1_obj = new Point($location_1_lola);
        $loc_2_obj = new Point($location_2_lola);

        $smarty->assign('search', $search);
        $smarty->assign('location_1_longitude', $loc_1_obj->get_longitude());
        $smarty->assign('location_1_latitude', $loc_1_obj->get_latitude());
        $smarty->assign('location_2_longitude', $loc_2_obj->get_longitude());
        $smarty->assign('location_2_latitude', $loc_2_obj->get_latitude());

        // Mid 1 & 2
        $mid_1_obj = new MidPoint_1($location_1_lola, $location_2_lola);
        $mid_2_obj = new MidPoint_2($location_1_lola, $location_2_lola);

        $smarty->assign('mid_1_longitude', $mid_1_obj->get_longitude());
        $smarty->assign('mid_1_latitude', $mid_1_obj->get_latitude());
        $smarty->assign('mid_2_longitude', $mid_2_obj->get_longitude());
        $smarty->assign('mid_2_latitude', $mid_2_obj->get_latitude());

       /******************************************************************
        * Distance between location 1 and location 2
        ******************************************************************/

        $dist_obj = new Distance($location_1_lola, $location_2_lola);
        $distance = $dist_obj->get_distance();

        if($debug)
        {
            echo "<br />Distance between two locations: ".$distance."<br />";
        }

        $nearby = $dist_obj->is_nearby($radius);
        $smarty->assign('nearby',$nearby);

        if (!$nearby)
	{
            $mid_sel_obj = new MidPointSelect($location_1_lola, $location_2_lola, $radius);

            /******************************************************************
             * Location 1 to mid point
             ******************************************************************/

            $dest_codes = $mid_sel_obj->get_midpoint_airport_codes();

            $result_obj = new Result($location1_airport_code, $location2_airport_code, $dest_codes, $travel_month);
            $loc1_to_mid_rss_item = $result_obj->get_loc1_to_mid_rss_item();
            $loc1_to_mid_obj = new MagpieRSS_Item($loc1_to_mid_rss_item);

            if($debug)
            {
                echo "<br />Location 1 Code: ";
                print_r($location1_airport_code);
                 echo "<br />Location 2 Code: ";
                print_r($location2_airport_code);
                echo "<br />Dest Codes: ";
                print_r($dest_codes);
            }

            $midpoint_lola = $mid_sel_obj->get_midpoint_lola();

            $smarty->assign('midpoint_longitude',$mid_sel_obj->get_midpoint_longitude());
	    $smarty->assign('midpoint_latitude',$mid_sel_obj->get_midpoint_latitude());

	    $smarty->assign('location_1',$loc1_to_mid_obj->get_originlocation());
	    $smarty->assign('location1AirportCode',$loc1_to_mid_obj->get_origincode());
            $smarty->assign('location_mid_1',$loc1_to_mid_obj->get_destlocation());
            $smarty->assign('mid1AirportCode',$loc1_to_mid_obj->get_destcode());

            $smarty->assign('flight1_cost',$loc1_to_mid_obj->get_price());
            $smarty->assign('flight1_departure',100);
            $smarty->assign('flight1_arrival',100);
            $smarty->assign('flight1_airline',$loc1_to_mid_obj->get_airline());
            $smarty->assign('flight1_description',$loc1_to_mid_obj->get_description());
            $smarty->assign('flight1_buzz',$loc1_to_mid_obj->get_guid());


          /******************************************************************
           * Location 2 to mid point
           ******************************************************************/
           $loc2_to_mid_rss_item = $result_obj->get_loc2_to_mid_rss_item();
           $loc2_to_mid_obj = new MagpieRSS_Item($loc2_to_mid_rss_item);

           $smarty->assign('location_2',$loc2_to_mid_obj->get_originlocation());
           $smarty->assign('location2AirportCode',$loc2_to_mid_obj->get_origincode());
           $smarty->assign('location_mid_2',$loc2_to_mid_obj->get_destlocation());
           $smarty->assign('mid2AirportCode',$loc2_to_mid_obj->get_destcode());

           $smarty->assign('flight2_cost',$loc2_to_mid_obj->get_price());
           $smarty->assign('flight2_departure',100);
           $smarty->assign('flight2_arrival',100);
           $smarty->assign('flight2_airline',$loc2_to_mid_obj->get_airline());
           $smarty->assign('flight2_description',$loc2_to_mid_obj->get_description());
           $smarty->assign('flight2_buzz',$loc2_to_mid_obj->get_guid());

           if(($flight1_cost==NULL) || ($flight2_cost==NULL))
           {
               /******************************************************************
                * Location 1 to Location 2
                ******************************************************************/
                $orig_code = $location1_airport_code;
                $dest_code = $location2_airport_code;

                $result_obj = new Result($orig_code, $dest_code, $travel_month);
                $origin_to_dest_rss_item = $result_obj->get_orig_to_dest_rss_item();
                $origin_to_dest_obj = new MagpieRSS_Item($origin_to_dest_rss_item);

                if($debug)
                {
                    echo "<br />Origin Codes: ";
                    print_r($orig_code);
                    echo "<br />Dest Codes: ";
                    print_r($dest_code);
                }

                $flightA_cost = $origin_to_dest_obj->get_price();
                if($flightA_cost != NULL)
                {
                    $smarty->assign('location2AirportCode',$origin_to_dest_obj->get_destcode());
                    $smarty->assign('location_1',$origin_to_dest_obj->get_originlocation());
                    $smarty->assign('location_2',$origin_to_dest_obj->get_destlocation());
                    $smarty->assign('location1AirportCode',$origin_to_dest_obj->get_origincode());

                    $smarty->assign('flightA_cost',$flightA_cost);
                    $smarty->assign('flightA_departure',100);
                    $smarty->assign('flightA_arrival',100);
                    $smarty->assign('flightA_airline',$origin_to_dest_obj->get_airline());
                    $smarty->assign('flightA_description',$origin_to_dest_obj->get_description());
                    $smarty->assign('flightA_buzz',$origin_to_dest_obj->get_guid());
                }

              /******************************************************************
               * Location 2 to Location 1
               ******************************************************************/
               $orig_code = $location2_airport_code;
               $dest_code = $location1_airport_code;

               $result_obj = new Result($orig_code, $dest_code, $travel_month);
               $origin_to_dest_rss_item = $result_obj->get_orig_to_dest_rss_item();
               $origin_to_dest_obj = new MagpieRSS_Item($origin_to_dest_rss_item);

               if($debug)
               {
                    echo "<br /><hr />";
                    echo "<br />Origin Codes: ";
                    print_r($orig_code);
                    echo "<br />Dest Codes: ";
                    print_r($dest_code);
               }

               $flightB_cost = $origin_to_dest_obj->get_price();
               if($flightB_cost != NULL)
               {
                   $smarty->assign('location1AirportCode',$origin_to_dest_obj->get_destcode());
                   $smarty->assign('location_2',$origin_to_dest_obj->get_originlocation());
                   $smarty->assign('location_1',$origin_to_dest_obj->get_destlocation());
                   $smarty->assign('location2AirportCode',$origin_to_dest_obj->get_origincode());

                   $smarty->assign('flightB_cost',$flightB_cost);
                   $smarty->assign('flightB_departure',100);
                   $smarty->assign('flightB_arrival',100);
                   $smarty->assign('flightB_airline',$origin_to_dest_obj->get_airline());
                   $smarty->assign('flightB_description',$origin_to_dest_obj->get_description());
                   $smarty->assign('flightB_buzz',$origin_to_dest_obj->get_guid());
               }
           }
       }
       else
       {
           $smarty->assign('midpoint_longitude',$mid_1_obj->get_longitude());
           $smarty->assign('midpoint_latitude',$mid_1_obj->get_latitude());
       }
    }

    $smarty->display('mmhw.tpl');

?>

