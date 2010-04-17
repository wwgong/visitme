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
    require_once('common.php');
    require_once('distance.php');
    require_once('point.php');

   /***********************************************
    *               MidPoint_1 Class
    ***********************************************/
    class MidPoint_1
    {
        protected  $longitude = NULL;
        protected  $latitude = NULL;

        function __construct($lola_1, $lola_2)
        {
            $this->set_lola($lola_1, $lola_2);
        }

        public function get_longitude()
        {
            return ($this->longitude);
        }

        public function get_latitude()
        {
            return ($this->latitude);
        }

        public function get_lola()
        {
            return (array($this->get_longitude(), $this->get_latitude()));
        }

	/* sets longitude and latitude for the midpoint */
        private function set_lola($lola_1, $lola_2)
        {
            if($lola_1 == $lola_2)
            {
                $this->latitude = $lola_1[1];
                $this->longitude = $lola_1[0];
            }
            else
            {
                $lola_1[1] = deg2rad($lola_1[1]);
                $lola_1[0] = deg2rad($lola_1[0]);
                $lola_2[1] = deg2rad($lola_2[1]);
                $lola_2[0] = deg2rad($lola_2[0]);

                $x1 = cos($lola_1[1]) * cos($lola_1[0]);
                $y1 = cos($lola_1[1]) * sin($lola_1[0]);
                $z1 = sin($lola_1[1]);

                $x2 = cos($lola_2[1]) * cos($lola_2[0]);
                $y2 = cos($lola_2[1]) * sin($lola_2[0]);
                $z2 = sin($lola_2[1]);

                $xm = ( $x1 + $x2 )/2;
                $ym = ( $y1 + $y2 )/2;
                $zm = ( $z1 + $z2 )/2;

                $lom = atan2($ym , $xm);
                $hyp = sqrt($xm * $xm + $ym * $ym);
                $lam = atan2( $zm , $hyp);

                $this->latitude = rad2deg($lam);
                $this->longitude = rad2deg($lom);
            }
        }
    }

   /***********************************************
    *             MidPointSelect Class
    *
    * Note: Currently, only consider 1 midpoint
    * between great-circle distance
    ***********************************************/
    class MidPointSelect
    {
        private $lola = NULL;
        private $longitude = NULL;
        private $latitude = NULL;
        private $airport_codes = array();

        public function __construct($location_1_lola, $location_2_lola, $radius)
        {
            $midpoint_info = $this->find_closest_midpoint($location_1_lola, $location_2_lola, $radius);

            $this->lola = $midpoint_info[0];
            $this->airport_codes = $midpoint_info[1];

            $mid_obj = new Point($this->lola);
            $this->longitude = $mid_obj->get_longitude();
            $this->latitude = $mid_obj->get_latitude();
        }

        public function get_midpoint_lola()
        {
            return ($this->lola);
        }

        public function get_midpoint_longitude()
        {
            return ($this->longitude);
        }

        public function get_midpoint_latitude()
        {
            return ($this->latitude);
        }

        public function get_midpoint_airport_codes()
        {
            return ($this->midpoint_airport_codes);
        }

        private function find_closest_midpoint($location_1_lola, $location_2_lola, $radius)
        {
            $mid_lola = NULL;
            $mid_airport_codes = array();

            $mid1_obj = new MidPoint_1($location_1_lola, $location_2_lola);

            $mid_lola = $mid1_obj->get_lola();
            $mid_airport_codes = get_airport_codes($mid1_obj->get_lola(), $radius);
   
           return (array($mid_lola, $mid_airport_codes));
        }
    }

?>
