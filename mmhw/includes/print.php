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
    require_once('rss.php');
    
    /***********************************************
    *              PrintRSS Class
    ***********************************************/
    class PrintRSS
    {
        public function print_rss_item($smarty, $rss_item, $orig_symbol, $dest_symbol)
        {
            $rss_item_obj = new RSSItem($rss_item);
	    $smarty->assign('location_'.$orig_symbol, $rss_item_obj->get_originlocation());
	    $smarty->assign('location_'.$orig_symbol.'_airportCode', $rss_item_obj->get_origincode());
            $smarty->assign('location_'.$dest_symbol, $rss_item_obj->get_destlocation());
            $smarty->assign('location_'.$dest_symbol.'_airportCode', $rss_item_obj->get_destcode());

            $smarty->assign('flight'.$orig_symbol.'_cost', $rss_item_obj->get_price());
            $smarty->assign('flight'.$orig_symbol.'_departdate', $rss_item_obj->get_departdate());
            $smarty->assign('flight'.$orig_symbol.'_returndate', $rss_item_obj->get_returndate());
            $smarty->assign('flight'.$orig_symbol.'_airline', $rss_item_obj->get_airline());
            $smarty->assign('flight'.$orig_symbol.'_description', $rss_item_obj->get_description());
            $smarty->assign('flight'.$orig_symbol.'_buzz', $rss_item_obj->get_guid());

            return ($smarty);
        }
    }
?>
