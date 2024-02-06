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
define("URLATAQUE", "http://localhost/Proyectos/WebServidorPhp/Ejercicios_API_REST/Ejercicio5/login_restful");
define("TIEMPOENSEGUNDOS", "180");

// si se ha pulsado el boton loguearse
if (isset($_POST["logear"])) {
    $errorUsuario = $_POST["usuario"] == "";
    $errorContraseña = $_POST["contrasenaLogin"] == "";

    $errorFormularioLogin = $errorUsuario || $errorContraseña;

    if (!$errorFormularioLogin) {
        $url = URLATAQUE . "/login";
        $datos["usuario"] = $_POST["usuario"];
        $datos["clave"] = md5($_POST["contrasenaLogin"]);
        $respuesta = consumir_servicios_REST($url, "post", $datos);
        $archivo = json_decode($respuesta);

        if (!$archivo) {
            session_destroy();
            die(errores("no se ha obtenido respuesta al conectarse" . $respuesta));
        }

        if (isset($archivo->error)) {
            session_destroy();
            die(errores($archivo->error));
        }

        if (isset($archivo->mensaje)) {
            $errorUsuario = true;
        } else {
            // existe, logearse
            $_SESSION["usuario"] = $archivo->usuario->usuario;
            $_SESSION["contraseña"] = $archivo->usuario->clave;
            $_SESSION["ultimaAccion"] = time();
            $_SESSION["token"] = $archivo->token;
            header("location:index.php");
            exit();
        }
    }
}

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
            die(errores("no se ha obtenido respuesta al comprobar usuario repetido llamada /repetido/usuarios/usuario/"));
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
            die(errores("no se ha obtenido respuesta al comprobar email , llamada /repetido/usuarios/email"));
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

            $url = URLATAQUE . "/actualizarUsuarioSinClave/" . $_POST["continuarEdicion"] . "";

            $datos["nombre"] = $_POST["nombreEdicion"];
            $datos["usuario"] = $_POST["usuarioEdicion"];
            $datos["email"] = $_POST["emailEdicion"];

            $respuesta = consumir_servicios_REST($url, "put", $datos);
            $archivo = json_decode($respuesta);

            if (!$archivo) {
                die(errores("no se ha obtenido respuesta al actualizar usuario sin clave llamada /actualizarUsuarioSinClave/" . $_POST["continuarEdicion"] . ""));
            }

            if (isset($archivo->error)) {
                die(errores($archivo->error));
            }

            $_SESSION["mensaje"] = $archivo->mensaje;

            // sino, contendra algo, llamar a actualizar con clave
        } else {

            $url = URLATAQUE . "/actualizarUsuarioConClave/" . $_POST["continuarEdicion"] . "";

            $datos["nombre"] = $_POST["nombreEdicion"];
            $datos["usuario"] = $_POST["usuarioEdicion"];
            $datos["clave"] = md5($_POST["contraseñaEdicion"]);
            $datos["email"] = $_POST["emailEdicion"];

            $respuesta = consumir_servicios_REST($url, "put", $datos);
            $archivo = json_decode($respuesta);

            if (!$archivo) {
                die(errores("no se ha obtenido respuesta al actualizar usuario con clave, llamada /actualizarUsuarioConClave/" . $_POST["continuarEdicion"] . ""));
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

    $url = URLATAQUE . "/borrarUsuario/" . urlencode($_POST["continuarBorrado"]);
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


if (isset($_POST["salir"])) {
    session_destroy();
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
    <?php
    // si estoy logeado
    if (isset($_SESSION["usuario"]) && isset($_SESSION["contraseña"])) {

        // comprobar seguridad 
        $url = URLATAQUE . "/loginSeguridad";
        $datos["usuario"] = $_SESSION["usuario"];
        $datos["clave"] = $_SESSION["contraseña"];
        $datos["token"] = $_SESSION["token"];
        $respuesta = consumir_servicios_REST($url, "post", $datos);
        $archivo = json_decode($respuesta);

        if (!$archivo) {
            session_destroy();
            die(errores("no se ha obtenido respuesta al conectarse" . $respuesta));
        }

        if (isset($archivo->error)) {
            session_destroy();
            die(errores($archivo->mensaje_error));
        }

        if (isset($archivo->mensaje)) {
            session_unset();
            $_SESSION["seguridad"] = $archivo->mensaje;
            header("location:index.php");
            exit();
        }

        // si el usuario existe, quedarme con los datos
        $datosUsuario = $archivo->usuario;

        // comprobar el tiempo de inactividad
        if (time() - $_SESSION["ultimaAccion"] > TIEMPOENSEGUNDOS) {
            session_unset();
            $_SESSION["seguridad"] = "el tiempo de session ha expirado";
            header("location:index.php");
            exit();
        }

        // actualizar el tiempo
        $_SESSION["ultimaAccion"] = time();


        // si el usuario es normal
        if ($datosUsuario->tipo == "normal") {
            ?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Document</title>
            </head>

            <body>
                <h1>Bienvenido -
                    <?php echo $datosUsuario->usuario . " - " . $datosUsuario->tipo ?>
                </h1>

                <form action="index.php" method="post">
                    <button type="submit" name="salir" id="salir">Salir</button>
                </form>
            </body>

            </html>

            <?php

            // si el usuario es administrador
        } else {
            echo "<h1>Listado de usuarios</h1>";

            // conectarnos para listar los usuarios en una tabla
            $url = URLATAQUE . "/usuarios";
            $respuesta = consumir_servicios_REST($url, "get");
            $archivo = json_decode($respuesta);

            if (!$archivo) {
                die(errores("no se ha obtenido respuesta al obtener los datos de los usuarios llamada /usuarios"));
            }

            if (isset($archivo->error)) {
                die(errores($archivo->error));
            }

            // montar la tabla
            echo "<table>";
            echo "<tr><td>Nombre de usuario</td><td>Borrar</td><td>Editar</td></tr>";

            foreach ($archivo->usuarios as $fila) {
                // la tabla contiene un formulario por cada boton que nos redirige a nosotros mismos, con los resultados de cada tupla
                echo "<tr>
                        <td><form action='#' method='post'> <button class='enlace' name='mostrarUsuario' id='usuario' type='submit' value='" . $fila->id_usuario . "'>" . $fila->usuario . "</button></form></td>
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
                    die(errores("no se ha obtenido respuesta al obtener usuario por id llamada /obtenerUsuario/id"));
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
                    die(errores("no se ha obtenido respuesta al editar llamada /obtenerUsuario/{id_usuario}"));
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
                                    if (strlen($_POST["contrasñea"]) > 15) {
                                        echo "<span>Campo vacio</span>";
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
            else if (isset($_SESSION["usuario"])) {
                echo "<form action='nuevousuario.php' method='post'>";
                echo "<p><button type='submit' name='nuevousuario' id='nuevousuario'>Insertar nuevo usuario</button></p>";
                echo "</form>";
            }
        }


        // si NO estoy logeado
    } else {
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
        </head>

        <body>
            <h1>Login App con servicios</h1>
            <form action="index.php" method="post">
                <p>
                    <label for="usuario">Usuario:</label>
                    <input type="text" name="usuario" id="usuario" value="<?php if (isset($_POST["usuario"]))
                        echo $_POST["usuario"] ?>">
                        <?php
                    if (isset($_POST["logear"]) && $errorUsuario) {
                        if ($_POST["usuario"] == "") {
                            echo "<span>El usuario no puede estar vacio</span>";
                        } else {
                            echo "<span>El usuario/contraseña no existe o es erroneo</span>";
                        }
                    }
                    ?>

                </p>

                <p>
                    <label for="contrasenaLogin">Contraseña:</label>
                    <input type="password" name="contrasenaLogin" id="contrasenaLogin" value="<?php if (isset($_POST["contrasenaLogin"]))
                        echo $_POST["contrasenaLogin"] ?>">
                        <?php
                    if (isset($_POST["logear"]) && $errorContraseña) {
                        if ($_POST["contrasenaLogin"] == "") {
                            echo "<span>La contraseña no puede estar vacia</span>";
                        }
                    }
                    ?>

                </p>

                <button name="logear" id="logear" type="submit">Login</button>
            </form>
            <?php
            if (isset($_SESSION["seguridad"])) {
                echo "<p>" . $_SESSION["seguridad"] . "</p>";
                session_unset();
            }
            ?>
        </body>

        </html>

        <?php
    }
    ?>
</body>

</html>