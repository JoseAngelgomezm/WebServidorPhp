<?php
// control de errores de edicion

// comprobar los errores
$errorNombre = $_POST["nombreEdicion"] == "" || strlen($_POST["nombreEdicion"]) > 50;
$errorUsuario = $_POST["usuarioEdicion"] == "" || strlen($_POST["usuarioEdicion"]) > 30;
$errorContraseña = strlen($_POST["contraseñaEdicion"]) > 50;
$errorDNI = $_POST["dniEdicion"] == "" || strlen($_POST["dniEdicion"]) !== 9 || (substr($_POST["dniEdicion"], -1) < "A" && substr($_POST["dniEdicion"], -1) > "Z") || !is_numeric(substr($_POST["dniEdicion"], 0, 8));

// si no hay error de dni, comprobar que sea valido
if (!$errorDNI) {
    $dniValido = comprobarDni($_POST["dniEdicion"]);
}

// MIRAR QUE NO SE REPITA DNI ; USUARIO
// si no hay errores en usuario y dni, ver si no estan repetidos
if (!$errorUsuario || !$errorDNI) {
    // intentar la conexion
    try {
        $conexion = mysqli_connect($host, $user, $pass, $bd);
        mysqli_set_charset($conexion, "utf8");
    } catch (Exception $e) {
        die(paginaError("no se ha podido realizar la conexion para comprobar la repeticion de datos en la actualizacion de datos"));
    }

    // realizar la consulta para ver que no se repita ni dni, ni usuario
    try {
        // sintaxis de la consulta
        $consultaUsuario = "SELECT * FROM usuarios WHERE usuario = '" . $_POST["usuarioEdicion"] . "'  AND id_usuario != '" . $_POST["guardarEdicion"] . "'";
        $consultaDni = "SELECT * FROM usuarios WHERE dni = '" . $_POST["dniEdicion"] . "' AND id_usuario != '" . $_POST["guardarEdicion"] . "'";
        // realizar las consultas para ver si estan repetidos
        $resultadoDni = mysqli_query($conexion, $consultaDni);
        $resultadoUsuario = mysqli_query($conexion, $consultaUsuario);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(paginaError("no se ha podido realizar la consulta de datos para comprobar la repeticion de datos en la actualizacion de datos"));
    }

    // si la consulta tiene alguna tupla, significa que estara repetido
    $repetidoDni = mysqli_num_rows($resultadoDni) > 0;
    $repetidoUsuario = mysqli_num_rows($resultadoUsuario) > 0;
    mysqli_close($conexion);
}

$errorFormularioEdicion = $errorNombre || $errorUsuario || $errorContraseña || $errorDNI || $repetidoDni || $repetidoUsuario || !$dniValido;

// si no hay errores de formulario, hacer la edicion
if (!$errorFormularioEdicion) {
    // intentar la conexion
    try {
        $conexion = mysqli_connect($host, $user, $pass, $bd);
        mysqli_set_charset($conexion, "utf8");
    } catch (Exception $e) {
        die(paginaError("no se ha podido realizar la conexion en la actualizacion de datos (update)"));
    }

    // intentar la consulta de insercion
    try {
        $consulta = "UPDATE usuarios SET id_usuario='" . $_POST["guardarEdicion"] . "', usuario='" . $_POST["usuarioEdicion"] . "',clave='" . $_POST["contraseñaEdicion"] . "',nombre='" . $_POST["nombreEdicion"] . "',dni='" . $_POST["dniEdicion"] . "',sexo='" . $_POST["sexoEdicion"] . "' WHERE id_usuario='" . $_POST["guardarEdicion"] . "'";
        $resultado = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(paginaError("no se ha podido realizar la edicion de datos update"));
    }
    echo "cambios realizados con exito";
}

// saber si ha subido foto
if ($_FILES["imagenEdicion"]["name"] !== "") {
    // controlar errores si se ha subido la foto
    $errorFoto = !getimagesize($_FILES["imagenEdicion"]["tmp_name"]) || $_FILES["imagenEdicion"]["size"] > 500 * 1024 || $_FILES["imagenEdicion"]["error"];
    $errorFormularioEdicion = $errorFoto;

    // si no hay error en la foto
    if (!$errorFoto) {
        // obtener la extension del archivo
        $nombreImagenDividido = explode(".", $_FILES["imagenEdicion"]["name"]);
        // si tiene mas de un punto
        if (count($nombreImagenDividido) > 1) {
            // obtener el ultimo
            $extension = end($nombreImagenDividido);
        }

        // poner la ruta de la foto en el id del que acabamos de modificar
        // intentar la consulta update
        try {
            $consulta = "UPDATE `usuarios` SET `foto` = '" . $_POST["guardarEdicion"] . ".$extension' WHERE `id_usuario` = '" . $_POST["guardarEdicion"] . "'";
            $resultado = mysqli_query($conexion, $consulta);

            // obtener la ruta de destino de nuestro servidor
            $rutaDestino = "img/" . $_POST["guardarEdicion"] . "." . $extension . "";

            // si el usuario tiene un imagen borrarla
            if (file_exists("img/" . $_POST["fotoEdicion"] . "") && $_POST["fotoEdicion"] != "no_imagen.jpg") {
                unlink("img/" . $_POST["fotoEdicion"] . "");
            }

            // moverla a la carpeta, si la consulta falla, no la mueve
            move_uploaded_file($_FILES["imagenEdicion"]["tmp_name"], $rutaDestino);
        } catch (Exception $e) {
            mysqli_close($conexion);
            die(paginaError("no se ha podido realizar modificacion de los datos de la foto"));
        }
        mysqli_close($conexion);
        echo " y imagen modificada con exito";
    }
}
?>