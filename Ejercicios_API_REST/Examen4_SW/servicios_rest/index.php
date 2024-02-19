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



// Una vez creado servicios los pongo a disposición
$app->run();
?>