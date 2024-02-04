<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

session_name("ejercicio5Tokensid2324");
session_start();
session_id();

require __DIR__ . '/Slim/autoload.php';

$app = new \Slim\App;

// funcion que comprueba si el par de usuario y clave estan en la bd
// datos["mensaje_error] => si hay algun error al pedir el servicio
// $datos["usuario"] => si no existe el usuario en la base de datos, los datos del usuario
// $datos["mensaje"] => si no se encuentra en la base de datos

require ("functions.php");

$app->get("/usuarios", function ($request) {

    echo json_encode(obtenerTodosLosUsuarios());

});



$app->post("/crearUsuario", function ($request) {

    $datos["nombre"] = $request->getParam('nombre');
    $datos["usuario"] = $request->getParam('usuario');
    $datos["clave"] = $request->getParam('clave');
    $datos["email"] = $request->getParam('email');

    echo json_encode(insertarUsuario($datos));
});


// servicio que devuelve true o false si esta logeado un usuario o no
$app->post("/login", function ($request) {

    // obtener los parametros del post con getParam
    $usuario = $request->getParam("usuario");
    $clave = $request->getParam("clave");
    

    // enviar el json con lo que devuelve la funcion
    echo json_encode(estaLogeado($usuario, $clave));
});

// servicio que devuelve true o false si esta logeado un usuario o no, requiriendo un token
$app->post("/loginSeguridad", function ($request) {

    // obtener los parametros del post con getParam
    $usuario = $request->getParam("usuario");
    $clave = $request->getParam("clave");

    // enviar el json con lo que devuelve la funcion
    echo json_encode(estaLogeado($usuario, $clave));
});


$app->put("/actualizarUsuarioConClave/{id_usuario}", function ($request) {

    $datos["id_usuario"] = $request->getAttribute('id_usuario');
    $datos["nombre"] = $request->getParam('nombre');
    $datos["usuario"] = $request->getParam('usuario');
    $datos["clave"] = $request->getParam('clave');
    $datos["email"] = $request->getParam('email');
    echo json_encode(actualizarUsuarioConClave($datos));
});


$app->put("/actualizarUsuarioSinClave/{id_usuario}", function ($request) {

    $datos["id_usuario"] = $request->getAttribute('id_usuario');
    $datos["nombre"] = $request->getParam('nombre');
    $datos["usuario"] = $request->getParam('usuario');
    $datos["email"] = $request->getParam('email');

    echo json_encode(actualizarUsuarioSinClave($datos));
});



$app->delete("/borrarUsuario/{id_usuario}", function ($request) {

    $datos["id_usuario"] = $request->getAttribute('id_usuario');

    echo json_encode(eliminarUsuario($datos));
});



$app->get('/repetido/{tabla}/{columna}/{valor}', function ($request) {

    $datos["tabla"] = $request->getAttribute('tabla');
    $datos["columna"] = $request->getAttribute('columna');
    $datos["valor"] = $request->getAttribute('valor');

    echo json_encode(obtenerRepetidosInsertar($datos));

});



$app->get('/repetido/{tabla}/{columna}/{valor}/{columna_id}/{valor_id}', function ($request) {


    $datos["tabla"] = $request->getAttribute('tabla');
    $datos["columna"] = $request->getAttribute('columna');
    $datos["valor"] = $request->getAttribute('valor');
    $datos["columna_id"] = $request->getAttribute('columna_id');
    $datos["valor_id"] = $request->getAttribute('valor_id');

    echo json_encode(obtenerRepetidosEditar($datos));

});

$app->get("/obtenerUsuario/{id_usuario}", function ($request) {

    $datos["id_usuario"] = $request->getAttribute('id_usuario');

    echo json_encode(obtenerUsuarioID($datos));
});


// Una vez creado servicios los pongo a disposición
$app->run();
?>