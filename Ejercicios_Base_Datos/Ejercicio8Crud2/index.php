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
    // intentar la conexion
    try {
        $conexion = mysqli_connect($host, $user, $pass, $bd);
        mysqli_set_charset($conexion, "utf8");
    } catch (Exception $e) {
        die(paginaError("no se ha podido realizar la conexion para confirmar el borrado"));
    }

    // si el usuario tiene una foto de perfil
    // intentar la consulta para traernos a nuestro usuario
    try {
        $consulta = "SELECT * FROM usuarios WHERE id_usuario='" . $_POST["continuarBorrado"] . "'";
        $resultado = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(paginaError("no se ha podido realizar la consulta para la foto en el borrado de datos"));
    }

    // obtener el valor de la foto
    $datos = mysqli_fetch_assoc($resultado);
    $imagen = $datos["foto"];

    // si el archivo existe en nuestra que carpeta de imagenes
    if (file_exists("img/" . $imagen)) {
        // eliminarla
        unlink("img/" . $imagen);
    }

    // intentar la consulta de borrado
    try {
        $consulta = "DELETE FROM usuarios WHERE id_usuario='" . $_POST["continuarBorrado"] . "'";
        $resultado = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(paginaError("no se ha podido realizar el borrado de datos"));
    }

    mysqli_close($conexion);
    header("location:index.php");
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
    // si se ha pulsado el boton guardar cambios del formulario de inserccion
    if (isset($_POST["guardarCambios"])) {
        // comprobar los errores
        $errorNombre = $_POST["nombreInsercion"] == "" || strlen($_POST["nombreInsercion"]) > 50;
        $errorUsuario = $_POST["usuarioInsercion"] == "" || strlen($_POST["usuarioInsercion"]) > 30;
        $errorContraseña = $_POST["contraseñaInsercion"] == "" || strlen($_POST["contraseñaInsercion"]) > 50;
        $errorDNI = $_POST["dniInsercion"] == "" || strlen($_POST["dniInsercion"]) !== 9 || (substr($_POST["dniInsercion"], -1) < "A" && substr($_POST["dniInsercion"], -1) > "Z") || !is_numeric(substr($_POST["dniInsercion"], 0, 8));

        // si no hay error de dni, comprobar que sea valido
        if (!$errorDNI) {
            $dniValido = comprobarDni($_POST["dniInsercion"]);
        }

        // MIRAR QUE NO SE REPITA DNI ; USUARIO
        // si no hay errores en usuario y dni, ver si no estan repetidos
        if (!$errorUsuario || !$errorDNI) {
            // intentar la conexion
            try {
                $conexion = mysqli_connect($host, $user, $pass, $bd);
                mysqli_set_charset($conexion, "utf8");
            } catch (Exception $e) {
                die(paginaError("no se ha podido realizar la conexion para comprobar la repeticion de datos"));
            }

            // realizar la consulta para ver que no se repita ni dni, ni usuario
            try {
                // sintaxis de la consulta
                $consultaUsuario = "SELECT * FROM usuarios WHERE usuario = '" . $_POST["usuarioInsercion"] . "'";
                $consultaDni = "SELECT * FROM usuarios WHERE dni = '" . $_POST["dniInsercion"] . "'";
                // realizar las consultas para ver si estan repetidos
                $resultadoDni = mysqli_query($conexion, $consultaDni);
                $resultadoUsuario = mysqli_query($conexion, $consultaUsuario);
            } catch (Exception $e) {
                mysqli_close($conexion);
                die(paginaError("no se ha podido realizar la consulta de datos para comprobar la repeticion de datos"));
            }

            // si la consulta tiene alguna tupla, significa que estara repetido
            $repetidoDni = mysqli_num_rows($resultadoDni) > 0;
            $repetidoUsuario = mysqli_num_rows($resultadoUsuario) > 0;
            mysqli_close($conexion);
        }



        $errorFormulario = $errorNombre || $errorUsuario || $errorContraseña || $errorDNI || $repetidoUsuario || $repetidoDni || !$dniValido;

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
            <input type="text" name="nombreInsercion" maxlength="50" value="<?php if (isset($_POST["nombreInsercion"]))
                echo $_POST["nombreInsercion"] ?>">
                <?php
            if (isset($_POST["nombreInsercion"]) && $_POST["nombreInsercion"] == "") {
                echo "<span>El nombre no puede estar vacio</span>";
            }
            ?>
            <br>

            <label for="usuarioInsercion">Usuario:</label>
            <br>
            <input type="text" name="usuarioInsercion" maxlength="30" value="<?php if (isset($_POST["usuarioInsercion"]))
                echo $_POST["usuarioInsercion"] ?>">
                <?php
            if (isset($_POST["usuarioInsercion"]) && $_POST["usuarioInsercion"] == "") {
                echo "<span>El usuario no puede estar vacio</span>";
            } else if (isset($repetidoUsuario) && $repetidoUsuario) {
                echo "<span>El nombre de usuario no está disponible</span>";
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
            <input type="text" name="dniInsercion" maxlength="9" value="<?php if (isset($_POST["dniInsercion"]))
                echo $_POST["dniInsercion"] ?>">
                <?php
            if (isset($_POST["dniInsercion"]) && $_POST["dniInsercion"] == "") {
                echo "<span>El DNI no puede estar vacío</span>";
            } else if (isset($errorDNI) && $errorDNI) {
                echo "<span>El dni que ha insertado no es un un formato de dni válido</span>";
            } else if (isset($repetidoDni) && $repetidoDni) {
                echo "<span>El dni que ha insertado ya se encuentra registrado</span>";
            } else if (isset($dniValido) && !$dniValido) {
                echo "<span>El dni que ha insertado no es un dni válido</span>";
            }
            ?>
            <br>

            <label>Sexo:</label>
            <br>
            <input type="radio" id="hombre" name="sexoInsercion" value="hombre" checked><label for="hombre"> Hombre</label>
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

        // si se ha pulsado el boton ver datos de usuario
    } else if (isset($_POST["verDatos"])) {
        // intentar la conexion
        try {
            $conexion = mysqli_connect($host, $user, $pass, $bd);
            mysqli_set_charset($conexion, "utf8");
        } catch (Exception $e) {
            die(paginaError("no se ha podido realizar la conexion para mostrar los datos del usuario"));
        }

        // intentar la consulta de listado
        try {
            $consulta = "SELECT * FROM usuarios WHERE id_usuario='" . $_POST["verDatos"] . "'";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die(paginaError("no se ha podido realizar la consulta de datos del usuario seleccionado"));
        }

        // Mostrar los datos si se ha encontrado al usuario
        if (mysqli_num_rows($resultado) == 1) {
            $datosUser = mysqli_fetch_assoc($resultado);
            echo "<p><strong>Nombre</strong>: " . $datosUser["nombre"] . "<p>";
            echo "<p><strong>Usuario</strong>: " . $datosUser["usuario"] . "<p>";
            echo "<p><strong>DNI</strong>: " . $datosUser["dni"] . "<p>";
            echo "<p><strong>Sexo</strong>: " . $datosUser["sexo"] . "<p>";
            echo "<p><strong>foto</strong>: " . $datosUser["foto"] . "<p>";
        } else {
            die(paginaError("El usuario que ha intentado consultar ya no se encuentra en la base de datos"));
        }

        mysqli_close($conexion);
    } else if (isset($_POST["editarUsuario"])) {
        ?>
                <h3>Editar un Usuario Existente</h3>
                <form action="#" method="post">

                    <label for="nombreEdicion">Nombre:</label>
                    <br>
                    <input type="text" name="nombreEdicion" maxlength="50" value="">
                <?php

                ?>
                    <br>
                    <label for="usuarioEdicion">Usuario:</label>
                    <br>
                    <input type="text" name="usuarioEdicion" maxlength="30" value="">
                <?php

                ?>
                    <br>
                    <label for="contraseñaEdicion">Contraseña:</label>
                    <br>
                    <input type="password" name="contraseñaEdicion" maxlength="50">
                <?php

                ?>
                    <br>
                    <label for="dniEdicion">DNI:</label>
                    <br>
                    <input type="text" name="dniEdicion" maxlength="9" value="">
                <?php

                ?>

                    <br>
                    <label for="sexoEdicion">Sexo:</label>
                    <br>
                    <input type="text" name="sexoEdicion" value="">
                    <br>

                    <label for="imagenEdicion">Incluir mi foto (MAX-500KB):</label>
                    <br>
                    <input type="text" accept="img" name="imagenEdicion">
                <?php
                if (isset($errorFoto) && $errorFoto) {
                    echo "<span>Error en la subida de la foto, imagen no válida o peso mayor a 500KB";
                }
                ?>

                    <br>

                    <button type="submit" name="guardarEdicion">Guardar Cambios</button>
                    <button type="submit" name="atras">Atrás</button>
                </form>
        <?php

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