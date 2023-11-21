<?php

// determinar los datos de conexion
$host = "localhost";
$user = "jose";
$pass = "josefa";
$bd = "bd_cv";


if (isset($_POST["atras"])) {
    header("location:index.php");

    // si se ha pulsado el boton borrar usuario
} else if (isset($_POST["continuarBorrado"])) {
    require("vistaConfirmarBorrado.php");
}


function paginaError($mensaje)
{
    $pagina = " <!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Document</title>
</head>
<body>
    <p>" . $mensaje . "</p>
</body>
</html>
";

    return $pagina;

}
function comprobarLetraNif($numeroDni)
{
    return substr("TRWAGMYFPDXBNJZSQVHLCKEO", $numeroDni % 23, 1);
}
function comprobarDni($cualquierDni)
{
    // coger los 8 primeros digitos
    $digitos = substr($cualquierDni, 0, 8);
    // comprobar que la letra es correcta
    // coger la letra
    $letra = substr($cualquierDni, -1);
    // obtener le pasamos los digitos, nos devuelve una letra y vemos si es igual la nuestra
    $letraValida = comprobarLetraNif($digitos) == $letra;

    return $letraValida;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practica 8 CRUD 2</title>
    <style>
        img {
            width: 150px;
            height: 150px;
        }
    </style>
</head>

<body>
    <h2>Práctica 8</h2>


    <?php
    // BOTON GUARDAR CAMBIOS INSERCION -------------------
    if (isset($_POST["guardarCambios"])) {
        require("vistaGuardarCambios.php");

    } else if (isset($_POST["borrarFoto"])) {
        require("vistaBorrarFoto.php");
    }

    // BOTON GUARDAR DATOS EDITADOS----------------------------------
    else if (isset($_POST["guardarEdicion"])) {
        require("vistaGuardarEdicion.php");
    }
    // si se ha pulsado el boton nuevo usuario o guardar cambios para insertar pero hay errores en el formulario o se pulsado el boton editar
    if (isset($_POST["nuevoUsuario"]) || (isset($_POST["guardarCambios"]) && $errorFormulario)) {
        require("vistaNuevoUsuario.php");

        // si se ha pulsado el boton ver datos de usuario
    } else if (isset($_POST["verDatos"])) {
        require("vistaVerDatos.php");

        // BOTON EDITAR USUARIO O GUARDAR EDICION ----------------------------
    } else if (isset($_POST["editarUsuario"]) || (isset($_POST["guardarEdicion"]) && $errorFormularioEdicion) || isset($_POST["borrarFoto"])) {
        require("vistaEditarUsuario.php");

    } else if (isset($_POST["borrarUsuario"])) {
        echo "<form action='#' method='post'><p>¿Estás seguro que desea borrar el usuario " . $_POST["borrarUsuario"] . "?</p><button name='continuarBorrado' value='" . $_POST["borrarUsuario"] . "'>Borrar</button><button name='atras''>Atras</button></form>";
    }

    // SIEMPRE MOSTRAR LA TABLA
    echo "<h3>Listado de usuarios</h3>";

    // intentar conectarnos a la base de datos
    try {
        $conexion = mysqli_connect($host, $user, $pass, $bd);
    } catch (Exception $e) {
        die(paginaError("se ha producido un error al conectarse con la base de datos para listar los usuarios de entrada"));

    }

    // obtener los datos de la tabla usuarios
    try {
        $consulta = "SELECT * FROM usuarios";
        $resultado = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(paginaError("se ha producido un error al consultar para listar los usuarios de entrada"));
    }

    // montar la tabla, mientras tengamos tupla
    echo "<table border='1px'>";
    echo "<tr>";
    echo "<td>#</td>";
    echo "<td>Foto</td>";
    echo "<td>Nombre</td>";
    echo "<td><form action='#' method='post'><button type='submit' name='nuevoUsuario'>Usuario+</button></form></td>";
    echo "</tr>";


    while ($datosUsuarios = mysqli_fetch_assoc($resultado)) {

        echo "<tr>";
        echo "<td><form method='post' action='#'>" . $datosUsuarios["id_usuario"] . "</form></td>";
        echo "<td><form method='post' action='#'><img src='img/" . $datosUsuarios["foto"] . "'></form></td>";
        echo "<td><form method='post' action='#'><button name='verDatos' type='submit' value='" . $datosUsuarios["id_usuario"] . "'>" . $datosUsuarios["nombre"] . "</button></form></td>";
        echo "<td><form method='post' action='#'><button type='submit' name='borrarUsuario' value='" . $datosUsuarios["id_usuario"] . "'>Borrar</button> - <button type='submit' name='editarUsuario' value='" . $datosUsuarios["id_usuario"] . "'>Editar</button></form></td>";
        echo "</tr>";
    }

    echo "</table>";
    // cerrar la conexion
    mysqli_close($conexion);



    ?>

</body>

</html>