<?php
if ($_POST["fotoEdicion"] != "no_imagen.jpg") {
    // intentar la conexion
    try {
        $conexion = mysqli_connect($host, $user, $pass, $bd);
        mysqli_set_charset($conexion, "utf8");
    } catch (Exception $e) {
        die(paginaError("no se ha podido realizar la conexion en el borrado de la foto"));
    }

    // poner la ruta de la foto en el id del que acabamos de modificar
    // intentar la consulta update
    try {
        $consulta = "UPDATE `usuarios` SET `foto` = 'no_imagen.jpg' WHERE `id_usuario` = '" . $_POST["borrarFoto"] . "'";
        $resultado = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(paginaError("no se ha podido realizar modificacion de los datos de la foto"));
    }
    mysqli_close($conexion);
    unlink("img/" . $_POST["fotoEdicion"] . "");
    echo "imagen eliminada con exito";
} else {
    echo "el usuario ya no tiene foto";
}

?>