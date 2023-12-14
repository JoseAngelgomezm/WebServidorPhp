<?php
function errorPagina($mensaje)
{
    echo " <!DOCTYPE html>
<html lang='es'>
<head>
 <meta charset='UTF-8'>
 <meta name='viewport' content='width=device-width, initial-scale=1.0'>
 <title>Error ejercicio login</title>
</head>
<body>
 <p>" . $mensaje . "</p>
</body>
</html>";
}

session_name("PrimerLogin");
session_start();
define("TIEMPOMAXIMOSEGUNDOS", 600);

// si se ha pulsado salir, destruir la sesion
if (isset($_POST["salir"])) {
    session_destroy();
    header("location:index.php");
    exit();
}

if (isset($_POST["botonBorrarUsuario"])) {
    //conectar a la base de datos
    try {
        $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro2");
    } catch (Exception $e) {
        die("<p>No se ha podido conectar a la base de datos, para el borrado</p></body></html>");
    }

    // intentar la consulta
    try {
        $consulta = "DELETE from usuarios where id_usuario='" . $_POST["botonBorrarUsuario"] . "'";
        $resultado = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(errorPagina("no se ha podido realizar la consulta para comprobar usuario/clave"));
    }

    mysqli_close($conexion);
    $_SESSION["mensaje"] = "<p>Se ha borrado con exito el usuario</p>";
    header("location:index.php");
    exit();
} else if (isset($_POST["botonInsertarUsuario"])) {
    // comprobar los errores
    $errorNombre = $_POST["nombre"] == "" || strlen($_POST["nombre"]) > 30;
    $errorUsuario = $_POST["usuario"] == "" || strlen($_POST["usuario"]) > 20;
    $errorContraseña = $_POST["contraseña"] == "" || strlen($_POST["contraseña"]) > 15;
    $errorEmail = $_POST["email"] == "" || strlen($_POST["email"]) > 50 || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $errorUsuarioRepetido = false;
    $errorEmailRepetido = false;

    // si no hay error de usuario
    if (!$errorUsuario) {
        // comprobar que el usuario no este repetido
        // intentar la conexion
        try {
            $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro2");
            mysqli_set_charset($conexion, "utf8");
        } catch (Exception $e) {
            die(errorPagina("<p>No se ha podido conectar a la base de datos al comprobar usuario repetido</p>"));
        }

        // intentar la consulta
        try {
            $consulta = "select * from usuarios where usuario='" . $_POST["usuario"] . "'";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(errorPagina("<p>usuario No se ha podido hacer la consulta para comprobar user repetido</p>"));
        }

        mysqli_close($conexion);

        $errorUsuarioRepetido = mysqli_num_rows($resultado) > 0;
    }

    // si no hay error de email
    if (!$errorEmail) {
        // intentar la conexion
        try {
            $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro2");
            mysqli_set_charset($conexion, "utf8");
        } catch (Exception $e) {
            die(errorPagina("<p>No se ha podido conectar a la base de datos</p>"));
        }

        // intentar la consulta
        try {
            $consulta = "select * from usuarios where email='" . $_POST["email"] . "'";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(errorPagina("<p>email No se ha podido hacer la consulta</p>"));
        }
        // si tiene mas de 1 tupla el email ya existirá, error de email sera true
        mysqli_close($conexion);
        $errorEmailRepetido = mysqli_num_rows($resultado) > 0;
    }

    // definir el error del formulario
    $errorFormularioInsercion = $errorNombre || $errorUsuario || $errorContraseña || $errorEmail || $errorUsuarioRepetido || $errorEmailRepetido;

    // si no hay error en el formulario, insertar
    if (!$errorFormularioInsercion) {
        try {
            $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro2");
        } catch (Exception $e) {
            session_destroy();
            die("<p>No se ha podido conectar a la base de datos, para la insercion</p></body></html>");
        }

        // intentar la consulta
        try {
            $consulta = "INSERT INTO `usuarios`(`nombre`, `usuario`, `clave`, `email`, `tipo`) VALUES ('" . $_POST["nombre"] . "','" . $_POST["usuario"] . "','" . md5($_POST["contraseña"]) . "','" . $_POST["email"] . "','" . $_POST["tipo"] . "')";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(errorPagina("no se ha podido realizar la insercion"));
        }
        mysqli_close($conexion);
        $_SESSION["mensaje"] = "<p>Se ha insertado un usuario correctamente</p>";
        header("location:index.php");
        exit();
    }

    // si se ha pulsado el boton actualizar usuario
} else if (isset($_POST["botonActualizarUsuario"])) {
    // comprobar los errores
    $errorNombre = $_POST["nombre"] == "" || strlen($_POST["nombre"]) > 30;
    $errorUsuario = $_POST["usuario"] == "" || strlen($_POST["usuario"]) > 20;
    $errorContraseña = $_POST["contraseña"] == "" || strlen($_POST["contraseña"]) > 15;
    $errorEmail = $_POST["email"] == "" || strlen($_POST["email"]) > 50 || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $errorUsuarioRepetido = false;
    $errorEmailRepetido = false;

    // si no hay error de usuario
    if (!$errorUsuario) {
        // comprobar que el usuario no este repetido
        // intentar la conexion
        try {
            $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro2");
            mysqli_set_charset($conexion, "utf8");
        } catch (Exception $e) {
            die(errorPagina("<p>No se ha podido conectar a la base de datos al comprobar usuario repetido al actualizar</p>"));
        }

        // intentar la consulta
        try {
            $consulta = "select * from usuarios where usuario='" . $_POST["usuario"] . "' AND id_usuario!='" . $_POST["botonActualizarUsuario"] . "'";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(errorPagina("<p>usuario No se ha podido hacer la consulta para comprobar user repetido al actualizar</p>"));
        }

        mysqli_close($conexion);

        $errorUsuarioRepetido = mysqli_num_rows($resultado) > 0;
    }

    // si no hay error de email
    if (!$errorEmail) {
        // intentar la conexion
        try {
            $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro2");
            mysqli_set_charset($conexion, "utf8");
        } catch (Exception $e) {
            die(errorPagina("<p>No se ha podido conectar a la base de datos</p>"));
        }

        // intentar la consulta
        try {
            $consulta = "select * from usuarios where email='" . $_POST["email"] . "' AND id_usuario!='" . $_POST["botonActualizarUsuario"] . "'";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(errorPagina("<p>email No se ha podido hacer la consulta</p>"));
        }
        // si tiene mas de 1 tupla el email ya existirá, error de email sera true
        mysqli_close($conexion);
        $errorEmailRepetido = mysqli_num_rows($resultado) > 0;
    }

    // definir el error del formulario
    $errorFormularioEdicion = $errorNombre || $errorUsuario || $errorContraseña || $errorEmail || $errorUsuarioRepetido || $errorEmailRepetido;

    // si no hay error en el formulario, actualizar
    if (!$errorFormularioEdicion) {
        try {
            $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro2");
        } catch (Exception $e) {
            session_destroy();
            die("<p>No se ha podido conectar a la base de datos, para la insercion</p></body></html>");
        }

        // intentar la consulta
        try {
            $consulta = "UPDATE `usuarios` SET `nombre`='" . $_POST["nombre"] . "',`usuario`='" . $_POST["usuario"] . "',`clave`='" . md5($_POST["contraseña"]) . "',`email`='" . $_POST["email"] . "',`tipo`='" . $_POST["tipo"] . "' WHERE id_usuario='" . $_POST["botonActualizarUsuario"] . "'";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(errorPagina("no se ha podido realizar la insercion"));
        }
        mysqli_close($conexion);
        $_SESSION["mensaje"] = "<p>Se ha actualizado un usuario correctamente</p>";
        header("location:index.php");
        exit();
    }

}else if(isset($_POST["botonConfirmarRegistro"])){
    // controlar errores de registro
    // comprobar los errores
    $errorNombre = $_POST["nombre"] == "" || strlen($_POST["nombre"]) > 30;
    $errorUsuario = $_POST["usuario"] == "" || strlen($_POST["usuario"]) > 20;
    $errorContraseña = $_POST["contraseña"] == "" || strlen($_POST["contraseña"]) > 15;
    $errorEmail = $_POST["email"] == "" || strlen($_POST["email"]) > 50 || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $errorUsuarioRepetido = false;
    $errorEmailRepetido = false;

    // si no hay error de usuario
    if (!$errorUsuario) {
        // comprobar que el usuario no este repetido
        // intentar la conexion
        try {
            $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro2");
            mysqli_set_charset($conexion, "utf8");
        } catch (Exception $e) {
            die(errorPagina("<p>No se ha podido conectar a la base de datos al comprobar usuario repetido en registro</p>"));
        }

        // intentar la consulta
        try {
            $consulta = "select * from usuarios where usuario='" . $_POST["usuario"] . "'";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(errorPagina("<p>usuario No se ha podido hacer la consulta para comprobar user repetido en registro</p>"));
        }

        mysqli_close($conexion);

        $errorUsuarioRepetido = mysqli_num_rows($resultado) > 0;
    }

    // si no hay error de email
    if (!$errorEmail) {
        // intentar la conexion
        try {
            $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro2");
            mysqli_set_charset($conexion, "utf8");
        } catch (Exception $e) {
            die(errorPagina("<p>No se ha podido conectar a la base de datos en registro</p>"));
        }

        // intentar la consulta
        try {
            $consulta = "select * from usuarios where email='" . $_POST["email"] . "'";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(errorPagina("<p>email No se ha podido hacer la consulta en registro</p>"));
        }
        // si tiene mas de 1 tupla el email ya existirá, error de email sera true
        mysqli_close($conexion);
        $errorEmailRepetido = mysqli_num_rows($resultado) > 0;
    }

    // definir el error del formulario
    $errorFormularioRegistro = $errorNombre || $errorUsuario || $errorContraseña || $errorEmail || $errorUsuarioRepetido || $errorEmailRepetido;

    // si no hay error en el formulario, insertar
    if (!$errorFormularioRegistro) {
        try {
            $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro2");
        } catch (Exception $e) {
            session_destroy();
            die("<p>No se ha podido conectar a la base de datos, para la insercion</p></body></html>");
        }

        // intentar la consulta
        try {
            $consulta = "INSERT INTO `usuarios`(`nombre`, `usuario`, `clave`, `email`, `tipo`) VALUES ('" . $_POST["nombre"] . "','" . $_POST["usuario"] . "','" . md5($_POST["contraseña"]) . "','" . $_POST["email"] . "','" . $_POST["tipo"] . "')";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(errorPagina("no se ha podido realizar la insercion"));
        }
        
        // poner los datos en la sesion para que conecte cuando recargue
        mysqli_close($conexion);
        $_SESSION["usuario"] = $_POST["usuario"];
        $_SESSION["contraseña"] = md5($_POST["contraseña"]);
        $_SESSION["mensaje"] = "<p>Se ha registrado un usuario correctamente</p>";
        // establecer el tiempo de la session
        $_SESSION["ultimaAccion"] = time();
        header("location:index.php");
        exit();
    }
}


// si exsite el session usuario, es que el usuario ya ha iniciado sesion
if (isset($_SESSION["usuario"])) {
    // COMPROBAR QUE EL USUARIO Y LA CONTRASEÑA CONTINUAN EN LA BASE DE DATOS
    //conectar a la base de datos
    try {
        $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro2");
    } catch (Exception $e) {
        die("<p>No se ha podido conectar a la base de datos, una vez logeado</p></body></html>");
    }

    // intentar la consulta
    try {
        $consulta = "SELECT * from usuarios where usuario='" . $_SESSION["usuario"] . "' and clave='" . $_SESSION["contraseña"] . "'";
        $resultado = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(errorPagina("no se ha podido realizar la consulta para comprobar usuario/clave"));
    }
    // CONTROL SEGURIDAD
    // si no hay tuplas, el usuario ha sido eliminado
    if (mysqli_num_rows($resultado) <= 0) {
        // cerrar conexion
        mysqli_close($conexion);
        // eliminar todas las variables de session que contiene nombre usuario y contraseña
        session_unset();
        // crear el mensaje de seguridad para preguntar por el en index
        $_SESSION["seguridad"] = "Ya no se encuentra en la BD";
        // cargar de nuevo la web
        header("location:index.php");
        exit();

        // si hay tuplas, es que sigue en la base de datos
    }
    mysqli_close($conexion);

    // HACER EL CONTROL DE INACTIVIDAD
    if (time() - $_SESSION["ultimaAccion"] > TIEMPOMAXIMOSEGUNDOS) {
        // eliminar todas las variables de session que contiene nombre usuario y contraseña
        session_unset();
        // crear el mensaje de inactividad, para preguntar por el index
        $_SESSION["inactividad"] = "Ya ha expirado su session";
        header("location:index.php");
        exit();
    }

    // si todo el control ha ido bien, poner el tiempo al actual
    $_SESSION["ultimaAccion"] = time();

    // obtener los datos en array asociativo, para dar acceso normal o administrador
    $datosUsuarioLogeado = mysqli_fetch_assoc($resultado);



    // mostrar la pagina para tipo usuario normal o administrador
    if ($datosUsuarioLogeado["tipo"] == "normal") {
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
        </head>

        <body>
            <h2>Bienvenido
                <?php echo $datosUsuarioLogeado["nombre"] ?>
            </h2>
            <form action="index.php" method="post">
                <button type="submit" name="salir">Salir</button>
            </form>
            <?php
    } else if ($datosUsuarioLogeado["tipo"] == "admin") {
        ?>
                <!DOCTYPE html>
                <html lang="es">

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Ejercicio Login</title>
                </head>

                <body>
                    <h2>Bienvenido
                    <?php echo $_SESSION["usuario"] ?>
                    </h2>
                    <form action="index.php" method="post">
                        <button type="submit" name="salir">Salir</button>
                    </form>
                    <?php


                    echo "<h1>Tipo cuenta: Administrador</h1>";
                    if (isset($_SESSION["mensaje"])) {
                        echo $_SESSION["mensaje"];
                    }
                    // OBTENER TODOS LOS USUARIOS QUE SEAN NORMALES
                    try {
                        $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro2");
                    } catch (Exception $e) {
                        die("<p>No se ha podido conectar a la base de datos, mostrar datos usuarios normales</p></body></html>");
                    }

                    // intentar la consulta
                    try {
                        $consulta = "SELECT * from usuarios where tipo='normal'";
                        $resultado = mysqli_query($conexion, $consulta);
                    } catch (Exception $e) {
                        mysqli_close($conexion);
                        session_destroy();
                        die(errorPagina("no se ha podido realizar la consulta para obtener los datos de los usuarios normales"));
                    }

                    mysqli_close($conexion);

                    echo "<form method='post' action='index.php'>";
                    echo "<table border='1px'>";
                    echo "<tr>";
                    echo "<td>Nombre</td>";
                    echo "<td>Usuario</td>";
                    echo "<td>Email</td>";
                    echo "<td>Tipo</td>";
                    echo "<td><button type='submit' name='botonNuevoUsuario'>Nuevo Ususario+</button></td>";
                    echo "</tr>";
                    while ($datosUsuarios = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>" . $datosUsuarios["nombre"] . "</td>";
                        echo "<td>" . $datosUsuarios["usuario"] . "</td>";
                        echo "<td>" . $datosUsuarios["email"] . "</td>";
                        echo "<td>" . $datosUsuarios["tipo"] . "</td>";
                        echo "<td><form method='post' action='index.php'><button name='botonBorrarUsuario' value='" . $datosUsuarios["id_usuario"] . "'>Borrar</button><button name='botonEditarUsuario' value='" . $datosUsuarios["id_usuario"] . "'>Editar</button></form></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "</form>";

                    if (isset($_POST["botonNuevoUsuario"]) || isset($_POST["botonInsertarUsuario"]) && $errorFormularioInsercion) {
                        ?>
                        <form action="index.php" method="post">
                            <br>
                            <label for="nombre">Nombre: </label><input type="text" name="nombre" value="<?php if (isset($_POST["nombre"]))
                                echo $_POST["nombre"] ?>">
                            <?php
                            if (isset($errorFormularioInsercion)) {
                                if ($_POST["nombre"] == "") {
                                    echo "<span>El nombre esta vacio</span>";
                                }
                            }
                            ?>
                            <br>
                            <br>
                            <label for="usuario">Usuario: </label><input type="text" name="usuario" value="<?php if (isset($_POST["usuario"]))
                                echo $_POST["usuario"] ?>">
                            <?php
                            if (isset($errorFormularioInsercion)) {
                                if ($_POST["usuario"] == "") {
                                    echo "<span>El usuario esta vacio</span>";
                                } else if (strlen($_POST["usuario"]) > 20) {
                                    echo "<span>El nombre no puede superar 20 caracteres";
                                } else if ($errorUsuarioRepetido) {
                                    echo "<span>El usuario esta repetido</span>";
                                }
                            }
                            ?>
                            <br>
                            <br>
                            <label for="contraseña">Contraseña: </label><input type="password" name="contraseña" value="">
                            <?php
                            if (isset($errorFormularioInsercion)) {
                                if ($_POST["contraseña"] == "") {
                                    echo "<span>La contraseña esta vacia</span>";
                                } else if (strlen($_POST["contraseña"]) > 15) {
                                    echo "<span>La contraseña es demasiado larga</span>";
                                }
                            }
                            ?>
                            <br>
                            <br>
                            <label for="email">Email: </label><input type="email" name="email" value="<?php if (isset($_POST["email"]))
                                echo $_POST["email"] ?>">
                            <?php
                            if (isset($errorFormularioInsercion)) {
                                if ($_POST["email"] == "") {
                                    echo "<span>El email esta vacio</span>";
                                } else if (strlen($_POST["email"]) > 50) {
                                    echo "<span>El email es demasiado largo</span>";
                                } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                                    echo "<span>El email no tiene un formato valido</span>";
                                } else if ($errorEmailRepetido) {
                                    echo "<span>El email ya se encuentra registrado</span>";
                                }
                            }
                            ?>
                            <br>
                            <br>
                            <label for="">Tipo Usuario: </label>
                            <label for="normal">Normal</label><input checked type="radio" name="tipo" id="normal" value="normal">
                            <label for="administrador">Administrador</label><input type="radio" name="tipo" id="administrador"
                                value="admin">
                            <br>
                            <br>
                            <button type="submit" name="botonInsertarUsuario">Insertar</button>
                        </form>
                    <?php

                        // Si se ha pulsado el boton editar usuario
                    } else if (isset($_POST["botonEditarUsuario"]) || isset($_POST["botonActualizarUsuario"]) && $errorFormularioEdicion) {

                        // obtener los datos del usuario que se pulsado con el boton editar
                        if (isset($_POST["botonEditarUsuario"])) {
                            $idUsuario = $_POST["botonEditarUsuario"];
                        } else if (isset($_POST["botonActualizarUsuario"])) {
                            $idUsuario = $_POST["botonActualizarUsuario"];
                        }
                        // intentar la conexion
                        try {
                            $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro2");
                            mysqli_set_charset($conexion, "utf8");
                        } catch (Exception $e) {
                            session_destroy();
                            die(errorPagina("<p>No se ha podido conectar a la base de datos al consultar datos edicion</p>"));
                        }

                        // intentar la consulta
                        try {
                            $consulta = "select * from usuarios where id_usuario='" . $idUsuario . "'";
                            $resultado = mysqli_query($conexion, $consulta);
                        } catch (Exception $e) {
                            session_destroy();
                            mysqli_close($conexion);
                            die(errorPagina("<p>usuario No se ha podido hacer la consulta para editar</p>"));
                        }

                        $datosUsuarioEdicion = mysqli_fetch_assoc($resultado);
                        mysqli_close($conexion);


                        ?>
                            <form action="index.php" method="post">
                                <br>
                                <label for="nombre">Nombre: </label><input type="text" name="nombre" value="<?php if (isset($_POST["botonEditarUsuario"])) {
                                    echo $datosUsuarioEdicion["nombre"];
                                } else if (isset($_POST["botonActualizarUsuario"]))
                                    echo $_POST["nombre"] ?>">
                            <?php
                                if (isset($errorFormularioEdicion)) {
                                    if ($_POST["nombre"] == "") {
                                        echo "<span>El nombre esta vacio</span>";
                                    }
                                }
                                ?>
                                <br>
                                <br>
                                <label for="usuario">Usuario: </label><input type="text" name="usuario" value="<?php if (isset($_POST["botonEditarUsuario"])) {
                                    echo $datosUsuarioEdicion["usuario"];
                                } else if (isset($_POST["botonActualizarUsuario"]))
                                    echo $_POST["usuario"] ?>">
                            <?php
                                if (isset($errorFormularioEdicion)) {
                                    if ($_POST["usuario"] == "") {
                                        echo "<span>El usuario esta vacio</span>";
                                    } else if (strlen($_POST["usuario"]) > 20) {
                                        echo "<span>El nombre no puede superar 20 caracteres";
                                    } else if ($errorUsuarioRepetido) {
                                        echo "<span>El usuario esta repetido</span>";
                                    }
                                }
                                ?>
                                <br>
                                <br>
                                <label for="contraseña">Contraseña: </label><input type="password" name="contraseña" value="">
                            <?php
                            if (isset($errorFormularioEdicion)) {
                                if ($_POST["contraseña"] == "") {
                                    echo "<span>La contraseña esta vacia</span>";
                                } else if (strlen($_POST["contraseña"]) > 15) {
                                    echo "<span>La contraseña es demasiado larga</span>";
                                }
                            }
                            ?>
                                <br>
                                <br>
                                <label for="email">Email: </label><input type="email" name="email" value="<?php if (isset($_POST["botonEditarUsuario"])) {
                                    echo $datosUsuarioEdicion["email"];
                                } else if (isset($_POST["botonActualizarUsuario"]))
                                    echo $_POST["email"] ?>">
                            <?php
                                if (isset($errorFormularioEdicion)) {
                                    if ($_POST["email"] == "") {
                                        echo "<span>El email esta vacio</span>";
                                    } else if (strlen($_POST["email"]) > 50) {
                                        echo "<span>El email es demasiado largo</span>";
                                    } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                                        echo "<span>El email no tiene un formato valido</span>";
                                    } else if ($errorEmailRepetido) {
                                        echo "<span>El email ya se encuentra registrado</span>";
                                    }
                                }
                                ?>
                                <br>
                                <br>
                                <label for="">Tipo Usuario: </label>

                                <label for="normal">Normal</label><input <?php if (isset($_POST["botonEditarUsuario"]) && $datosUsuarioEdicion["tipo"] == "normal") {
                                    echo "checked";
                                } else if (isset($_POST["botonActualizarUsuario"]) && $_POST["tipo"] == "normal")
                                    echo "checked"; ?> type="radio"
                                    name="tipo" id="normal" value="normal">
                                <label for="administrador">Administrador</label><input <?php if (isset($_POST["botonEditarUsuario"]) && $datosUsuarioEdicion["tipo"] == "admin") {
                                    echo "checked";
                                } else if (isset($_POST["botonActualizarUsuario"]) && $_POST["tipo"] == "admin")
                                    echo "checked"; ?> type="radio"
                                    name="tipo" id="administrador" value="admin">
                                <br>
                                <br>
                                <button type="submit" name="botonActualizarUsuario"
                                    value="<?php echo $datosUsuarioEdicion["id_usuario"] ?>">Actualizar</button>
                            </form>

                    <?php
                    }

                    ?>
                </body>

                </html>
            <?php


    }

    // si se ha pulsado el boton registrarse
} else if (isset($_POST["botonRegistro"]) || isset($_POST["botonConfirmarRegistro"]) && $errorFormularioRegistro) {
    // poner el formulario de insercion
    ?>
            <form action="index.php" method="post">
                <br>
                <label for="nombre">Nombre: </label><input type="text" name="nombre" value="<?php if (isset($_POST["nombre"]))
                    echo $_POST["nombre"] ?>">
                <?php
                if (isset($errorFormularioRegistro)) {
                    if ($_POST["nombre"] == "") {
                        echo "<span>El nombre esta vacio</span>";
                    }
                }
                ?>
                <br>
                <br>
                <label for="usuario">Usuario: </label><input type="text" name="usuario" value="<?php if (isset($_POST["usuario"]))
                    echo $_POST["usuario"] ?>">
                <?php
                if (isset($errorFormularioRegistro)) {
                    if ($_POST["usuario"] == "") {
                        echo "<span>El usuario esta vacio</span>";
                    } else if (strlen($_POST["usuario"]) > 20) {
                        echo "<span>El nombre no puede superar 20 caracteres";
                    } else if ($errorUsuarioRepetido) {
                        echo "<span>El usuario esta repetido</span>";
                    }
                }
                ?>
                <br>
                <br>
                <label for="contraseña">Contraseña: </label><input type="password" name="contraseña" value="">
                <?php
                if (isset($errorFormularioRegistro)) {
                    if ($_POST["contraseña"] == "") {
                        echo "<span>La contraseña esta vacia</span>";
                    } else if (strlen($_POST["contraseña"]) > 15) {
                        echo "<span>La contraseña es demasiado larga</span>";
                    }
                }
                ?>
                <br>
                <br>
                <label for="email">Email: </label><input type="email" name="email" value="<?php if (isset($_POST["email"]))
                    echo $_POST["email"] ?>">
                <?php
                if (isset($errorFormularioRegistro)) {
                    if ($_POST["email"] == "") {
                        echo "<span>El email esta vacio</span>";
                    } else if (strlen($_POST["email"]) > 50) {
                        echo "<span>El email es demasiado largo</span>";
                    } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                        echo "<span>El email no tiene un formato valido</span>";
                    } else if ($errorEmailRepetido) {
                        echo "<span>El email ya se encuentra registrado</span>";
                    }
                }
                ?>
                <br>
                <br>
                <label for="">Tipo Usuario: </label>
                <label for="normal">Normal</label><input checked type="radio" name="tipo" id="normal" value="normal">
                <label for="administrador">Administrador</label><input type="radio" name="tipo" id="administrador"
                    value="admin">
                <br>
                <br>
                <button type="submit" name="botonConfirmarRegistro">Confirmar registro</button>
                <button type="submit" name="salir">Salir</button>
            </form>

        <?php
    // sino, mostrar el formulario de login
} else {
    // controlar los errores
    if (isset($_POST["botonLogin"])) {
        $errorUsuario = $_POST["usuario"] == "" || strlen($_POST["usuario"]) > 20;
        $errorContraseña = $_POST["contraseña"] == "" || strlen($_POST["contraseña"]) > 15;

        $errorFormulario = $errorUsuario || $errorContraseña;

        // si no hay errores en los campos, verificar el usuario
        if (!$errorFormulario) {
            //conectar a la base de datos
            try {
                $conexion = mysqli_connect("localhost", "jose", "josefa", "bd_foro2");
            } catch (Exception $e) {
                die("<p>No se ha podido conectar a la base de datos, 1</p></body></html>");
            }

            // intentar la consulta
            try {
                $consulta = "SELECT usuario from usuarios where usuario='" . $_POST["usuario"] . "' and clave='" . md5($_POST["contraseña"]) . "'";
                $resultado = mysqli_query($conexion, $consulta);
            } catch (Exception $e) {
                mysqli_close($conexion);
                session_destroy();
                die(errorPagina("no se ha podido realizar la consulta para obtener usuario, clave"));
            }

            mysqli_close($conexion);

            // si obtengo tuplas, el usuario y contraseña existen, logea
            if (mysqli_num_rows($resultado) > 0) {

                // saltar al index, guardando usuario y contraseña
                $_SESSION["usuario"] = $_POST["usuario"];
                $_SESSION["contraseña"] = md5($_POST["contraseña"]);
                $_SESSION["ultimaAccion"] = time();
                header("location:index.php");
                exit();

                // sino, el usuario no existe 
            } else {
                $errorUsuarioNoExiste = true;
                $errorUsuario = true;
            }
        }
    }
    ?>

            <!DOCTYPE html>
            <html lang="es">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Document</title>
                <style>
                    .error {
                        color: red;
                    }
                </style>
            </head>

            <body>
                <h2>Primer Login</h2>
                <form action="index.php" method="post">
                    <p>
                        <label for="usuario">Usuario: </label>
                        <input type="text" name="usuario" id="usuario" value="<?php if (isset($_POST["usuario"]))
                            echo $_POST["usuario"] ?>">
                        <?php
                        if (isset($_POST["usuario"]) && $errorUsuario) {
                            if ($_POST["usuario"] == "") {
                                echo "<span class='error'>Campo vacio</span>";
                            } else if (strlen($_POST["usuario"]) > 20) {
                                echo "<span class='error'>El usuario es demasiado largo</span>";
                            } else if (isset($errorUsuarioNoExiste) && $errorUsuarioNoExiste) {
                                echo "<span class='error'>El usuario no existe o la clave no es correcta</span>";
                            }
                        }
                        ?>
                    </p>

                    <p>
                        <label for="contraseña">Contraseña: </label>
                        <input type="password" name="contraseña" id="contraseña" value="">
                        <?php
                        if (isset($_POST["contraseña"]) && $errorContraseña) {
                            if ($_POST["contraseña"] == "") {
                                echo "<span class='error'>Campo vacio</span>";
                            } else if (strlen($_POST["contraseña"]) > 15) {
                                echo "<span class='error'>La contraseña es demasiado larga</span>";
                            }
                        }
                        ?>
                    </p>
                    <p>
                        <button type="submit" name="botonLogin">Login</button>
                        <button type="submit" name="botonRegistro">Registrarse</button>
                    </p>
                </form>
                <?php
                // si se ha eliminado el usuario y existe session seguridad
                if (isset($_SESSION["seguridad"])) {
                    echo "<p class='error'>" . $_SESSION["seguridad"] . "</p>";
                    // para eliminar este $_SESSION seguridad
                    session_destroy();
                }

                // si se ha transcurrido el tiempo definido para la session
                if (isset($_SESSION["inactividad"])) {
                    echo "<p class='error'>" . $_SESSION["inactividad"] . "</p>";
                    // para eliminar este $_SESSION inactividad
                    session_destroy();
                }
                ?>
            </body>

            </html>
        <?php
}
?>