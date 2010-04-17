
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

   /***********************************************
    *                  MMHW Class
    ***********************************************/
    class MMHW
    {
        private $search = false;
        private $nearby = true; 
        private $radius = 0;
        private $debug = false;
        private $host_url = null;

        private $smarty_obj = null;
        private $print_obj = null;
        private $calendar_obj = null;

        public function __construct($host_url, $version, $radius, $debug)
        {
            $this->smarty_obj = new Smarty();
            $this->print_obj = new PrintRSS();
            $this->calendar_obj = new Calendar();

            $this->radius = $radius;
            $this->debug = $debug;
            $this->host_url = $host_url;

            $this->smarty_obj->assign('host_url', $host_url);
            $this->smarty_obj->assign('version', $version);
            $this->smarty_obj = $this->calendar_obj->travel_time($this->smarty_obj);
        }

        private function init()
        {
            $inputs = array();
            $location1_input = $_GET['loc1'];
            $location2_input = $_GET['loc2'];
            $month_input = $_GET['tmonth'];
            $year_input = $_GET['tyear'];
            $filter_opt_input = $_GET['filter'];

            if(($location1_input != null) && ($location2_input != null) && ($month_input != null) && ($year_input != null) && ($filter_opt_input != null))
            {
               $inputs = $this->validate_inputs($location1_input, $location2_input, $month_input, $year_input, $filter_opt_input);
            }

            return ($inputs);
        }

        private function validate_inputs($location1_input, $location2_input, $month_input, $year_input, $filter_opt_input)
        {
            // Form data
            $location1_input = mysql_real_escape_string($location1_input);
            $location2_input = mysql_real_escape_string($location2_input);
            $month_input = mysql_real_escape_string($month_input);
            $year_input = mysql_real_escape_string($year_input);
            $filter_opt_input = mysql_real_escape_string($filter_opt_input);

            // validate and parse airport codes, month, year, and filter options from input strings
            $util_obj = new Util();
            $location1_airport_code = $util_obj->val_n_parse_airport_code($location1_input);
            $location2_airport_code = $util_obj->val_n_parse_airport_code($location2_input);
            $travel_month = $util_obj->val_n_format_time_inputs($month_input, $year_input);
            $filter_opt = $util_obj->val_filter_opt($filter_opt_input);

            if($location1_airport_code == null)
            {
                $util_obj->add_err("Invalid airport 1: ".$location1_input);
            }
            if($location2_airport_code == null)
            {
                $util_obj->add_err("Invalid airport 2: ".$location2_input);
            }
            if($travel_month == null)
            {
                $util_obj->add_err("Invalid travel month/year: ".$month_input.'/'.$year_input);
            }
            if($filter_opt == null)
            {
                $util_obj->add_err("Invalid filter option: ".$filter_opt_input);
            }
            $util_obj->is_inputs_val_passed($this->host_url);

            return (array($location1_airport_code, $location2_airport_code, $travel_month, $filter_opt));
        }

        private function dynamic_search($location_1_lola, $location_2_lola, $midpoint_lola, $location1_airport_code, $location2_airport_code, $travel_month, $filter_opt)
        {
            $dynamic_search_obj = new DynamicSearch($location_1_lola,
                                                    $location_2_lola,
                                                    $midpoint_lola,
                                                    $location1_airport_code,
                                                    $location2_airport_code,
                                                    $travel_month,
                                                    $this->radius,
                                                    $filter_opt);

            if($dynamic_search_obj->found_meeting_location())
            {
                $this->smarty_obj = $this->print_obj->print_rss_item($this->smarty_obj, $dynamic_search_obj->get_loc1_to_mid_rss_item(), Constants::LOCATION_1_SYMBOL, Constants::LOCATION_MID_SYMBOL);
                $this->smarty_obj = $this->print_obj->print_rss_item($this->smarty_obj, $dynamic_search_obj->get_loc2_to_mid_rss_item(), Constants::LOCATION_2_SYMBOL, Constants::LOCATION_MID_SYMBOL);

                // Get lola of meeting point
                $new_dest_code_obj = new RSSItem($dynamic_search_obj->get_loc1_to_mid_rss_item());
                $meeting_point_lola = get_lola_airport($new_dest_code_obj->get_destcode());
                $meeting_point_obj = new Point($meeting_point_lola);

                $this->smarty_obj->assign('meeting_point_longitude',$meeting_point_obj->get_longitude());
                $this->smarty_obj->assign('meeting_point_latitude',$meeting_point_obj->get_latitude());
            }
            else
            {
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
                    $this->smarty_obj = $this->print_obj->print_rss_item($this->smarty_obj, $origin_to_dest_rss_item, Constants::LOCATION_A_SYMBOL, Constants::LOCATION_B_SYMBOL);
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
                    $this->smarty_obj = $this->print_obj->print_rss_item($this->smarty_obj, $origin_to_dest_rss_item, Constants::LOCATION_B_SYMBOL, Constants::LOCATION_A_SYMBOL);
                }
            }
        }

        public function run()
        {
            $data_array = $this->init();

            if(count($data_array) == Constants::NUM_OF_INPUTS - 1) // -1 because month and year are consolidated into one
            {
                $location1_airport_code = $data_array[0];
                $location2_airport_code = $data_array[1];
                $travel_month = $data_array[2];
                $filter_opt = $data_array[3];

                $location_1_lola = get_lola_airport($location1_airport_code);
                $location_2_lola = get_lola_airport($location2_airport_code);

                // If either origin or destination lola isn't available
                if (!(is_array($location_1_lola) && is_array($location_2_lola)))
                {
                    exit("Unable to find longitude/latitude of airport-1/airport-2");
                }

                $this->search = true;

                // Location 1 & 2
                $loc_1_obj = new Point($location_1_lola);
                $loc_2_obj = new Point($location_2_lola);

                $this->smarty_obj->assign('search', $this->search);
                $this->smarty_obj->assign('location_1_longitude', $loc_1_obj->get_longitude());
                $this->smarty_obj->assign('location_1_latitude', $loc_1_obj->get_latitude());
                $this->smarty_obj->assign('location_2_longitude', $loc_2_obj->get_longitude());
                $this->smarty_obj->assign('location_2_latitude', $loc_2_obj->get_latitude());

               /******************************************************************
                * Select midpoint with shorter distance
                ******************************************************************/
                $mid_sel_obj = new MidPointSelect($location_1_lola, $location_2_lola, $this->radius);
                $midpoint_lola = $mid_sel_obj->get_midpoint_lola();

                $this->smarty_obj->assign('midpoint_longitude',$mid_sel_obj->get_midpoint_longitude());
                $this->smarty_obj->assign('midpoint_latitude',$mid_sel_obj->get_midpoint_latitude());

               /******************************************************************
                * Distance between location 1 and location 2
                ******************************************************************/

                $dist_obj = new Distance($location_1_lola, $location_2_lola);
                $distance = $dist_obj->get_distance();

                if($this->debug)
                {
                    echo "<br />Distance between two locations: ".$distance."<br />";
                }

                $this->nearby = $dist_obj->is_nearby($this->radius);
                $this->smarty_obj->assign('nearby',$this->nearby);

                if (!$this->nearby)
                {
                   /******************************************************************
                    * Location 1 to mid point
                    ******************************************************************/

                    $dest_codes = $mid_sel_obj->get_midpoint_airport_codes();

                    $result_obj = new Result($location1_airport_code, $location2_airport_code, $dest_codes, $midpoint_lola, $travel_month, $filter_opt);
                    $loc1_to_mid_rss_item = $result_obj->get_loc1_to_mid_rss_item();

                    $this->smarty_obj = $this->print_obj->print_rss_item($this->smarty_obj, $loc1_to_mid_rss_item, Constants::LOCATION_1_SYMBOL, Constants::LOCATION_MID_SYMBOL);

                    $loc1_to_mid_obj = new RSSItem($loc1_to_mid_rss_item);
                    $flight1_cost = $loc1_to_mid_obj->get_price();

                  /******************************************************************
                   * Location 2 to mid point
                   ******************************************************************/
                   $loc2_to_mid_rss_item = $result_obj->get_loc2_to_mid_rss_item();

                   $this->smarty_obj = $this->print_obj->print_rss_item($this->smarty_obj, $loc2_to_mid_rss_item, Constants::LOCATION_2_SYMBOL, Constants::LOCATION_MID_SYMBOL);

                   $loc2_to_mid_obj = new RSSItem($loc2_to_mid_rss_item);
                   $flight2_cost = $loc2_to_mid_obj->get_price();

                   /***************** Meeting point lola *****************/
                   if(($flight1_cost!=NULL) && ($flight2_cost!=NULL))
                   {
                       $meeting_point_lola = get_lola_airport($loc2_to_mid_obj->get_destcode());
                       $meeting_point_obj = new Point($meeting_point_lola);
                       $this->smarty_obj->assign('meeting_point_longitude',$meeting_point_obj->get_longitude());
                       $this->smarty_obj->assign('meeting_point_latitude',$meeting_point_obj->get_latitude());
                   }

                   if(($flight1_cost==NULL) || ($flight2_cost==NULL))
                   {
                       /******************************************************************
                        * Dynamic Search:   Increase radius to search for flight info to
                        *                   common destination dynamically...
                        ******************************************************************/
                       $this->dynamic_search($location_1_lola, $location_2_lola, $midpoint_lola, $location1_airport_code, $location2_airport_code, $travel_month, $filter_opt);
                   }
               }
           }
           $this->smarty_obj->display('mmhw.tpl');
        }



 }

?>

