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
define("URLATAQUE", "http://localhost/Proyectos/WebServidorPhp/Ejercicios_API_REST/Ejercicio3y4/login_restful");
define("TIEMPOENSEGUNDOS", "60");

// si se han pulsado alguno de los 2 botones, el de continuar de esta pagina
// o el boton nuevo usuario del index
if (isset($_POST["nuevousuario"]) || isset($_POST["continuar"])) {

    // si se ha pulsado el boton continuar comprobar los errores
    if (isset($_POST["continuar"])) {
        $errorNombre = $_POST["nombre"] == "" || strlen($_POST["nombre"]) > 30;
        $errorUsuario = $_POST["usuario"] == "" || strlen($_POST["usuario"]) > 20;

        // si no hay error de usuario comprobar que no esta repetido
        if (!$errorUsuario) {

            $url = URLATAQUE . "/repetido/usuarios/usuario/" . urlencode($_POST["usuario"]) . "";
            $respuesta = consumir_servicios_REST($url, "get");
            $archivo = json_decode($respuesta);

            if (!$archivo) {
                die(errores("No se ha podido comprobar el usuario repetido"));
            }

            if (isset($archivo->error)) {
                die(errores($archivo->erorr));
            }

            if ($archivo->mensaje === true) {
                $errorUsuario = true;
            }
        }

        $errorContraseña = $_POST["contraseña"] == "" || strlen($_POST["contraseña"]) > 15;

        $errorEmail = $_POST["email"] == "" || strlen($_POST["email"]) > 50 || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
        // si no hay error de email, comprobar que no este repetido
        if (!$errorEmail) {

            $url = URLATAQUE . "/repetido/usuarios/email/" . $_POST["email"] . "";
            $respuesta = consumir_servicios_REST($url, "get");
            $archivo = json_decode($respuesta);

            if (!$archivo) {
                die(errores("No se ha podido comprobar el email repetido"));
            }

            if (isset($archivo->error)) {
                die(errores($archivo->erorr));
            }

            if ($archivo->mensaje === true) {
                $errorEmail = true;
            }

        }

        $errorFormulario = $errorNombre || $errorUsuario || $errorContraseña || $errorEmail;

        // si no hay errores, insertar los datos y llevarnos al index que ya se veran los nuevos datos
        if (!$errorFormulario) {
            $url = URLATAQUE . "/crearUsuario";
            $datos["nombre"] = $_POST["nombre"];
            $datos["usuario"] = $_POST["usuario"];
            $datos["clave"] = md5($_POST["contraseña"]);
            $datos["email"] = $_POST["email"];
            $respuesta = consumir_servicios_REST($url, "post", $datos);
            $archivo = json_decode($respuesta);

            if (!$archivo) {
                var_dump($respuesta);
                die(errores("No se ha obtenido respuesta al insertar el usuario"));
            }

            if (isset($archivo->error)) {
                die(errores($archivo->erorr));
            }

            if (isset($archivo->ult_id)) {
                $_SESSION["mensaje"] = "Se ha insertado un usuario con id". $archivo->ult_id;
            }

            // enviarnos al index
            header("location:index.php");
        }
    }
    ?>
    <!-- mostrar la web con el formulario manteniendo campos -->
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>nuevo usuario</title>
    </head>

    <body>
        <h1>Nuevo usuario</h1>
        <form action="" method="post">
            <p>
                <label for="nombre">Nombre: </label>
                <input type="text" name="nombre" value="<?php if (isset($_POST["nombre"]))
                    echo $_POST["nombre"] ?>" maxlength="30">
                    <?php
                if (isset($_POST["continuar"]) && $errorNombre) {
                    if ($_POST["nombre"] == "") {
                        echo "<span>Campo vacio</span>";
                    } else {
                        echo "<span>Tamaño erroneo</span>";
                    }
                }
                ?>
            </p>
            <p>
                <label for="usuario">Usuario: </label>
                <input type="text" name="usuario" value="<?php if (isset($_POST["usuario"]))
                    echo $_POST["usuario"] ?>" maxlength="20">
                    <?php
                if (isset($_POST["continuar"]) && $errorUsuario) {
                    if ($_POST["usuario"] == "") {
                        echo "<span>Campo vacio</span>";
                    } else if (strlen($_POST["usuario"]) > 20) {
                        echo "<span>Tamaño erroneo</span>";
                    } else {
                        echo "<span>Usuario repetido</span>";
                    }
                }
                ?>
            </p>
            <p>
                <label for="contraseña">Contraseña: </label>
                <input type="password" name="contraseña" value="" maxlength="15">
                <?php
                if (isset($_POST["continuar"]) && $errorContraseña) {
                    if ($_POST["contraseña"] == "") {
                        echo "<span>Campo vacio</span>";
                    } else {
                        echo "<span>Tamaño erroneo</span>";
                    }
                }
                ?>
            </p>
            <p>
                <label for="email">Email: </label>
                <input type="email" name="email" value="<?php if (isset($_POST["email"]))
                    echo $_POST["email"] ?>" maxlength="50">
                    <?php
                if (isset($_POST["continuar"]) && $errorEmail) {
                    if ($_POST["email"] == "") {
                        echo "<span>Campo vacio</span>";
                    } else if (strlen($_POST["email"]) > 50) {
                        echo "<span>Tamaño erroneo</span>";
                    } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                        echo "<span>El email no está bien escrito</span>";
                    } else {
                        echo "<span>El email está repetido</span>";
                    }
                }
                ?>
            </p>
            <p>
                <button type="submit" name="continuar">Continuar</button>
                <button type="submit" name="volver">Volver</button>
            </p>
        </form>
    </body>

    </html>

    <?php
    // si no se ha pulsado ningun boton de los requeridos enviarnos a index
} else {
    header("location:index.php");
}
?>