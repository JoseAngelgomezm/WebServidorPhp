<?php
define("TIEMPOMAXIMOSEGUNDOSSIMULACRO", "10");
session_name("SimulacroExamenLoginVideoclub");
session_start();
define("HOST", "localhost");
define("USER", "jose");
define("PASSWORD", "josefa");
define("BD", "bd_videoclub_2");

function LetraNIF($dni)
{
    $valor = (int) ($dni / 23);
    $valor *= 23;
    $valor = $dni - $valor;
    $letras = "TRWAGMYFPDXBNJZSQVHLCKEO";
    $letraNif = substr($letras, $valor, 1);
    return $letraNif;
}


function errorPagina($mensaje)
{
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Document</title>
    </head>
    <body>
        <p>$mensaje</p>
    </body>
    </html>";
}
if (isset($_POST["continuar"])) {
    // controlar los errores
    $errorNombreUsuario = $_POST["usuario"] == "";
    $errorContraseña = $_POST["password"] == "";
    $errorRepiteContraseña = $_POST["passwordRepite"] == "" || $_POST["password"] !== $_POST["passwordRepite"];
    $errorDni = $_POST["dni"] == "" || strlen($_POST["dni"]) >= 10 || strlen($_POST["dni"]) <= 8 || !is_numeric(substr($_POST["dni"], 0, 8)) || is_numeric(substr($_POST["dni"], -1)) || LetraNIF(substr($_POST["dni"], 0, 8)) !== substr($_POST["dni"], -1);
    $errorEmail = $_POST["email"] == "" || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $errorTelefono = $_POST["telefono"] == "" || strlen($_POST["telefono"]) >= 10 || !is_numeric($_POST["telefono"]);

    $errorFormularioRegistro = $errorNombreUsuario || $errorContraseña || $errorRepiteContraseña || $errorDni || $errorEmail || $errorTelefono;


    if (!$errorFormularioRegistro) {
        // comprobar que el dni no este repetido
        try {
            $conexion = mysqli_connect(HOST, USER, PASSWORD, BD);
        } catch (Exception $e) {
            session_destroy();
            die(errorPagina("No se ha podido conectar a la bd para verificar datos de usuario"));
        }

        // intentar la consulta
        try {
            $consulta = "select DNI from usuarios where DNI='" . $_POST["dni"] . "'";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(errorPagina("No se ha podido conectar a la bd para realizar consulta de usuario"));
        }

        mysqli_close($conexion);
        // si obtenemos tupla, es que el dni ya existe
        if (mysqli_num_rows($resultado) >= 1) {
            $errorDniRepetido = true;
            $errorFormularioRegistro = true;
        } else {
            $errorDniRepetido = false;
        }

        // si el dni no esta repetido
        if (!$errorDniRepetido) {
            // comprobar el usuario que no este repetido
            // realizar la conexion para comprobar datos de usuario
            try {
                $conexion = mysqli_connect(HOST, USER, PASSWORD, BD);
            } catch (Exception $e) {
                session_destroy();
                die(errorPagina("No se ha podido conectar a la bd para verificar datos de usuario"));
            }

            // intentar la consulta
            try {
                $consulta = "select usuario from usuarios where usuario='" . $_POST["usuario"] . "'";
                $resultado = mysqli_query($conexion, $consulta);
            } catch (Exception $e) {
                mysqli_close($conexion);
                session_destroy();
                die(errorPagina("No se ha podido conectar a la bd para realizar consulta de usuario"));
            }

            mysqli_close($conexion);

            // si obtenemos tupla, es que el usuario ya existe
            if (mysqli_num_rows($resultado) >= 1) {
                $errorUsuarioRepetido = true;
                $errorFormularioRegistro = true;
            } else {
                $errorUsuarioRepetido = false;
            }
        }

        // si el usuario no esta repetido ni el dni esta repetido
        if (isset($errorUsuarioRepetido) && !$errorUsuarioRepetido && isset($errorDniRepetido) && !$errorDniRepetido) {
            // hacer la insercion del usuario

            // realizar la conexion para insertar al usuario
            try {
                $conexion = mysqli_connect(HOST, USER, PASSWORD, BD);
            } catch (Exception $e) {
                session_destroy();
                die(errorPagina("No se ha podido conectar a la bd para verificar datos de usuario"));
            }

            // intentar la consulta
            try {
                $consulta = "INSERT INTO `usuarios`(`DNI`, `usuario`, `clave`, `telefono`, `email`) VALUES ('" . $_POST["dni"] . "','" . $_POST["usuario"] . "','" . md5($_POST["password"]) . "','" . $_POST["telefono"] . "','" . $_POST["email"] . "')";
                $resultado = mysqli_query($conexion, $consulta);
            } catch (Exception $e) {
                mysqli_close($conexion);
                session_destroy();
                die(errorPagina("No se ha podido conectar a la bd para insertar usuario"));
            }

            mysqli_close($conexion);

            // establecer la session con los datos del nuevo usuario
            $_SESSION["usuario"] = $_POST["usuario"];
            $_SESSION["clave"] = md5($_POST["password"]);
            // cuando registro, el tipo usuario sera normal
            $_SESSION["tipo"] = "normal";
            // definir el tiempo nuevo
            $_SESSION["ultimaAccion"] = time();
            header("location:index.php");
            exit();
        }

    }

} else if (isset($_POST["volver"])) {

    session_destroy();
    header("location:index.php");
    exit();

} else if (isset($_POST["entrar"])) {
    // comprobar usuario y contraseña
    $errorUsuario = $_POST["usuario"] == "";
    $errorPassword = $_POST["password"] == "";

    $errorFormularioEntrar = $errorUsuario || $errorPassword;

    if (!$errorFormularioEntrar) {
        // realizar la conexion para comprobar datos de usuario
        try {
            $conexion = mysqli_connect(HOST, USER, PASSWORD, BD);
        } catch (Exception $e) {
            session_destroy();
            die(errorPagina("No se ha podido conectar a la bd para verificar datos de usuario"));
        }

        // intentar la consulta
        try {
            $consulta = "select * from usuarios where usuario='" . $_POST["usuario"] . "' and clave='" . md5($_POST["password"]) . "'";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(errorPagina("No se ha podido conectar a la bd para realizar consulta de usuario"));
        }

        mysqli_close($conexion);


        // si tenemos resultado, es que el usuario puede logear
        if (mysqli_num_rows($resultado) > 0) {
            $_SESSION["usuario"] = $_POST["usuario"];
            $_SESSION["clave"] = md5($_POST["password"]);
            // obtener el tipo de la persona que ha logeado
            $datosUsuario = mysqli_fetch_assoc($resultado);
            $_SESSION["tipo"] = $datosUsuario["tipo"];
            // definir el tiempo de ultima accion
            $_SESSION["ultimaAccion"] = time();
            header("location:index.php");
            exit();
        } else {
            $errorUsuarioContraseña = true;
            $errorFormularioEntrar = $errorUsuarioContraseña;
        }
    }
}

// si se ha pulsado el boton registrarse o el continuar y hay errores
if (isset($_POST["registrarse"]) || isset($_POST["continuar"]) && $errorFormularioRegistro) {
    ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <h2>videoClub</h2>
        <form action="index.php" method="post">
            <p>
                <label for="usuario">Nombre de usuario: </label>
                <input type="text" name="usuario" value="<?php if (isset($_POST["continuar"]))
                    echo $_POST["usuario"] ?>">
                    <?php
                if (isset($_POST["continuar"]) && $errorFormularioRegistro) {
                    if ($_POST["usuario"] == "") {
                        echo "<span>El usuario esta vacio</span>";
                    } else if (isset($errorUsuarioRepetido) && $errorUsuarioRepetido) {
                        echo "<span>El usuario ya existe en la base de datos</span>";
                    }
                }
                ?>
            </p>

            <p>
                <label for="password">Contraseña: </label>
                <input type="password" name="password">
                <?php
                if (isset($_POST["continuar"]) && $errorFormularioRegistro) {
                    if ($_POST["password"] == "") {
                        echo "<span>La contraseña esta vacia</span>";
                    }
                }
                ?>
            </p>

            <p>
                <label for="passwordRepite">Repite contraseña: </label>
                <input type="password" name="passwordRepite">
                <?php
                if (isset($_POST["continuar"]) && $errorFormularioRegistro) {
                    if ($_POST["password"] == "") {
                        echo "<span>La contraseña esta vacia</span>";
                    } else if ($_POST["password"] !== $_POST["passwordRepite"]) {
                        echo "<span>Las contraseñas no coinciden</span>";
                    }
                }
                ?>
            </p>
            <p>
                <label for="dni">DNI: </label>
                <input type="text" name="dni" value="<?php if (isset($_POST["continuar"]))
                    echo $_POST["dni"] ?>">
                    <?php
                if (isset($_POST["dni"]) && $errorFormularioRegistro) {
                    if ($_POST["dni"] == "") {
                        echo "<span>El dni esta vacio</span>";
                    } else if (strlen($_POST["dni"]) >= 10 || strlen($_POST["dni"]) <= 8) {
                        echo "<span>El dni no contiene 9 caracteres</span>";
                    } else if (!is_numeric(substr($_POST["dni"], 0, 8))) {
                        echo "<span>Los 8 primero digitos tienen que ser numeros</span>";
                    } else if (LetraNIF(substr($_POST["dni"], 0, 8)) !== substr($_POST["dni"], -1)) {
                        echo "<span>No es un dni valido</span>";
                    } else if (isset($errorDniRepetido) && $errorDniRepetido) {
                        echo "<span>Este dni ya se encuentra registrado</span>";
                    }
                }
                ?>
            </p>

            <p>
                <label for="email">Email: </label>
                <input type="text" name="email" value="<?php if (isset($_POST["email"]))
                    echo $_POST["email"] ?>">
                    <?php
                if (isset($_POST["email"]) && $errorFormularioRegistro) {
                    if ($_POST["email"] == "") {
                        echo "<span>El email esta vacio</span>";
                    } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                        echo "<span>El email no es un email valido</span>";
                    }
                }
                ?>
            </p>

            <p>
                <label for="telefono">Telefono: </label>
                <input type="text" name="telefono" value="<?php if (isset($_POST["telefono"]))
                    echo $_POST["telefono"] ?>">
                    <?php
                if (isset($_POST["telefono"]) && $errorFormularioRegistro) {
                    if ($_POST["telefono"] == "") {
                        echo "<span>El telefono esta vacio</span>";
                    } else if (!is_numeric($_POST["telefono"])) {
                        echo "<span>El telefono solo puede contener numeros</span>";
                    } else if (strlen($_POST["telefono"]) >= 10) {
                        echo "<span>El telefono supera el limite de 9 digitos</span>";
                    }
                }
                ?>
            </p>

            <p>
                <button type="submit" name="volver">volver</button>
                <button type="submit" name="continuar">continuar</button>
            </p>
        </form>
    </body>

    </html>
    <?php

    // si un usuario a logeado,
} else if (isset($_SESSION["usuario"])) {
    // comprobar seguridad
    // realizar la conexion para comprobar datos de usuario
    try {
        $conexion = mysqli_connect(HOST, USER, PASSWORD, BD);
    } catch (Exception $e) {
        session_destroy();
        die(errorPagina("No se ha podido conectar a la bd para verificar datos de usuario"));
    }

    // intentar la consulta
    try {
        $consulta = "select * from usuarios where usuario='" . $_SESSION["usuario"] . "'";
        $resultado = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(errorPagina("No se ha podido conectar a la bd para realizar consulta de usuario"));
    }

    // si no obtenemos tuplas, se ha eliminado al usuario
    if (mysqli_num_rows($resultado) <= 0) {
        mysqli_close($conexion);
        session_unset();
        $_SESSION["mensaje"] = "<span>El usuario ha sido eliminado</span>";
        header("location:index.php");
        exit();
    }

    // si el usuario existe, comprobar el tiempo de conexion
    if ((time() - $_SESSION["ultimaAccion"]) > TIEMPOMAXIMOSEGUNDOSSIMULACRO) {
        session_unset();
        mysqli_close($conexion);
        $_SESSION["mensaje"] = "<span>has superado el tiempo de conexion</span>";
        header("location:index.php");
        exit();
    }

    // si pasa los controles, renovar el tiempo
    $_SESSION["ultimaAccion"] = time();

    if ($_SESSION["tipo"] === "admin") {
        ?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Document</title>
            </head>

            <body>
                <h1>ViceoClub</h1>
                <form action="index.php" method="post">
                    <p>Bienvenido <strong>
                        <?php echo $_SESSION["usuario"]." - Tipo: ". $_SESSION["tipo"] ?>
                        </strong> - <button name="volver">Salir</button>
                    </p>
                </form>
            </body>

            </html>
        <?php
    } else if ($_SESSION["tipo"] === "normal") {
        // el usuario es tipo normal
        // intentar la consulta
        try {
            $consulta = "select * from peliculas";
            $resultado = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(errorPagina("No se ha podido conectar a la bd para realizar consulta de usuario"));
        }
        mysqli_close($conexion);
        ?>
                <!DOCTYPE html>
                <html lang="en">

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Document</title>
                    <style>
                        table {
                            border: solid 1px;
                            border-collapse: collapse;
                            width: 90%;
                            margin: 0 auto;
                            text-align: center;
                        }

                        td {
                            border: solid 1px;

                        }

                        tr#cabecera {
                            background-color: lightgray;
                        }
                    </style>
                </head>

                <body>
                    <h1>ViceoClub</h1>
                    <form action="index.php" method="post">
                        <p>Bienvenido <strong>
                        <?php echo $_SESSION["usuario"]." - Tipo: ". $_SESSION["tipo"] ?>
                            </strong> - <button name="volver">Salir</button>
                        </p>
                    </form>

                    <h3>Listado de peliculas</h3>
                    <table>
                        <tr id="cabecera">
                            <td>id</td>
                            <td>Titulo</td>
                            <td>Caratula</td>
                        </tr>
                    <?php

                    while ($tuplasPeliculas = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>" . $tuplasPeliculas["id_pelicula"] . "</td>";
                        echo "<td>" . $tuplasPeliculas["titulo"] . "</td>";
                        echo "<td><img src='imagenes/" . $tuplasPeliculas["caratula"] . "'></td>";
                        echo "</tr>";
                    }
                    ?>
                    </table>

                </body>

                </html>
        <?php
    }


    // sino mostrar el formulario de login
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
            <h2>videoClub</h2>
            <form action="index.php" method="post">
                <p>
                    <label for="usuario">Nombre de usuario: </label>
                    <input type="text" name="usuario" value="<?php if (isset($_POST["entrar"]))
                        echo $_POST["usuario"] ?>">
                    <?php
                    if (isset($_POST["entrar"]) && $errorFormularioEntrar) {
                        if ($_POST["usuario"] == "") {
                            echo "<span>El usuario esta vacio</span>";
                        } else if (isset($errorUsuarioContraseña) && $errorUsuarioContraseña) {
                            echo "<span>El usuario/contraseña no existe en la bd</span>";
                        }
                    }
                    ?>
                </p>
                <p>
                    <label for="password">Contraseña: </label>
                    <input type="password" name="password">
                    <?php
                    if (isset($_POST["entrar"]) && $errorFormularioEntrar) {
                        if ($_POST["password"] == "") {
                            echo "<span>La contraseña esta vacia</span>";
                        }
                    }
                    ?>
                </p>
                <p>
                    <button type="submit" name="entrar">Entrar</button>
                    <button type="submit" name="registrarse">Registrarse</button>
                </p>
            </form>

            <?php
            if (isset($_SESSION["mensaje"])) {
                echo $_SESSION["mensaje"];
                session_destroy();
            }
            ?>
        </body>

        </html>
    <?php
}
?>