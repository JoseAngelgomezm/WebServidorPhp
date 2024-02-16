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


function login($datos)
{

    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        return array("error" => "Error al conectarse a la base de datos " . $e . "");
    }

    try {
        $consulta = "SELECT * FROM usuarios WHERE usuario=? and clave=? ";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["usuario"], $datos["contraseña"]]);
    } catch (Exception $e) {
        return array("error" => "Error realizar la consulta " . $e . "");
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);

        session_start();
        $_SESSION["usuario"] = $respuesta["usuario"]["usuario"];
        $_SESSION["clave"] = $respuesta["usuario"]["clave"];
        $respuesta["api_session"] = session_id();

    } else {
        return array("error" => "No se ha obtenido ninguna tupla");
    }

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function logueado($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        return array("error" => "Error al conectarse a la base de datos " . $e . "");
    }

    try {
        $consulta = "SELECT * FROM usuarios WHERE usuario = ? and clave = ? ";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["usuario"], $datos["contraseña"]]);
    } catch (Exception $e) {
        return array("error" => "Error realizar la consulta " . $e . "");
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    } else {
        return array("error" => "No se ha obtenido ninguna tupla");
    }

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function obtenerHorarios($datos)
{

    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        return array("error" => "Error al conectarse a la base de datos " . $e . "");
    }

    try {
        $consulta = "SELECT * FROM horario_lectivo, grupos WHERE horario_lectivo.grupo=grupos.id_grupo AND usuario = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["id_usuario"]]);
    } catch (Exception $e) {
        return array("error" => "Error realizar la consulta " . $e . "");
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["horario"] = $sentencia->fetchall(PDO::FETCH_ASSOC);
    } else {
        return array("error" => "No se ha obtenido ninguna tupla");
    }

    $sentencia = null;
    $conexion = null;

    return $respuesta;

}
;

function obtenerHorarioGuardia($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        return array("error" => "Error al conectarse a la base de datos " . $e . "");
    }

    try {
        $consulta = "SELECT * FROM horario_lectivo , usuarios WHERE horario_lectivo.usuario=usuarios.id_usuario AND dia = ? AND grupo = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["dia"], $datos["id_grupo"]]);
    } catch (Exception $e) {
        return array("error" => "Error realizar la consulta " . $e . "");
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["horario"] = $sentencia->fetchall(PDO::FETCH_ASSOC);
    } else {
        return array("error" => "No se ha obtenido ninguna tupla");
    }

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function obtenerNombreGrupo($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        return array("error" => "Error al conectarse a la base de datos " . $e . "");
    }

    try {
        $consulta = "SELECT id_grupo FROM grupos WHERE nombre = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["nombre_grupo"]]);
    } catch (Exception $e) {
        return array("error" => "Error realizar la consulta " . $e . "");
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["id_grupo"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    } else {
        return array("error" => "No se ha obtenido ninguna tupla");
    }

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function obtenerUsuario($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        return array("error" => "Error al conectarse a la base de datos " . $e . "");
    }

    try {
        $consulta = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["id_usuario"]]);
    } catch (Exception $e) {
        return array("error" => "Error realizar la consulta " . $e . "");
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    } else {
        return array("error" => "No se ha obtenido ninguna tupla");
    }

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

?>