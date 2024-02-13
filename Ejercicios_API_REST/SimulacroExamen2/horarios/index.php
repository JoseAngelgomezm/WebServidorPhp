<?php
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

define("URLBASE", "http://localhost/Proyectos/WebServidorPhp/Ejercicios_API_REST/SimulacroExamen2/servicios_rest");

session_name("Examen2SimulacroPrueba23/24");
session_start();

if (isset($_POST["salir"])) {
    consumir_servicios_REST(URLBASE . "/salir", "get");
    session_destroy();
    header("Location:index.php");
    exit();
}

if (isset($_SESSION["usuario"])) {
    $salto = "index.php";
    require("vistas/vista_seguridad.php");

    if ($datosUsuario->tipo === "normal") {

        require("vistas/vista_normal.php");

    } else if ($datosUsuario->tipo === "admin") {

        require("vistas/vista_admin.php");

    }

} else {

    require("vistas/vista_login.php");

}

?>