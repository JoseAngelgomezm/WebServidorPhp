<?php
// al princpipio del codigo, iniciar la sesion de conexion para poder acceder a la varible reservada $_SESSION
session_start();

// si se ha pulsado borrar sesion , saltara a esta misma pagina y borrara los datos
if (isset($_POST["borrarSesion"])) {
    // con sesion unset elimina los datos al instante
    session_unset();
    // con sesion destroy los datos se mostraran en esta ejecucion, a partir de la siguiente no
    session_destroy();
    
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibimos Sesion</title>
</head>

<body>
    <h1>Estyo en la web de recibido</h1>
    <p>Tu nombre es:
        <?php echo $_SESSION["nombre"] ?>
    </p>
    <p>Tu clave es:
        <?php echo $_SESSION["clave"] ?>
    </p>

    <p><a href="teoriaSesiones.php">Ir a creacion</a></p>
</body>

</html>