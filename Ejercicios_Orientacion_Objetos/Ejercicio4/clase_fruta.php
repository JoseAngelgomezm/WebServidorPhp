<?php
class Fruta
{

    public function __construct($color_nuevo, $tamaño_nuevo){
        $this->color = $color_nuevo;
        $this->tamaño = $tamaño_nuevo;
        // para propiedades estaticas modificamos los valores con self
        self::$n_frutas ++ ;
    }

    public function __destruct(){
        self::$n_frutas -- ;
    }

    private $color;
    private $tamaño;
    private static $n_frutas = 0;

    public static function numeroFrutas(){
        return self::$n_frutas;
    }

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

    public function imprimir($nombre)
    {
        echo "<h2>Mi fruta $nombre tiene la siguientes propiedades</h2>";
        echo "<p><strong>Color: </strong> " . $this->getColor() . "</strong></p>";
        echo "<p><strong>Tamaño: </strong>" . $this->getTamaño() . "</strong</p>";
    }

}

?>