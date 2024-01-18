<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    // EN ESTA CONSTANTE GUARDAMOS LA DIRECCION DE LA API
    define("DIRECCION_SERVICIOS","http://localhost/Proyectos/WebServidorPhp/Teoria/Teoria_servicios_web/Api/");

    // Esta funcion solo funciona en el get
    $url = DIRECCION_SERVICIOS."/saludo";
    $respuesta = file_get_contents($url);

    // hacemos el decode del json que recibimos, que crea un objeto con el json recibido
    $obj = json_decode($respuesta);

    // la forma de saber si hemos recibido un json correctamente es preguntando si obj es false
    if(!$obj){
        die("<p>Error consumiendo el servicio ".$url."</p>".$respuesta);
    }

    // hemos obtenido el json , mostrar el saludo
    echo "<p>El saludo recibido ha sido ".$obj -> mensaje."</p>";

    // ESTA FUNCION SE NECESITA PARA CONSUMIR UN SERVICIO DE UNA API, QUE DEVUELVE UNA RESPUESTA
    // DE UN JSON
    function consumir_servicios_REST($url,$metodo,$datos=null)
    {
        $llamada=curl_init();
        curl_setopt($llamada,CURLOPT_URL,$url);
        curl_setopt($llamada,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($llamada,CURLOPT_CUSTOMREQUEST,$metodo);
        if(isset($datos))
            curl_setopt($llamada,CURLOPT_POSTFIELDS,http_build_query($datos));
        $respuesta=curl_exec($llamada);
        curl_close($llamada);
        return $respuesta;
    }

    // HACEMOS LA LLAMADA A LA URL MEDIANTE EL METODO GET, SIN PARAMETROS, OBTENEMOS JSON
    $respuesta = consumir_servicios_REST($url,"GET");
    // PASAMOS EL JSON A OBJ
    $obj = json_decode($respuesta);

    // la forma de saber si hemos recibido un json correctamente es preguntando si obj es false
     if(!$obj){
        die("<p>Error consumiendo el servicio ".$url."</p>".$respuesta);
    }

    echo "<p>MEDIANTE FUNCION El saludo recibido ha sido ".$obj -> mensaje."</p>";



    // LLAMADA MEDIANTE METODO GET SALUDO CON PARAMETROS POR URL

    // para llamada con parametros, especificarlos despues de saludo con saludo/"parametro"
    // $url = DIRECCION_SERVICIOS."/saludo/JoseAngel";
    // si ponemos el nombre con espacios, deja de ser una url, usar urlenconde
    $url = DIRECCION_SERVICIOS."/saludo/".urlencode("Jose Angel Gomez Morillo")."";
    $respuesta = consumir_servicios_REST($url,"GET");
    // PASAMOS EL JSON A OBJ
    $obj = json_decode($respuesta);

    // la forma de saber si hemos recibido un json correctamente es preguntando si obj es false
     if(!$obj){
        die("<p>Error consumiendo el servicio ".$url."</p>".$respuesta);
    }

    echo "<p>MEDIANTE FUNCION CON PARAMETROS EN URL El saludo recibido ha sido ".$obj -> mensaje."</p>";

    // LLAMADA MEDIANTE METODO GET SALUDO CON PARAMETROS POR LLAMADA EN LA FUNCION
    $url = DIRECCION_SERVICIOS."/saludo";
    // pasamos por parametro un array asociativo, con el nombre del parametro que queremos obtener
    // (el mismo que el nombre que tiene definido en la api) y el valor del atributo
    $datos["nombre"] = "Jose Angel";
    $respuesta = consumir_servicios_REST($url,"POST", $datos);
    // PASAMOS EL JSON A OBJ
    $obj = json_decode($respuesta);

    // la forma de saber si hemos recibido un json correctamente es preguntando si obj es false
     if(!$obj){
        die("<p>Error consumiendo el servicio ".$url."</p>".$respuesta);
    }

    echo "<p>MEDIANTE FUNCION CON PARAMETROS El saludo recibido ha sido ".$obj -> mensaje."</p>";


    // LLAMADA MEDIANTE METODO POST

    
    ?>
</body>

</html>