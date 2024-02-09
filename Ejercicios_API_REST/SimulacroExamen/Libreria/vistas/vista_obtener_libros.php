<h2>Listado de libros</h2>
    <?php
    $url = URLBASE . "/obtenerLibros";
    $respuesta = consumir_servicios_REST($url, "get");
    $archivo = json_decode($respuesta);

    if (!$archivo) {
        session_destroy();
        die(error_page("Error obtencion", "No se ha obtenido respuesta"));
    }

    if (isset($archivo->error)) {
        session_destroy();
        die(error_page("Error obtencion", $archivo->error));
    }

    if (isset($archivo->mensaje)) {
        $_SESSION["mensajeLibros"] = $archivo->mensaje;
    }

    echo "<div id='libros'>";
    if (isset($archivo->libros)) {
        foreach ($archivo->libros as $value) {
            echo "<div>
            <img src='" . $value->portada . "'>
            <p>$value->titulo - $value->precio €</p>
            </div>";
        }
    }
    echo "</div>";


    ?>