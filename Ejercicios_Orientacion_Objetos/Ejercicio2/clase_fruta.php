<?php
class Fruta
{

    public function __construct($color_nuevo, $tamaño_nuevo){
        $this->color = $color_nuevo;
        $this->tamaño = $tamaño_nuevo;
        $this->imprimir();

    }

    private $color;
    private $tamaño;

    public function setColor($color_nuevo)
    {
        $this->color = $color_nuevo;
    }

    public function setTamaño($tamaño_nuevo)
    {
        $this->tamaño = $tamaño_nuevo;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function getTamaño()
    {
        return $this->tamaño;
    }

    private function imprimir()
    {
        echo "<h2>Mi fruta pera tiene la siguientes propiedades</h2>";
        echo "<p><strong>Color: </strong> " . $this->getColor() . "</strong></p>";
        echo "<p><strong>Tamaño: </strong>" . $this->getTamaño() . "</strong</p>";
    }

}

?>