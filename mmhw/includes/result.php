<?php
    require_once("distance.php");
    require_once("sqlfunctions.php");
    
   /***********************************************
    *              Result Class
    ***********************************************/
    class Result
    {
        private $origin_to_dest_rss = NULL;
        private $two_points_to_mid_rss = NULL;
        private $loc1_to_mid_rss = NULL;
        private $loc2_to_mid_rss = NULL;
        const MAX_DIST_BTW_TWO_MID_NEARBY_AIRPORTS = 1;

        public function __construct()
        {  
            $num = func_num_args();
            $args = func_get_args();
            switch($num)
            {
                case 3:
                    $this->__call('__construct0', $args);
                    break;
                case 4:
                    $this->__call('__construct1', $args);
                    break;
                default:
                    throw new Exception();
            }
        }

        private function __call($name, $arg)
        {
            return (call_user_func_array(array($this,$name), $arg));
        }
        
        private function __construct0($origin_airport_codes, $dest_airport_codes, $travel_month)
        {
            $this->origin_to_dest_rss = $this->orig_to_dest($origin_airport_codes, $dest_airport_codes, $travel_month);
        }

        private function __construct1($loc1_airport_codes, $loc2_airport_codes, $mid_airport_codes, $travel_month)
        { 
            $this->two_points_to_mid_rss = $this->orig_to_dest_3pointscheck($loc1_airport_codes, $loc2_airport_codes, $mid_airport_codes, $travel_month);

            $this->loc1_to_mid_rss = $this->two_points_to_mid_rss[0];
            $this->loc2_to_mid_rss = $this->two_points_to_mid_rss[1];
        }

        public function get_origin_to_dest_rss()
        {
            return ($this->origin_to_dest_rss);
        }

        public function get_loc1_to_mid_rss()
        {
            return ($this->loc1_to_mid_rss);
        }

        public function get_loc2_to_mid_rss()
        {
            return ($this->loc2_to_mid_rss);
        }

        private function orig_to_dest($origCodes, $destCodes, $travel_month)
        {
	    $fares = array();
            if (($origCodes != NULL) && ($destCodes != NULL) && ($travel_month != NULL))
            {
                $orig_codes = array();
                $dest_codes = array();
                $orig_codes = $this->format_input($origCodes);
                $dest_codes = $this->format_input($destCodes);

		foreach ($orig_codes as $code)
		{
                    $rss = get_fares_code_to_city($code, $dest_codes, $travel_month);
                   
                    if (sizeof($rss->items) > 0)
                    {
			if (sizeof($fares->items) < 1 || $rss->items[0]['kyk']['price'] < $fares->items[0]['kyk']['price'])
			{
                            $fares = $rss;
			}
                    }
		}
            }

            return ($fares);
        }

        // To convert to array format if it is not
        private function format_input($input)
        {
            $temp = array();
            if(!is_array($input))
            {
                $temp[0] = $input;
                return ($temp);
            }
            return ($input);
        }

        /*
         * This function works with either single or multiple airport codes of location 1,
         * location 2, and mid point.
         * Single airport code scenario: Input two airport codes
         * Multiple airport codes scenario: Input two locations where each location may
         *                                  have many surrounding airports
         */
        private function orig_to_dest_3pointscheck($loc1_codes, $loc2_codes, $mid_codes, $travel_month)
        {
            $fares = array();
            $rss_1_list = array();
            $rss_2_list = array();
            $index = 0;

            if (($loc1_codes != NULL) && ($loc2_codes != NULL) && ($mid_codes != NULL) && ($travel_month != NULL))
            {
                $loc1_airport_codes = array();
                $loc2_airport_codes = array();
                $mid_airport_codes = array();
                $loc1_airport_codes = $this->format_input($loc1_codes);
                $loc2_airport_codes = $this->format_input($loc2_codes);
                $mid_airport_codes  = $this->format_input($mid_codes);
                
		foreach ($loc1_airport_codes as $loc1_code)
		{       
                    $rss_1 = get_fares_code_to_city($loc1_code, $mid_airport_codes, $travel_month);
                    
                    if (sizeof($rss_1->items) > 0)
                    {
			$rss_1_list[$index] = $rss_1;
                        $index = $index + 1;
                    }
		}

                $index = 0; // Reset value

                foreach ($loc2_airport_codes as $loc2_code)
		{
                    $rss_2 = get_fares_code_to_city($loc2_code, $mid_airport_codes, $travel_month);

                    if (sizeof($rss_2->items) > 0)
                    {
			$rss_2_list[$index] = $rss_2;
                        $index = $index + 1;
                    }
		}

                
                foreach ($rss_1_list as $rss1)
                {
                    foreach($rss_2_list as $rss2)
                    {
                        // Give priority if two people can fly to the same airport...then, return immediately...
                        if($rss1->items[0]['kyk']['destcode'] == $rss2->items[0]['kyk']['destcode'])
                        {
                             $fares = array($rss1, $rss2);
                             return ($fares);
                        }
                        // If two different nearby airports
                        else
                        {
                            // Find distance between two nearby airports of midpoint
                            $location_1_lola = get_lola_airport($rss1->items[0]['kyk']['destcode']);
                            $location_2_lola = get_lola_airport($rss2->items[0]['kyk']['destcode']);
                            $dist_obj = new Distance($location_1_lola, $location_2_lola);
                            $distance = $dist_obj->get_distance();
                            
                            if($distance <= Result::MAX_DIST_BTW_TWO_MID_NEARBY_AIRPORTS)
                            {
                              // If $fares empty (initially)
                                if((sizeof($fares[0]->items) < 1) && (sizeof($fares[1]->items) < 1))
                                {
                                    $fares = array($rss1, $rss2);
                                }                        
                                // Otherwise, find the lowest fare combination...
                                else
                                {
                                    $curr_total_lowest_fares = $fares[0]->items[0]['kyk']['price'] + $fares[1]->items[0]['kyk']['price'];
                                    $curr_total = $rss1->items[0]['kyk']['price'] + $rss2->items[0]['kyk']['price'];
                                    if($curr_total < $curr_total_lowest_fares)
                                    {
                                        $fares = array($rss1, $rss2);
                                    }
                                }
                            }     
                        }

                    }
                }

            }
            return ($fares);
        }
    }
?>
