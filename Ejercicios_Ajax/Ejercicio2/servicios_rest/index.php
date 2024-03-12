<?php

define("SERVIDOR_BD", "localhost");
define("USUARIO_BD", "jose");
define("CLAVE_BD", "josefa");
define("NOMBRE_BD", "bd_tienda");

require("src/funciones_servicios.php");

require __DIR__ . '/Slim/autoload.php';

$app = new \Slim\App;


$app->post('/login', function ($request) {

    $datos["usuario"] = $request->getParam("usuario");
    $datos["clave"] = $request->getParam("clave");

    echo json_encode(loguear($datos));
});


$app->get("/logueado", function ($request) {

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if (isset ($_SESSION["usuario"])) {
        echo json_encode(logueado());
    } else {
        echo json_encode(array ("no_auth" => "No tienes permisos para usar este servicio"));
    }
});

$app->post("/salir", function ($request) {

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();
    session_destroy();

    return array ("log_out" => "Cerrada session en la API");
});


// metodo GET que devuelve todos los productos
$app->get('/productos', function ($request) {

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if (isset ($_POST["usuario"])) {
        // obtener los productos y devolverlos en un json
        echo json_encode(obtenerProductos());
    } else {
        echo json_encode(array ("no_auth" => "No tienes permisos para usar este servicio"));
    }


});

// metodo GET que devuelve un producto dado un codigo de producto
$app->get('/producto/{codigo}', function ($request) {

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if (isset ($_SESSION["usuario"])) {
        // obtener el producto y devolverlos en un json
        $codigoProducto = $request->getAttribute('codigo');
        echo json_encode(obtenerProductosCodigo($codigoProducto));
    } else {
        echo json_encode(array ("no_auth" => "No tienes permisos para usar este servicio"));
    }
});

// metodo POST que inserta un producto
$app->post('/producto/insertar', function ($request) {


    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if (isset ($_SESSION["usuario"]) && $_SESSION["tipo"] == "admin") {
        $datos["cod"] = $request->getParam('cod');
        $datos["nombre"] = $request->getParam('nombre_corto');
        $datos["nombre_corto"] = $request->getParam('nombre_corto');
        $datos["descripcion"] = $request->getParam('descripcion');
        $datos["PVP"] = $request->getParam('PVP');
        $datos["familia"] = $request->getParam('familia');

        echo json_encode(insertarProducto($datos));
    } else {
        echo json_encode(array ("no_auth" => "No tienes permisos para usar este servicio"));
    }
});

// metodo POST que actualizar un producto
$app->put('/producto/actualizar/{codigo}', function ($request) {

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if (isset ($_SESSION["usuario"]) && $_SESSION["tipo"] == "admin") {
        $datos["codigo"] = $request->getAttribute("codigo");
        $datos["nombre"] = $request->getParam("nombre");
        $datos["nombre_corto"] = $request->getParam("nombre_corto");
        $datos["descripcion"] = $request->getParam("descripcion");
        $datos["PVP"] = $request->getParam("PVP");
        $datos["familia"] = $request->getParam("familia");
        echo json_encode(actualizarProducto($datos));
    } else {
        echo json_encode(array ("no_auth" => "No tienes permisos para usar este servicio"));
    }

});


// metodo POST que borra un producto
$app->delete('/producto/borrar/{codigo}', function ($request) {

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if (isset ($_SESSION["usuario"]) && $_SESSION["tipo"] == "admin") {
        $datos["codigo"] = $request->getAttribute('codigo');
        echo json_encode(borrarProducto($datos));
    } else {
        echo json_encode(array ("no_auth" => "No tienes permisos para usar este servicio"));
    }
});

// metodo get que devuelve la informacion de las familias
$app->get('/familias', function ($request) {

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if (isset ($_SESSION["usuario"]) && $_SESSION["tipo"] == "admin") {
        $datos["codigo"] = $request->getAttribute('codigo');
        echo json_encode(obtenerFamilias($datos));
    } else {
        echo json_encode(array ("no_auth" => "No tienes permisos para usar este servicio"));
    }
});

$app->get('/repetido/{tabla}/{columna}/{valor}', function ($request) {

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if (isset ($_SESSION["usuario"]) && $_SESSION["tipo"] == "admin") {
        $datos["tabla"] = $request->getAttribute('tabla');
        $datos["columna"] = $request->getAttribute('columna');
        $datos["valor"] = $request->getAttribute('valor');

        echo json_encode(obtenerRepetidosInsertar($datos));
    } else {
        echo json_encode(array ("no_auth" => "No tienes permisos para usar este servicio"));
    }
});

$app->get('/repetido/{tabla}/{columna}/{valor}/{columna_id}/{valor_id}', function ($request) {

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if (isset ($_SESSION["usuario"]) && $_SESSION["tipo"] == "admin") {
        $datos["tabla"] = $request->getAttribute('tabla');
        $datos["columna"] = $request->getAttribute('columna');
        $datos["valor"] = $request->getAttribute('valor');
        $datos["columna_id"] = $request->getAttribute('columna_id');
        $datos["valor_id"] = $request->getAttribute('valor_id');

        echo json_encode(obtenerRepetidosEditar($datos));
    } else {
        echo json_encode(array ("no_auth" => "No tienes permisos para usar este servicio"));
    }
});

// Una vez creado los servicios los pongo a disposición
$app->run();
