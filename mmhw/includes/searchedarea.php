<?php
/*
Copyright 2010 GoPandas
This file is part of VisitME.

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
    *              SearchedArea Class
    ***********************************************/
    class SearchedArea
    {
        private $posX = 0;
        private $negX = 0;
        private $posY = 0;
        private $negY = 0;

        public function  __construct($posX, $negX, $posY, $negY)
        {
            $this->posX = $posX;
            $this->negX = $negX;
            $this->posY = $posY;
            $this->negY = $negY;
        }

        public function setXY($value)
        {
            $this->posX = $this->posX + $value;
            $this->negX = $this->negX - $value;
            $this->posY = $this->posY + $value;
            $this->negY = $this->negY - $value;
        }

        public function get_posX()
        {
            return ($this->posX);
        }

        public function get_negX()
        {
            return ($this->negX);
        }

        public function get_posY()
        {
            return ($this->posY);
        }

        public function get_negY()
        {
            return ($this->negY);
        }

        public function getX()
        {
            return (array($this->posX, $this->negX));
        }

        public function getY()
        {
            return (array($this->posY, $this->negY));
        }

        public function getXY()
        {
            return (array(array($this->posX, $this->negX), array($this->posY, $this->negY)));
        }
    }

?>
