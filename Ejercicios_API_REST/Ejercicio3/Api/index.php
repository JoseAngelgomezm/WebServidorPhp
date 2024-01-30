<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

require __DIR__ . '/Slim/autoload.php';

$app = new \Slim\App;

// datos de conexion
define("HOST", "localhost");
define("USERNAME", "jose");
define("PASSWORD", "josefa");
define("NAMEDB", "bd_foro2");

// funcion que comprueba si el par de usuario y clave estan en la bd
// datos["mensaje_error] => si hay algun error al pedir el servicio
// $datos["usuario"] => si no existe el usuario en la base de datos, los datos del usuario
// $datos["mensaje"] => si no se encuentra en la base de datos

function actualizarUsuario($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (PDOException $e) {
        return array("error" => "No se ha podido conectar a la base de datos" . $e->getMessage());
    }

    try {
        $consulta = "select * from usuarios where usuario=? and clave=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["id_usuario"]]);
    } catch (PDOException $e) {
        $sentencia = null;
        $consulta = null;
        return array("error" => "No se ha podido conectar a la base de datos" . $e->getMessage());
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["mensaje"] = "El usuario " . $datos["id_usuario"] . " ha sido actualizado con exito";
    } else {
        $respuesta["mensaje"] = "El usuario " . $datos["id_usuario"] . " no se ha podido actualizar, no existe";
    }


    $sentencia = null;
    $consulta = null;
    return $respuesta;
}

function estaLogeado($usuario, $clave)
{

    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (PDOException $e) {
        return array("error" => "No se ha podido conectar a la base de datos" . $e->getMessage());
    }

    try {
        $consulta = "select * from usuarios where usuario=? and clave=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$usuario, $clave]);
    } catch (PDOException $e) {
        $sentencia = null;
        $consulta = null;
        return array("error" => "No se ha podido conectar a la base de datos" . $e->getMessage());
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    } else {
        $respuesta["mensaje"] = "El usuario no existe en la base de datos";
    }

    $sentencia = null;
    $consulta = null;

    return $respuesta;

}

function obtenerTodosLosUsuarios()
{
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (PDOException $e) {
        return array("error" => "No se ha podido conectar a la base de datos" . $e->getMessage());
    }

    try {
        $consulta = "select * from usuarios";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute();
    } catch (PDOException $e) {
        return array("error" => "No se ha podido consultar a la base de datos" . $e->getMessage());
    }


    // si obtengo datos
    if ($sentencia->rowCount() > 0) {
        $respuesta["usuarios"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        $sentencia = null;
        $consulta = null;
        return $respuesta;
    } else {
        $respuesta["error"] = "no se ha obtenido ningun usuario";
        $sentencia = null;
        $consulta = null;
        return $respuesta;
    }


}

function eliminarUsuario($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (PDOException $e) {
        return array("error" => "No se ha podido conectar a la base de datos" . $e->getMessage());
    }

    try {
        $consulta = "delete from usuarios where id_usuario = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute($datos["id_usuario"]);
    } catch (PDOException $e) {
        return array("error" => "No se ha podido consultar a la base de datos" . $e->getMessage());
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["mensaje"] = "El usuario " . $datos["id_usuario"] . " ha sido eliminado con exito";
    } else {
        $respuesta["mensaje"] = "El usuario " . $datos["id_usuario"] . " no se ha podido eliminar, no existe";
    }

    $sentencia = null;
    $consulta = null;
    return $respuesta;

}

function obtenerRepetidosInsertar($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (Exception $e) {
        $respuesta["mensaje"] = "No se ha podido conectar a la base de datos" . $e->getMessage();
        return $respuesta;
    }

    // realizar la consulta
    try {
        $consulta = "SELECT * FROM " . $datos["tabla"] . " where " . $datos["columna"] . " = ? ";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["valor"]]);
    } catch (Exception $e) {
        $respuesta["mensaje"] = "No se ha podido consulta a la base de datos" . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["mensaje"] = true;
    } else {
        $respuesta["mensaje"] = false;
    }

    return $respuesta;
}

function insertarUsuario($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (PDOException $e) {
        return array("error" => "No se ha podido conectar a la base de datos" . $e->getMessage());
    }

    try {
        $consulta = "insert into usuarios (nombre,usuario,clave,email) values(?,?,?,?)";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["nombre"], $datos["usuario"], $datos["clave"], $datos["email"]]);
    } catch (PDOException $e) {
        return array("error" => "No se ha podido consultar a la base de datos" . $e->getMessage());
    }

    // si obtengo datos
    $respuesta["ult_id"] = $conexion->lastInsertId();
}

function obtenerRepetidosEditar($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (Exception $e) {
        $respuesta["mensaje"] = "No se ha podido conectar a la base de datos" . $e->getMessage();
        return $respuesta;
    }

    // realizar la consulta
    try {
        $consulta = "SELECT * FROM " . $datos["tabla"] . " where " . $datos["columna"] . " = ? and " . $datos["columna_id"] . " <> ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["valor"], $datos["valor_id"]]);
    } catch (Exception $e) {
        $respuesta["mensaje"] = "No se ha podido consulta a la base de datos" . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["mensaje"] = true;
    } else {
        $respuesta["mensaje"] = false;
    }

    return $respuesta;
}

$app->delete("/login_restful/borrarUsuario/{id_usuario}", function ($request) {

    $datos["id_usuario"] = $request->getAttribute();

    echo json_encode(eliminarUsuario($datos));
});

$app->put("/login_restful/actualizarUsuario/{id_usuario}", function ($request) {

    $datos["id_usuario"] = $request->getAttribute();
    $datos["nombre"] = $request->getParam();
    $datos["usuario"] = $request->getParam();
    $datos["clave"] = $request->getParam();
    $datos["email"] = $request->getParam();
    echo json_encode(actualizarUsuario($datos));
});

$app->get("/login_restful/usuarios", function ($request) {

    echo json_encode(obtenerTodosLosUsuarios());

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

// servicio que devuelve true o false si esta logeado un usuario o no
$app->post("/login", function ($request) {

    // obtener los parametros del post con getParam
    $usuario = $request->getParam("usuario");
    $clave = $request->getParam("clave");

    // enviar el json con lo que devuelve la funcion
    echo json_encode(estaLogeado($usuario, $clave));
});

$app->post("login_restful/crearUsuario", function ($request) {
    $datos["nombre"] = $request->getParam();
    $datos["usuario"] = $request->getParam();
    $datos["clave"] = $request->getParam();
    $datos["email"] = $request->getParam();

    echo json_encode(insertarUsuario($datos));
});

// Una vez creado servicios los pongo a disposición
$app->run();
?>