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



$app->post('/login', function ($request) {

    $lector = $request->getParam('lector');
    $clave = $request->getParam('clave');

    echo json_encode(loginUsuario($lector, $clave));
});

$app->post('/logueado', function ($request) {
    $token = $request->getParam("api_session");
    session_id($token);
    session_start();
    // si existe un $_SESSION["usuario] en esta id de session, esque esta logeado
    if (isset($_SESSION["usuario"])) {
        echo json_encode(usuarioLogueado($_SESSION["usuario"], $_SESSION["clave"]));
    } else {
        
        echo json_encode(array("no_auth" => "No tienes permisos para usar este servicio"));
    }

});

$app->post('/salir', function ($request) {
    $token = $request->getParam('api_session');
    // recibir el token del que llama al servicio
    session_id($token);
    session_start();
    session_destroy();

    echo json_encode(array("log_out" => "Cerrada sesion de la API"));
});

$app->get('/obtenerLibros', function ($request) {

    echo json_encode(obtenerLibros());
});

$app->post('/crearLibro', function ($request) {

    $token = $request->getParam('api_session');
    // recibir el token del que llama al servicio
    session_id($token);
    session_start();

    if (isset($_SESSION["usuario"])) {

        $datos["referencia"] = $request->getParam('referencia');
        $datos["titulo"] = $request->getParam('titulo');
        $datos["autor"] = $request->getParam('autor');
        $datos["descripcion"] = $request->getParam('descripcion');
        $datos["precio"] = $request->getParam('precio');

        echo json_encode(insertar_libro($datos));
    } else {
        session_destroy();
        echo json_encode(array("no_auth" => "No tienes permisos para usar este servicio"));
    }
});

$app->put("/actualizarPortada/{referencia}", function ($request) {

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if (isset($_SESSION["usuario"])) {
        $datos["referencia"] = $request->getAttribute('referencia');
        $datos["nombre"] = $request->getParam("nombre");

        echo json_encode(actualizarPortada($datos));
    } else {
        session_destroy();
        echo json_encode(array("no_auth" => "No tienes permisos para usar este servicio"));
    }

});

$app->get("/repetido/{tabla}/{columna}/{valor}", function ($request) {

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if (isset($_SESSION["usuario"])) {
        $datos["tabla"] = $request->getAttribute("tabla");
        $datos["columna"] = $request->getAttribute("columna");
        $datos["valor"] = $request->getAttribute("valor");
        echo json_encode(consultarRepetido($datos));
    } else {
        session_destroy();
        echo json_encode(array("no_auth" => "No tienes permisos para usar este servicio"));
    }

});

// Una vez creado servicios los pongo a disposición
$app->run();
?>