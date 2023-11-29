<?php
session_name("sesionPractica9");
session_start();
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

if (isset($_POST["atras"])) {
    header("location:index.php");

} else if (isset($_POST["guardarEdicion"])) {
    // comprobar los errores
    $errorTitulo = $_POST["titulo"] == "" || strlen($_POST["titulo"]) > 15;
    $errorDirector = $_POST["director"] == "" || strlen($_POST["director"]) > 20;
    $errorSinopsis = $_POST["sinopsis"] == "";
    $errorTematica = $_POST["tematica"] == "" || strlen($_POST["tematica"]) > 15;

    $errorFormularioEdicion = $errorTitulo || $errorDirector || $errorSinopsis || $errorTematica;

    // si no hay errores en el formulario, preguntar si hay foto
    if (!$errorFormularioEdicion) {

        if ($_FILES["fotoCaratula"]["name"] !== "") {

            // comprobar errores de la imagen
            $errorImagen = !getimagesize($_FILES["fotoCaratula"]["tmp_name"]) || $_FILES["fotoCaratula"]["error"];
            $errorFormulario = $errorImagen;
            if (!$errorImagen) {
                // si no hay error en foto, quedarnos con la extension
                $nombreFicheroPartes = explode(".", $_FILES["fotoCaratula"]["name"]);
                // si el fichero tiene mas de un punto, quedaros con el ultimo
                if (count($nombreFicheroPartes) > 1) {
                    $extension = end($nombreFicheroPartes);
                }

                // crear la variable que contedra el directorio de va la imagen con su nombre
                $directorioDestino = "img/" . $_POST["tituloPelicula"] . "." . $extension . "";

                // eliminar la foto si la tenia
                if (file_exists(".$directorioDestino.")) {
                    unlink($_POST["tituloPelicula"] . "." . $extension);
                }

                // Intentar moverla a nuestro directorio del servidor
                @$var = move_uploaded_file($_FILES["fotoCaratula"]["tmp_name"], $directorioDestino);

                if ($var) {
                    // si se consigue mover, hacer el update 

                    // intentar la conexion para acceder a los datos de la bd
                    try {
                        $conexionVideoClub = mysqli_connect($host, $user, $password, $bd);
                    } catch (Exception $e) {
                        die(errores("No se ha podido conectar a la base de datos para editar la caratula pelicula"));
                    }

                    // intentar la consulta de la caratula
                    try {
                        $consultaSelect = "UPDATE peliculas SET caratula='" . $_POST["tituloPelicula"] . "." . $extension . "' WHERE idPelicula='" . $_POST["guardarEdicion"] . "'";
                        $resultadoSelect = mysqli_query($conexionVideoClub, $consultaSelect);
                    } catch (Exception $e) {
                        mysqli_close($conexionVideoClub);
                        die(errores("No se ha podido consultar las peliculas editar los datos de una pelicula"));
                    }

                    // si hay foto
                    $_SESSION["mensajeEdicion"] = "<p>Se ha modificado una pelicula con exito</p>";
                    session_destroy();


                } else {
                    mysqli_close($conexionVideoClub);
                    echo "<p>No se ha podido mover la imagen</p>";
                }
                mysqli_close($conexionVideoClub);
            }


        } else {
            // si no hay foto
            $_SESSION["mensajeEdicion"] = "<p>Se ha modificado una pelicula con exito, pero sin foto</p>";
            session_destroy();
        }
    }

    // SI EXISTE EL BOTON INSERTAR NUEVO USUARIO
} else if (isset($_POST["insertarNuevaPelicula"])) {
    // comprobar los errores
    $errorTitulo = $_POST["titulo"] == "" || strlen($_POST["titulo"]) > 15;
    $errorDirector = $_POST["director"] == "" || strlen($_POST["director"]) > 20;
    $errorSinopsis = $_POST["sinopsis"] == "";
    $errorTematica = $_POST["tematica"] == "" || strlen($_POST["tematica"]) > 15;

    $errorFormularioInsercion = $errorTitulo || $errorDirector || $errorSinopsis || $errorTematica;

    // si no hay errores en el formulario
    if (!$errorFormularioInsercion) {

        // si no hay error del formulario de insercion
        // intentar la conexion para insertar la pelicula
        try {
            $conexionVideoClub = mysqli_connect($host, $user, $password, $bd);
        } catch (Exception $e) {
            die(errores("No se ha podido conectar a la base de datos para insertar la pelicula"));
        }

        // intentar la consulta de la pelicula
        try {
            $consultaInsert = "INSERT INTO `peliculas`(`titulo`, `director`, `sinopsis`, `tematica`) VALUES ('" . $_POST["titulo"] . "','" . $_POST["director"] . "','" . $_POST["sinopsis"] . "','" . $_POST["tematica"] . "')";
            $resultadoSelect = mysqli_query($conexionVideoClub, $consultaInsert);
        } catch (Exception $e) {
            mysqli_close($conexionVideoClub);
            die(errores("No se ha podido insertar la pelicula"));
        }

        // preguntar si hay foto
        if ($_FILES["fotoCaratula"]["name"] !== "") {

            // comprobar errores de la imagen
            $errorImagen = !getimagesize($_FILES["fotoCaratula"]["tmp_name"]) || $_FILES["fotoCaratula"]["error"];
            $errorFormulario = $errorImagen;
            if (!$errorImagen) {
                // si no hay error en foto, quedarnos con la extension
                $nombreFicheroPartes = explode(".", $_FILES["fotoCaratula"]["name"]);
                // si el fichero tiene mas de un punto, quedaros con el ultimo
                if (count($nombreFicheroPartes) > 1) {
                    $extension = end($nombreFicheroPartes);
                }

                // crear la variable que contedra el directorio de va la imagen con su nombre
                $directorioDestino = "img/" . $_POST["tituloPelicula"] . "." . $extension . "";

                // eliminar la foto si la tenia
                if (file_exists(".$directorioDestino.")) {
                    unlink($_POST["tituloPelicula"] . "." . $extension);
                }

                // Intentar moverla a nuestro directorio del servidor
                @$var = move_uploaded_file($_FILES["fotoCaratula"]["tmp_name"], $directorioDestino);

                if ($var) {
                    // si se consigue mover, hacer el update 

                    // obtener el id de la ultima pelicula insertada
                    $ultimoIDpelicula = mysqli_insert_id($conexionVideoClub);

                    // intentar la conexion para acceder a los datos de la bd
                    try {
                        $conexionVideoClub = mysqli_connect($host, $user, $password, $bd);
                    } catch (Exception $e) {
                        die(errores("No se ha podido conectar a la base de datos para editar la caratula pelicula"));
                    }

                    // intentar la consulta de la caratula
                    try {
                        $consultaSelect = "UPDATE peliculas SET caratula='" . $_POST["tituloPelicula"] . "." . $extension . "' WHERE idPelicula='" . $ultimoIDpelicula . "'";
                        $resultadoSelect = mysqli_query($conexionVideoClub, $consultaSelect);
                    } catch (Exception $e) {
                        mysqli_close($conexionVideoClub);
                        die(errores("No se ha podido consultar las peliculas editar los datos de una pelicula"));
                    }



                } else {
                    mysqli_close($conexionVideoClub);
                    echo "<p>No se ha podido mover la imagen</p>";
                }
                mysqli_close($conexionVideoClub);
                $_SESSION["mensajeInsertar"] = "<p>Se ha insertado una pelicula con exito</p>";
                session_destroy();
            }


        } else {
            // si no hay foto
            $_SESSION["mensajeInsertar"] = "<p>Se ha insertado una pelicula con exito, pero sin foto</p>";
            session_destroy();
        }
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

        textarea {
            resize: none;
        }
    </style>
</head>

<body>

    <?php
    if (isset($_SESSION["mensajeBorrado"])) {
        echo $_SESSION["mensajeBorrado"];
    } else if (isset($_SESSION["mensajeEdicion"])) {
        echo $_SESSION["mensajeEdicion"];
    } else if (isset($_SESSION["mensajeInsertar"])) {
        echo $_SESSION["mensajeInsertar"];
    }

    // Si se han pulsado los botones editar o borrar
    if (isset($_POST["botonEditar"]) || isset($errorFormularioEdicion) && $errorFormularioEdicion) {
        require("vistaBotonEditar.php");

    } else if (isset($_POST["botonBorrar"])) {

        echo "<h1>Borrado de un usuario</h1>";
        require("vistaBotonBorrar.php");

    } else if (isset($_POST["botonVerDatos"])) {
        echo "<h1>Datos de la película " . $_POST["botonVerDatos"] . "</h1>";
        require("vistaBotonVerDatos.php");
    } else if (isset($_POST["botonNuevaPelicula"]) || isset($errorFormularioInsercion) && $errorFormularioInsercion) {
        require("vistaNuevaPelicula.php");
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