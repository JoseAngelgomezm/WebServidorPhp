<?php
// al princpipio del codigo, iniciar la sesion de conexion para acceder a la variable $_SESSION reservada
session_start();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teoria Sesiones</title>
</head>

<body>
    <h1>Teoria de Sesiones</h1>
    <?php
    // si no existe crearlas, para no crearlas cada vez que se entra al index
    if (!isset($_SESSION["nombre"])) {
        // se crea la variable $_SESSION que es un array associativo en el cual podemos guardar
        // datos mientras la sesion este activa
        $_SESSION["nombre"] = "Jose Angel";
        $_SESSION["clave"] = md5("unaClave");
    }



    ?>
    <p>Tu nombre es:
        <?php echo $_SESSION["nombre"] ?>
    </p>
    <p>Tu clave es:
        <?php echo $_SESSION["clave"] ?>
    </p>

    <p><a href="recibido.php">Ir a Recibido</a></p>

    <form action="recibido.php" method="post">
        <button type="submit" name="borrarSesion">Borrar datos sesión</button>
    </form>
</body>

</html>