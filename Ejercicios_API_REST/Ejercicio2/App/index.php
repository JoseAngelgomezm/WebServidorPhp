<?php
define("URLSERVICIOS", "http://localhost/Proyectos/WebServidorPhp/Ejercicios_API_REST/Ejercicio1/servicios_rest");

function consumir_servicios_REST($url, $metodo, $datos = null)
{
    $llamada = curl_init();
    curl_setopt($llamada, CURLOPT_URL, $url);
    curl_setopt($llamada, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($llamada, CURLOPT_CUSTOMREQUEST, $metodo);
    if (isset($datos))
        curl_setopt($llamada, CURLOPT_POSTFIELDS, http_build_query($datos));
    $respuesta = curl_exec($llamada);
    curl_close($llamada);
    return $respuesta;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php

    $insertar = false;
    if ($insertar) {
        // prueba del servicio insertar
        $url = URLSERVICIOS . "/producto/insertar";
        $datos["cod"] = 0001123123123;
        $datos["nombre"] = "un elemento de prueba2qw123";
        $datos["nombre_corto"] = "elemento prueba2qwe123";
        $datos["descripcion"] = "una descripcion cualquiera";
        $datos["PVP"] = 5;
        $datos["familia"] = "TV";
        $respuesta = consumir_servicios_REST($url, "post", $datos);
        $obj = json_decode($respuesta);

        if (!$obj) {
            die("<p>Error consumiendo el servicio</p>");
        }

        if (isset($obj->mensaje)) {
            die("<p>" . $obj->mensaje . "</p>");
        }

        echo "<p>" . $obj->mensaje . "</p>";
    }

    if ($insertar) {
        // prueba del servicio actualizar
        $url = URLSERVICIOS . "/producto/actualizar/156018259";
        $datos["nombre"] = "actualizado";
        $datos["nombre_corto"] = "actualizado";
        $datos["descripcion"] = "actualizado";
        $datos["PVP"] = 100;
        $datos["familia"] = "TV";
        $respuesta = consumir_servicios_REST($url, "post", $datos);
        $obj = json_decode($respuesta);

        if (!$obj) {
            die("<p>Error consumiendo el servicio</p>");
        }

        if (isset($obj->mensaje)) {
            die("<p>" . $obj->mensaje . "</p>");
        }

        echo "<p>" . $obj->mensaje . "</p>";
    }

    // prueba del servicio borrar
    $url = URLSERVICIOS . "/producto/borrar/304723";
    $respuesta = consumir_servicios_REST($url, "post");
    $obj = json_decode($respuesta);

    if (!$obj) {
        die("<p>Error consumiendo el servicio</p>");
    }

    if (isset($obj->mensaje)) {
        die("<p>" . $obj->mensaje . "</p>");
    }

    echo "<p>" . $obj->mensaje . "</p>";


    // prueba del servicio obtener informacion familias
    $url = URLSERVICIOS . "/familias";
    $respuesta = consumir_servicios_REST($url, "get");
    $obj = json_decode($respuesta);

    if (!$obj) {
        die("<p>Error consumiendo el servicio</p>");
    }

    if (isset($obj->mensaje)) {
        die("<p>" . $obj->mensaje . "</p>");
    }

    echo "<p>" . $obj->mensaje . "</p>";


    // prueba obtener repetido de un valor concreto de una tabla
    $ulr = URLSERVICIOS . "/repetido/{tabla}/{columna}/{valor}";
    $respuesta = consumir_servicios_REST($url, "POST");
    $obj = json_decode($respuesta);

    if (!$obj) {
        die("<p>Error consumiendo el servicio</p>");
    }

    if (isset($obj->mensaje)) {
        die("<p>" . $obj->mensaje . "</p>") ;
    }

    echo "<p>" . $obj->mensaje . "</p>";


    // prueba obtener repetido de un valor concreto de una tabla al editar
    $ulr = URLSERVICIOS . "/repetido/{tabla}/{columna}/{valor}/{columna_id}/{valor_id}";
    $respuesta = consumir_servicios_REST($url, "POST");
    $obj = json_decode($respuesta);

    if (!$obj) {
        die("<p>Error consumiendo el servicio</p>");
    }

    if (isset($obj->mensaje)) {
        die("<p>" . $obj->mensaje . "</p>") ;
    }

    echo "<p>" . $obj->mensaje . "</p>";
    ?>
</body>

</html>