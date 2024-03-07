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
    return $respuesta;
}

function obtenerUsuario($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
    }

    try {
        $consulta = "SELECT * FROM usuarios WHERE usuario=? AND clave=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["usuario"], $datos["clave"]]);
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
    }


    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
        session_start();
        $_SESSION["usuario"] = $respuesta["usuario"]["usuario"];
        $_SESSION["clave"] = $respuesta["usuario"]["clave"];
        $respuesta["api_session"] = session_id();
    } else {
        $respuesta["mensaje"] = "El usuario no se encuentra regis. en la bd";
    }

    $sentencia = null;
    $conexion = null;
    return $respuesta;
}

function obtenerUsuarioLogueado($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        $respuesta["error"] = "No se ha podido conectar a la base de datos";
    }

    try {
        $consulta = "SELECT * FROM usuarios where usuario=? and clave=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["usuario"], $datos["clave"]]);
    } catch (Exception $e) {
        $respuesta["error"] = "No se ha podido conectar a la base de datos";
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    } else {
        $respuesta["mensaje"] = "El usuario no existe en la bd";
    }

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function obtenerHorarioProfesor($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        $respuesta["error"] = "No se ha podido conectar a la base de datos";
    }

    try {
        $consulta = "SELECT * from horario_lectivo, grupos where horario_lectivo.grupo = grupos.id_grupo and usuario =?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["id_usuario"]]);
    } catch (Exception $e) {
        $respuesta["error"] = "No se ha podido realizar la consulta";
    }


    if ($sentencia->rowCount() > 0) {
        $respuesta["horario"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $respuesta["mensaje"] = "Este profesor no tiene horario";
    }

    $sentencia = null;
    $conexion = null;

    return $respuesta;

}

function obtenerProfesoresGuardia($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        $respuesta["error"] = "No se ha podido conectar a la base de datos";
    }

    try {
        $consulta = "SELECT * from horario_lectivo, usuarios where horario_lectivo.usuario = usuarios.id_usuario and horario_lectivo.grupo = '8' and horario_lectivo.dia = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["dia"]]);
    } catch (Exception $e) {
        $respuesta["error"] = "No se ha podido realizar la consulta";
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["profesores_guardia"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    }

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}


?>