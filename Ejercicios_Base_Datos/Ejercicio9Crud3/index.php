<?php

$user = "jose";
$password = "josefa";
$host = "localhost";
$bd = "bd_videoclub";



function errores($texto)
{
    return "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Document</title>
    </head>
    <body>
        <p>" . $texto . "</p>
    </body>
    </html>";
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practica 9</title>
</head>

<body>


    <!-- SIEMPRE MOSTRAR LA TABLA -->
    <?php
    // intentar la conexion para acceder a los datos de la bd
    try {
        $conexionVideoClub = mysqli_connect($host, $user, $password, $bd);
    } catch (Exception $e) {
        die(errores("No se ha podido conectar a la base de datos para mostrar la tabla"));
    }

    // intentar la conexion para acceder a los datos de la bd
    try {
        $consultaSelect = "SELECT * from peliculas";
        $resultadoSelect = mysqli_query($conexionVideoClub, $consultaSelect);
    } catch (Exception $e) {
        mysqli_close($conexionVideoClub);
        die(errores("No se ha podido conectar a la base de datos para mostrar la tabla"));
    }

    // obtener los datos 
    $datosUsuariosSelect = mysqli_fetch_assoc($resultadoSelect);

    // montar la tabla
    foreach ($datosUsuarioSelect as $clave => $valor) {
        
    }
    ?>
    <table>

    </table>
</body>

</html>