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

    $token = $request->getParam('api_session');
    // recibir el token del que llama al servicio
    session_id($token);
    session_start();

    // si existe un $_SESSION["usuario] en esta id de session, esque esta logeado
    if (isset($_SESSION["usuario"])) {
        echo json_encode(usuarioLogueado($_SESSION["usuario"], $_SESSION["clave"]));
    } else {
        echo json_encode(array("error" => "no tiene permisos para usar este servicio"));
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
        echo json_encode(array("error" => "No tienes permisos para usar este servicio"));
    }
});

function insertar_libro($datos)
{

    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $consulta = "INSERT INTO libros (referencia,titulo,autor,descripcion,precio) values (?,?,?,?,?)";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["referencia"], $datos["titulo"], $datos["autor"],$datos["descripcion"], $datos["precio"]]);

    } catch (PDOException $e) {
        $respuesta["error"] = "No se puede realizar la consulta" . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["libros"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    } else {
        $respuesta["mensaje"] = "No hay libros en la base de datos";
    }

    $conexion = null;
    $sentencia = null;
    return $respuesta;

}




// Una vez creado servicios los pongo a disposición
$app->run();
?>