<?php

require "src/funciones_servicios.php";
require __DIR__ . '/Slim/autoload.php';

$app = new \Slim\App;




$app->post('/login', function ($request) {

    $usuario = $request->getParam("usuario");
    $clave = $request->getParam("clave");


    echo json_encode(login($usuario, $clave));
});


$app->post('/salir', function ($request) {

    $api_session = $request->getParam("api_session");
    session_id($api_session);
    session_start();
    session_destroy();
    echo json_encode(array ("log_out" => "Cerrada sesión en la API"));
});


$app->get("/logueado", function ($request) {

    $api_session = $request->getParam("api_session");
    session_id($api_session);
    session_start();

    if (isset ($_SESSION["usuario"])) {
        echo json_encode(logueado($_SESSION["usuario"], $_SESSION["clave"]));
    } else {
        return $respuesta["no_auth"] = "No tiene permisos para usar el servicio";
    }

});

$app->get("/usuario/{id_usuario}", function ($request) {
    $api_session = $request->getParam("api_session");
    session_id($api_session);
    session_start();

    if (isset ($_SESSION["usuario"])) {
        $datos["id_usuario"] = $request->getAttribute("id_usuario");
        echo json_encode(obtenerUsuario($datos));
    } else {
        return $respuesta["no_auth"] = "No tiene permisos para usar el servicio";
    }
});

$app->get("/usuariosGuardia/{dia}/{hora}", function ($request) {
    $api_session = $request->getParam("api_session");
    session_id($api_session);
    session_start();

    if (isset ($_SESSION["usuario"])) {
        $datos["dia"] = $request->getAttribute("dia");
        $datos["hora"] = $request->getAttribute("hora");
        echo json_encode(obtenerUsuariosGuardia($datos));
    } else {
        return $respuesta["no_auth"] = "No tiene permisos para usar el servicio";
    }
});

// Una vez creado servicios los pongo a disposición
$app->run();
?>