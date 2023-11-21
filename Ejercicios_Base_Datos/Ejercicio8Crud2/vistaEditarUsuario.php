<?php
if (isset($_POST["editarUsuario"])) {
    $idUsuario = $_POST["editarUsuario"];
} else if (isset($_POST["guardarEdicion"])) {
    $idUsuario = $_POST["guardarEdicion"];
} else {
    $idUsuario = $_POST["borrarFoto"];
}

// intentar la conexion para consultar los datos del usuario seleccionado
try {
    $conexion = mysqli_connect($host, $user, $pass, $bd);
    mysqli_set_charset($conexion, "utf8");
} catch (Exception $e) {
    die(paginaError("no se ha podido realizar la conexion para editar los datos del usuario"));
}

// intentar la consulta para mostrar los datos del usuario seleccionado en el formulario
try {
    $consulta = "SELECT * FROM `usuarios` WHERE `id_usuario` = '" . $idUsuario . "'";
    $resultado = mysqli_query($conexion, $consulta);
} catch (Exception $e) {
    mysqli_close($conexion);
    die(paginaError("no se ha podido realizar la consulta para para editar los datos del usuario"));
}


$datosUser = mysqli_fetch_assoc($resultado);
mysqli_close($conexion);

?>
<h3>Editar un Usuario Existente</h3>
<form action="#" method="post" enctype="multipart/form-data">

    <label for="nombreEdicion">Nombre:</label>
    <br>
    <input type="text" name="nombreEdicion" maxlength="50" value="<?php if (isset($_POST["guardarEdicion"])) {
        echo $_POST["nombreEdicion"];
    } else {
        echo $datosUser["nombre"];
    } ?>">
    <?php
    if (isset($_POST["nombreEdicion"]) && $_POST["nombreEdicion"] == "") {
        echo "<span>El nombre no puede estar vacio</span>";
    }
    ?>
    <br>
    <label for="usuarioEdicion">Usuario:</label>
    <br>
    <input type="text" name="usuarioEdicion" maxlength="30" value="<?php if (isset($_POST["guardarEdicion"])) {
        echo $_POST["usuarioEdicion"];
    } else {
        echo $datosUser["usuario"];
    } ?>">
    <?php
    if (isset($_POST["usuarioEdicion"])) {
        if ($_POST["usuarioEdicion"] == "") {
            echo "<span>El usuario no puede estar vacio</span>";
        } else if (strlen($_POST["usuarioEdicion"]) > 50) {
            echo "<span>El nombre de usuario es demasiado largo</span>";
        } else if (isset($repetidoUsuario) && $repetidoUsuario) {
            echo "<span>El nombre de usuario no está disponible</span>";
        }
    }
    ?>
    <br>
    <label for="contraseñaEdicion">Contraseña:</label>
    <br>
    <input type="password" name="contraseñaEdicion" maxlength="15"
        placeholder="Contraseña no visible, introduce una nueva para cambiarla">
    <?php
    if (isset($_POST["contraseñaInsercion"]) && $_POST["contraseñaInsercion"] == "") {
        echo "<span>La contraseña no puede estar vacía</span>";
    }
    ?>
    <br>
    <label for="dniEdicion">DNI:</label>
    <br>
    <input type="text" name="dniEdicion" maxlength="9" value="<?php if (isset($_POST["guardarEdicion"])) {
        echo $_POST["dniEdicion"];
    } else {
        echo $datosUser["dni"];
    } ?>">
    <?php
    if (isset($_POST["dniEdicion"]) && $_POST["dniEdicion"] == "") {
        echo "<span>El DNI no puede estar vacío</span>";
    } else if (isset($errorDNI) && $errorDNI) {
        echo "<span>El dni que ha insertado no es un un formato de dni válido</span>";
    } else if (isset($repetidoDni) && $repetidoDni) {
        echo "<span>El dni que ha insertado ya se encuentra registrado</span>";
    } else if (isset($dniValido) && !$dniValido) {
        echo "<span>El dni que ha insertado no es un dni válido</span>";
    }
    ?>
    <br>
    <label>Sexo:</label>
    <br>
    <input type="radio" id="hombre" name="sexoEdicion" value="hombre" <?php if ($datosUser["sexo"] == "hombre")
        echo "checked" ?>><label for="hombre"> Hombre</label>
        <input type="radio" id="mujer" name="sexoEdicion" value="mujer" <?php if ($datosUser["sexo"] == "mujer")
        echo "checked" ?>><label for="mujer"> Mujer</label>

        <br>
        <label for="imagenEdicion">Cambiar la foto (MAX-500KB):</label>
        <input type="file" accept="img" name="imagenEdicion">
        <?php
    if (isset($errorFoto) && $errorFoto) {
        echo "<span>Error en la subida de la foto, imagen no válida o peso mayor a 500KB";
    }
    ?>
    <br>
    <input type="hidden" name="fotoEdicion" value="<?php echo $datosUser["foto"] ?>">
    <img src="img/<?php echo $datosUser["foto"] ?>">
    <br>
    <button type="submit" name="guardarEdicion" value="<?php echo $datosUser["id_usuario"] ?>">Guardar
        Cambios</button>
    <button type="submit" name="borrarFoto" value="<?php echo $datosUser["id_usuario"] ?>">Borrar Foto</button>
    <button type="submit" name="atras">Atrás</button>
</form>