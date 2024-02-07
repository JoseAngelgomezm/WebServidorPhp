<?php

session_name("examenlibreriaSimulacro22-23");
session_start();
define("URLBASE", "http://localhost/Proyectos/WebServidorPhp/Ejercicios_API_REST/SimulacroExamen/servicios_rest");

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
    $html .= '<body>' . $body . '</html>';
    return $html;
}

if(isset($_POST["salir"])){
    $datos["api_session"] = $_SESSION["api_session"];
    consumir_servicios_REST(URLBASE."/salir", "post", $datos);
    session_destroy();

}

if (isset($_SESSION["usuario"])) {

        require("vistas/vista_seguridad.php");

    if ($_SESSION["tipo"] === "normal") {

        require("vistas/vista_usuario_normal.php");

    } else if($_SESSION["tipo"] === "admin") {

        require("admin/gest_admin.php");

    }

} else {
    require("vistas/vista_login.php");
}

?>