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
    *                 Util Class
    ***********************************************/
    class Util
    {
        /* Ex: 03/2010 to 201003 */
        public function format_travel_month_input($input)
        {
            $month = substr($input, 0, 2);
            $year = substr($input, -4);
            $tm = $year.$month;

            return ($tm);
        }

        public function parse_airport_code($input)
        {
            $airport_code = null;

            if(strlen($input) == 3)
            {
                $airport_code = $input;
            }
            else
            {
                $startSymbol = "(";
                $pos = strpos($input, $startSymbol);
                $airport_code = substr($input, $pos+1, 3);
            }

            return ($airport_code);
        }
    }
?>
