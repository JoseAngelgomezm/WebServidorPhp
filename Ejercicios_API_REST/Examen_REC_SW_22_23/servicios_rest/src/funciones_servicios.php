<?php
require "config_bd.php";

function conexion_pdo()
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

        $respuesta["mensaje"] = "Conexi&oacute;n a la BD realizada con &eacute;xito";

        $conexion = null;
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
    }

    $conexion = null;
    $sentencia = null;
    return $respuesta;
}


function conexion_mysqli()
{

    try {
        $conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
        mysqli_set_charset($conexion, "utf8");
        $respuesta["mensaje"] = "Conexi&oacute;n a la BD realizada con &eacute;xito";
        mysqli_close($conexion);
    } catch (Exception $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
    }

    $conexion = null;
    $sentencia = null;
    return $respuesta;
}


function loguear($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
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

    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);

        session_start();
        $_SESSION["usuario"] = $respuesta["usuario"]["usuario"];
        $respuesta["api_session"] = session_id();

    } else {
        $respuesta["mensaje"] = "el usuario no se encuentra registrado en la bd";
    }

    $conexion = null;
    $sentencia = null;

    return $respuesta;
}

function logueado($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
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

    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);

    } else {
        $respuesta["mensaje"] = "el usuario no se encuentra registrado en la bd";
    }


    $conexion = null;
    $sentencia = null;
    return $respuesta;
}

function obtnerUsuarioId($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        $respuesta["error"] = $e->getMessage();
    }

    try {
        $consulta = "SELECT * FROM usuarios where id_usuario = ? ";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["id_usuario"]]);
    } catch (Exception $e) {
        $respuesta["error"] = $e->getMessage();
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    } else {
        $respuesta["error"] = "El usuario con id" . $datos["id_usuario"] . " no se encuentra registrado en la bd";
    }


    $conexion = null;
    $sentencia = null;
    return $respuesta;
}

function obtenerUsuariosGuardia($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        $respuesta["error"] = $e->getMessage();
    }

    try {
        $consulta = "SELECT * from usuarios, horario_guardias where horario_guardias.usuario = usuarios.id_usuario and dia = ? and hora =  ? ";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["dia"], $datos["hora"]]);
    } catch (Exception $e) {
        $respuesta["error"] = $e->getMessage();
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $respuesta["error"] = "Error al encontrar un usuario con esa hora y ese dia";
    }


    $conexion = null;
    $sentencia = null;
    return $respuesta;
}

function usuarioSiGuardia($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        $respuesta["error"] = $e->getMessage();
    }

    try {
        $consulta = "SELECT * from usuarios, horario_guardias where horario_guardias.usuario = usuarios.id_usuario and dia = ? and hora =  ? and horario_guardias.usuario = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["dia"], $datos["hora"], $datos["id_usuario"]]);
    } catch (Exception $e) {
        $respuesta["error"] = $e->getMessage();
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["de_guardia"] = true;
    } else {
        $respuesta["de_guardia"] = false;
    }

    $conexion = null;
    $sentencia = null;
    return $respuesta;
}

?>