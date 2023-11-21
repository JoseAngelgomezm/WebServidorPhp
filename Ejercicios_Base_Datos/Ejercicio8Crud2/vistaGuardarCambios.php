<?php
// comprobar los errores
$errorNombre = $_POST["nombreInsercion"] == "" || strlen($_POST["nombreInsercion"]) > 50;
$errorUsuario = $_POST["usuarioInsercion"] == "" || strlen($_POST["usuarioInsercion"]) > 30;
$errorContraseña = $_POST["contraseñaInsercion"] == "" || strlen($_POST["contraseñaInsercion"]) > 50;
$errorDNI = $_POST["dniInsercion"] == "" || strlen($_POST["dniInsercion"]) !== 9 || (substr($_POST["dniInsercion"], -1) < "A" && substr($_POST["dniInsercion"], -1) > "Z") || !is_numeric(substr($_POST["dniInsercion"], 0, 8));

// si no hay error de dni, comprobar que sea valido
if (!$errorDNI) {
    $dniValido = comprobarDni($_POST["dniInsercion"]);
}

// MIRAR QUE NO SE REPITA DNI ; USUARIO
// si no hay errores en usuario y dni, ver si no estan repetidos
if (!$errorUsuario || !$errorDNI) {
    // intentar la conexion
    try {
        $conexion = mysqli_connect($host, $user, $pass, $bd);
        mysqli_set_charset($conexion, "utf8");
    } catch (Exception $e) {
        die(paginaError("no se ha podido realizar la conexion para comprobar la repeticion de datos"));
    }

    // realizar la consulta para ver que no se repita ni dni, ni usuario
    try {
        // sintaxis de la consulta
        $consultaUsuario = "SELECT * FROM usuarios WHERE usuario = '" . $_POST["usuarioInsercion"] . "'";
        $consultaDni = "SELECT * FROM usuarios WHERE dni = '" . $_POST["dniInsercion"] . "'";
        // realizar las consultas para ver si estan repetidos
        $resultadoDni = mysqli_query($conexion, $consultaDni);
        $resultadoUsuario = mysqli_query($conexion, $consultaUsuario);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(paginaError("no se ha podido realizar la consulta de datos para comprobar la repeticion de datos"));
    }

    // si la consulta tiene alguna tupla, significa que estara repetido
    $repetidoDni = mysqli_num_rows($resultadoDni) > 0;
    $repetidoUsuario = mysqli_num_rows($resultadoUsuario) > 0;
    mysqli_close($conexion);
}



$errorFormulario = $errorNombre || $errorUsuario || $errorContraseña || $errorDNI || $repetidoUsuario || $repetidoDni || !$dniValido;

// si no hay errores de formulario, hacer la insercion
if (!$errorFormulario) {

    // intentar la conexion
    try {
        $conexion = mysqli_connect($host, $user, $pass, $bd);
        mysqli_set_charset($conexion, "utf8");
    } catch (Exception $e) {
        die(paginaError("no se ha podido realizar la conexion en la insercion"));
    }

    // intentar la consulta de insercion
    try {
        $consulta = "INSERT INTO usuarios(usuario, clave, nombre, dni, sexo) VALUES ('" . $_POST["usuarioInsercion"] . "','" . $_POST["contraseñaInsercion"] . "','" . $_POST["nombreInsercion"] . "','" . $_POST["dniInsercion"] . "','" . $_POST["sexoInsercion"] . "')";
        $resultado = mysqli_query($conexion, $consulta);
    } catch (Exception $e) {
        mysqli_close($conexion);
        die(paginaError("no se ha podido realizar la insercion de datos"));
    }

    // saber si ha subido foto
    if ($_FILES["imagenInsercion"]["name"] !== "") {
        // controlar errores si se ha subido la foto
        $errorFoto = !getimagesize($_FILES["imagenInsercion"]["tmp_name"]) || $_FILES["imagenInsercion"]["size"] > 500 * 1024 || $_FILES["imagenInsercion"]["error"];
        $errorFormulario = $errorFoto;

        // si no hay error en la foto
        if (!$errorFoto) {
            // obtener la extension del archivo
            $nombreImagenDividido = explode(".", $_FILES["imagenInsercion"]["name"]);
            // si tiene mas de un punto
            if (count($nombreImagenDividido) > 1) {
                $extension = end($nombreImagenDividido);
            }

            // obtener el id del ultimo insertado
            $ultimoID = mysqli_insert_id($conexion);
            // obtener la ruta de destino
            $rutaDestino = "img/" . $ultimoID . "." . $extension . "";

            // poner la ruta de la foto en el ultimo usuario insertado
            // intentar la consulta update
            try {
                // traernos el id del ultimo usuario insertado
                $ultimoID = mysqli_insert_id($conexion);
                $consulta = "UPDATE `usuarios` SET `foto` = '$ultimoID.$extension' WHERE `id_usuario` = '$ultimoID';";
                $resultado = mysqli_query($conexion, $consulta);
                // mover la foto a la carpeta, si falla la consulta, no la moverá
                move_uploaded_file($_FILES["imagenInsercion"]["tmp_name"], $rutaDestino);
            } catch (Exception $e) {
                mysqli_close($conexion);
                die(paginaError("no se ha podido realizar modificacion de los datos de la foto"));
            }
        }
        mysqli_close($conexion);
    }
}
?>