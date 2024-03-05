<?php

// llamar al servicio logueado
$url = URLBASE."/logueado";
$datos["api_session"] = $_SESSION["api_session"];
$datos["usuario"] = $_SESSION["usuario"];
$datos["clave"] = $_SESSION["clave"];
$respuesta = consumir_servicios_REST($url,"get",$datos); 
$archivo = json_decode($respuesta);

if(!$archivo){
    session_destroy();
    die("No se ha obtenido respuesta pasando seguridad en url ".$url."");
}

if(isset($archivo->error)){
    session_destroy();
    die($archivo->error);
}

if(isset($archivo->mensaje)){
    session_unset();
    $_SESSION["mensaje"] = "no has pasado la seguridad";
    header("location:index.php");
    exit();
}

if(isset($archivo->usuario)){
    // el usuario existe, comprobar inactividad
    if(time() - $_SESSION["ultimaAccion"] > TIEMPOINACTIVIDAD){
        session_unset();
        $_SESSION["mensaje"] = "Tiempo inactividad agotado";
        header("location:index.php");
        exit();
    }else{
        // quedarnos con los datos
        $datos_usuario_logueado = $archivo->usuario;
    }

}

?>