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

   /***********************************************
    *              Constants Class
    ***********************************************/
    class Constants
    {
        /* Earth circumference = 24902 miles (at the Equator)
         * Each degree of latitude/ = 24902 / 360 = 69.172 miles
         * longitude at the equator
         */

        // Dynamic search values
        const EVENT_HORIZON = 60; //  0 < EVENT_HORIZON < 90; Max distance (radius) from midpoint where airports will be searched
        const RADIUS_INCREMENTAL_VALUE = 0.7; // in degree
        const DISTANCE_MULTIPLIER = 2;

        const EARTH_RADIUS = 3959; // in miles
        const DEG_2_MILE = 69.172; // in miles

        // Symbols used for distinguishing Smarty locations' variables
        const LOCATION_1_SYMBOL = '1';
        const LOCATION_2_SYMBOL = '2';
        const LOCATION_MID_SYMBOL = 'mid';
        const LOCATION_A_SYMBOL = 'A';
        const LOCATION_B_SYMBOL = 'B';

        // Filter options
        private $filters = array('BEST_MATCHED_DATES_FILTER'=>0,
                                 'LOWEST_COMBINED_FARES_FILTER'=>1,
                                 'CLOSET_TO_MIDPOINT_FILTER'=>2);

        public function get_filter($i)
        {
            return ($this->filters[$i]);
        }

        public function get_filters()
        {
            return ($this->filters);
        }
    }
?>
