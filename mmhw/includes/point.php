<?php
   /***********************************************
    *              Point Class
    ***********************************************/
    class Point
    {
        private $longitude = NULL;
        private $latitude = NULL;
        private $lola = array();

        public function __construct($lola)
        {
            $this->lola = $lola;
            $this->longitude = $lola[0];
            $this->latitude = $lola[1];
        }

        public function get_lola()
        {
            return ($this->lola);
        }

        public function get_longitude()
        {
            return ($this->longitude);
        }

        public function get_latitude()
        {
            return ($this->latitude);
        }

    }
?>
