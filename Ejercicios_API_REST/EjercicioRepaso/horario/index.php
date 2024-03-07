<?php
session_name("examenDeRepasoPorMi");
session_start();

define("URLREPASO", "http://localhost/Proyectos/WebServidorPhp/Ejercicios_API_REST/EjercicioRepaso/servicios_rest");
define("TIEMPOMINSESSION", "60 * 5");

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

function error_page($cabecera, $mensaje)
{
    $html = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    $html .= '<title>Error</title></head>';
    $html .= '<body><h1>' . $cabecera . '</h1>' . $mensaje . '</body></html>';
    return $html;
}

if(isset($_POST["salir"])){
    session_destroy();
    header("location:index.php");
    exit();
}


if (isset($_POST["login"])) {
    // controlar errores
    $errorUsuario = $_POST["usuario"] == "";
    $errorContraseña = $_POST["contraseña"] == "";

    $errorFormulario = $errorUsuario || $errorContraseña;

    if (!$errorFormulario) {
        $url = URLREPASO . "/login";
        $datos["usuario"] = $_POST["usuario"];
        $datos["clave"] = md5($_POST["contraseña"]);
        $respuesta = consumir_servicios_REST($url, "get", $datos);
        $archivo = json_decode($respuesta);

        if (!$archivo) {
            die("No se ha obtenido respuesta en " . $url . "");
        }

        if (isset($archivo->error)) {
            die($archivo->error);
        }

        if (isset($archivo->mensaje)) {
            $errorUsuario = true;
        }

        if(isset($archivo->usuario)){
            $_SESSION["usuario"] = $archivo->usuario->usuario;
            $_SESSION["clave"] = $archivo->usuario->clave;
            $_SESSION["api_session"] = $archivo->api_session;
            $_SESSION["ultimaAccion"] = time();
        }
       
    }
}


if (isset($_SESSION["usuario"])) {

    require "vistas/seguridad.php";

    if ($datos_usuario_logueado->tipo === "normal") {
        require "vistas/vista_normal.php";
    } else {
        require "vistas/vista_admin.php";
    }
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
    </head>

    <body>
        <form action="index.php" method="post">
            <p>
                <label for="usuario">Nombre usuario:</label>
                <input type="text" name="usuario" id="usuario" value="<?php if (isset($_POST["usuario"]))
                    echo $_POST["usuario"] ?>">
                    <?php
                if (isset($_POST["usuario"]) && $errorUsuario) {
                    if ($_POST["usuario"] == "") {
                        echo "<span>No puede estar vacio</span>";
                    } else {
                        echo "<span>Credenciales erroneas</span>";
                    }
                }
                ?>
            </p>

            <p>
                <label for="contraseña">Contraseña:</label>
                <input type="password" name="contraseña" id="contraseña">
                <?php
                if (isset($_POST["contraseña"]) && $errorContraseña) {
                    if ($_POST["contraseña"] == "") {
                        echo "<span>No puede estar vacio</span>";
                    }
                }
                ?>
            </p>

            <p>
                <button name="login" type="submit">Entrar</button>
            </p>
            <?php
                if(isset($_SESSION["mensaje"])){
                    echo $_SESSION["mensaje"];
                }
            ?>
        </form>
    </body>

    </html>
    <?php
}

?>