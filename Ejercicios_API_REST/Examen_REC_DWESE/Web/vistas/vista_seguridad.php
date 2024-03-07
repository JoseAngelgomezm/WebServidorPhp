<?php
// loguear
$url = URLBASE . "/logueado";
$datos["api_session"] = $_SESSION["api_session"];
$respuesta = consumir_servicios_REST($url, "get", $datos);
$archivo = json_decode($respuesta);

if (!$archivo) {
    $datos["api_session"] = $_SESSION["api_session"];
    consumir_servicios_REST("/salir", "post", $datos);
    session_destroy();
    die(error_page("Error", "No se ha obtenido respuesta en " . $url . ""));
}

if (isset($archivo->error)) {
    $datos["api_session"] = $_SESSION["api_session"];
    consumir_servicios_REST("/salir", "post", $datos);
    session_destroy();
    die(error_page("Error", "error al hacer la consulta " . $archivo->error . ""));
}

if (isset($archivo->no_auth)) {
    $datos["api_session"] = $_SESSION["api_session"];
    consumir_servicios_REST("/salir", "post", $datos);
    session_unset();
    $_SESSION["seguridad"] = $archivo->no_auth;
    header("location:index.php");
    exit();
}

// inactividad
if (time() - $_SESSION["ultimaAccion"] > TIEMPOINACTIVIDAD) {
    $datos["api_session"] = $_SESSION["api_session"];
    consumir_servicios_REST("/salir", "post", $datos);
    session_unset();
    $_SESSION["seguridad"] = "Ha expirado el tiempo de conexion";
    header("location:index.php");
    exit();
}

$_SESSION["ultimaAccion"] = time();
$datos_usuario_logueado = $archivo->usuario;

?>