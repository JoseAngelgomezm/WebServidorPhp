<?php
// intentar la conexion para acceder a los datos de la bd
try {
    $conexionVideoClub = mysqli_connect($host, $user, $password, $bd);
} catch (Exception $e) {
    die(errores("No se ha podido conectar a la base de datos para mostrar los datos de la pelicula"));
}

// intentar la conexion para acceder a los datos de la bd
try {
    $consultaSelect = "SELECT * from peliculas WHERE idPelicula='".$_POST["botonVerDatos"]."'";
    $resultadoSelect = mysqli_query($conexionVideoClub, $consultaSelect);
} catch (Exception $e) {
    mysqli_close($conexionVideoClub);
    die(errores("No se ha podido consultar las peliculas para mostrar los datos de la pelicula"));
}

mysqli_close($conexionVideoClub);

// si obtenemos resultados de la pelicula
if (mysqli_num_rows($resultadoSelect) > 0) {
    // pasar los datos a php 
    $datosUsuarioSelect = mysqli_fetch_assoc($resultadoSelect);
    // mostrar los datos
    echo "
    <p><strong>idPelicula: </strong> ".$datosUsuarioSelect["idPelicula"]."</p>
    <p><strong>Titulo: </strong>".$datosUsuarioSelect["titulo"]."</p>
    <p><strong>Director: </strong>".$datosUsuarioSelect["director"]."</p>
    <p><strong>Tematica: </strong>".$datosUsuarioSelect["tematica"]."</p>
    <p><strong>Sinopsis: </strong>".$datosUsuarioSelect["sinopsis"]."</p>
    <p><img src='img/".$datosUsuarioSelect["caratula"]."'></p>
";

} else {
    echo "<p>La pelicula de la que intentas mostrar los datos no existe</p>";
}
?>