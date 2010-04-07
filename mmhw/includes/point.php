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
    *              Point Class
    ***********************************************/
    class Point
    {
        private $longitude = NULL;
        private $latitude = NULL;
        private $lola = array();

        public function __construct($lola)
        {
            $this->lola = $lola;
            $this->longitude = $lola[0];
            $this->latitude = $lola[1];
        }

        public function get_lola()
        {
            return ($this->lola);
        }

        public function get_longitude()
        {
            return ($this->longitude);
        }

        public function get_latitude()
        {
            return ($this->latitude);
        }

    }
?>
