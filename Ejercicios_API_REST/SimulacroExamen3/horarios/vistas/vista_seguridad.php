<?php

    $url = URLBASE."/logueado";
    $datos["api_session"] = $_SESSION["api_session"];
    $datos["usuario"] = $_SESSION["usuario"];
    $datos["contraseña"] = $_SESSION["contraseña"];
    $respuesta = consumir_servicios_REST($url,"get",$datos);
    $archivo = json_decode($respuesta);

    if(!$archivo){
        session_destroy();
        die(error_page("Error","No se ha obtenido respuesta en la llamada a ".$url.""));
    }

    if(isset($archivo->error)){
        session_unset();
        $_SESSION["mensajeError"] = $archivo->error." en seguridad";
        header("location:index.php");
        exit();
    }

    if(time() - $_SESSION["ultimaAccion"] > 10000){
        session_unset();
        $_SESSION["mensajeError"] = "tiempo de inactivdad agotado";
        header("location:index.php");
        exit();
    }

    $datosUsuario = $archivo->usuario;
    $_SESSION["ultimaAccion"] = time();

?>