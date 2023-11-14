<!DOCTYPE html>
<html lang="es">

<?php
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
?>

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
    // determinar los datos de conexion
    $host = "localhost";
    $user = "jose";
    $pass = "josefa";
    $bd = "bd_cv";

    if (isset($_POST["atras"])) {
        header("location:index.php");
    }
    // si se ha pulsado el boton guardar cambios del formulario de inserccion
    if (isset($_POST["guardarCambios"])) {
        // comprobar los errores
        $errorNombre = $_POST["nombreInsercion"] == "" || strlen($_POST["nombreInsercion"]) > 50;
        $errorUsuario = $_POST["usuarioInsercion"] == "" || strlen($_POST["usuarioInsercion"]) > 30;
        $errorContraseña = $_POST["contraseñaInsercion"] == "" || strlen($_POST["contraseñaInsercion"]) > 50;
        $errorDNI = $_POST["dniInsercion"] == "" || strlen($_POST["dniInsercion"]) > 10;

        $errorFormulario = $errorNombre || $errorUsuario || $errorContraseña || $errorDNI;

        // MIRAR QUE NO SE REPITA DNI ; USUARIO ; EMAIL

        // si no hay errores de formulario, hacer la insercion
        if (!$errorFormulario) {

            // intentar la conexion
            try {
                $conexion = mysqli_connect($host, $user, $pass, $bd);
                mysqli_set_charset($conexion, "utf8");
            } catch (Exception $e) {
                die(paginaError("no se ha podido realizar la conexion en la insercion"));
            }

            // intentar la consulta de insercion
            try {
                $consulta = "INSERT INTO usuarios(usuario, clave, nombre, dni, sexo) VALUES ('" . $_POST["usuarioInsercion"] . "','" . $_POST["contraseñaInsercion"] . "','" . $_POST["nombreInsercion"] . "','" . $_POST["dniInsercion"] . "','" . $_POST["sexoInsercion"] . "')";
                $resultado = mysqli_query($conexion, $consulta);
            } catch (Exception $e) {
                mysqli_close($conexion);
                die(paginaError("no se ha podido realizar la insercion de datos"));
            }

            // saber si ha subido foto
            if ($_FILES["imagenInsercion"]["name"] !== "") {
                // controlar errores si se ha subido la foto
                $errorFoto = $_FILES["imagenInsercion"]["error"] || !getimagesize($_FILES["imagenInsercion"]["tmp_name"]) || $_FILES["imagenInsercion"]["size"] > 500 * 1024;
                $errorFormulario = $errorFoto;

                // si no hay error en la foto
                if (!$errorFoto) {
                    // obtener la extension del archivo
                    $nombreImagenDividido = explode(".", $_FILES["imagenInsercion"]["name"]);
                    $extension = end($nombreImagenDividido);

                    // obtener el id del ultimo insertado
                    $ultimoID = mysqli_insert_id($conexion);

                    $rutaDestino = "img/" . $ultimoID . "." . $extension . "";

                    // moverla a la carpeta
                    move_uploaded_file($_FILES["imagenInsercion"]["tmp_name"], $rutaDestino);

                    // poner la ruta de la foto en el ultimo usuario insertado
                    // intentar la consulta update
                    try {
                        // traernos el id del ultimo usuario insertado
                        $ultimoID = mysqli_insert_id($conexion);
                        $consulta = "UPDATE `usuarios` SET `foto` = '$ultimoID.$extension' WHERE `id_usuario` = '$ultimoID';";
                        $resultado = mysqli_query($conexion, $consulta);
                    } catch (Exception $e) {
                        mysqli_close($conexion);
                        die(paginaError("no se ha podido realizar modificacion de los datos de la foto"));
                    }
                }
                mysqli_close($conexion);
            }
        }
    }
    // si se ha pulsado el boton nuevo usuario o guardar cambios para insertar pero hay errores en el formulario o se pulsado el boton editar
    if (isset($_POST["nuevoUsuario"]) || (isset($_POST["guardarCambios"]) && $errorFormulario)) {
        ?>
        <h3>Agregar Nuevo Usuario</h3>
        <form action="#" method="post" enctype="multipart/form-data">

            <label for="nombreInsercion">Nombre:</label>
            <br>
            <input type="text" name="nombreInsercion" maxlength="50">
            <?php
            if (isset($_POST["nombreInsercion"]) && $_POST["nombreInsercion"] == "") {
                echo "<span>El nombre no puede estar vacio</span>";
            }
            ?>
            <br>

            <label for="usuarioInsercion">Usuario:</label>
            <br>
            <input type="text" name="usuarioInsercion" maxlength="30">
            <?php
            if (isset($_POST["usuarioInsercion"]) && $_POST["usuarioInsercion"] == "") {
                echo "<span>El usuario no puede estar vacio</span>";
            }
            ?>
            <br>


            <label for="contraseñaInsercion">Contraseña:</label>
            <br>
            <input type="password" name="contraseñaInsercion" maxlength="50">
            <?php
            if (isset($_POST["contraseñaInsercion"]) && $_POST["contraseñaInsercion"] == "") {
                echo "<span>La contraseña no puede estar vacía</span>";
            }
            ?>
            <br>

            <label for="dniInsercion">DNI:</label>
            <br>
            <input type="text" name="dniInsercion" maxlength="10">
            <?php
            if (isset($_POST["dniInsercion"]) && $_POST["dniInsercion"] == "") {
                echo "<span>El DNI no puede estar vacío</span>";
            }
            ?>
            <br>

            <label>Sexo:</label>
            <br>
            <input type="radio" id="hombre" name="sexoInsercion" value="hombre"><label for="hombre"> Hombre</label>
            <input type="radio" id="mujer" name="sexoInsercion" value="mujer"><label for="mujer"> Mujer</label>
            <br>

            <label for="imagenInsercion">Incluir mi foto (MAX-500KB):</label>
            <input type="file" accept="img" name="imagenInsercion">
            <?php
            if (isset($errorFoto) && $errorFoto) {
                echo "<span>Error en la subida de la foto, imagen no válida o peso mayor a 500KB";
            }
            ?>

            <br>

            <button type="submit" name="guardarCambios">Guardar Cambios</button>
            <button type="submit" name="atras">Atrás</button>
        </form>
        <?php
        // si se ha pulsado el boton borrar usuario
    } else if (isset($_POST["borrarUsuario"])) {

        // intentar la conexion
        try {
            $conexion = mysqli_connect($host, $user, $pass, $bd);
            mysqli_set_charset($conexion, "utf8");
        } catch (Exception $e) {
            die(paginaError("no se ha podido realizar la conexion en la insercion"));
        }


        // intentar la consulta de borrado
        try {
            $consulta = "DELETE FROM usuarios WHERE id_usuario='" . $_POST["borrarUsuario"] . "'";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die(paginaError("no se ha podido realizar el borrado de datos"));
        }

        // si tiene foto de perfil, borrarla
        if(file_exists("img/".$_POST["borrarUsuario"].".*")){
            unlink("img/".$_POST["borrarUsuario"]."");
        }

        mysqli_close($conexion);
        header("location:index.php");

        // si se ha pulsado el boton editar usuario
    }

    // SIEMPRE MOSTRAR LA TABLA
    echo "<h3>Listado de usuarios</h3>";

    // intentar conectarnos a la base de datos
    try {
        $conexion = mysqli_connect($host, $user, $pass, $bd);
    } catch (Exception $e) {
        die(paginaError("se ha producido un error al conectarse con la base de datos"));

    }

    // obtener los datos de la tabla usuarios
    try {
        $consulta = "SELECT * FROM usuarios";
        $resultado = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(paginaError("se ha producido un error al conectarse con la base de datos"));
    }

    // montar la tabla, mientras tengamos tupla
    echo "<form action='#' method='post'>";
    echo "<table border='1px'>";
    echo "<tr>";
    echo "<td>#</td>";
    echo "<td>Foto</td>";
    echo "<td>Nombre</td>";
    echo "<td><form action='#' method='post'><button type='submit' name='nuevoUsuario'>Usuario+</button></form></td>";
    echo "</tr>";


    while ($datosUsuarios = mysqli_fetch_assoc($resultado)) {

        echo "<tr>";
        echo "<td>" . $datosUsuarios["id_usuario"] . "</td>";
        echo "<td> <img src='img/" . $datosUsuarios["foto"] . "'></td>";
        echo "<td>" . $datosUsuarios["nombre"] . "</td>";
        echo "<td><button type='submit' name='borrarUsuario' value='" . $datosUsuarios["id_usuario"] . "'>Borrar</button> - <button type='submit' name='editarUsuario' value='" . $datosUsuarios["id_usuario"] . "'>Editar</button></td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</form>";
    // cerrar la conexion
    mysqli_close($conexion);



    ?>

</body>

</html>