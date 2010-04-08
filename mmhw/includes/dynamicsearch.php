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
    require_once('distance.php');
    require_once('sqlfunctions.php');

   /***********************************************
    *              DynamicSearch Class
    ***********************************************/
    class DynamicSearch {
        const MAX_RADIUS = 100;
        const RADIUS_INCREMENTAL_VALUE = 5.0;
        private $curr_radius = null;
        private $new_mid_codes = null;
        private $index;

        private $loc1_to_mid_rss_item = null;
        private $loc2_to_mid_rss_item = null;
        private $found_meeting_location = null;

        //put your code here
        public function  __construct($lola_1, $lola_2, $lola_mid, $loc1_code, $loc2_code, $mid_codes, $travel_month, $radius)
        { 
            $this->curr_radius = $radius;
            $this->new_mid_codes = array();
            $this->index = 0;
            $this->found_meeting_location = false;
            $this->search($lola_1, $lola_2, $lola_mid, $loc1_code, $loc2_code, $mid_codes, $travel_month);
        }

        public function get_loc1_to_mid_rss_item()
        {
            return ($this->loc1_to_mid_rss_item);
        }

        public function get_loc2_to_mid_rss_item()
        {
            return ($this->loc2_to_mid_rss_item);
        }

        public function found_meeting_location()
        {
            return ($this->found_meeting_location);
        }

        private function search($lola_1, $lola_2, $lola_mid, $loc1_code, $loc2_code, $mid_codes, $travel_month)
        {
            $dist_obj = new Distance($lola_1, $lola_2);
            $this->curr_radius = $this->curr_radius + DynamicSearch::RADIUS_INCREMENTAL_VALUE;
            $nearby = $dist_obj->is_nearby($this->curr_radius);
            $old_codes = $mid_codes;
            
            if((!$nearby) && ($this->curr_radius <= DynamicSearch::MAX_RADIUS)) 
            {
                $new_codes = get_airport_codes($lola_mid, $this->curr_radius);

                if($old_codes == null)
                {
                    if($new_codes == null)
                    {
                        $this->search($lola_1, $lola_2, $lola_mid, $loc1_code, $loc2_code, $old_codes, $travel_month);
                    }
                    else
                    {
                        $this->new_mid_codes = $new_codes;
                    }
                }
                else
                {
                    if(sizeof($new_codes) <= sizeof($old_codes))
                    {
                        $this->search($lola_1, $lola_2, $lola_mid, $loc1_code, $loc2_code, $old_codes, $travel_month);
                    }
                    else
                    {
                        foreach($new_codes as $new_code)
                        {
                            $found = false;
                            foreach($old_codes as $old_code)
                            {
                                if($old_code == $new_code)
                                {
                                    $found = true;
                                    break;
                                }
                            }
                            if(!$found)
                            {
                                $this->new_mid_codes[$this->index] = $new_code;
                                $this->index++;
                            }
                        }
                    }
                }
                
                if(sizeof($this->new_mid_codes) > 0)
                {    
                    $result_obj = new Result($loc1_code, $loc2_code, $this->new_mid_codes, $travel_month);
                    $loc1_to_mid_rss_item = $result_obj->get_loc1_to_mid_rss_item();
                    $loc1_to_mid_obj = new RSSItem($loc1_to_mid_rss_item);
                    $loc2_to_mid_rss_item = $result_obj->get_loc2_to_mid_rss_item();
                    $loc2_to_mid_obj = new RSSItem($loc2_to_mid_rss_item);

                    if(($loc1_to_mid_obj->get_price() == null) || ($loc2_to_mid_obj->get_price() == null))
                    {
                        $this->index = 0;
                        $this->new_mid_codes = array();
                        $this->search($lola_1, $lola_2, $lola_mid, $loc1_code, $loc2_code, $new_codes, $travel_month);
                    }
                    else
                    {
                        $this->loc1_to_mid_rss_item = $loc1_to_mid_rss_item;
                        $this->loc2_to_mid_rss_item = $loc2_to_mid_rss_item;
                        $this->found_meeting_location = true;
                    }
                }
            }
            return;
        }
    }
?>
