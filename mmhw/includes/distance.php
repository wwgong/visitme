<?php
   /***********************************************
    *              Distance Class
    ***********************************************/
    class Distance {
        private $distance = 0;
        const DISTANCE_MULTIPLIER = 2;

        public function __construct($lola_1, $lola_2)
        {
            $this->set_distance($lola_1, $lola_2);
        }

        public function get_distance()
        {
            return ($this->distance);
        }

        private function set_distance($lola_1, $lola_2)
        {
            $this->distance = sqrt(pow($lola_1[0] - $lola_2[0], 2) + pow($lola_1[1] - $lola_2[1], 2));
        }

        public function is_nearby($radius)
        {
            if($this->distance > (Distance::DISTANCE_MULTIPLIER * $radius))
            { 
                return (false);
            }
            else
            {
                return (true);
            }
        }
}
?>
