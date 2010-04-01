<?php
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
