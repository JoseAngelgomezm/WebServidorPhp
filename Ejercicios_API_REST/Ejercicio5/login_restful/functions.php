<?php

// datos de conexion
define("HOST", "localhost");
define("USERNAME", "jose");
define("PASSWORD", "josefa");
define("NAMEDB", "bd_foro2");

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


function insertarUsuario($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (PDOException $e) {
        return array("error" => "No se ha podido conectar a la base de datos" . $e->getMessage());
    }

    try {
        $consulta = "INSERT INTO `usuarios` (`nombre`, `usuario`, `clave`, `email`) values(?,?,?,?)";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["nombre"], $datos["usuario"], $datos["clave"], $datos["email"]]);
    } catch (PDOException $e) {
        return array("error" => "No se ha podido consultar a la base de datos" . $e->getMessage());
    }

    // si obtengo datos
    $respuesta["ult_id"] = $conexion->lastInsertId();

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
        session_destroy();
        $sentencia = null;
        $consulta = null;
        return array("error" => "No se ha podido conectar a la base de datos" . $e->getMessage());
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    } else {
        $respuesta["mensaje"] = "El usuario no existe en la base de datos";
        $respuesta["token"] = session_id();
    }

    $sentencia = null;
    $consulta = null;

    return $respuesta;

}




function actualizarUsuarioConClave($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (PDOException $e) {
        return array("error" => "No se ha podido conectar a la base de datos" . $e->getMessage());
    }

    try {
        $consulta = "UPDATE `usuarios` SET `nombre`=?,`usuario`=?,`clave`=?,`email`= ? WHERE `id_usuario`= ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["nombre"], $datos["usuario"], $datos["clave"], $datos["email"], $datos["id_usuario"]]);
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


function actualizarUsuarioSinClave($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (PDOException $e) {
        return array("error" => "No se ha podido conectar a la base de datos" . $e->getMessage());
    }

    try {
        $consulta = "UPDATE usuarios SET nombre=?,usuario=?,email=? WHERE id_usuario=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["nombre"], $datos["usuario"], $datos["email"], $datos["id_usuario"]]);
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




function eliminarUsuario($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (PDOException $e) {
        return array("error" => "No se ha podido conectar a la base de datos" . $e->getMessage());
    }

    try {
        $consulta = "DELETE FROM usuarios where id_usuario=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["id_usuario"]]);
    } catch (PDOException $e) {
        return array("error" => "No se ha podido consultar a la base de datos" . $e->getMessage());
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["mensaje"] = "El usuario " . $datos["id_usuario"] . " ha sido eliminado con exito";
    } else {
        $respuesta["mensaje"] = "El usuario " . $datos["id_usuario"] . " no ha sido eliminado con exito, porque no existe";
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
        return array("error" => "No se ha podido conectar a la base de datos" . $e->getMessage());
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

    $sentencia = null;
    $consulta = null;
    return $respuesta;
}



function obtenerRepetidosEditar($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (Exception $e) {
        return array("error" => "No se ha podido conectar a la base de datos" . $e->getMessage());
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

    $sentencia = null;
    $consulta = null;
    return $respuesta;
}

function obtenerUsuarioID($datos)
{

    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (Exception $e) {
        return array("error" => "No se ha podido conectar a la base de datos" . $e->getMessage());
    }

    // realizar la consulta
    try {
        $consulta = "SELECT * FROM usuarios where id_usuario=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["id_usuario"]]);
    } catch (Exception $e) {
        $respuesta["error"] = "No se ha podido consulta a la base de datos" . $e->getMessage();
        return $respuesta;
    }

    // si se ha obtenido algo
    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    } else {
        $respuesta["usuario"] = -1;
    }

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}
?>