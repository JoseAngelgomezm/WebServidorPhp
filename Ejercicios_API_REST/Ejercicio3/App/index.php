<?php
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

function consumir_servicios_REST($url, $metodo, $datos = null)
{
    $llamada = curl_init();
    curl_setopt($llamada, CURLOPT_URL, $url);
    curl_setopt($llamada, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($llamada, CURLOPT_CUSTOMREQUEST, $metodo);
    if (isset($datos))
        curl_setopt($llamada, CURLOPT_POSTFIELDS, http_build_query($datos));
    $respuesta = curl_exec($llamada);
    curl_close($llamada);
    return $respuesta;
}

session_name("PrimeraAppLogin_23_24");
session_start();
define("URLATAQUE", "http://localhost/Proyectos/WebServidorPhp/Ejercicios_API_REST/Ejercicio3/Api");
define("TIEMPOENSEGUNDOS", "60");

// si existe el boton continuar edicion
if (isset($_POST["continuarEdicion"])) {
    // controlar los errores
    $errorNombre = $_POST["nombreEdicion"] == "" || strlen($_POST["nombreEdicion"]) > 30;
    $errorUsuario = $_POST["usuarioEdicion"] == "" || strlen($_POST["usuarioEdicion"]) > 20;

    // si no hay error de usuario comprobar que no esta repetido
    if (!$errorUsuario) {
        $url = URLATAQUE . "/repetido/usuarios/usuario/" . urlencode($_POST["usuarioEdicion"]) . "/id_usuario/" . $_POST["continuarEdicion"] . "";
        $respuesta = consumir_servicios_REST($url, "get");
        $archivo = json_decode($respuesta);

        if (!$archivo) {
            die(errores("no se ha obtenido respuesta"));
        }

        if (isset($archivo->error)) {
            die(errores($archivo->error));
        }

        if ($archivo->mensaje === true) {
            $errorUsuario = true;
        }
    }

    $errorContraseña = strlen($_POST["contraseñaEdicion"]) > 15;

    $errorEmail = $_POST["emailEdicion"] == "" || strlen($_POST["emailEdicion"]) > 50 || !filter_var($_POST["emailEdicion"], FILTER_VALIDATE_EMAIL);

    // si no hay error de email, comprobar que no este repetido
    if (!$errorEmail) {

        $url = URLATAQUE . "/repetido/usuarios/email/" . urlencode($_POST["emailEdicion"]) . "/id_usuario/" . $_POST["continuarEdicion"] . "";
        $respuesta = consumir_servicios_REST($url, "get");
        $archivo = json_decode($respuesta);

        if (!$archivo) {
            die(errores("no se ha obtenido respuesta"));
        }

        if (isset($archivo->error)) {
            die(errores($archivo->error));
        }

        if ($archivo->mensaje === true) {
            $errorEmail = true;
        }

    }

    $errorFormulario = $errorNombre || $errorUsuario || $errorContraseña || $errorEmail;

    // si no hay error de formulario editar los datos
    if (!$errorFormulario) {

        // si esta vacia la contraseña llamo a editar sin clave
        if ($_POST["contraseñaEdicion"] == "") {

            $url = URLATAQUE . "/login_restful/actualizarUsuarioSinClave/" . $_POST["continuarEdicion"] . "";

            $datos["nombre"] = $_POST["nombreEdicion"];
            $datos["usuario"] = $_POST["usuarioEdicion"];
            $datos["email"] = $_POST["emailEdicion"];

            $respuesta = consumir_servicios_REST($url, "put", $datos);
            $archivo = json_decode($respuesta);

            if (!$archivo) {
                die(errores("no se ha obtenido respuesta"));
            }

            if (isset($archivo->error)) {
                die(errores($archivo->error));
            }

            $_SESSION["mensaje"] = $archivo->mensaje;

            // sino, contendra algo llamar a actualizar con clave
        } else {

            $url = URLATAQUE . "/login_restful/actualizarUsuarioConClave/" . $_POST["continuarEdicion"] . "";

            $datos["nombre"] = $_POST["nombreEdicion"];
            $datos["usuario"] = $_POST["usuarioEdicion"];
            $datos["clave"] = $_POST["contraseñaEdicion"];
            $datos["email"] = $_POST["emailEdicion"];

            $respuesta = consumir_servicios_REST($url, "put", $datos);
            $archivo = json_decode($respuesta);

            if (!$archivo) {
                die(errores("no se ha obtenido respuesta"));
            }

            if (isset($archivo->error)) {
                die(errores($archivo->error));
            }

            $_SESSION["mensaje"] = $archivo->mensaje;
        }

        // enviarnos a index
        header("location:index.php");
    }
}

//si se ha pulsado el boton continuar borrado
if (isset($_POST["continuarBorrado"])) {

    $url = URLATAQUE . "/login_restful/borrarUsuario/" . urlencode($_POST["continuarBorrado"]);
    $respuesta = consumir_servicios_REST($url, "delete");
    $archivo = json_decode($respuesta);

    if (!$archivo) {
        die(errores("no se ha obtenido respuesta"));
    }

    if (isset($archivo->error)) {
        die(errores($archivo->error));
    }

    $_SESSION["mensaje"] = $archivo->mensaje;

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
    $url = URLATAQUE . "/login_restful/usuarios";
    $respuesta = consumir_servicios_REST($url, "get");
    $archivo = json_decode($respuesta);

    if (!$archivo) {
        die(errorPagina("no se ha obtenido respuesta"));
    }

    if (isset($archivo->error)) {
        die(errorPagina($archivo->error));
    }

    // montar la tabla
    echo "<table>";
    echo "<tr><td>Nombre de usuario</td><td>Borrar</td><td>Editar</td></tr>";

    foreach ($archivo->usuarios as $fila) {
        // la tabla contiene un formulario por cada boton que nos redirige a nosotros mismos, con los resultados de cada tupla
        echo "<tr>
                <td><form action='#' method='post'> <button class='enlace' name='mostrarUsuario' id='usuario' type='submit' value='" . $fila->id_usuario . "'>" . $fila->nombre . "</button></form></td>
                <td><form action='#' method='post'><button type='submit' id='editar' name='editar' value='" . $fila->id_usuario . "'><img class='enlace' src='Images/bx-pencil.svg'></button></form></td>
                <td><form action='#' method='post'><input type='hidden' name='nombreUsuario' value='" . $fila->nombre . "'><button type='submit' id='borrar' name='borrar' value='" . $fila->id_usuario . "'><img class='enlace' src='Images/bx-x-circle.svg'></button></form></td>
                </tr>";
    }
    echo "</table>";

    // si se ha pulsado algun boton de usuario, mostrar los detalles
    if (isset($_POST["mostrarUsuario"])) {
        echo "se ha pulsado el boton usuario con id " . $_POST["mostrarUsuario"];

        // consulta para obtener los datos de un usuario
        $url = URLATAQUE . "/obtenerUsuario/" . $_POST["mostrarUsuario"] . "";
        $respuesta = consumir_servicios_REST($url, "get");
        $archivo = json_decode($respuesta);

        if (!$archivo) {
            die(errores("no se ha obtenido respuesta"));
        }

        if (isset($archivo->error)) {
            die(errores($archivo->error));
        }


        // si hemos obtenido alguna tupla
        foreach ($archivo as $datosUsuario) {
            # code...
            // mostrar los datos
            echo "<p><strong>Nombre: </strong>" . $datosUsuario->nombre . "</p>";
            echo "<p><strong>Usuario: </strong>" . $datosUsuario->usuario . "</p>";
            echo "<p><strong>Email: </strong>" . $datosUsuario->email . "</p>";
        }

        // boton volver solo si se ha pulsado algun boton de usuario
        echo "<form action='#' method='post'>";
        echo "<p><button type='submit'>Volver a insertar usuario</button></p>";
        echo "</form>";

        if (isset($_SESSION["mensaje"])) {
            echo "<p>" . $_SESSION["mensaje"] . "</p>";
            unset($_SESSION["mensaje"]);
        }

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

        $url = URLATAQUE . "/obtenerUsuario/" . $idUsuario . "";

        $respuesta = consumir_servicios_REST($url, "get");
        $archivo = json_decode($respuesta);

        if (!$archivo) {
            die(errores("no se ha obtenido respuesta"));
        }

        if (isset($archivo->error)) {
            die(errores($archivo->error));
        }


        // si hemos obtenido alguna tupla
        if (isset($archivo->usuario)) {
            // obtener los datos del usuario en un array asociativo
            $datosUsuario = $archivo->usuario;

            // si existe el boton editar recoger datos de la bd
            if (isset($_POST["editar"])) {
                // recoger los datos de usuario
                $nombreUsuario = $datosUsuario->nombre;
                $usuarioUsuario = $datosUsuario->usuario;
                $emailUsuario = $datosUsuario->email;

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

    ?>
</body>

</html>