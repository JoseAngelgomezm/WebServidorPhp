<?php
// intentar la conexion para acceder a los datos de la bd
try {
    $conexionVideoClub = mysqli_connect($host, $user, $password, $bd);
} catch (Exception $e) {
    die(errores("No se ha podido conectar a la base de datos para eliminar una pelicula"));
}

// intentar la consulta de la pelicula
try {
    $consultaSelect = "DELETE from peliculas WHERE idPelicula='" . $_POST["botonBorrar"] . "'";
    $resultadoSelect = mysqli_query($conexionVideoClub, $consultaSelect);
} catch (Exception $e) {
    mysqli_close($conexionVideoClub);
    die(errores("No se ha podido consultar las peliculas editar los datos de una pelicula"));
}

mysqli_close($conexionVideoClub);
$_SESSION["mensajeBorrado"] = "<p>Se ha eliminado una pelicula con exito</p>";
session_destroy();

?>