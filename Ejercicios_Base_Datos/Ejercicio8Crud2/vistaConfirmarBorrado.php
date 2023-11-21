<?php
// intentar la conexion
try {
    $conexion = mysqli_connect($host, $user, $pass, $bd);
    mysqli_set_charset($conexion, "utf8");
} catch (Exception $e) {
    die(paginaError("no se ha podido realizar la conexion para confirmar el borrado"));
}

// si el usuario tiene una foto de perfil
// intentar la consulta para traernos a nuestro usuario
try {
    $consulta = "SELECT * FROM usuarios WHERE id_usuario='" . $_POST["continuarBorrado"] . "'";
    $resultado = mysqli_query($conexion, $consulta);
} catch (Exception $e) {
    mysqli_close($conexion);
    die(paginaError("no se ha podido realizar la consulta para la foto en el borrado de datos"));
}

// obtener el valor de la foto
$datos = mysqli_fetch_assoc($resultado);
$imagen = $datos["foto"];

// si el archivo existe en nuestra que carpeta de imagenes
if (file_exists("img/" . $imagen) && $imagen != "no_imagen.jpg") {
    // eliminarla
    unlink("img/" . $imagen);
}

// intentar la consulta de borrado
try {
    $consulta = "DELETE FROM usuarios WHERE id_usuario='" . $_POST["continuarBorrado"] . "'";
    $resultado = mysqli_query($conexion, $consulta);
} catch (Exception $e) {
    mysqli_close($conexion);
    die(paginaError("no se ha podido realizar el borrado de datos"));
}

mysqli_close($conexion);
header("location:index.php");

?>