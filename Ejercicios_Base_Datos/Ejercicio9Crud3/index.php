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

if(isset($_POST["atras"])){
    header("location:index.php");
}else if(isset($_POST["guardarEdicion"])){
    // comprobar los errores
    $errorTitulo = $_POST["titulo"] == "" || strlen($_POST["titulo"]) > 15;
    $errorDirector = $_POST["director"] == "" || strlen($_POST["director"]) > 20;
    $errorSinopsis = $_POST["sinopsis"] == "" ;
    $errorTematica = $_POST["tematica"] == "" || strlen($_POST["tematica"]) > 15;
    $errorCaratula = $_POST["caratula"] == "" || strlen($_POST["caratula"]) > 30;
    
   $errorFormularioEdicion = $errorTitulo  || $errorDirector || $errorSinopsis || $errorTematica || $errorCaratula;

   // si no hay errores en el formulario, preguntar si hay foto
   if($_FILES["fotoCaratula"]["name"] !== ""){
    
    // si hay foto, quedarnos con la extension
    $nombreFicheroPartes = explode(".",$_FILES["fotoCaratula"]["name"]);
    // si el fichero tiene mas de un punto, quedaros con el ultimo
    if(count($nombreFicheroPartes) > 1){
        $extension = end($nombreFicheroPartes);
    }

    // crear la variable que contedra el directorio de va la imagen con su nombre
    $directorioDestino = "img/".$_POST["guardarEdicion"].".".$extension."";

    // Intentar moverla a nuestro directorio del servidor
    @$var = move_uploaded_file($_FILES["fotoCaratula"]["tmp_name"] , $directorioDestino);


   }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practica 9</title>
    <style>
        img {
            width: 200px
        }

        table {
            margin: 0 auto;
            text-align: center;
            width: 70%;
            border: solid 10px lightgreen
        }

        td {
            border: solid 2px black
        }

        textarea{
            resize: none;
        }
    </style>
</head>

<body>
    <!-- Si se han pulsado los botones editar o borrar -->
    <?php
    if (isset($_POST["botonEditar"])) {

        echo "<h1>Edicion de un usuario</h1>";
        require("vistaBotonEditar.php");

    } else if (isset($_POST["botonBorrar"])) {

        echo "<h1>Borrado de un usuario</h1>";
        require("vistaBotonBorrar.php");

    } else if (isset($_POST["botonVerDatos"])) {
        echo "<h1>Datos de la película " . $_POST["botonVerDatos"] . "</h1>";
        require("vistaBotonVerDatos.php");
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
    echo "<tr>
    <td>idPelicula</td>
    <td>titulo</td>
    <td>director</td>
    <td>
    <form method='post' action='#'> 
    <button type='submit' name='botonNuevaPelicula'>Nueva Pelicula+</button> 
    </form>
    </td>
    </tr>";
    // montar la tabla
    while ($datosUsuariosSelect = mysqli_fetch_assoc($resultadoSelect)) {
        echo "<tr>";

        echo "<td>" . $datosUsuariosSelect["idPelicula"] . "</td>";
        echo "<td>
            <form method='post' action='#'>
            <button name='botonVerDatos' type='submit' value='" . $datosUsuariosSelect["idPelicula"] . "'>" . $datosUsuariosSelect["titulo"] . "</button>
            </form>
            </td>";
        echo "<td><img src='img/" . $datosUsuariosSelect["caratula"] . "'</td>";
        echo "<td>
            <form method='post' action='#'>
            <button type='submit' name='botonBorrar' value='" . $datosUsuariosSelect["idPelicula"] . "'>Borrar</button> - 
            <button type='submit' name='botonEditar' value='" . $datosUsuariosSelect["idPelicula"] . "'>Editar</button>
            </form>";

        echo "</tr>";
    }
    echo "</table>";
    ?>
</body>

</html>