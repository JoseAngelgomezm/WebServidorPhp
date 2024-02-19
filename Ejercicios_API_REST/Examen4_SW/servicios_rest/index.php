<?php

require "src/funciones_servicios.php";
require __DIR__ . '/Slim/autoload.php';

$app = new \Slim\App;



$app->get('/conexion_PDO', function ($request) {

    echo json_encode(conexion_pdo());
});


$app->post('/login', function ($request) {

    $datos["usuario"] = $request->getParam("usuario");
    $datos["clave"] = $request->getParam("clave");

    echo json_encode(login($datos));
});


$app->get("/logueado", function ($request) {

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if (isset($_SESSION["usuario"])) {
        $datos["usuario"] = $_SESSION["usuario"];
        $datos["clave"] = $_SESSION["clave"];
        echo json_encode(logueado($datos));
    }
});

$app->post("/salir", function ($request) {

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();
    session_destroy();

    return array("log_out" => "Cerrada session en la API");
});


$app->get("/alumnos", function ($request) {

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if (isset($_SESSION["usuario"])) {
        echo json_encode(obtenerAlumnos());
    }
});

$app->get("/notasAlumno/{cod_alu}", function ($request) {

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if (isset($_SESSION["usuario"])) {
        $datos["cod_alu"] = $request->getAttribute("cod_alu");
        echo json_encode(obtenerAsignaturasEvaluadas($datos));
    }
});

$app->delete("/quitarNota/{cod_alu}", function ($request) {
    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if (isset($_SESSION["usuario"])) {
        $datos["cod_usu"] = $request->getAttribute("cod_alu");
        $datos["cod_asig"] = $request->getParam("cod_asig");
        echo json_encode(eliminarAsignatura($datos));
    }
});

function eliminarAsignatura($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
    }

    try {
        $consulta = "delete from notas where cod_usu=? and cod_asig = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["cod_usu"], $datos["cod_asig"]]);
    } catch (Exception $e) {
        $respuesta["error"] = "Error al conectar en el borrado";
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["mensaje"] = "Asignatura descalificada con éxito";
    } else {
        $respuesta["error"] = "no se ha encontrado asignatura";
    }

    $sentencia = null;
    $conexion = null;
    return $respuesta;

}



// Una vez creado servicios los pongo a disposición
$app->run();
?>