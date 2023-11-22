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
    <style>
        img{width:200px}
        table{margin: 0 auto; text-align: center; width:70% ; border: solid 10px lightgreen}
        td{border: solid 2px black}
    </style>
</head>

<body>
      <!-- Si se han pulsado los botones editar o borrar -->
    <?php
        if(isset($_POST["botonEditar"])){

        }else if(isset($_POST["botonBorrar"])){

        }
    ?>

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
        die(errores("No se ha podido consultar las peliculas para mostrar la tabla"));
    }

    // obtener los datos 
    


    echo "<table>";
    echo "<tr><td>idPelicula</td><td>titulo</td><td>director</td><td>sinopsis</td><td>tematica</td><td>caratula</td><td>acciones</td></tr>";
    // montar la tabla
    while ($datosUsuariosSelect = mysqli_fetch_assoc($resultadoSelect)) {
        echo "<tr>";

        echo "<td>" . $datosUsuariosSelect["idPelicula"] . "</td>";
        echo "<td>" . $datosUsuariosSelect["titulo"] . "</td>";
        echo "<td>" . $datosUsuariosSelect["director"] . "</td>";
        echo "<td>" . $datosUsuariosSelect["sinopsis"] . "</td>";
        echo "<td>" . $datosUsuariosSelect["tematica"] . "</td>";
        echo "<td><img src='img/" . $datosUsuariosSelect["caratula"] . "'</td>";
        echo "<td>
        <form method='post' action='#'>
        <button type='submit' name='botonEditar' value='".$datosUsuariosSelect["idPelicula"]."'>Borrar</button> - 
        <button type='submit' name='botonBorrar' value='".$datosUsuariosSelect["idPelicula"]."'>Editar</button>
        </form>";

        echo "</tr>";
    }
    echo "</table>";
    ?>
</body>

</html>