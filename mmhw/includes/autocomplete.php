<?php/*Copyright 2010 GoPandasThis file is part of Meet Me Half Way ( an extension of VisitME ).VisitME is free software: you can redistribute it and/or modify itunder the terms of the GNU General Public License as published by theFree Software Foundation, either version 3 of the License, or (at youroption) any later version.VisitME is distributed in the hope that it will be useful, but WITHOUTANY WARRANTY; without even the implied warranty of MERCHANTABILITY orFITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public Licensefor more details.You should have received a copy of the GNU General Public Licensealong with VisitME. If not, see http://www.gnu.org/licenses/.*/    require_once("sqlfunctions.php");      $input = strtoupper($_GET['q']);    if (!$q) return;    $input =  mysql_real_escape_string($input);    $ajax_obj = new AutoSuggestQuery();    // Matched airport code comes first    $sql = "SELECT a.*, c.name AS countryname FROM airports a, country c WHERE a.code = '".$input."' AND c.code = a.country";    $ajax_obj->suggest($sql);    // Then, airport name(s) and city's/cities' name(s) that airport(s) located...    $sql = "SELECT a.*, c.name AS countryname FROM airports a, country c                 WHERE ((a.name LIKE '".$input."%' AND a.code <> '".$input."') OR                      (a.city LIKE '".$input."%' AND a.code <> '".$input."' AND a.name NOT LIKE '".$input."%'))                       AND c.code = a.country ORDER BY a.city";    $ajax_obj->suggest($sql);   /***********************************************    *            AutoSuggestQuery Class    ***********************************************/    class AutoSuggestQuery    {        private $index;        const MAX_NUM_OF_SUGGESTS = 9;        public function  __construct()        {            $this->index = 0;        }        public function suggest($sql)        {            $result = sql_result($sql);            $airport_list = array();            while(($airport = sql_fetch_obj($result)) && ($this->index < AutoSuggestQuery::MAX_NUM_OF_SUGGESTS))            {                $airport_list[$this->index] = $airport->city.", ";                if($airport->country == "US")                {                    $airport_list[$this->index] = $airport_list[$this->index].$airport->state." - ".$airport->name." (".$airport->code.")";                }                else                {                    $airport_list[$this->index] = $airport_list[$this->index].$airport->countryname." - ".$airport->name." (".$airport->code.")";                }                $buff = $airport_list[$this->index];                echo "$buff\n";                $this->index++;            }        }    }?>