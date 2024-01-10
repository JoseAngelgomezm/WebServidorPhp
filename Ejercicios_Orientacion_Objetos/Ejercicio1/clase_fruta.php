<?php
    class Fruta  {
        private $color;
        private $tamaño;

        public function setColor($color_nuevo){
            $this->color = $color_nuevo;
        }

        public function setTamaño($tamaño_nuevo){
            $this->tamaño = $tamaño_nuevo;
        }

        public function getColor(){
            return $this->color;
         }

        public function getTamaño(){
           return $this->tamaño;
        }

        
    }

?>