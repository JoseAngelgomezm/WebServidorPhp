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

$app->get("/login", function ($request) {

    $datos["usuario"] = $request->getParam("usuario");
    $datos["clave"] = $request->getParam("clave");

    echo json_encode(obtenerUsuario($datos));
});

$app->get("/logueado", function ($request) {

    $api_session = $request->getParam("api_session");

    session_id($api_session);
    session_start();

    if (isset ($_SESSION["usuario"])) {
        $datos["usuario"] = $request->getParam("usuario");
        $datos["clave"] = $request->getParam("clave");
        echo json_encode(obtenerUsuarioLogueado($datos));
    } else {
        return $respuesta["no_auth"] = "Inicia session para poder usar estos servicios";
    }

});

$app->get("/salir", function ($request) {
    
    $api_session = $request->getParam("api_session");

    session_id($api_session);
    session_start();
    session_destroy();
    return $respuesta["mensaje"] = "Se ha cerrado la session en la api";
});

$app->get("/obtenerHorarioProfesor", function($request){
    
    $api_session = $request->getParam("api_session");

    session_id($api_session);
    session_start();

    if (isset ($_SESSION["usuario"])) {
        $datos["id_usuario"] = $request->getParam("id_usuario");
        echo json_encode(obtenerHorarioProfesor($datos));
    } else {
        return $respuesta["no_auth"] = "Inicia session para poder usar estos servicios";
    }
});

$app->get("/obtenerProfesoresGuardia/{dia}", function($request){
    
    $api_session = $request->getParam("api_session");

    session_id($api_session);
    session_start();

    if (isset ($_SESSION["usuario"])) {
        $datos["id_usuario"] = $request->getParam("id_usuario");
        $datos["dia"] = $request->getAttribute("dia");
        echo json_encode(obtenerProfesoresGuardia($datos));
    } else {
        return $respuesta["no_auth"] = "Inicia session para poder usar estos servicios";
    }
});


// Una vez creado servicios los pongo a disposición
$app->run();
?>