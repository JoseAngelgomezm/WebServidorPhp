<?php

require "src/funciones_servicios.php";
require __DIR__ . '/Slim/autoload.php';

$app= new \Slim\App;



$app->get('/conexion_PDO',function($request){

    echo json_encode(conexion_pdo());
});

$app->get('/conexion_MYSQLI',function($request){
    
    echo json_encode(conexion_mysqli());
});

$app->get("/salir",function($request){
    
    $token = $request->getParam("api_session");
    session_id($token);
    session_start();
    session_destroy();
    echo json_encode(array("mensaje" => "se ha cerrado la session correctamente en la api"));
    
});

$app->get("/login",function($request){

    $datos["usuario"] = $request->getParam("usuario");
    $datos["contraseña"] = $request->getParam("contraseña");
    echo json_encode(login($datos));

});

$app->get("/logueado",function($request){

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if(isset($_SESSION["usuario"])){
        $datos["usuario"] = $request->getParam("usuario");
        $datos["contraseña"] = $request->getParam("contraseña");
        echo json_encode(logueado($datos));
    }else{
        echo json_encode(array("error" => "no tienes permiso para usar la api"));
    }

});

$app->get("/obtenerHorarios",function($request){

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if(isset($_SESSION["usuario"])){
        $datos["id_usuario"] = $request->getParam("id_usuario");
        echo json_encode(obtenerHorarios($datos));
    }else{
        echo json_encode(array("error" => "no tienes permiso para usar la api"));
    }

});


$app->get("/obtenerHorariosGuardia",function($request){

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if(isset($_SESSION["usuario"])){
        $datos["dia"] = $request->getParam("dia");
        $datos["id_grupo"] = $request->getParam("id_grupo");
        echo json_encode(obtenerHorarioGuardia($datos));
    }else{
        echo json_encode(array("error" => "no tienes permiso para usar la api"));
    }

});

$app->get("/obtenerIdGrupo/{nombre_grupo}",function($request){

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if(isset($_SESSION["usuario"])){
        $datos["nombre_grupo"] = $request->getAttribute("nombre_grupo");
        echo json_encode(obtenerNombreGrupo($datos));
    }else{
        echo json_encode(array("error" => "no tienes permiso para usar la api"));
    }

});


$app->get("/obtenerUsuario/{id_usuario}",function($request){

    $token = $request->getParam("api_session");
    session_id($token);
    session_start();

    if(isset($_SESSION["usuario"])){
        $datos["id_usuario"] = $request->getAttribute("id_usuario");
        echo json_encode(obtenerUsuario($datos));
    }else{
        echo json_encode(array("error" => "no tienes permiso para usar la api"));
    }

});


// Una vez creado servicios los pongo a disposición
$app->run();
?>
