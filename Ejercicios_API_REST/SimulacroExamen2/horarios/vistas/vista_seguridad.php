<?php

$url = URLBASE."/logueado";
$datos["api_session"] = $_SESSION["api_session"];
$respuesta = consumir_servicios_REST($url,"post",$datos);
$archivo = json_decode($respuesta);

if(!$archivo){
    session_destroy();
    error_page("Error en seguridad", "no se ha obtenido respuesta");
}

if(isset($archivo->error)){
    session_destroy();
    error_page("Error en seguridad", $archivo->error);
}

if(isset($archivo->mensaje)){
    session_destroy();
    error_page("Error en seguridad", $archivo->mensaje);
}

$datosUsuario = $archivo->usuario;


if(time() - $_SESSION["ultimaAccion"] >= 6000){
    session_unset();
    $_SESSION["mensajeSeguridad"] = "Se te ha expulsado por inactividad";
    header("Location:".$salto);
    exit;
}


$_SESSION["ultimaAccion"] = time();


?>