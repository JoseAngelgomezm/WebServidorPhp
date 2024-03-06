<?php
session_name("EXAMEN_REC_SIMULACRO");
session_start();
define("TIEMPOMINIMO", "60 * 5");
define("URLBASE", "http://localhost/Proyectos/WebServidorPhp/Ejercicios_API_REST/Examen_REC_SW_22_23/servicios_rest");

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

function error_page($title, $cabecera, $mensaje)
{
    $html = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    $html .= '<title>' . $title . '</title></head>';
    $html .= '<body><h1>' . $cabecera . '</h1>' . $mensaje . '</body></html>';
    return $html;
}

if (isset($_POST["salir"])) {
    session_destroy();
    header("location:index.php");
    exit();
}

if (isset($_POST["entrar"])) {
    $errorUsuario = $_POST["usuario"] == "";
    $errorContraseña = $_POST["contraseña"] == "";

    $errorFormulario = $errorUsuario || $errorContraseña;

    if (!$errorFormulario) {
        // loguear
        $url = URLBASE . "/login";
        $datos["usuario"] = $_POST["usuario"];
        $datos["clave"] = md5($_POST["contraseña"]);
        $respuesta = consumir_servicios_REST($url, "POST", $datos);
        $archivo = json_decode($respuesta);

        if (!$archivo) {
            die("No se ha obtenido respuesta al intentar loguear en url " . $url . "");
        }

        if (isset($archivo->error)) {
            die($archivo->error);
        }

        if (isset($archivo->mensaje)) {
            $errorUsuario = true;
        }

        if (isset($archivo->usuario)) {
            $_SESSION["usuario"] = $archivo->usuario->usuario;
            $_SESSION["clave"] = $archivo->usuario->clave;
            $_SESSION["api_session"] = $archivo->api_session;
            $_SESSION["ultimaAccion"] = time();
        }

    }
}


if (isset($_SESSION["usuario"])) {

    // pasar seguridad
    require "vistas/vista_seguridad.php"

        ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestión de guardias</title>
    </head>

    <body>
        <p>Bienvenido
            <?php echo $_SESSION["usuario"] ?>
        <form action="index.php" method="post"><button type="submit" name="salir">Salir</button></form>
        </p>

        <h2>Equipos de guardia del IES Mar de alborán</h2>
        <?php
        $dias = ["", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes"];

        

        ?>


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
        <title>Gestión de guardias</title>
    </head>

    <body>
        <h2>Gestión de guardias</h2>
        <form action="index.php" method="post">
            <p>

                <label for="usuario">Usuario: </label>
                <input type="text" name="usuario" id="usuario" value="<?php if (isset($_POST["usuario"]))
                    echo $_POST["usuario"] ?>">
                    <?php
                if (isset($_POST["entrar"]) && $errorUsuario) {
                    if ($_POST["usuario"] == "") {
                        echo "<span>El usuario no puede estar vacio</span>";
                    } else {
                        echo "<span>El usuario/contraseña no son correctos</span>";
                    }

                }
                ?>
            </p>

            <p>

                <label for="contraseña">Contraseña: </label>
                <input type="password" name="contraseña" id="contraseña">
                <?php
                if (isset($_POST["entrar"]) && $errorContraseña) {
                    if ($_POST["contraseña"] == "") {
                        echo "<span>La contraseña no puede estar vacia</span>";
                    }

                }
                ?>

            </p>

            <button type="submit" name="entrar">Entrar</button>
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