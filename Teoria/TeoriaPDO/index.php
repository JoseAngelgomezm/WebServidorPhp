<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>PDO</h1>
    <?php
    define("HOST", "localhost");
    define("USERNAME", "jose");
    define("PASSWORD", "josefa");
    define("NAMEDB", "bd_foro");

    $usuario = "cristo43";
    $clave = md5("cristobal");

    // conexion mediante PDO
    $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . NAMEDB, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

    // consulta mediante PDO
    try {
        // string de la consulta donde los valores son interrogantes
        $consulta = "select * from usuarios where usuario=? and clave=?";
        // preparar la consulta con prepare del objeto conexion
        $sentencia = $conexion->prepare($consulta);
        // pasar los datos a sustituir por los interrogantes en un array , en orden de aparicion de los interrogantes
        $sentencia->execute([$usuario, $clave]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        die("<p>No se ha podido realizar la consulta " . $e->getMessage() . "</p></body></html>");
    }

    // si la sentencia no obtiene rows es que esos datos no existen
    if ($sentencia->rowCount() <= 0) {
        echo "<p>No existe un usuario con esas credenciales</p>";
    } else {
        // el resultado se obtiene del objeto sentencia con el fetch
        $resultadoUsuario = $sentencia->fetch(PDO::FETCH_ASSOC); // tambien tenemos PDO::FETCH_NUM Y PDO::FETCH_OBJECT
        echo "<p>El usuario es " . $resultadoUsuario["usuario"] . " y la constraseña es " . $resultadoUsuario["clave"] . "</p>";

    }

    // CONSULTAS CON MAS DE UN RESULTADO
    
    // consulta mediante PDO
    try {
        // string de la consulta donde los valores son interrogantes, en este caso no buscamos por valores, traemos todos los usuarios
        $consulta = "select * from usuarios";
        // preparar la consulta con el metodo prepare del objeto conexion
        $sentencia = $conexion->prepare($consulta);
        // pasar los datos a sustituir por los interrogantes en un array , en orden de aparicion de los interrogantes, como no hay, no se le pasa nada
        $sentencia->execute();
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        die("<p>No se ha podido realizar la consulta " . $e->getMessage() . "</p></body></html>");
    }

    echo "<h2>Todos los usuarios</h2>";
    // si la sentencia no obtiene rows es que la tabla esta vacia
    if ($sentencia->rowCount() <= 0) {
        echo "<p>No hay datos aun en la base de datos</p>";
    } else {
        // el resultado se obtiene del objeto $sentencia con el fetchAll
        $resultadoUsuario = $sentencia->fetchAll(PDO::FETCH_ASSOC); // tambien tenemos PDO::FETCH_NUM Y PDO::FETCH_OBJECT
        // recorrer cada resultado con un foreach
        foreach ($resultadoUsuario as $cadaResultado)
            echo "<p>El usuario es " . $cadaResultado["usuario"] . "</p>";
    }

    // SENTENCIA INSERT
    $nombre = "jose";
    $usuario = "joselito";
    $clave = md5("joselito");
    $email = "joselito@gmail.com";
    $insertar = false;

    if ($insertar) {
        // consulta mediante PDO
        try {
            // string de la consulta donde los valores son interrogantes
            $consulta = "INSERT INTO usuarios (nombre,usuario,clave,email) values(?, ?, ?, ?)";
            // preparar la consulta con el metodo prepare del objeto conexion
            $sentencia = $conexion->prepare($consulta);
            // pasar los datos a sustituir por los interrogantes en un array , en orden de aparicion de los interrogantes
            $sentencia->execute([$nombre, $usuario, $clave, $email]);
        } catch (PDOException $e) {
            $sentencia = null;
            $conexion = null;
            die("<p>No se ha podido realizar la consulta " . $e->getMessage() . "</p></body></html>");
        }
        // obtener el ultimo insertado
        echo "<p>Insertado el usuario predefinido" . $conexion->lastInsertId() . "</p>";
    } else {
        echo "<p>No se ha insertado, cambia el valor a true</p>";
    }


    // siempre al terminar de trabajar con la conexion
    $sentencia = null;
    $conexion = null;


    ?>
</body>

</html>