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
define("URLATAQUE", "http://localhost/Proyectos/WebServidorPhp/Ejercicios_API_REST/EjercicioAppLogin/Api");
define("TIEMPOENSEGUNDOS", "60");


if (isset($_POST["salir"])) {
    session_destroy();
    header("location:index.php");
}

// si se ha pulsado boton login
if (isset($_POST["logear"])) {
    $errorUsuario = $_POST["usuario"] == "";
    $errorContraseña = $_POST["contraseña"] == "";

    $errorFormularioLogin = $errorUsuario || $errorContraseña;

    if (!$errorFormularioLogin) {
        $url = URLATAQUE . "/login";
        $datos["usuario"] = $_POST["usuario"];
        $datos["clave"] = md5($_POST["contraseña"]);
        $respuesta = consumir_servicios_REST($url, "post", $datos);
        $archivo = json_decode($respuesta);

        if (!$archivo) {
            session_destroy();
            die(errores("no se ha obtenido respuesta al conectarse" . $respuesta));
        }

        if (isset($archivo->mensaje_error)) {
            session_destroy();
            die(errores($archivo->mensaje_error));
        }

        if (isset($archivo->mensaje)) {
            $errorUsuario = true;
        } else {
            // existe, logearse
            $_SESSION["usuario"] = $archivo->usuario->usuario;
            $_SESSION["contraseña"] = $archivo->usuario->clave;
            $_SESSION["ultimaAccion"] = time();
            header("location:index.php");
        }
    }
}



// si estoy logeado
if (isset($_SESSION["usuario"]) && isset($_SESSION["contraseña"])) {

    // comprobar seguridad 
    $url = URLATAQUE . "/login";
    $datos["usuario"] = $_SESSION["usuario"];
    $datos["clave"] = $_SESSION["contraseña"];
    $respuesta = consumir_servicios_REST($url, "post", $datos);
    $archivo = json_decode($respuesta);

    if (!$archivo) {
        session_destroy();
        die(errores("no se ha obtenido respuesta al conectarse" . $respuesta));
    }

    if (isset($archivo->mensaje_error)) {
        session_destroy();
        die(errores($archivo->mensaje_error));
    }

    if (isset($archivo->mensaje)) {
        session_unset();
        $_SESSION["seguridad"] = "este usuario ya no se encuentra en la base de datos";
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
                <label for="contraseña">Contraseña:</label>
                <input type="text" name="contraseña" id="contraseña" value="<?php if (isset($_POST["contraseña"]))
                    echo $_POST["contraseña"] ?>">
                    <?php
                if (isset($_POST["logear"]) && $errorContraseña) {
                    if ($_POST["contraseña"] == "") {
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