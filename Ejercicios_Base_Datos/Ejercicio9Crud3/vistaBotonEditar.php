<?php
// intentar la conexion para acceder a los datos de la bd
try {
    $conexionVideoClub = mysqli_connect($host, $user, $password, $bd);
} catch (Exception $e) {
    die(errores("No se ha podido conectar a la base de datos para editar los datos de una pelicula"));
}

if(isset($_POST["botonEditar"])){
    $idPelicula = $_POST["botonEditar"];
}else if(isset($_POST["guardarEdicion"])){
    $idPelicula = $_POST["guardarEdicion"];
}

// intentar la consulta de la pelicula
try {
    $consultaSelect = "SELECT * from peliculas WHERE idPelicula='" . $idPelicula . "'";
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
    <input type="text" name="titulo" value="<?php if(isset($_POST["titulo"])){ echo $_POST["titulo"]; } else echo $datosUsuarioSelect["titulo"] ?>">
    <?php
    if(isset($errorTitulo) && $errorTitulo){
        if($_POST["titulo"] == ""){
            echo "<span>el titulo no puede estar vacio</span>";
        }else{
            echo "<span>el titulo puede tener mas de 15 caracteres</span>";
        }
    }
    ?>
    <br>
    <label for="director">Director de la pelicula</label>
    <br>
    <input type="text" name="director" value="<?php if(isset($_POST["director"])){ echo $_POST["director"]; } else echo $datosUsuarioSelect["director"] ?>">
    <?php
    if(isset($errorDirector) && $errorDirector){
        if($_POST["director"] == ""){
            echo "<span>el titulo no puede estar vacio</span>";
        }else{
            echo "<span>el titulo puede tener mas de 20 caracteres</span>";
        }
    }
    ?>
    

    <br>
    <label for="Tematica">Tematica</label>
    <br>
    <input type="text" name="tematica" value="<?php if(isset($_POST["tematica"])){ echo $_POST["tematica"]; } else echo $datosUsuarioSelect["tematica"] ?>">
    <?php
    if(isset($errorTematica) && $errorTematica){
        if($_POST["titulo"] == ""){
            echo "<span>la tematica no puede estar vacia</span>";
        }else{
            echo "<span>la tematica no puede contener mas de 15 caracteres</span>";
        }
    }
    ?>

    <br>
    <label for="sinopsis">Sinopsis</label>
    <br>
    <textarea cols="50" rows="10" name="sinopsis"><?php if(isset($_POST["sinopsis"])){ echo $_POST["sinopsis"]; } else echo $datosUsuarioSelect["sinopsis"] ?></textarea>
    <?php
    if(isset($errorSinopsis) && $errorSinopsis){
        if($_POST["sinopsis"] == ""){
            echo "<span>la tematica no puede estar vacia</span>";
        }
    }
    ?>
    <br>
    <img src="img/<?php echo $datosUsuarioSelect["caratula"] ?>">

    <br>
    <label for="fotoCaratula">Cambiar caratula de la película: </label>
    <input type="file" name="fotoCaratula" accept="img">

    <br>
    <button type='submit' name="guardarEdicion" value="<?php echo $datosUsuarioSelect["idPelicula"] ?>">Guardar Edicion</button>
    <input type="hidden" value="<?php echo $datosUsuarioSelect["titulo"] ?>" name="tituloPelicula">
    <button type='submit'>Atras</button>
    <br>
    <br>
    </form>
    <?php

} else {
    echo "<p>La pelicula de la que intentas mostrar los datos no existe</p>";
}

?>