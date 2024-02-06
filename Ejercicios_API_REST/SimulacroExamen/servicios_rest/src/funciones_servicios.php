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

function loginUsuario($lector, $clave)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $consulta = "SELECT * FROM usuarios WHERE lector=? AND clave=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$lector, $clave]);

    } catch (PDOException $e) {
        $respuesta["error"] = "No se puede realizar la consulta" . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
        session_name("Examen_SW_23_24");
        session_start();

        $_SESSION["usuario"] = $respuesta["usuario"]["lector"];
        $_SESSION["clave"] = $respuesta["usuario"]["clave"];
        $_SESSION["tipo"] = $respuesta["usuario"]["tipo"];
        $respuesta["api_session"] = session_id();

    } else {
        $respuesta["mensaje"] = "El usuario no se encuentra registrado en la BD";
    }

    $conexion = null;
    $sentencia = null;
    return $respuesta;
}

function usuarioLogueado($lector, $clave)
{
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (PDOException $e) {
        return array("error" => "No se ha podido conectar a la base de datos" . $e->getMessage());
    }

    try {
        $consulta = "SELECT * FROM usuarios WHERE lector=? AND clave=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$lector, $clave]);

    } catch (PDOException $e) {
        $respuesta["error"] = "No se puede realizar la consulta" . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    } else {
        $respuesta["mensaje"] = "El usuario no se encuentra registrado en la BD";
    }

    $conexion = null;
    $sentencia = null;
    return $respuesta;
}

function obtenerLibros()
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $consulta = "SELECT * FROM libros";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute();
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
    }

    if ($sentencia->rowCount() > 0) {

        $respuesta["libros"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    } else {

        $respuesta["mensaje"] = "El usuario no se encuentra registrado en la BD";

    }

    return $respuesta;
}

function insertar_libro($datos)
{

    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $consulta = "INSERT INTO libros (referencia,titulo,autor,descripcion,precio) values (?,?,?,?,?)";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["referencia"], $datos["titulo"], $datos["autor"], $datos["descripcion"], $datos["precio"]]);

    } catch (PDOException $e) {
        $respuesta["error"] = "No se puede realizar la consulta" . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["mensaje"] = "Libro insertado correctamente";
    }

    $conexion = null;
    $sentencia = null;
    return $respuesta;

}

function actualizarPortada($datos)
{

    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $consulta = "UPDATE libros set portada=? where referencia = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["nombre"], $datos["referencia"]]);

    } catch (PDOException $e) {
        $respuesta["error"] = "No se puede realizar la consulta" . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["mensaje"] = "Portada actualizada correctamente";
    }

    $conexion = null;
    $sentencia = null;
    return $respuesta;

}

function consultarRepetido($datos)
{

    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $consulta = "SELECT * FROM ? WHERE ? = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["tabla"], $datos["columna"], $datos["valor"]]);

    } catch (PDOException $e) {
        $respuesta["error"] = "No se puede realizar la consulta" . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["repetido"] = true;
    } else {
        $respuesta["repetido"] = false;
    }

    $conexion = null;
    $sentencia = null;
    return $respuesta;

}


?>