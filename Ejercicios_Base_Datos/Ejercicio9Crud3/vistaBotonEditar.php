<?php
// intentar la conexion para acceder a los datos de la bd
try {
    $conexionVideoClub = mysqli_connect($host, $user, $password, $bd);
} catch (Exception $e) {
    die(errores("No se ha podido conectar a la base de datos para editar los datos de una pelicula"));
}

// intentar la consulta de la pelicula
try {
    $consultaSelect = "SELECT * from peliculas WHERE idPelicula='" . $_POST["botonEditar"] . "'";
    $resultadoSelect = mysqli_query($conexionVideoClub, $consultaSelect);
} catch (Exception $e) {
    mysqli_close($conexionVideoClub);
    die(errores("No se ha podido consultar las peliculas editar los datos de una pelicula"));
}

mysqli_close($conexionVideoClub);

// si obtenemos resultados de la pelicula
if (mysqli_num_rows($resultadoSelect) > 0) {
    // pasar los datos a variable
    $datosUsuarioSelect = mysqli_fetch_assoc($resultadoSelect);
    // mostrar los datos en un formulario
    ?>
    <form method="post" action="#" enctype="multipart/form-data">
    <label for="titulo">Título de la película</label>
    <br>
    <input type="text" name="titulo" value="<?php echo $datosUsuarioSelect["titulo"] ?>">

    <br>
    <label for="director">Director de la pelicula</label>
    <br>
    <input type="text" name="director" value="<?php echo $datosUsuarioSelect["director"] ?>">

    <br>
    <label for="Tematica">Tematica</label>
    <br>
    <input type="text" name="tematica" value="<?php echo $datosUsuarioSelect["tematica"] ?>">

    <br>
    <label for="sinopsis">Sinopsis</label>
    <br>
    <textarea cols="50" rows="10" name="sinpsis"><?php echo $datosUsuarioSelect["sinopsis"] ?></textarea>
    
    <br>
    <img src="img/<?php echo $datosUsuarioSelect["caratula"] ?>">

    <br>
    <label for="fotoCaratula">Cambiar caratula de la película: </label>
    <input type="file" name="fotoCaratula" accept="img">

    <br>
    <button type='submit' name="guardarEdicion" value="<?php echo $datosUsuarioSelect["idPelicula"] ?>">Guardar Edicion</button>
    <button type='submit'>Atras</button>
    <br>
    <br>
    </form>
    <?php

} else {
    echo "<p>La pelicula de la que intentas mostrar los datos no existe</p>";
}

?>