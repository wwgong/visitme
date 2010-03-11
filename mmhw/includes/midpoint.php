<?php
    require_once('common.php');

   /***********************************************
    *               MidPoint Class
    ***********************************************/
    abstract class MidPoint
    {
        protected  $longitude = NULL;
        protected  $latitude = NULL;

        function __construct($lola_1, $lola_2)
        {
            $this->set_longitude($lola_1, $lola_2);
            $this->set_latitude($lola_1, $lola_2);
        }

        public function get_longitude()
        {
            return ($this->longitude);
        }

        public function get_latitude()
        {
            return ($this->latitude);
        }

        public function get_lola()
        {
            return (array($this->longitude, $this->latitude));
        }

        public function set_latitude($lola_1, $lola_2)
        {
            //$this->latitude = ($lola_1[1] + $lola_2[1]) / 2;
			$lola_1[1] = deg2rad($lola_1[1]);
			$lola_1[0] = deg2rad($lola_1[0]);
			$lola_2[1] = deg2rad($lola_2[1]);
			$lola_2[0] = deg2rad($lola_2[0]);
			
			$x1 = cos($lola_1[1]) * cos($lola_1[0]);
			$y1 = cos($lola_1[1]) * sin($lola_1[0]);
			$z1 = sin($lola_1[1]);
			
			$x2 = cos($lola_2[1]) * cos($lola_2[0]);
			$y2 = cos($lola_2[1]) * sin($lola_2[0]);
			$z2 = sin($lola_2[1]);
			
			$xm = ( $x1 + $x2 )/2; 
			$ym = ( $y1 + $y2 )/2; 
			$zm = ( $z1 + $z2 )/2; 
			
			$lom = atan2($ym , $xm);
			$hyp = sqrt($xm * $xm + $ym * $ym);
			$lam = atan2( $zm , $hyp);
			
			$this->latitude = rad2deg($lam);
			echo $this->latitude;
        }

        abstract public function set_longitude($lola_1, $lola_2);
    }

   /***********************************************
    *              MidPoint_1 Class
    ***********************************************/

    class MidPoint_1 extends MidPoint
    {
        function __construct($lola_1, $lola_2)
        {
            parent::__construct($lola_1, $lola_2);
        }

        public function set_longitude($lola_1, $lola_2)
        {
            //$this->longitude = ($lola_1[0] + $lola_2[0]) / 2;
			
			$lola_1[1] = deg2rad($lola_1[1]);
			$lola_1[0] = deg2rad($lola_1[0]);
			$lola_2[1] = deg2rad($lola_2[1]);
			$lola_2[0] = deg2rad($lola_2[0]);
			
			$x1 = cos($lola_1[1]) * cos($lola_1[0]);
			$y1 = cos($lola_1[1]) * sin($lola_1[0]);
			$z1 = sin($lola_1[1]);
			
			$x2 = cos($lola_2[1]) * cos($lola_2[0]);
			$y2 = cos($lola_2[1]) * sin($lola_2[0]);
			$z2 = sin($lola_2[1]);
			
			$xm = ( $x1 + $x2 )/2; 
			$ym = ( $y1 + $y2 )/2; 
			$zm = ( $z1 + $z2 )/2; 
			
			$lom = atan2($ym , $xm);
			$hyp = sqrt($xm * $xm + $ym * $ym);
			$lam = atan2( $zm , $hyp);
			
			$this->longitude = rad2deg($lom);
			echo $this->longitude;
			
        }
    }

   /***********************************************
    *              MidPoint_2 Class
    ***********************************************/
    class MidPoint_2 extends MidPoint
    {
        function __construct($lola_1, $lola_2)
        {
            parent::__construct($lola_1, $lola_2);
        }

        public function set_longitude($lola_1, $lola_2)
        {
            $temp = ($lola_1[0] + $lola_2[0]) / 2;
            $this->longitude = ($temp + 180) % 360;
        }
    }

   /***********************************************
    *             MidPointSelect Class
    ***********************************************/
    class MidPointSelect
    {
        private $midpoint_lola = NULL;
        private $midpoint_longitude = NULL;
        private $midpoint_latitude = NULL;
        private $midpoint_airport_codes = array();

        public function __construct($location_1_lola, $location_2_lola, $radius)
        {  
            $midpoint_info = $this->find_closest_midpoint($location_1_lola, $location_2_lola, $radius);
           
            $this->midpoint_lola = $midpoint_info[0];
            $this->midpoint_airport_codes = $midpoint_info[1];

            $this->midpoint_longitude = $this->midpoint_lola[0];
            $this->midpoint_latitude = $this->midpoint_lola[1];
        }

        public function get_midpoint_lola()
        {
            return ($this->midpoint_lola);
        }

        public function get_midpoint_longitude()
        {
            return ($this->midpoint_longitude);
        }

        public function get_midpoint_latitude()
        {
            return ($this->midpoint_latitude);
        }
        
        public function get_midpoint_airport_codes()
        {
            return ($this->midpoint_airport_codes);
        }
        
        private function find_closest_midpoint($location_1_lola, $location_2_lola, $radius)
        {
            $mid_lola = NULL;
            $mid_airport_codes = array();
          
            $mid_1 = new MidPoint_1($location_1_lola, $location_2_lola);
            $midpoint_1_airport_codes =  get_airport_codes($mid_1->get_lola(), $radius);
            
            $mid_2 = new MidPoint_2($location_1_lola, $location_2_lola);
            $midpoint_2_airport_codes =  get_airport_codes($mid_2->get_lola(), $radius);
      
            if(($midpoint_1_airport_codes == NULL) && ($midpoint_2_airport_codes != NULL))
            {
                $mid_lola = $mid_2->get_lola();
                $mid_airport_codes = $midpoint_2_airport_codes;
            }
            else  if(($midpoint_1_airport_codes != NULL) &&($midpoint_2_airport_codes == NULL))
            {
                $mid_lola = $mid_1->get_lola();
                $mid_airport_codes = $midpoint_1_airport_codes;
            }
            else
            {
                $distance_1 = sqrt(pow($location_1_lola[0] - $mid_1->get_longitude(), 2) + pow($location_1_lola[1] - $mid_1->get_latitude(), 2));
                $distance_2 = sqrt(pow($location_1_lola[0] - $mid_2->get_longitude(), 2) + pow($location_1_lola[1] - $mid_2->get_latitude(), 2));

                if($distance_1 <= $distance_2)
                {
                    $mid_lola = $mid_1->get_lola();
                    $mid_airport_codes = $midpoint_1_airport_codes; // Both midpoint_1 and midpoint_2 airport codes may be either NULL or not NULL
                }
                else
                {
                    $mid_lola = $mid_2->get_lola();
                    $mid_airport_codes = $midpoint_2_airport_codes; // Both midpoint_1 and midpoint_2 airport codes may be either NULL or not NULL
                }
            }
      
            return (array($mid_lola, $mid_airport_codes));
        }
    }

?>
