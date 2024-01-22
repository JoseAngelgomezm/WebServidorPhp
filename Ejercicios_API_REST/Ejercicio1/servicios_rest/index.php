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
        $consulta = "INSERT INTO `producto`(`cod`, `nombre`, `nombre_corto`, `descripcion`, `PVP`, `familia`) VALUES ('?,?,?,?,?,?')";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$datos["cod"], $datos["nombre"], $datos["nombre_corto"], $datos["descripcion"], $datos["PVP"], $datos["familia"]]);
    } catch (Exception $e) {
        $respuesta["mensaje"] = "No se ha podido consulta a la base de datos" . $e->getMessage();
        return $respuesta;
    }


    $respuesta["mensaje"] = "El producto " . $datos["nombre_corto"] . " se ha insertado correctamente";


}

require __DIR__ . '/Slim/autoload.php';

$app = new \Slim\App;

// metodo get que devuelve todos los prodcutos
$app->get('/productos', function ($request) {

    // obtener los productos y devolverlos en un json
    echo json_encode(obtenerProductos());

});

// metodo get que devuelve un producto dado un codigo de producto
$app->get('/producto/{codigo}', function ($request) {

    $codigoProducto = $request->getAttribute('codigo');

    // obtener el producto y devolverlos en un json
    echo json_encode(obtenerProductosCodigo($codigoProducto));

});

$app->post('/producto/insertar', function ($request) {

    $datos["cod"] = $request->getParam('cod');
    $datos["nombre"] = $request->getParam('nombre_corto');
    $datos["nombre_corto"] = $request->getParam('nombre_corto');
    $datos["descripcion"] = $request->getParam('descripcion');
    $datos["PVP"] = $request->getParam('PVP');
    $datos["familia"] = $request->getParam('familia');

    echo json_encode(insertarProducto($datos));

});

// Una vez creado servicios los pongo a disposición
$app->run();
