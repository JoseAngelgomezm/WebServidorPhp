<?php
class Pelicula
{
    private $nombre;
    private $director;
    private $precio;
    private $alquilada;
    private $fechaPrevistaDevolucion;

    public function __construct($nombre_nuevo, $director_nuevo, $precio_nuevo, $alquilada_si_no, $fechaPrevistaDevolucion_nuevo)
    {
        $this->nombre = $nombre_nuevo;
        $this->director = $director_nuevo;
        $this->precio = $precio_nuevo;
        $this->alquilada = $alquilada_si_no;
        $this->fechaPrevistaDevolucion = $fechaPrevistaDevolucion_nuevo;
    }

    public function getRecargo()
    {
        
        $fechaEntrega = new DateTime($this->fechaPrevistaDevolucion);
        $fechaActual = new DateTime("now");
        $diasPasados = $fechaEntrega->diff($fechaActual)->days;

        return $diasPasados * 1.2;

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
     * Get the value of director
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * Set the value of director
     */
    public function setDirector($director): self
    {
        $this->director = $director;

        return $this;
    }

    /**
     * Get the value of precio
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set the value of precio
     */
    public function setPrecio($precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get the value of alquilada
     */
    public function getAlquilada()
    {
        return $this->alquilada;
    }

    /**
     * Set the value of alquilada
     */
    public function setAlquilada($alquilada): self
    {
        $this->alquilada = $alquilada;

        return $this;
    }

    /**
     * Get the value of fechaAlquiler
     */
    public function getFechaPrevistaDevolucion()
    {
        return $this->fechaPrevistaDevolucion;
    }

    /**
     * Set the value of fechaAlquiler
     */
    public function setFechaPrevistaDevolucion($fechaPrevistaDevolucion): self
    {
        $this->fechaAlquiler = $fechaPrevistaDevolucion;

        return $this;
    }
}

?>