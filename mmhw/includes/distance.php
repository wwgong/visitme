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

    class Distance {
        private $distance = 0;
        const DISTANCE_MULTIPLIER = 2;

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
            $this->distance = sqrt(pow($lola_1[0] - $lola_2[0], 2) + pow($lola_1[1] - $lola_2[1], 2));
        }

        public function is_nearby($radius)
        {
            if($this->distance > (Distance::DISTANCE_MULTIPLIER * $radius))
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
