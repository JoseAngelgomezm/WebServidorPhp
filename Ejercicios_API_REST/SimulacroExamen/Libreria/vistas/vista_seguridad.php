<?php

// comprobar si el usuario existe
$url = URLBASE."/logueado";
$datos["api_session"] = $_SESSION["api_session"];
$respuesta = consumir_servicios_REST($url, "post", $datos);
$archivo = json_decode($respuesta);

if(!$archivo){
    session_destroy();
    die(error_page("Error en seguridad","<p>No se ha obtenido respuesta</p>"));
}

if(isset($archivo->error)){
    session_destroy();
    die(error_page("Error en seguridad","<p>".$archivo->error."</p>"));
}

if(isset($archivo->mensaje)){
    session_destroy();
    die(error_page("Error en seguridad","<p>".$archivo->mensaje."</p>"));
}

if(isset($archivo->no_auth)){
    session_destroy();
    die(error_page("Error en seguridad","<p>".$archivo->no_auth."</p>"));
}



$datosUsuario = $archivo->usuario;


if(time() - $_SESSION["ultimaAccion"] > 5 ){
    session_unset();
    $_SESSION["mensajeSeguridad"] = "Se te ha expulsado por inactividad";
    header("location:index.php");
    exit();
}

$_SESSION["ultimaAccion"] = time();
$_SESSION["tipo"] = $datosUsuario->tipo;