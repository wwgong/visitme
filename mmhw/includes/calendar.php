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
    require_once('smarty/libs/Smarty.class.php');

   /***********************************************
    *              Calendar Class
    ***********************************************/
    class Calendar
    {
        public function travel_time($smarty)
        {
            $today = getdate();
            $curr_year = $today['year'];   
            $curr_mon = $today['mon'];

            $smarty->assign('month_selection', $this->month_options($curr_mon));
            $smarty->assign('year_selection', $this->year_options($curr_year));

            return ($smarty);
        }

        private function month_options($curr_mon)
        {
            $month_array = array(1=>"January", 2=>"February", 3=>"March", 4=>"April", 5=>"May", 6=>"June",
                                 7=>"July", 8=>"August", 9=>"September", 10=>"October", 11=>"November", 12=>"December");

            $str = '<SELECT NAME="tmonth">';

            for($i=$curr_mon; $i<=12; $i++)
            {
                $str = $str.'<OPTION VALUE="'.$i.'" '.($i==$curr_mon? 'SELECTED' : '').'>'.$month_array[$i].'</OPTION>';
            }
            $str = $str.'</SELECT>';

            return ($str);
        }

        private function year_options($curr_year)
        {
            $str = '<SELECT NAME="tyear" ONCHANGE="repopulate_month(this.options[this.selectedIndex].value);">';
            for($i=$curr_year,$j=0; $j<=1; $i++,$j++)
            {
                $str = $str.'<OPTION VALUE="'.$i.'" '.($i==$curr_year? 'SELECTED' : '').'>'.$i.'</OPTION>';
            }
            $str = $str.'</SELECT>';

            return ($str);
        }
    }
?>
