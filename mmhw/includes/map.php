<?php
    require_once('point.php');
   /***********************************************
    *                 Map Class
    ***********************************************/
    class Map
    {
        // Use Google Map API
        private $baseurl = 'http://maps.google.com/maps/api/staticmap?';

        public function get_static_markers_map_url($size, $markerList)
        {
            $link = $this->baseurl.'size='.$size;
            foreach($markerList as $marker)
            {
                $link = $link.'&markers=color:'.$marker['color'].'|label:'.$marker['label'].'|'.$marker['latitude'].','.$marker['longitude'];
            }
            $link = $link.'&sensor=false';

            return ($link);
        }

        public function get_static_zoom_map_url($size, $lola, $zoom)
        {
            $point_obj = new Point($lola);
            $link = $this->baseurl.'size='.$size.'&center='.$point_obj->get_latitude().','.$point_obj->get_longitude().'&zoom='.$zoom.'&sensor=false';

            return ($link);
        }
    }
?>
