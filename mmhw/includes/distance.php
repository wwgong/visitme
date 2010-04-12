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
            $this->distance = sqrt(pow($point_1->get_longitude() - $point_2->get_longitude(), 2) + pow($point_1->get_latitude() - $point_2->get_latitude(), 2));
            if($this->distance > 180)
            {
                $this->distance = 360 - $this->distance;
                if($this->distance < 0)
                {
                    $this->distance = $this->distance * (-1);
                }
            }
        }

        public function is_nearby($radius)
        {
            if($this->distance > (Constants::DISTANCE_MULTIPLIER * $radius))
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
