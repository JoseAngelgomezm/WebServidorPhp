<?php
require 'clase_fruta.php';

class Uva extends Fruta
{

    private $tieneSemilla;

    public function __construct($color_nuevo, $tamaño_nuevo, $siTieneSemillas)
    {
        parent::__construct($color_nuevo, $tamaño_nuevo);
        $this->tieneSemilla = $siTieneSemillas;
    }

    public function tieneSemillas()
    {
        return $this->tieneSemilla;
    }

    public function setTieneSemilla($tieneSemilla)
    {
       return $this->tieneSemilla = $tieneSemilla;
    }
}

?>