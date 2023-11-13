<?php
function errorPagina($titulo, $body)
{
    $page = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . $titulo . '</title>
    </head>
    <body>
        ' . $body . '
    </body>
    </html>';
    return $page;
}

// si existe el boton continuar edicion
if (isset($_POST["continuarEdicion"])) {
    // controlar los errores
    $errorNombre = $_POST["nombreEdicion"] == "" || strlen($_POST["nombreEdicion"]) > 30;
    $errorUsuario = $_POST["usuarioEdicion"] == "" || strlen($_POST["usuarioEdicion"]) > 20;

    // si no hay error de usuario comprobar que no esta repetido
    if (!$errorUsuario) {
        // intentar la conexion
        try {
            $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro");
            mysqli_set_charset($conexion, "utf8");
        } catch (Exception $e) {
            die(errorPagina("Practica1 CRUD error", "<p>No se ha podido conectar a la base de datos</p>"));
        }

        // intentar la consulta
        try {
            $consulta = "select * from usuarios where usuario='" . $_POST["usuarioEdicion"] . "' AND id_usuario <> '" . $_POST["continuarEdicion"] . "'";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die(errorPagina("Practica1 CRUD error", "<p>usuario No se ha podido hacer la consulta comprobar usuario repetido</p>"));
        }
        // si tiene mas de 1 tupla el nombre de usuario ya existirá, error de usuario sera true
        $errorUsuario = mysqli_num_rows($resultado) > 0;
        mysqli_close($conexion);
    }

    $errorContraseña = strlen($_POST["contraseñaEdicion"]) > 15;

    $errorEmail = $_POST["emailEdicion"] == "" || strlen($_POST["emailEdicion"]) > 50 || !filter_var($_POST["emailEdicion"], FILTER_VALIDATE_EMAIL);
    
    // si no hay error de email, comprobar que no este repetido
    if (!$errorEmail) {

        // intentar la conexion
        try {
            $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro");
            mysqli_set_charset($conexion, "utf8");
        } catch (Exception $e) {
            die(errorPagina("Practica1 CRUD error", "<p>No se ha podido conectar a la base de datos</p>"));
        }

        // intentar la consulta
        try {
            $consulta = "select * from usuarios where email='" . $_POST["emailEdicion"] . "' AND id_usuario <> '" . $_POST["continuarEdicion"] . "'";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die(errorPagina("Practica1 CRUD error", "<p>email No se ha podido hacer la consulta comprobar email repetido</p>"));
        }
        // si tiene mas de 1 tupla el email ya existirá, error de email sera true
        $errorEmail = mysqli_num_rows($resultado) > 0;
        mysqli_close($conexion);

    }

    $errorFormulario = $errorNombre || $errorUsuario || $errorContraseña || $errorEmail;

    // si no hay error de formulario editar los datos
    if (!$errorFormulario) {
        // intentar la conexion
        try {
            $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro");
            mysqli_set_charset($conexion, "utf8");
        } catch (Exception $e) {
            die(errorPagina("Practica1 CRUD error", "<p>No se ha podido conectar a la base de datos</p>"));
        }

        // si la contraseña no esta vacia cambiarla por la nueva
        if ($_POST["contraseñaEdicion"] !== "") {
            // intentar la consulta de edicion
            try {
                $consulta = "update usuarios set nombre='" . $_POST["nombreEdicion"] . "',usuario='" . $_POST["usuarioEdicion"] . "',clave='" . md5($_POST["contraseñaEdicion"]) . "',email='" . $_POST["emailEdicion"] . " 'where id_usuario='" . $_POST["continuarEdicion"] . "' ";
                $resultado = mysqli_query($conexion, $consulta);
            } catch (Exception $e) {
                mysqli_close($conexion);
                die(errorPagina("Practica1 CRUD error", "<p>No se ha podido editar el usuario con contraseña</p>"));
            }
            // sino , la contraseña se quedara como estaba y no se mete en el update
        } else {
            // intentar la consulta de edicion
            try {
                $consulta = "update usuarios set nombre='" . $_POST["nombreEdicion"] . "',usuario='" . $_POST["usuarioEdicion"] . "',email='" . $_POST["emailEdicion"] . " 'where id_usuario='" . $_POST["continuarEdicion"] . "' ";
                $resultado = mysqli_query($conexion, $consulta);
            } catch (Exception $e) {
                mysqli_close($conexion);
                die(errorPagina("Practica1 CRUD error", "<p>No se ha podido editar el usuario sin contraseña</p>"));
            }
        }


        mysqli_close($conexion);

        // enviarnos a index
        header("location:index.php");
    }
}

//si se ha pulsado el boton continuar borrado
if (isset($_POST["continuarBorrado"])) {
    // conectarnos para eliminar un usuario de la base de datos
    try {
        $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro", );
        mysqli_set_charset($conexion, "UTF8");
    } catch (Exception $e) {
        die(errorPagina("Error Eliminar", "<p>Error al conectarse a la base de datos para eliminar</p>"));
    }

    // intentar borrado
    try {
        // crear la consulta
        $consulta = "delete from usuarios where id_usuario='" . $_POST["continuarBorrado"] . "'";
        // hacer el borrado
        $resultado = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die("Error al intentar eliminar el registro " . $e->getMessage() . "</p></body></html>");
    }
    mysqli_close($conexion);
    header("location:index.php");

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información usuarios</title>
</head>
<style>
    table,
    td,
    th {
        border: solid 1px black;
        text-align: center;
    }

    table {
        border-collapse: collapse;
    }

    .enlace {
        border: none;
        background: none;
        color: blue;
        text-decoration: underline;
        cursor: pointer;

    }
</style>

<body>
    <h1>Listado de usuarios</h1>
    <?php

    // conectarnos para listar los usuarios en una tabla
    try {
        $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro", );
        mysqli_set_charset($conexion, "UTF8");
    } catch (Exception $e) {
        die("<p>no se ha podido conectar a la base de datos " . $e->getMessage() . "</p></body></html>");
    }

    // hacer la consulta de los usuarios
    try {
        $consulta = "select * from usuarios";
        $resultado = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die("Error al realizar la consulta" . $e->getMessage() . "</p></body></html>");
    }

    // montar la tabla
    echo "<table>";
    echo "<tr><td>Nombre de usuario</td><td>Borrar</td><td>Editar</td></tr>";
    while ($fila = mysqli_fetch_assoc($resultado)) {
        // la tabla contiene un formulario por cada boton que nos redirige a nosotros mismos, con los resultados de cada tupla
        echo "<tr>
                <td><form action='#' method='post'> <button class='enlace' name='mostrarUsuario' id='usuario' type='submit' value='" . $fila["id_usuario"] . "'>" . $fila["nombre"] . "</button></form></td>
                <td><form action='#' method='post'><button type='submit' id='editar' name='editar' value='" . $fila["id_usuario"] . "'><img class='enlace' src='Images/bx-pencil.svg'></button></form></td>
                <td><form action='#' method='post'><input type='hidden' name='nombreUsuario' value='" . $fila["nombre"] . "'><button type='submit' id='borrar' name='borrar' value='" . $fila["id_usuario"] . "'><img class='enlace' src='Images/bx-x-circle.svg'></button></form></td>
                </tr>";
    }
    echo "</table>";

    // si se ha pulsado algun boton de usuario, mostrar los detalles
    if (isset($_POST["mostrarUsuario"])) {
        echo "se ha pulsado el boton usuario con id " . $_POST["mostrarUsuario"];

        // consulta para obtener los datos de un usuario
        try {
            $consulta = "select * from usuarios where id_usuario = '" . $_POST["mostrarUsuario"] . "'";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die("Error al realizar la consulta detalles usuario" . $e->getMessage() . "</p></body></html>");
        }

        // si hemos obtenido alguna tupla
        if (mysqli_num_rows($resultado) > 0) {
            // obtener los datos en un array asociativo
            $datosUsuario = mysqli_fetch_assoc($resultado);
            // mostrar los datos
            echo "<p><strong>Nombre: </strong>" . $datosUsuario["nombre"] . "</p>";
            echo "<p><strong>Usuario: </strong>" . $datosUsuario["usuario"] . "</p>";
            echo "<p><strong>Email: </strong>" . $datosUsuario["email"] . "</p>";
        } else {
            // si no hemos obtenido ninguna tupla, avisar de que ya no existe el usuario en la bd
            echo "<p>Este usuario ya no existe en la base de datos</p>";
        }

        // boton volver solo si se ha pulsado algun boton de usuario
        echo "<form action='#' method='post'>";
        echo "<p><button type='submit'>Volver a insertar usuario</button></p>";
        echo "</form>";


        // si se ha pulsado el boton borrar
    } else if (isset($_POST["borrar"])) {
        // mostrar la confirmacion de borrado con los botones
        echo "<p>Está seguro que quieres borrar el usuario " . $_POST["nombreUsuario"] . "";
        echo "<form method='post' action='#'>";
        echo "<p><button type='submit' name='continuarBorrado' value='" . $_POST["borrar"] . "'>Eliminar usuario</button> <button type='submit'>Volver</button></p>";
        echo "</form>";

        // si se ha pulsado el boton editar o continuar edicion y hay errores
    } else if (isset($_POST["editar"]) || isset($_POST["continuarEdicion"])) {

        if (isset($_POST["editar"])) {
            $idUsuario = $_POST["editar"];
        } else {
            $idUsuario = $_POST["continuarEdicion"];
        }

        // intentar la conexion
        try {
            $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro");
            mysqli_set_charset($conexion, "utf8");
        } catch (Exception $e) {
            die(errorPagina("Practica1 CRUD error", "<p>No se ha podido conectar a la base de datos</p>"));
        }

        // consultar los datos del usuario que hemos pulsado el boton editar
        try {
            $consulta = "select * from usuarios where id_usuario = '" . $idUsuario . "'";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die("Error al realizar la consulta para editar usuario" . $e->getMessage() . "</p></body></html>");
        }

        // si hemos obtenido alguna tupla
        if (mysqli_num_rows($resultado) > 0) {
            // obtener los datos del usuario en un array asociativo
            $datosUsuario = mysqli_fetch_assoc($resultado);

            // si existe el boton editar recoger datos de la bd
            if (isset($_POST["editar"])) {
                // recoger los datos de usuario
                $nombreUsuario = $datosUsuario["nombre"];
                $usuarioUsuario = $datosUsuario["usuario"];
                $emailUsuario = $datosUsuario["email"];

                // si no recoger los datos de los $_POST del formulario mostrado
            } else {
                // recoger los datos de usuario
                $nombreUsuario = $_POST["nombreEdicion"];
                $usuarioUsuario = $_POST["usuarioEdicion"];
                $emailUsuario = $_POST["emailEdicion"];
            }


            // si el usuario no existe
        } else {
            $mensajeError = "Este usuario ya no existe en la base de datos";
        }

        // si el error de usuario existe mnostrar el error 
        if (isset($mensajeError)) {
            echo $mensajeError;
            // sino mostrar el formulario, que se muestra siempre que existe el boton editar y continuar editar
        } else {
            ?>
                    <form action="#" method="post">
                        <p>
                            <label for="nombre">Nombre: </label>
                            <input type="text" name="nombreEdicion" value="<?php echo $nombreUsuario ?>" maxlength="30">
                        <?php
                        if (isset($_POST["continuarEdicion"]) && $errorNombre) {
                            if ($_POST["nombreEdicion"] == "") {
                                echo "<span>Campo vacio</span>";
                            } else {
                                echo "<span>Tamaño erroneo</span>";
                            }
                        }
                        ?>
                        </p>

                        <p>
                            <label for="usuario">Usuario: </label>
                            <input type="text" name="usuarioEdicion" value="<?php echo $usuarioUsuario ?>" maxlength="20">
                        <?php
                        if (isset($_POST["continuarEdicion"]) && $errorUsuario) {
                            if ($_POST["usuarioEdicion"] == "") {
                                echo "<span>Campo vacio</span>";
                            } else if (strlen($_POST["usuarioEdicion"]) > 20) {
                                echo "<span>Tamaño erroneo</span>";
                            } else {
                                echo "<span>Usuario repetido</span>";
                            }
                        }
                        ?>
                        </p>
                        <p>
                            <label for="contraseña">Contraseña: </label>
                            <input type="text" name="contraseñaEdicion" placeholder="Contraseña no visible. teclea una para cambiarla"
                                maxlength="15">
                        <?php
                        if (isset($_POST["continuarEdicion"]) && $errorContraseña) {
                            if ($_POST["contraseñaEdicion"] == "") {
                                echo "<span>Campo vacio</span>";
                            } else {
                                echo "<span>Tamaño erroneo</span>";
                            }
                        }
                        ?>
                        </p>
                        <p>
                            <label for="email">Email: </label>
                            <input type="email" name="emailEdicion" value="<?php echo $emailUsuario ?>" maxlength="50">
                        <?php
                        if (isset($_POST["continuarEdicion"]) && $errorEmail) {
                            if ($_POST["emailEdicion"] == "") {
                                echo "<span>Campo vacio</span>";
                            } else if (strlen($_POST["emailEdicion"]) > 50) {
                                echo "<span>Tamaño erroneo</span>";
                            } else if (!filter_var($_POST["emailEdicion"], FILTER_VALIDATE_EMAIL)) {
                                echo "<span>El email no está bien escrito</span>";
                            } else {
                                echo "<span>El email está repetido</span>";
                            }
                        }
                        ?>
                        </p>
                        <p>
                            <button type="submit" name="continuarEdicion" value="<?php echo $idUsuario ?>">Continuar</button>
                            <button type="submit">Volver</button>
                        </p>
                    </form>
            <?php
        }

    } // si no se ha pulsado el boton de ningun usuario mostrar el boton para insertar un usuario
    else {
        echo "<form action='nuevousuario.php' method='post'>";
        echo "<p><button type='submit' name='nuevousuario' id='nuevousuario'>Insertar nuevo usuario</button></p>";
        echo "</form>";
    }
    mysqli_free_result($resultado);
    mysqli_close($conexion);

    ?>
</body>

</html>