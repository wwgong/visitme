<?php
   /***********************************************
    *              Markers Class
    ***********************************************/
    class Markers
    {
        private $markers = NULL;
        private $index = 0;

        public function __construct()
        {
            $this->markers = array();
            $this->index = 0;
        }

        public function get_markers()
        {
            return ($this->markers);
        }

        public function add_marker($color, $label, $longitude, $latitude)
        {
            $marker = array("color"=>$color, "label"=>$label, "latitude"=>$latitude, "longitude"=>$longitude);
            $this->markers[$this->index] = $marker;
            $this->index = $this->index + 1;
        }
    }
?>
