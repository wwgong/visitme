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
                case 4: // 4 args constructor
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

        private function __construct1($loc1_airport_code, $loc2_airport_code, $mid_airport_codes, $travel_month)
        { 
            $this->two_points_to_mid_rss_item = $this->orig_to_dest_3pointscheck($loc1_airport_code, $loc2_airport_code, $mid_airport_codes, $travel_month);
                        
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

        private function orig_to_dest_3pointscheck($loc1_code, $loc2_code, $mid_codes, $travel_month)
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
                                $fare_obj1 = new RSSItem($fares[0]);
                                $fare_obj2 = new RSSItem($fares[1]);
                                
                                $curr_total_lowest_fares = $fare_obj1->get_price() + $fare_obj2->get_price();
                                $curr_total = $rss_1_item_obj->get_price() + $rss_2_item_obj->get_price();
                                if($curr_total < $curr_total_lowest_fares)
                                {
                                    $fares = array($rss_1_item, $rss_2_item);
                                }
                            }
                        }

                    }
                }
            }
            return ($fares);
        }
    }
?>
