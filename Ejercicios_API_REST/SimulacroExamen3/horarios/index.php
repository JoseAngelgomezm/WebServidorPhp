<?php

session_name("examenbdhorarioexamen");
session_start();

define("URLBASE", "http://localhost/proyectos/WebServidorPhp/Ejercicios_API_REST/SimulacroExamen3/servicios_rest");

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


if (isset($_SESSION["usuario"])) {

    require("vistas/vista_seguridad.php");

    if ($datosUsuario->tipo == "normal") {

        require("vistas/vista_normal.php");

    } else if ($datosUsuario->tipo == "admin") {

        require("vistas/vista_admin.php");

    }

} else {

    require("vistas/vista_login.php");

}

?>