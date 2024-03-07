<?php
session_name("ExamenRec_SW_23_24");
session_start();
define("URLBASE","http://localhost/Proyectos/Examen_REC_DWESE/servicios_rest");
define("TIEMPOINACTIVIDAD", 600 * 5);
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

function error_page($title, $body)
{
    $html = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    $html .= '<title>' . $title . '</title></head>';
    $html .= '<body>' . $body . '</body></html>';
    return $html;
}

if(isset($_POST["salir"])){
    $datos["api_session"] = $_SESSION["api_session"];
    consumir_servicios_REST("/salir","post",$datos);
    session_destroy();
    header("location:index.php");
    exit();
}

if (isset($_POST["entrar"])){
    // comprobar errores
    $errorUsuario = $_POST["usuario"] == "";
    $errorContraseña = $_POST["contraseña"] == "";

    $errorFormulario = $errorUsuario || $errorContraseña;

    if(!$errorFormulario){
        // loguear
        $url = URLBASE."/login";
        $datos["usuario"] = $_POST["usuario"];
        $datos["clave"] = md5($_POST["contraseña"]);
        $respuesta = consumir_servicios_REST($url,"post",$datos);
        $archivo = json_decode($respuesta);

        if(!$archivo){
            die(error_page("Error","No se ha obtenido respuesta en ".$url.""));
        }

        if(isset($archivo->error)){
            die(error_page("Error","error al hacer la consulta ".$archivo->error.""));
        }

        if(isset($archivo->mensaje)){
            $errorUsuario = true;
        }

        if(isset($archivo->usuario)){
            $_SESSION["usuario"] = $archivo->usuario->usuario;
            $_SESSION["contraseña"] = $archivo->usuario->clave;
            $_SESSION["api_session"] = $archivo->api_session;
            $_SESSION["ultimaAccion"] = time();
            header("location:index.php");
            exit();
        }
    }
}


if (isset($_SESSION["usuario"])) {
    require ("vistas/vista_seguridad.php");
    require "vistas/vista_usuario.php";

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
        <h2>Gestión de guardias</h2>
        <form action="index.php" method="post">

            <p>
                <label for="usuario">Usuario:</label>
                <input type="text" name="usuario" value="<?php if (isset($_POST["usuario"]))
                    echo $_POST["usuario"] ?>">
                    <?php
                if (isset($_POST["usuario"]) && $errorUsuario) {
                    if ($_POST["usuario"] == "") {
                        echo "<span>El usuario no puede estar vacio</span>";
                    } else {
                        echo "<span>El usuario/contraseña no registrado</span>";
                    }
                }
                ?>
            </p>

            <p>
                <label for="contraseña">Contraseña:</label>
                <input type="password" name="contraseña">
                <?php
                if(isset($_POST["contraseña"]) && $errorContraseña){
                    if($_POST["contraseña"] == ""){
                        echo "<span>la contraseña no puede estar vacia</span>";
                    }
                }
                ?>
            </p>
                <button type="submit" name="entrar">Entrar</button>
                <?php
                    if(isset($_SESSION["seguridad"])){
                        echo $_SESSION["seguridad"];
                        session_destroy();
                    }
                ?>
        </form>
    </body>

    </html>
    <?php
}

?>