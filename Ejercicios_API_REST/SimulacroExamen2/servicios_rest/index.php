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


$app->post('/salir', function ($request) {
    $token = $request->getParam('api_session');
    // recibir el token del que llama al servicio
    session_id($token);
    session_start();
    session_destroy();

    echo json_encode(array("log_out" => "Cerrada sesion de la API"));
});


$app->post('/login', function ($request) {

    $usuario = $request->getParam('usuario');
    $clave = $request->getParam('clave');

    echo json_encode(loginUsuario($usuario, $clave));
});

$app->post('/logueado', function ($request) {
    $token = $request->getParam("api_session");
    session_id($token);
    session_start();
    // si existe un $_SESSION["usuario] en esta id de session, esque esta logeado
    if (isset($_SESSION["usuario"])) {
        echo json_encode(usuarioLogueado($_SESSION["usuario"], $_SESSION["clave"]));
    } else {
        session_destroy();
        echo json_encode(array("no_auth" => "No tienes permisos para usar este servicio"));
    }

});

$app->get("/obtenerProfesores", function ($request) {

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if (isset($_SESSION["usuario"])) {
        echo json_encode(obtenerProfesores());
    } else {
        session_destroy();
        echo json_encode(array("no_auth" => "No tienes permisos para usar este servicio"));
    }

});


$app->get("/obtenerHorarioProfesor/{id_profesor}", function ($request) {

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if (isset($_SESSION["usuario"])) {
        $datos["id_profesor"] = $request->getAttribute("id_profesor");
        echo json_encode(obtenerHorarioProfesor($datos));
    } else {
        session_destroy();
        echo json_encode(array("no_auth" => "No tienes permisos para usar este servicio"));
    }

});


$app->get("/obtenerHorario/{dia}/{hora}",function($request){

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if (isset($_SESSION["usuario"])) {
        $datos["dia"] = $request->getAttribute("dia");
        $datos["hora"] = $request->getAttribute("hora");
        echo json_encode(obtenerHorario($datos));
    } else {
        session_destroy();
        echo json_encode(array("no_auth" => "No tienes permisos para usar este servicio"));
    }

});

function obtenerHorario($datos){

    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "SELECT * from horario_lectivo where dia=? and hora=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["dia"], $datos["hora"]]);
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible realizar la consulta:" . $e->getMessage();
        $sentencia = null;
        $conexion = null;
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["horarios"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $respuesta["mensaje"] = "No se ha obtenido datos de la bd";
    }

    $sentencia = null;
    $conexion = null;

    return $respuesta;

}




// Una vez creado servicios los pongo a disposición
$app->run();
?>