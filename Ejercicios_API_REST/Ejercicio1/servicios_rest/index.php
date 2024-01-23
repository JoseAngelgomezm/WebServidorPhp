<?php

define("HOST", "localhost");
define("USERNAME", "jose");
define("PASSWORD", "josefa");
define("NAMEDB", "bd_tienda");

function obtenerProductos()
{

    // conectarnos a la bd mediante pdo
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    } catch (Exception $e) {
        $respuesta["mensaje_error"] = "No se ha podido conectar a la base de datos" . $e->getMessage();
        return $respuesta;
    }

    // realizar la consulta
    try {
        $consulta = "SELECT * from producto";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute();
    } catch (Exception $e) {
        $respuesta["mensaje_error"] = "No se ha podido consulta a la base de datos" . $e->getMessage();
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
        $consulta = "SELECT * FROM ".$datos["tabla"]." where ".$datos["columna"]." = ? ";
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
        $consulta = "SELECT * FROM ".$datos["tabla"]." where ".$datos["columna"]." = ? and ".$datos["columna_id"]." <> ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["valor"] , $datos["valor_id"]]);
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

require __DIR__ . '/Slim/autoload.php';

$app = new \Slim\App;

// metodo GET que devuelve todos los productos
$app->get('/productos', function ($request) {

    // obtener los productos y devolverlos en un json
    echo json_encode(obtenerProductos());

});

// metodo GET que devuelve un producto dado un codigo de producto
$app->get('/producto/{codigo}', function ($request) {

    $codigoProducto = $request->getAttribute('codigo');

    // obtener el producto y devolverlos en un json
    echo json_encode(obtenerProductosCodigo($codigoProducto));

});

// metodo POST que inserta un producto
$app->post('/producto/insertar', function ($request) {

    $datos["cod"] = $request->getParam('cod');
    $datos["nombre"] = $request->getParam('nombre_corto');
    $datos["nombre_corto"] = $request->getParam('nombre_corto');
    $datos["descripcion"] = $request->getParam('descripcion');
    $datos["PVP"] = $request->getParam('PVP');
    $datos["familia"] = $request->getParam('familia');

    echo json_encode(insertarProducto($datos));

});

// metodo POST que actualizar un producto
$app->post('/producto/actualizar/{codigo}', function ($request) {

    $datos["codigo"] = $request->getAttribute("codigo");
    $datos["nombre"] = $request->getParam("nombre");
    $datos["nombre_corto"] = $request->getParam("nombre_corto");
    $datos["descripcion"] = $request->getParam("descripcion");
    $datos["PVP"] = $request->getParam("PVP");
    $datos["familia"] = $request->getParam("familia");

    echo json_encode(actualizarProducto($datos));
});


// metodo POST que borra un producto
$app->post('/producto/borrar/{codigo}', function ($request) {

    $datos["codigo"] = $request->getAttribute('codigo');

    echo json_encode(borrarProducto($datos));
});

// metodo get que devuelve la informacion de las familias
$app->get('/familias', function ($request) {

    $datos["codigo"] = $request->getAttribute('codigo');

    echo json_encode(obtenerFamilias($datos));
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

// Una vez creado los servicios los pongo a disposición
$app->run();
