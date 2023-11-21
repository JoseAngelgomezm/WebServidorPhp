<?php
// intentar la conexion
try {
    $conexion = mysqli_connect($host, $user, $pass, $bd);
    mysqli_set_charset($conexion, "utf8");
} catch (Exception $e) {
    die(paginaError("no se ha podido realizar la conexion para mostrar los datos del usuario"));
}

// intentar la consulta de listado
try {
    $consulta = "SELECT * FROM usuarios WHERE id_usuario='" . $_POST["verDatos"] . "'";
    $resultado = mysqli_query($conexion, $consulta);
} catch (Exception $e) {
    mysqli_close($conexion);
    die(paginaError("no se ha podido realizar la consulta de datos del usuario seleccionado"));
}

// Mostrar los datos si se ha encontrado al usuario
if (mysqli_num_rows($resultado) == 1) {
    $datosUser = mysqli_fetch_assoc($resultado);
    echo "<p><strong>Nombre</strong>: " . $datosUser["nombre"] . "<p>";
    echo "<p><strong>Usuario</strong>: " . $datosUser["usuario"] . "<p>";
    echo "<p><strong>DNI</strong>: " . $datosUser["dni"] . "<p>";
    echo "<p><strong>Sexo</strong>: " . $datosUser["sexo"] . "<p>";
    echo "<p><strong>Foto:<br></strong><img src='img/" . $datosUser["foto"] . "'</img><p>";
} else {
    die(paginaError("El usuario que ha intentado consultar ya no se encuentra en la base de datos"));
}

mysqli_close($conexion);
?>