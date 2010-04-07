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
    *              RSSItemParser Class
    ***********************************************/
    class RSSItemParser
    {
        private $rss = null;

        public function __construct($rss)
        {
            $this->rss = $rss;
        }

        public function get_num_of_items()
        {
            $count = 0;
            foreach ($this->rss->items as $item)
            {
                $count++;
            }
            return ($count);
        }

        public function get_item($index)
        {
            return ($this->rss->items[$index]);
        }

        public function get_all_items()
        {
            return ($this->rss->items);
        }
        
    }


   /***********************************************
    *              RSSItem Class
    ***********************************************/
    class RSSItem
    {
        private $title = null;
        private $description = null;
        private $origincode = null;
        private $originname = null;
        private $originlocation = null;
        private $destcode = null;
        private $destname = null;
        private $destlocation = null;
        private $airline = null;
        private $price = null;
        private $currency = null;
        private $departdate = null;
        private $returndate = null;
        private $summary = null;
        private $guid = null;

        public function __construct($item)
        {
            $this->title = $item['title'];
            $this->description = $item['description'];
            $this->origincode = $item['kyk']['origincode'];
            $this->originname = $item['kyk']['originname'];
            $this->originlocation = $item['kyk']['originlocation'];
            $this->destcode = $item['kyk']['destcode'];
            $this->destname = $item['kyk']['destname'];
            $this->destlocation = $item['kyk']['destlocation'];
            $this->airline = $item['kyk']['airline'];
            $this->price = $item['kyk']['price'];
            $this->currency = $item['kyk']['currency'];
            $this->departdate = $item['kyk']['departdate'];
            $this->returndate = $item['kyk']['returndate'];
            $this->summary = $item['summary'];
            $this->guid = $item['guid'];
        }

        public function get_title()
        {
            return ($this->title);
        }

        public function get_description()
        {
            return ($this->description);
        }

        public function get_origincode()
        {
            return ($this->origincode);
        }

        public function get_originname()
        {
            return ($this->originname);
        }

        public function get_originlocation()
        {
            return ($this->originlocation);
        }

        public function get_destcode()
        {
            return ($this->destcode);
        }

        public function get_destname()
        {
            return ($this->destname);
        }

        public function get_destlocation()
        {
            return ($this->destlocation);
        }

        public function get_airline()
        {
            return ($this->airline);
        }

        public function get_price()
        {
            return ($this->price);
        }

        public function get_currency()
        {
            return ($this->currency);
        }

        public function get_departdate()
        {
            return ($this->departdate);
        }

        public function get_returndate()
        {
            return ($this->returndate);
        }

        public function get_summary()
        {
            return ($this->summary);
        }

        public function get_guid()
        {
            return ($this->guid);
        }
    }
?>
