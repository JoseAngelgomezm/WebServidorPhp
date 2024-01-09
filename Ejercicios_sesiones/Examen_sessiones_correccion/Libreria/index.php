<?php
session_name("examen3_23_24");
session_start();
define("USER", "jose");
define("PASSWD", "josefa");
define("ADDRESS", "localhost");
define("NAMEBD", "bd_libreria_exam");
define("TIEMPOINACTIVIDAD", "60");
function errorPagina($titulo, $mensaje)
{
    echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>" . $titulo . "</title>
</head>
<body>
    <p>" . $mensaje . "</p>
</body>
</html>";
}

// si se ha pulsado salir
if (isset($_POST["salir"])) {
    session_destroy();
    header("location:index.php");
}

// conectarse para listar siempre
try {
    $conexion = mysqli_connect(ADDRESS, USER, PASSWD, NAMEBD);
} catch (Exception $e) {
    die(errorPagina("Examen 23-24", "no se ha podido conectar a la base de datos para listar"));
}

if (isset($_POST["entrar"])) {
    // verificar los datos del formulario

    $errorUsuario = $_POST["usuario"] == "";
    $errorContraseña = $_POST["contraseña"] == "";

    $errorFormulario = $errorUsuario || $errorContraseña;

    // si no hay error de formulario hacer el login
    if (!$errorFormulario) {

        // comprobar que existe el usuario y la contraseña
        try {
            $resultado = mysqli_query($conexion, "select * from usuarios where lector='" . $_POST["usuario"] . "' and clave='" . md5($_POST["contraseña"]) . "' ");
        } catch (Exception $e) {
            mysqli_close($conexion);
            session_destroy();
            die(errorPagina("Examen 23-24", "no se ha podido conectar a la base de datos para listar"));
        }

        // si he obtenido tuplas
        if (mysqli_num_rows($resultado) > 0) {
            // logear
            $_SESSION["usuario"] = $_POST["usuario"];
            $_SESSION["contraseña"] = md5($_POST["contraseña"]);
            $_SESSION["ultimaAccion"] = time();
            // obtener el tipo de usuario
            $datosUsuarioLogeado = mysqli_fetch_assoc($resultado);
            if ($datosUsuarioLogeado["tipo"] == "normal") {
                header("location:index.php");
            } else {
                header("location:admin/gest_libros.php");
            }
        } else {
            $errorFormulario = true;
        }
    }
}

if (isset($_SESSION["usuario"])) {
    // control seguridad

    // obtener el usuario actual
    try {
        $resultado = mysqli_query($conexion, "select * from usuarios where lector='" . $_SESSION["usuario"] . "' and clave='" . $_SESSION["contraseña"] . "' ");
    } catch (Exception $e) {
        mysqli_close($conexion);
        session_destroy();
        die(errorPagina("Examen 23-24", "no se ha podido conectar a la base de datos para listar"));
    }

    // si no obtengo tuplas
    if (mysqli_num_rows($resultado) <= 0) {
        session_unset();
        $_SESSION["mensaje"] = "no se encuentra el base de datos";
        header("location:index.php");
    }

    // comprobar inactividad
    if (time() - $_SESSION["ultimaAccion"] > TIEMPOINACTIVIDAD) {
        session_unset();
        $_SESSION["mensaje"] = "tiempo de inactividad caducado";
        header("location:index.php");
    }

    // obtener los datos del usuario para obtener tipo
    $datosUsuarioLogeado = mysqli_fetch_assoc($resultado);

    // controlar que solo entren los tipo normal
    if ($datosUsuarioLogeado["tipo"] == "normal") {
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
            <style>
            div#contenido {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                flex: 0 33%;
            }

            div#contenido div {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                flex: 0 33%;
            }

            div#contenido div p {
                flex-direction: row;
                flex: 0 50%;
                text-align: center;
            }

            img {
                width: 100%;
                height: auto;
            }
        </style>
        </head>

        <body>
            <h3>Listado de libros</h3>
            <?php echo "<span>Bienvenido </span><strong>" . $_SESSION["usuario"] . "</strong><form action='index.php' method='post'><button type='submit' name='salir'>Salir</button></form>" ?>
            <div id="contenido">
                <?php
                // intentar la consulta de listado
                try {
                    $consulta = "SELECT * FROM libros";
                    $resultado = mysqli_query($conexion, $consulta);
                } catch (Exception $e) {
                    mysqli_close($conexion);
                    session_unset();
                    die(errorPagina("Examen 23-24", "no se ha podido conectar a la base de datos para listar"));
                }

                while ($datosLibros = mysqli_fetch_assoc($resultado)) {
                    echo "<div><img src='Images/" . $datosLibros["portada"] . "' /><p>" . $datosLibros["titulo"] . "</p><p>" . $datosLibros["precio"] . "</p> </div>";
                }
                ?>
            </div>
        </body>

        </html>
        <?php
    } else if ($datosUsuarioLogeado == "admin") {
        header("location:admin/gest_libros.php");
    }

} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
            div#contenido {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                flex: 0 33%;
            }

            div#contenido div {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                flex: 0 33%;
            }

            div#contenido div p {
                flex-direction: row;
                flex: 0 50%;
                text-align: center;
            }

            img {
                width: 100%;
                height: auto;
            }
        </style>
    </head>

    <body>
        <h3>Libreria</h3>
        <form action="index.php" method="post">
            <p>
                <label for="usuario">Nombre Usuario:</label>
                <input type="text" name="usuario" value="<?php if (isset($_POST["usuario"]))
                    echo $_POST["usuario"] ?>">
                    <?php
                if (isset($errorFormulario) && $errorFormulario) {
                    if ($_POST["usuario"] == "") {
                        echo "<span>El usuario esta vacio</span>";
                    } else {
                        echo "<span>Usuario o clave incorrectos</span>";
                    }
                }
                ?>
            </p>

            <p>
                <label for="contraseña">Contraseña:</label>
                <input type="password" name="contraseña">
                <?php
                if (isset($errorFormulario) && $errorFormulario) {
                    if ($_POST["contraseña"] == "") {
                        echo "<span>La contraseña esta vacia</span>";
                    }
                }
                ?>
            </p>

            <p>
                <button type="submit" name="entrar">Entrar</button>
            </p>
        </form>

        <?php
        if (isset($_SESSION["mensaje"])) {
            echo "<p>" . $_SESSION["mensaje"] . "</p>";
            unset($_SESSION["mensaje"]);
        }
        ?>

        <h3>Listado de libros</h3>
        <div id="contenido">
            <?php

            // intentar la consulta de listado
            try {
                $consulta = "SELECT * FROM libros";
                $resultado = mysqli_query($conexion, $consulta);
            } catch (Exception $e) {
                mysqli_close($conexion);
                session_unset();
                die(errorPagina("Examen 23-24", "no se ha podido conectar a la base de datos para listar"));
            }

            while ($datosLibros = mysqli_fetch_assoc($resultado)) {
                echo "<div><img src='Images/" . $datosLibros["portada"] . "' /><p>" . $datosLibros["titulo"] . "</p><p>" . $datosLibros["precio"] . "</p> </div>";

            }
            ?>
        </div>
    </body>

    </html>
    <?php
}
mysqli_close($conexion);
?>