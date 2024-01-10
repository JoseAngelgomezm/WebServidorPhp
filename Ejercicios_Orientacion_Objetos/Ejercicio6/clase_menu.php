<?php
class Menu
{
    private $urls = [];
    private $nombres = [];

    public function vertical()
    {
        echo "<ul>";
        for ($i = 0; $i < count($this->urls); $i++) {
            echo "<li><a href='" . $this->urls[$i] . "'>" . $this->nombres[$i] . "</a></li>";
        }
        echo "</ul>";
    }

    public function horizontal()
    {
        $ultimo = count($this->nombres) - 1;
        for ($i = 0; $i < count($this->urls); $i++) {

            if ($i === $ultimo) {
                echo "<a href='" . $this->urls[$i] . "'>" . $this->nombres[$i] . "</a>";
            } else {
                echo "<a href='" . $this->urls[$i] . "'>" . $this->nombres[$i] . "</a> - ";
            }

        }
    }

    public function cargar($url, $nombre)
    {
        $this->urls[] = $url;
        $this->nombres[] = $nombre;
    }

    /**
     * Get the value of urls
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * Set the value of urls
     */
    public function setUrls($urls): self
    {
        $this->urls = $urls;

        return $this;
    }

    /**
     * Get the value of nombres
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Set the value of nombres
     */
    public function setNombres($nombres): self
    {
        $this->nombres = $nombres;

        return $this;
    }
}
?>