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
    require_once ('constants.php');
    require_once ('point.php');

   /***********************************************
    *              Distance Class
    ***********************************************/
    class Distance {
        private $distance = 0;

        public function __construct($lola_1, $lola_2)
        {
            $this->set_distance($lola_1, $lola_2);
        }

        public function get_distance()
        {
            return ($this->distance);
        }

        private function set_distance($lola_1, $lola_2)
        {
            $point_1 = new Point($lola_1);
            $point_2 = new Point($lola_2);

            /*** Haversine formula ***/
            $lat_1 = deg2rad($point_1->get_latitude());
            $lat_2 = deg2rad($point_2->get_latitude());
            $long_1 = deg2rad($point_1->get_longitude());
            $long_2 = deg2rad($point_2->get_longitude());

            $delta_lat = $lat_2 - $lat_1;
            $delta_long = $long_2 - $long_1;
    
            $a = pow(sin($delta_lat/2), 2) + cos($lat_1) * cos($lat_2) * pow(sin($delta_long/2), 2);
            $c = 2 * atan2(sqrt($a), sqrt(1-$a));
  
            $this->distance = (Constants::EARTH_RADIUS * $c) / Constants::DEG_2_MILE;
        }

        public function is_nearby($radius)
        {
            if($this->distance >= (Constants::DISTANCE_MULTIPLIER * $radius))
            {
                return (false);
            }
            else
            {
                return (true);
            }
        }
}
?>
