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

$app->post("/salir",function($request){
    
    $api_session = $request->getParam("api_session");

    
    session_id($api_session);
    session_start();
    session_destroy();
    return $respuesta["logout"] = "cerrada la sesion de la api";
});

$app->post("/login",function($request){

    $datos["usuario"] = $request->getParam("usuario");
    $datos["clave"] = $request->getParam("clave");

    echo json_encode(loguear($datos));

});

$app->get("/logueado",function($request){

    $api_session = $request->getParam("api_session");

    session_id($api_session);
    session_start();

    if(isset($_SESSION["usuario"])){
        $datos["usuario"] = $request->getParam("usuario");
        $datos["clave"] = $request->getParam("clave");

        json_encode(logueado($datos));
    }else{
        return $respuesta["mensaje"] = "El usuario no se encuentra registrado en la bd";
    }

    

});

function logueado($datos){
    try {
        $conexion= new PDO("mysql:host=".SERVIDOR_BD.";dbname=".NOMBRE_BD,USUARIO_BD,CLAVE_BD,array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES 'utf8'"));
    } catch (Exception $e) {
        $respuesta["error"] = $e->getMessage(); 
    }

    try {
        $consulta = "SELECT * FROM usuarios where usuario = ? and clave = ? ";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["usuario"], $datos["clave"]]);
    } catch (Exception $e) {
        $respuesta["error"] = $e->getMessage(); 
    }

    if($sentencia->rowCount() > 0){
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);

    }else{
        $respuesta["mensaje"] = "el usuario no se encuentra registrado en la bd";
    }

    return $respuesta;
}


// Una vez creado servicios los pongo a disposición
$app->run();
?>
