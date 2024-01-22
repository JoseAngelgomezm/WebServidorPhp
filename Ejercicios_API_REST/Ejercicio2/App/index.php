<?php
define("URLSERVICIOS","http://localhost/Proyectos/WebServidorPhp/Ejercicios_API_REST/Ejercicio1/servicios_rest");

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
        // prueba del servicio put
        $url = URLSERVICIOS."/producto/insertar";
        $datos["cod"] = 0001;
        $datos["nombre"] = "un elemento de prueba";
        $datos["nombre_corto"] = "elemento prueba";
        $datos["descripcion"] = "una descripcion cualquiera";
        $datos["familia"] = "TV";
        $respuesta = consumir_servicios_REST($url, "post", $datos);
        $obj = json_decode($respuesta);

        if(!$obj){
            die("<p>Error consumiendo el servicio</p>");
        }

        if(isset($obj -> mensaje)){
            die("<p>".$obj -> mensaje."</p>");
        }

        echo "<p>".$obj -> mensaje."</p>" 
    ?>
</body>

</html>