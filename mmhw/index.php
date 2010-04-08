
<?php
/*
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
*/

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
    require_once('includes/rss.php');
    require_once('includes/dynamicsearch.php');
    require_once('includes/print.php');
    require_once('includes/calendar.php');

    $smarty = new Smarty();
    $smarty->assign('host_url', $host_url);
    $smarty->assign('version', $version);

    $location1_input = $_GET['loc1'];
    $location2_input = $_GET['loc2'];
    $month_input = $_GET['tmonth'];
    $year_input = $_GET['tyear'];

    $search = false;
    $nearby = true; // By default
    $travel_month = null;

    // Symbols used for distinguishing Smarty variables
    $location_1_symbol = '1';
    $location_2_symbol = '2';
    $location_mid_symbol = 'mid';
    $location_A_symbol = 'A';
    $location_B_symbol = 'B';
    
    $print_obj = new PrintRSS();
    $calendar_obj = new Calendar();
    $smarty = $calendar_obj->travel_time($smarty);

    if(($location1_input != NULL) && ($location2_input != NULL) && ($month_input != NULL) && ($year_input != NULL))
    {
        // Form data
        $location1_input = mysql_real_escape_string($location1_input);
        $location2_input = mysql_real_escape_string($location2_input);
        $month_input = mysql_real_escape_string($month_input);
        $year_input = mysql_real_escape_string($year_input);

        // validate and parse airport codes, month, and year from input strings
        $util_obj = new Util();
        $location1_airport_code = $util_obj->val_n_parse_airport_code($location1_input);
        $location2_airport_code = $util_obj->val_n_parse_airport_code($location2_input);
        $travel_month = $util_obj->val_n_format_time_inputs($month_input, $year_input);

        // Prompt error pop-up message
        if($location1_airport_code == null)
        {
            exit("Invalid airport 1: ".$location1_input);
        }
        if($location2_airport_code == null)
        {
             exit("Invalid airport 2: ".$location2_input);
        }
        if($travel_month == null)
        {
            exit("Invalid travel month/year: ".$month_input.'/'.$year_input);
        }
        
        $location_1_lola = get_lola_airport($location1_airport_code);
	$location_2_lola = get_lola_airport($location2_airport_code);

        // If either origin or destination lola isn't available
        if (!(is_array($location_1_lola) && is_array($location_2_lola)))
        {
            exit("Unable to find longitude/latitude of airport-1/airport-2");
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
            $midpoint_lola = $mid_sel_obj->get_midpoint_lola();

            $smarty->assign('midpoint_longitude',$mid_sel_obj->get_midpoint_longitude());
	    $smarty->assign('midpoint_latitude',$mid_sel_obj->get_midpoint_latitude());

           /******************************************************************
            * Location 1 to mid point
            ******************************************************************/

            $dest_codes = $mid_sel_obj->get_midpoint_airport_codes();

            $result_obj = new Result($location1_airport_code, $location2_airport_code, $dest_codes, $travel_month);
            $loc1_to_mid_rss_item = $result_obj->get_loc1_to_mid_rss_item();

            $smarty = $print_obj->print_rss_item($smarty, $loc1_to_mid_rss_item, $location_1_symbol, $location_mid_symbol);

            $loc1_to_mid_obj = new RSSItem($loc1_to_mid_rss_item);
            $flight1_cost = $loc1_to_mid_obj->get_price();

          /******************************************************************
           * Location 2 to mid point
           ******************************************************************/
           $loc2_to_mid_rss_item = $result_obj->get_loc2_to_mid_rss_item();

           $smarty = $print_obj->print_rss_item($smarty, $loc2_to_mid_rss_item, $location_2_symbol, $location_mid_symbol);

           $loc2_to_mid_obj = new RSSItem($loc2_to_mid_rss_item);
           $flight2_cost = $loc2_to_mid_obj->get_price();

           /***************** Meeting point lola *****************/
           if(($flight1_cost!=NULL) && ($flight2_cost!=NULL))
           {
               $meeting_point_lola = get_lola_airport($loc2_to_mid_obj->get_destcode());
               $meeting_point_obj = new Point($meeting_point_lola);
               $smarty->assign('meeting_point_longitude',$meeting_point_obj->get_longitude());
               $smarty->assign('meeting_point_latitude',$meeting_point_obj->get_latitude());
           }

           if(($flight1_cost==NULL) || ($flight2_cost==NULL))
           {
               // Increase radius to search for flight info to common destination dynamically...
               $dynamic_search_obj = new DynamicSearch($location_1_lola, 
                                                       $location_2_lola,
                                                       $midpoint_lola,
                                                       $location1_airport_code,
                                                       $location2_airport_code,
                                                       $mid_sel_obj->get_midpoint_airport_codes(),
                                                       $travel_month,
                                                       $radius);

               if($dynamic_search_obj->found_meeting_location())
               {
                    $smarty = $print_obj->print_rss_item($smarty, $dynamic_search_obj->get_loc1_to_mid_rss_item(), $location_1_symbol, $location_mid_symbol);
                    $smarty = $print_obj->print_rss_item($smarty, $dynamic_search_obj->get_loc2_to_mid_rss_item(), $location_2_symbol, $location_mid_symbol);

                    // Get lola of meeting point
                    $new_dest_code_obj = new RSSItem($dynamic_search_obj->get_loc1_to_mid_rss_item());
                    $meeting_point_lola = get_lola_airport($new_dest_code_obj->get_destcode());
                    $meeting_point_obj = new Point($meeting_point_lola);
                    
                    $smarty->assign('meeting_point_longitude',$meeting_point_obj->get_longitude());
                    $smarty->assign('meeting_point_latitude',$meeting_point_obj->get_latitude());
               }
               
               /******************************************************************
                * Location 1 to Location 2
                ******************************************************************/
                $orig_code = $location1_airport_code;
                $dest_code = $location2_airport_code;

                $result_obj = new Result($orig_code, $dest_code, $travel_month);
                $origin_to_dest_rss_item = $result_obj->get_orig_to_dest_rss_item();
                
                $origin_to_dest_obj = new RSSItem($origin_to_dest_rss_item);
                $flightA_cost = $origin_to_dest_obj->get_price();
          
                if($flightA_cost != NULL)
                {
                    $smarty = $print_obj->print_rss_item($smarty, $origin_to_dest_rss_item, $location_A_symbol, $location_B_symbol);
                }

              /******************************************************************
               * Location 2 to Location 1
               ******************************************************************/
               $orig_code = $location2_airport_code;
               $dest_code = $location1_airport_code;

               $result_obj = new Result($orig_code, $dest_code, $travel_month);
               $origin_to_dest_rss_item = $result_obj->get_orig_to_dest_rss_item();

               $origin_to_dest_obj = new RSSItem($origin_to_dest_rss_item);
               $flightB_cost = $origin_to_dest_obj->get_price();
               if($flightB_cost != NULL)
               {
                   $smarty = $print_obj->print_rss_item($smarty, $origin_to_dest_rss_item, $location_B_symbol, $location_A_symbol);
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

