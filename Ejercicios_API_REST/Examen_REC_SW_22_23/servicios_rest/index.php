<?php

require "src/funciones_servicios.php";
require __DIR__ . '/Slim/autoload.php';

$app = new \Slim\App;



$app->get('/conexion_PDO', function ($request) {

    echo json_encode(conexion_pdo());
});

$app->get('/conexion_MYSQLI', function ($request) {

    echo json_encode(conexion_mysqli());
});

$app->post("/salir", function ($request) {

    $api_session = $request->getParam("api_session");


    session_id($api_session);
    session_start();
    session_destroy();
    return $respuesta["logout"] = "cerrada la sesion de la api";
});

$app->post("/login", function ($request) {

    $datos["usuario"] = $request->getParam("usuario");
    $datos["clave"] = $request->getParam("clave");

    echo json_encode(loguear($datos));

});

$app->get("/logueado", function ($request) {

    $api_session = $request->getParam("api_session");

    session_id($api_session);
    session_start();

    if (isset ($_SESSION["usuario"])) {
        $datos["usuario"] = $request->getParam("usuario");
        $datos["clave"] = $request->getParam("clave");

        echo json_encode(logueado($datos));
    } else {
        return $respuesta["no_auth"] = "El usuario no se encuentra logueado en la api";
    }
});

$app->get("/usuario/{id_usuario}", function ($request) {

    $api_session = $request->getParam("api_session");
    session_id($api_session);
    session_start();

    if (isset ($_SESSION["usuario"])) {
        $datos["id_usuario"] = $request->getAttribute("id_usuario");
        echo json_encode(obtnerUsuarioId($datos));
    }else{
        return $respuesta["no_auth"] = "El usuario no se encuentra logueado en la api";
    }

});


$app->get("/usuariosGuardia/{dia}/{hora}",function($request){
    $api_session = $request->getParam("api_session");
    session_id($api_session);
    session_start();

    if (isset ($_SESSION["usuario"])) {
        $datos["dia"] = $request->getAttribute("dia");
        $datos["hora"] = $request->getAttribute("hora");
        echo json_encode(obtenerUsuariosGuardia($datos));
    }else{
        return $respuesta["no_auth"] = "El usuario no se encuentra logueado en la api";
    }
});

$app->get("/deGuardia/{dia}/{hora}/{id_usuario}",function($request){
    $api_session = $request->getParam("api_session");
    session_id($api_session);
    session_start();

    if (isset ($_SESSION["usuario"])) {
        $datos["dia"] = $request->getAttribute("dia");
        $datos["hora"] = $request->getAttribute("hora");
        $datos["id_usuario"] = $request->getAttribute("id_usuario");
        echo json_encode(usuarioSiGuardia($datos));
    }else{
        return $respuesta["no_auth"] = "El usuario no se encuentra logueado en la api";
    }
});



// Una vez creado servicios los pongo a disposición
$app->run();
?>