<?php

// traer el cargador del slim
require __DIR__ . '/Slim/autoload.php';

// crear un nueva api
$app= new \Slim\App;

// TODOS LOS METODOS TIENEN COMO PARAMETRO UNA CADENA DE TEXTO Y UNA FUNCION
// la cadena de texto es el nombre del metodo , escribimos /nombre del metodo 
// que una app usara el nombre de este metodo al final de su URL junto al tipo de metodo GET/POST/DELETE/PUT
// por ejemplo www.miapp.com/saludo mediante una peticion HTTP GET

// el metodo get sin parametros
$app -> get("/saludo" , function(){

    // SIEMPRE TENEMOS QUE TENER UN ARRAY ASOCIATIVO, YA QUE EL json_encode() CODIFICA DE ARRAY A JSON
    // ENVIAMOS $respuesta["mensaje"], para recibirlo tenemos que acceder a la posicion del array asociativo "mensaje"
    $mensaje["mensaje"] = "hola";

    // CON ECHO TERMINAMOS, MANDAMOS EL JSON, que recibe un array y codifica un json
    echo json_encode($mensaje);

});

// el metodo get que trae parametros en la URL, obtenemos el nombre y devolvemos el saludo con el nombre
// se define la variable en la llamada a la funcion, que se le suele llamar request
$app -> get("/saludo/{nombre}" , function($request){

    // obtenemos el valor que hemos recibido como parametro
    // accedemos al metodo del objeto $request->getAttribute("mismo nombre del parametro recibido")
    $valorRecibido = $request->getAttribute("nombre");
    // SIEMPRE TENEMOS QUE TENER UN ARRAY ASOCIATIVO, YA QUE EL json_encode() CODIFICA DE ARRAY A JSON
    // ENVIAMOS $respuesta["mensaje"], para recibirlo tenemos que acceder a la posicion del array asociativo "mensaje"
    $mensaje["mensaje"] = "hola ".$valorRecibido;

    // CON ECHO TERMINAMOS, MANDAMOS EL JSON, que recibe un array asociativo y codifica un json
    echo json_encode($mensaje);

});

// el metodo post que trae parametros
$app->post("/saludo", function($request){
    
    //Para acceder a los atributos de la llamada POST se accede mediante getParam 
    $valorRecibido = $request->getParam("nombre");

    // ENVIAMOS $respuesta["mensaje"], para recibirlo tenemos que acceder a la posicion del array asociativo "mensaje"
    $respuesta["mensaje"] = "hola ".$valorRecibido;

    // CON ECHO TERMINAMOS, MANDAMOS EL JSON, que recibe un array asociativo y codifica un json
    echo json_encode($respuesta);
});

// el metodo delete


// el metodo put



// Una vez creado servicios los pongo a disposición
$app->run();
?>