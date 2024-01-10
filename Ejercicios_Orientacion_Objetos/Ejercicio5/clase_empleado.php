<?php
class Empleado
{
    private $nombre;
    private $sueldo;

    public function __construct($nombre_nuevo, $sueldo_nuevo)
    {
        $this->nombre = $nombre_nuevo;
        $this->sueldo = $sueldo_nuevo;
    }

    /**
     * Get the value of nombre
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     */
    public function setNombre($nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of sueldo
     */
    public function getSueldo()
    {
        return $this->sueldo;
    }

    /**
     * Set the value of sueldo
     */
    public function setSueldo($sueldo): self
    {
        $this->sueldo = $sueldo;

        return $this;
    }

    public function imprimirEmpleado(){
        if($this->sueldo > 3000){
            echo "<p><strong>".$this->nombre."</strong> cobra ".$this->sueldo." y tiene que pagar impuestos</p>";
        }else{
            echo "<p><strong>".$this->nombre."</strong> cobra ".$this->sueldo." y NO tiene que pagar impuestos</p>";
        }
    }
}
?>