<?php

function errorPaginaExamen($mensaje)
{
    echo "<!DOCTYPE html>
 <html lang='en'>
 <head>
     <meta charset='UTF-8'>
     <meta name='viewport' content='width=device-width, initial-scale=1.0'>
     <title>Document</title>
 </head>
 <body>
     <p>".$mensaje."</p>
 </body>
 </html>";
}

session_name("ExamenSimulacro2");
session_start();

// SERVIDOR_BD,USUARIO_BD,CLAVE_BD y NOMBRE_BD son CTES
define("SERVIDOR_BD", "localhost");
define("NOMBRE_BD", "bd_videoclub_exam");
define("USUARIO_BD", "jose");
define("CLAVE_BD", "josefa");
define("TIEMPOMAXIMODEFINIDO", "60");


// si se ha pulsado entrar
if (isset($_POST["volver"])) {
    session_destroy();
    header("location:index.php");
    exit();
}

if (isset($_POST["continuar"])) {
    // comprobar los errores del formulario de registro
    $errorUsuario = $_POST["usuario"] == "";
    $errorContraseña = $_POST["contraseña"] == "";
    // si no hay error de usuario, comprobar que no este repetido
    if (!$errorUsuario) {
        // intentar la conexion
        try {
            $conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
        } catch (Exception $e) {
            session_destroy();
            die(errorPaginaExamen("no se ha podido conectar a la base de datos para el registro comprobar usuario"));
        }

        // intentar la consulta
        try {
            $consulta = "select * from clientes where usuario='" . $_POST["usuario"] . "'";
            $resultadoConsulta = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(errorPaginaExamen("no se ha podido realizar la consulta para comprobar usuario"));
        }

        mysqli_close($conexion);

       
        // si obtengo tupla esta repetido
        if (mysqli_num_rows($resultadoConsulta) > 0) {
            $errorUsuarioRepetido = true;
            $errorUsuario = true;
        }
    }

    $errorContraseñaRepetida = $_POST["contraseña"] !== $_POST["repiteContraseña"];

    $errorFormularioRegistro = $errorUsuario || $errorContraseña || $errorContraseñaRepetida;

    // insertar el usuario si no hay error en el formulario
    if (!$errorFormularioRegistro) {
        // intentar la conexion
        try {
            $conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
        } catch (Exception $e) {
            session_destroy();
            die(errorPaginaExamen("no se ha podido conectar a la base de datos para el registro al hacer insert"));
        }

        // intentar la consulta
        try {
            $consulta = "INSERT INTO `clientes`( `usuario`, `clave`, `foto`) VALUES ('" . $_POST["usuario"] . "','" . $_POST["contraseña"] . "','no_imagen.jpg')";
            $resultadoConsulta = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(errorPaginaExamen("no se ha podido realizar la consulta para insertar usuario"));
        }
      
        $idUltimoUsuario = mysqli_insert_id($conexion);
        mysqli_close($conexion);

        // si se ha seleccionado foto
        if ($_FILES["foto"]["name"] !== "") {
            // obtener la extension
            $partesNombreFoto = explode(".", $_FILES["foto"]["name"]);
            // siempre obtener el ultimo, por si se separa por mas de un punto
            if (count($partesNombreFoto) > 0) {
                $extension = end($partesNombreFoto);
            }
            // mover la foto
            @$fd = move_uploaded_file($_FILES["foto"]["tmp_name"], "images/" . $idUltimoUsuario . "." . $extension . "");
            if (!$fd) {
                session_destroy();
                die(errorPaginaExamen("no se ha podido mover la foto"));
            } else {
                $foto = $idUltimoUsuario . "." . $extension;
            }

           
              // actualizar datos del usuario
            try {
                $conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
            } catch (Exception $e) {
                session_destroy();
                die(errorPaginaExamen("no se ha podido conectar a la base de datos para upgradear la foto"));
            }

            // intentar la consulta
            try {
                $consulta = "UPDATE `clientes` SET foto='" . $foto . "' WHERE `id_cliente`='" . $idUltimoUsuario . "'";
                $resultadoConsulta = mysqli_query($conexion, $consulta);
            } catch (Exception $e) {
                mysqli_close($conexion);
                session_destroy();
                die(errorPaginaExamen("no se ha podido realizar la consulta upgrade para la foto"));
            }
            mysqli_close($conexion);

            // poner los datos de conexion
            $_SESSION["ultimaAccion"] = time();
            $_SESSION["usuario"] = $_POST["usuario"];
            header("location:index.php");
            exit();
        }
    }
}

if (isset($_POST["entrar"])) {
    // controlar errores del formulario de login
    $errorUsuario = $_POST["usuario"] == "";
    $errorContraseña = $_POST["contraseña"] == "";

    $errorFormulario = $errorUsuario || $errorContraseña;

    // si no hay errores en el formulario de entrada, logearlo
    if (!$errorFormulario) {
        // poner los datos de conexion
        $_SESSION["ultimaAccion"] = time();
        $_SESSION["usuario"] = $_POST["usuario"];
        header("location:index.php");
        exit();
    }


    // si el usuario esta logeado, sino mostar el login
} else if (isset($_SESSION["usuario"])) {
    // controlar la seguridad
    // buscar el usuario en la base de datos
    try {
        $conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    } catch (Exception $e) {
        session_destroy();
        die(errorPaginaExamen("no se ha podido conectar a la base de datos para comprobar la seguridad"));
    }

    // intentar la consulta
    try {
        $consulta = "select * from clientes where usuario='" . $_SESSION["usuario"] . "'";
        $resultadoConsulta = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(errorPaginaExamen("no se ha podido realizar la consulta del usuario para comprobar la seguridad"));
    }
    $datosUsuario = mysqli_fetch_assoc($resultadoConsulta);
    mysqli_close($conexion);

    // si no hay tuplas, no existe el usuario
    if (mysqli_num_rows($resultadoConsulta) <= 0) {
        session_unset();
        $_SESSION["mensaje"] = "<p>no te encuentras en la bd</p>";
        header("location:index.php");
        exit();
    }

    // comprobar el tiempo de conexion
    if (time() - $_SESSION["ultimaAccion"] > TIEMPOMAXIMODEFINIDO) {
        session_unset();
        $_SESSION["mensaje"] = "<p>has superado el tiempo de inactividad</p>";
        header("location:index.php");
        exit();
    }

    // si todo ha ido bien, preguntar si el tipo de usuario es normal o administrador
    if ($datosUsuario["tipo"] === "admin") {
        header("Location:admin/gest_clientes.php");
        exit();
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
                <h1>VideoClub</h1>

                <form action="index.php" method="post">
                    <span>Bienvenido
                        <?php echo $_SESSION["usuario"] ?> <button name="volver">salir</button>
                    <span>
                    <p>Foto de perfil: <img src="Images/<?php echo $datosUsuario["foto"] ?>"></p>
                </form>
            </body>

            </html>
        <?php
    }

    // si se ha pulsado registrarse
} else if (isset($_POST["registrarse"]) || isset($_POST["continuar"]) && $errorFormularioRegistro) {
    ?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Document</title>
            </head>

            <body>
                <h1>VideClub</h1>
                <form action="index.php" method="post" enctype="multipart/form-data">
                    <p>
                        <label for="usuario">Nombre de usuario: </label>
                        <input type="text" name="usuario" value="<?php if (isset($_POST["usuario"]))
                            echo $_POST["usuario"] ?>">
                    <?php
                        if (isset($_POST["continuar"]) && $errorFormularioRegistro) {
                            if ($_POST["usuario"] == "") {
                                echo "<span>El usuario esta vacio</span>";
                            } else if (isset($errorUsuarioRepetido)) {
                                echo "<span>El usuario esta repetido</span>";
                            }
                        }
                        ?>
                    </p>

                    <p>
                        <label for="contraseña">Contraseña: </label>
                        <input type="password" name="contraseña" value="">
                    <?php
                    if (isset($_POST["continuar"]) && $errorFormularioRegistro) {
                        if ($_POST["contraseña"] == "") {
                            echo "<span>La contraseña esta vacia</span>";
                        }
                    }
                    ?>
                    </p>

                    <p>
                        <label for="repiteContraseña">Repite Contraseña: </label>
                        <input type="password" name="repiteContraseña" value="">
                    <?php
                    if (isset($_POST["continuar"]) && $errorFormularioRegistro) {
                        if ($errorContraseñaRepetida) {
                            echo "<span>La contraseña no se repite</span>";
                        }
                    }
                    ?>
                    </p>

                    <p>
                        <label for="foto">Foto: </label>
                        <input type="file" name="foto">
                    </p>

                    <p>
                        <button name="volver">Volver</button>
                        <button name="continuar">Continuar</button>
                    </p>
                </form>
            </body>

            </html>

    <?php


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
                <h1>VideClub</h1>
                <form action="index.php" method="post">
                    <p>
                        <label for="usuario">Nombre de usuario: </label>
                        <input type="text" name="usuario" value="<?php if (isset($_POST["usuario"]))
                            echo $_POST["usuario"] ?>">
                    <?php
                        if (isset($_POST["entrar"]) && $errorFormulario) {
                            if ($_POST["usuario"] == "") {
                                echo "<span>El usuario esta vacio</span>";
                            }
                        }
                        ?>
                    </p>

                    <p>
                        <label for="contraseña">Contraseña: </label>
                        <input type="password" name="contraseña" value="">
                    <?php
                    if (isset($_POST["contraseña"]) && $errorFormulario) {
                        if ($_POST["contraseña"] == "") {
                            echo "<span>La contraseña esta vacia</span>";
                        }
                    }
                    ?>
                    </p>

                    <p>
                        <button name="entrar">Entrar</button>
                        <button name="registrarse">Registrarse</button>
                    </p>
                <?php
                if (isset($_SESSION["mensaje"])) {
                    echo $_SESSION["mensaje"];
                    session_destroy();
                }
                ?>
                </form>
            </body>

            </html>

    <?php
}
?>