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
    require_once("sqlfunctions.php");
    require_once("rss.php");
    require_once("distance.php");
    require_once("constants.php");
    
   /***********************************************
    *              Result Class
    ***********************************************/
    class Result
    {
        private $orig_to_dest_rss_item = NULL;
        private $two_points_to_mid_rss_item = NULL;
        private $loc1_to_mid_rss_item = NULL;
        private $loc2_to_mid_rss_item = NULL;

        public function __construct()
        {  
            $num = func_num_args();
            $args = func_get_args();
            switch($num)
            {
                case 3: // 3 args constructor
                    $this->__call('__construct0', $args);
                    break;
                case 6: // 6 args constructor
                    $this->__call('__construct1', $args);
                    break;
                default:
                    throw new Exception();
            }
        }

        private function __call($name, $arg)
        {
            return (call_user_func_array(array($this,$name), $arg));
        }
        
        private function __construct0($origin_airport_code, $dest_airport_code, $travel_month)
        {
            $this->orig_to_dest_rss_item = $this->orig_to_dest($origin_airport_code, $dest_airport_code, $travel_month);
        }

        private function __construct1($loc1_airport_code, $loc2_airport_code, $mid_airport_codes, $mid_lola, $travel_month, $filter_opt)
        {   
            $this->two_points_to_mid_rss_item = $this->orig_to_dest_3pointscheck($loc1_airport_code, $loc2_airport_code, $mid_airport_codes, $mid_lola, $travel_month, $filter_opt);
                        
            $this->loc1_to_mid_rss_item = $this->two_points_to_mid_rss_item[0];
            $this->loc2_to_mid_rss_item = $this->two_points_to_mid_rss_item[1];
        }

        public function get_orig_to_dest_rss_item()
        {
            return ($this->orig_to_dest_rss_item);
        }

        public function get_loc1_to_mid_rss_item()
        {
            return ($this->loc1_to_mid_rss_item);
        }

        public function get_loc2_to_mid_rss_item()
        {
            return ($this->loc2_to_mid_rss_item);
        }

        private function orig_to_dest($orig_code, $dest_code, $travel_month)
        {
	    $rss = null;
            if (($orig_code != NULL) && ($dest_code != NULL) && ($travel_month != NULL))
            {   
                $rss = get_fares_code_to_city($orig_code, $dest_code, $travel_month);
                $rss_obj = new RSSItemParser($rss);
                $rss_item = $rss_obj->get_item(0);
            }
            return ($rss_item);
        }

        private function orig_to_dest_3pointscheck($loc1_code, $loc2_code, $mid_codes, $mid_lola, $travel_month, $filter_opt)
        {
            $fares = null;

            if (($loc1_code != NULL) && ($loc2_code != NULL) && ($mid_codes != NULL) && ($travel_month != NULL))
            {   
                $rss_1 = get_fares_code_to_city($loc1_code, $mid_codes, $travel_month);
                $rss_2 = get_fares_code_to_city($loc2_code, $mid_codes, $travel_month);
                
                $rss_1_obj = new RSSItemParser($rss_1);
                $rss_1_items = $rss_1_obj->get_all_items();
                $rss_2_obj = new RSSItemParser($rss_2);
                $rss_2_items = $rss_2_obj->get_all_items();
                     
                foreach($rss_1_items as $rss_1_item)
                {
                    foreach($rss_2_items as $rss_2_item)
                    {
                        $rss_1_item_obj = new RSSItem($rss_1_item);
                        $rss_2_item_obj = new RSSItem($rss_2_item);

                        if($rss_1_item_obj->get_destcode() == $rss_2_item_obj->get_destcode())
                        {   
                            // Initially empty
                            if($fares == null)
                            {
                                $fares = array($rss_1_item, $rss_2_item);
                            }
                            // Find lowest combined fares
                            else
                            {
                                $fares = $this->filter($fares[0], $fares[1], $rss_1_item, $rss_2_item, $mid_lola, $filter_opt);
                            }
                        }

                    }
                }
            }
            return ($fares);
        }

        private function filter($curr_rss_item_1, $curr_rss_item_2, $new_rss_item_1, $new_rss_item_2, $mid_lola, $filter_opt)
        {
            $rss_items = null;
            $curr_obj_1 = new RSSItem($curr_rss_item_1);
            $curr_obj_2 = new RSSItem($curr_rss_item_2);

            $new_obj_1 = new RSSItem($new_rss_item_1);
            $new_obj_2 = new RSSItem($new_rss_item_2);

            $const_obj = new Constants();

            switch($filter_opt)
            {
                case $const_obj->get_filter('BEST_MATCHED_DATES_FILTER'):
                    $rss_items_pair = $this->find_best_matched_dates($curr_rss_item_1, $curr_rss_item_2, $new_rss_item_1, $new_rss_item_2);
                    if(($new_rss_item_1 == $rss_items_pair[0]) && ($new_rss_item_2 == $rss_items_pair[1]))
                    {
                        $rss_items = array($new_rss_item_1, $new_rss_item_2);
                    }
                    else
                    {
                        $rss_items = array($curr_rss_item_1, $curr_rss_item_2);
                    }
                    break;

                case $const_obj->get_filter('LOWEST_COMBINED_FARES_FILTER'):
                    $curr_total_lowest_fares = $curr_obj_1->get_price() + $curr_obj_2->get_price();
                    $new_total = $new_obj_1->get_price() + $new_obj_2->get_price();
                    if($new_total < $curr_total_lowest_fares)
                    {
                        $rss_items = array($new_rss_item_1, $new_rss_item_2);
                    }
                    else
                    {
                        $rss_items = array($curr_rss_item_1, $curr_rss_item_2);
                    }
                    break;

                case $const_obj->get_filter('CLOSET_TO_MIDPOINT_FILTER'):
                    
                    $new_dest_lola = get_lola_airport($new_obj_1->get_destcode());
                    $new_dist_obj = new Distance($new_dest_lola, $mid_lola);
                    $new_dist = $new_dist_obj->get_distance();

                    $curr_dest_lola = get_lola_airport($curr_obj_1->get_destcode());
                    $curr_dist_obj = new Distance($curr_dest_lola, $mid_lola);
                    $curr_dist = $curr_dist_obj->get_distance();
                   
                    if($new_dist < $curr_dist)
                    {
                        $rss_items = array($new_rss_item_1, $new_rss_item_2);
                    }
                    else
                    {
                        $rss_items = array($curr_rss_item_1, $curr_rss_item_2);
                    }
                    break;

                default:
                    $rss_items = array($curr_rss_item_1, $curr_rss_item_2);
            }

            return ($rss_items);
        }

        private function find_best_matched_dates($curr_rss_item_1, $curr_rss_item_2, $new_rss_item_1, $new_rss_item_2)
        {
            $curr_overlapped_days = $this->get_num_of_overlapped_days($curr_rss_item_1, $curr_rss_item_2);
            $new_overlapped_days = $this->get_num_of_overlapped_days($new_rss_item_1, $new_rss_item_2);
            
            if($curr_overlapped_days < $new_overlapped_days)
            {
                return (array($new_rss_item_1, $new_rss_item_2));
            }
            else
            {
                return (array($curr_rss_item_1, $curr_rss_item_2));
            }
        }

        private function get_num_of_overlapped_days($rss_item_1, $rss_item_2)
        {
            $overlapped_days = 0; //

            $obj1 = new RSSItem($rss_item_1);
            $obj2 = new RSSItem($rss_item_2);

            $depart1 = strtotime($obj1->get_departdate());
            $return1 = strtotime($obj1->get_returndate());
            $timespan1 = (($return1 - $depart1) / (60 * 60 * 24)) + 1; // +1 to include today

            $depart2 = strtotime($obj2->get_departdate());
            $return2 = strtotime($obj2->get_returndate());
            $timespan2 = (($return2 - $depart2) / (60 * 60 * 24)) + 1; // +1 to include today

            $depart_diff = ($depart1 - $depart2) / (60 * 60 * 24);
            if($depart_diff < 0) // if negative diff value, depart1 first...
            {
                $depart_diff = $depart_diff * (-1);
                $overlapped_days = $timespan1 - $depart_diff;
                if($overlapped_days <= 0)
                {
                    $overlapped_days = 0;
                }
                else if($overlapped_days > $timespan2)
                {
                    $overlapped_days = $timespan2;
                }
                else
                {
                    $overlapped_days = $overlapped_days;
                }
            }
            else // if positive diff value, depart2 first...
            {
                $overlapped_days = $timespan2 - $depart_diff;
                if($overlapped_days <= 0)
                {
                    $overlapped_days = 0;
                }
                else if($overlapped_days > $timespan1)
                {
                    $overlapped_days = $timespan1;
                }
                else
                {
                    $overlapped_days = $overlapped_days;
                }
            }
             return ($overlapped_days);
        }
    }
?>
