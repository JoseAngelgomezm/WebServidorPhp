<?php
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
        $_SESSION["clave"] = $respuesta["usuario"]["clave"];
        $respuesta["api_session"] = session_id();

    } else {
        $respuesta["mensaje"] = "el usuario no se encuentra registrado en la bd";
    }

    $conexion = null;
    $sentencia = null;

    return $respuesta;
}

function logueado()
{

    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
    }

    try {
        $consulta = "SELECT * FROM usuarios WHERE usuario=? AND clave=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$_SESSION["usuario"], $_SESSION["clave"]]);
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
    }


    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);

    } else {
        $respuesta["mensaje"] = "El usuario no se encuentra regis. en la bd";
    }

    $sentencia = null;
    $conexion = null;
    return $respuesta;
}

function obtenerProductos()
{

    // conectarnos a la bd mediante pdo
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (Exception $e) {
        $respuesta["mensaje"] = "No se ha podido conectar a la base de datos" . $e->getMessage();
        return $respuesta;
    }

    // realizar la consulta
    try {
        $consulta = "SELECT * from producto";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute();
    } catch (Exception $e) {
        $respuesta["mensaje"] = "No se ha podido consulta a la base de datos" . $e->getMessage();
        return $respuesta;
    }

    // obtener los productos
    $respuesta["productos"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    // limpiar sentencia y conexion
    $sentencia = null;
    $conexion = null;

    // devolver
    return $respuesta;
}

function obtenerProductosCodigo($codigo)
{

    // conectarnos a la bd mediante pdo
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (Exception $e) {
        $respuesta["mensaje"] = "No se ha podido conectar a la base de datos" . $e->getMessage();
        return $respuesta;
    }

    // realizar la consulta
    try {
        $consulta = "SELECT * FROM producto WHERE cod=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$codigo]);
    } catch (Exception $e) {
        $respuesta["mensaje"] = "No se ha podido consulta a la base de datos" . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        // obtener el producto
        $respuesta["producto"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    } else {
        $respuesta["mensaje"] = "El producto con codigo " . $codigo . " no existe";
    }

    // limpiar sentencia y conexion
    $sentencia = null;
    $conexion = null;

    // devolver
    return $respuesta;
}

function insertarProducto($datos)
{

    // conectarnos a la bd mediante pdo
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (Exception $e) {
        $respuesta["mensaje"] = "No se ha podido conectar a la base de datos" . $e->getMessage();
        return $respuesta;
    }

    // realizar la consulta
    try {
        $consulta = "INSERT INTO `producto`(`cod`, `nombre`, `nombre_corto`, `descripcion`, `PVP`, `familia`) VALUES (?,?,?,?,?,?)";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["cod"], $datos["nombre"], $datos["nombre_corto"], $datos["descripcion"], $datos["PVP"], $datos["familia"]]);
    } catch (Exception $e) {
        $respuesta["mensaje"] = "No se ha podido consulta a la base de datos" . $e->getMessage();
        return $respuesta;
    }


    $respuesta["mensaje"] = "El producto " . $datos["nombre_corto"] . " se ha insertado correctamente";

    return $respuesta;
}

function actualizarProducto($datos)
{
    // conectarnos a la bd mediante pdo
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (Exception $e) {
        $respuesta["mensaje"] = "No se ha podido conectar a la base de datos" . $e->getMessage();
        return $respuesta;
    }

    // realizar la consulta
    try {
        $consulta = "UPDATE producto SET nombre=?,nombre_corto=?,descripcion=?,PVP=?,familia=? WHERE cod=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["nombre"], $datos["nombre_corto"], $datos["descripcion"], $datos["PVP"], $datos["familia"], $datos["codigo"]]);
    } catch (Exception $e) {
        $respuesta["mensaje"] = "No se ha podido consulta a la base de datos" . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["mensaje"] = "El producto " . $datos["codigo"] . " se ha actualizado correctamente";
    } else {
        $respuesta["mensaje"] = "El producto " . $datos["codigo"] . " no existe en la base de datos";
    }


    return $respuesta;
}

function borrarProducto($datos)
{
    // conectarnos a la bd mediante pdo
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (Exception $e) {
        $respuesta["mensaje"] = "No se ha podido conectar a la base de datos" . $e->getMessage();
        return $respuesta;
    }

    // realizar la consulta
    try {
        $consulta = "DELETE FROM producto where cod=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["codigo"]]);
    } catch (Exception $e) {
        $respuesta["mensaje"] = "No se ha podido consulta a la base de datos" . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["mensaje"] = "El producto " . $datos["codigo"] . " se ha eliminado correctamente";
    } else {
        $respuesta["mensaje"] = "El producto " . $datos["codigo"] . " no existe en la base de datos";
    }

    return $respuesta;
}

function obtenerFamilias($datos)
{
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (Exception $e) {
        $respuesta["mensaje"] = "No se ha podido conectar a la base de datos" . $e->getMessage();
        return $respuesta;
    }

    // realizar la consulta
    try {
        $consulta = "SELECT * FROM familia";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute();
    } catch (Exception $e) {
        $respuesta["mensaje"] = "No se ha podido consulta a la base de datos" . $e->getMessage();
        return $respuesta;
    }

    $respuesta["familia"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
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
        $respuesta["repetido"] = true;
    } else {
        $respuesta["repetido"] = false;
    }

    return $respuesta;
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
        $respuesta["repetido"] = true;
    } else {
        $respuesta["repetido"] = false;
    }

    return $respuesta;
}

?>