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
    require_once ("constants.php");
   /***********************************************
    *                 Util Class
    ***********************************************/
    class Util
    {
        private $err_buffer; // To store invalid input error message(s)
        private $err_index;

        public function  __construct()
        {
            $this->err_buffer = array();
            $this->err_index = 0;
        }

        public function add_err($err_msg)
        {
            $this->err_buffer[$this->err_index] = $err_msg;
            $this->err_index++;
        }

        public function is_inputs_val_passed($host_url)
        {
            if(count($this->err_buffer) > 0)
            {
                $errors = "";
                foreach($this->err_buffer as $err)
                {
                    $errors = $errors.$err."<br />";
                }
                $errors = $errors."<br /><a href='".$host_url."' />Go Back</a>";
                exit($errors);
            }
            else
            {
                return;
            }
        }

        public function val_filter_opt($input)
        {
            $const_obj = new Constants();
            $num_of_filters = sizeof($const_obj->get_filters());

            if((!is_numeric($input)) || ($input < 0) || ($input > $num_of_filters-1))
            {
                return (null);
            }
            else
            {
                return ($input);
            }
        }
        
        /* Ex: 03/2010 to 201003 */
        public function val_n_format_time_inputs($month_input, $year_input)
        {
            $month = "";
            $year = "";
            $tm = "";
            $today = getdate();
            $curr_year = $today['year'];
            $curr_mon = $today['mon'];

            /****** Validate month and year inputs *****/
            // Validate month input
            if((is_numeric($month_input)) && (($month_input > 0) && ($month_input <= 12)))
            {
                if(($year_input == $curr_year) && ($month_input < $curr_mon))
                {
                    return (null);
                }
                if(($year_input == $curr_year+1) && ($month_input > $curr_mon))
                {
                    return (null);
                }
            }
            else
            {
                return (null);
            }

            // Validate year input
            if((!is_numeric($year_input)) || ($year_input < $curr_year) || ($year_input > $curr_year+1))
            {
                return (null);
            }

            /***** If passed validation, format them *****/
            // Format month input
            if(($month_input>0) && ($month_input<10))
            {
                $month = "0".$month_input;
            }
            else
            {
                $month = $month_input;
            }

            $year = $year_input;
            $tm = $year.$month;

            return ($tm);
        }

        public function val_n_parse_airport_code($input)
        {
            $city = null;
            $state = null;
            $country = null;
            $airport_name = null;
            $airport_code = null;
            $is_airport_code_only = false;
            $input_length = strlen($input);

            // Validate & format airport code input
            if($input_length == 3) // 3-alphabets airportcode
            {
                $airport_code = $input;
                $is_airport_code_only = true;
            }
            elseif($input_length > 3)
            {
                // strpos starts from index 0
                $comma_pos = strpos($input, ",");
                $city = substr($input, 0, $comma_pos);

                $dash_pos = strpos($input, "-");
                $temp = substr($input, $comma_pos+2, $dash_pos-1-($comma_pos+2));
                if(strlen($temp) == 2)
                {
                    $state = $temp;
                }
                else if(strlen($temp) > 2)
                {
                    $country = $temp;
                }
          
                $open_bracket_pos = strpos($input, "(");
                $airport_name = substr($input, $dash_pos+2, $open_bracket_pos-1-($dash_pos+2));

                $closed_bracket_pos = strpos($input, ")");
                $airport_code = substr($input, $open_bracket_pos+1, $closed_bracket_pos-($open_bracket_pos+1));
       
                $airport_code_len = strlen($airport_code);
                if($airport_code_len != 3)
                {
                    return (null);
                }
               
                if(($input_length - ($closed_bracket_pos+1)) != 0)
                {
                    return (null);
                }
            }
            else
            {
                return (null);
            }
            
            $validate = is_valid_airport_code($city, $state, $country, $airport_name, $airport_code, $is_airport_code_only);
            if(!$validate)
            {
                return (null);
            }
            
            return ($airport_code);
        }
    }
?>
