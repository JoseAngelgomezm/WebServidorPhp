<?php

require __DIR__ . '/Slim/autoload.php';

$app= new \Slim\App;

// datos de conexion
define("HOST", "localhost");
define("USERNAME", "jose");
define("PASSWORD", "josefa");
define("NAMEDB", "bd_tienda");

// funcion que comprueba si el par de usuario y clave estan en la bd
// datos["mensaje_error] => si hay algun error al pedir el servicio
// $datos["usuario"] => si no existe el usuario en la base de datos, los datos del usuario
// $datos["mensaje"] => si no se encuentra en la base de datos
function estaLogeado($usuario, $clave){

    try{
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }catch(PDOException $e){
        return array("mensaje_error" => "No se ha podido conectar a la base de datos".$e->getMessage());
    }

    try{
        $consulta = "select * from usuarios where usuario=? and clave=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$usuario, $clave]);
    }catch(PDOException $e){
        $sentencia = null;
        $consulta = null;
        return array("mensaje_error" => "No se ha podido conectar a la base de datos".$e->getMessage());
    }

    if($sentencia->rowCount()>0){
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    }else{
        $respuesta["mensaje"] = "El usuario no existe en la base de datos";
    }

    return $respuesta;

}

// servicio que devuelve true o false si esta logeado un usuario o no
$app -> post("/login", function($request){

    // obtener los parametros del post con getParam
    $usuario = $request->getParam("usuario");
    $clave = $request->getParam("clave");

    // enviar el json con lo que devuelve la funcion
    echo json_encode(estaLogeado($usuario,$clave));
});



// Una vez creado servicios los pongo a disposición
$app->run();
?>