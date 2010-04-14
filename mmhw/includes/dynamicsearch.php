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
    require_once('constants.php');
    require_once('searchedarea.php');
    require_once('point.php');

   /***********************************************
    *              DynamicSearch Class
    ***********************************************/
    class DynamicSearch {
        private $curr_radius = 0;

        private $loc1_to_mid_rss_item = NULL;
        private $loc2_to_mid_rss_item = NULL;
        private $found_meeting_location = false;
        private $searched_area_obj = NULL;

        public function  __construct($lola_1, $lola_2, $lola_mid, $loc1_code, $loc2_code, $travel_month, $radius, $filter_opt)
        { 
            $this->curr_radius = $radius;
            $this->found_meeting_location = false;

            $mid_obj = new Point($lola_mid);
            // Initially, posX = negX and posY = negY
            $this->searched_area_obj = new SearchedArea($mid_obj->get_longitude(), $mid_obj->get_longitude(), $mid_obj->get_latitude(), $mid_obj->get_latitude());
            // Add radius value to the searched area since the area within radius is already searched before dynamic search begins...
            $this->searched_area_obj->add_XY_val($radius);
           
            $this->search($lola_1, $lola_2, $lola_mid, $loc1_code, $loc2_code, $travel_month, $filter_opt);
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

        public function get_curr_radius()
        {
            return ($this->curr_radius);
        }

        public function search($lola_1, $lola_2, $lola_mid, $loc1_code, $loc2_code, $travel_month, $filter_opt)
        {
             $index = 0;
             $new_mid_codes = array();
  
            $dist_obj = new Distance($lola_1, $lola_2);
            $this->curr_radius = $this->curr_radius + Constants::RADIUS_INCREMENTAL_VALUE;
            $nearby = $dist_obj->is_nearby($this->curr_radius);
            ///////echo "<br /> Distance: ".$dist_obj->get_distance()." curr_radius: ".$this->curr_radius;///////////////////
            if((!$nearby) && ($this->curr_radius < Constants::EVENT_HORIZON)) //
            {
                $new_mid_codes = get_airport_codes_dynsearch($this->searched_area_obj, Constants::RADIUS_INCREMENTAL_VALUE);
                // Add RADIUS_INCREMENTAL_VALUE value to the already searched area...
                $this->searched_area_obj->add_XY_val(Constants::RADIUS_INCREMENTAL_VALUE);
                
                if(sizeof($new_mid_codes) > 0)
                {    
                    $result_obj = new Result($loc1_code, $loc2_code, $new_mid_codes, $lola_mid, $travel_month, $filter_opt);
                    $loc1_to_mid_rss_item = $result_obj->get_loc1_to_mid_rss_item();
                    $loc1_to_mid_obj = new RSSItem($loc1_to_mid_rss_item);
                    $loc2_to_mid_rss_item = $result_obj->get_loc2_to_mid_rss_item();
                    $loc2_to_mid_obj = new RSSItem($loc2_to_mid_rss_item);

                    if(($loc1_to_mid_obj->get_price() == NULL) || ($loc2_to_mid_obj->get_price() == NULL))
                    {
                        $this->search($lola_1, $lola_2, $lola_mid, $loc1_code, $loc2_code, $travel_month, $filter_opt);
                    }
                    else
                    {
                        $this->loc1_to_mid_rss_item = $loc1_to_mid_rss_item;
                        $this->loc2_to_mid_rss_item = $loc2_to_mid_rss_item;
                        $this->found_meeting_location = TRUE;
                        return;
                    }
                }
                else
                {
                   $this->search($lola_1, $lola_2, $lola_mid, $loc1_code, $loc2_code, $travel_month, $filter_opt);
                }
            }
            return;
        }
    }
?>
