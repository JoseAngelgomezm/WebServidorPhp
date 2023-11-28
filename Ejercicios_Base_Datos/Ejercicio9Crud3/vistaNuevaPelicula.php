<?php
// mostrar el formulario
 ?>
 <form method="post" action="#" enctype="multipart/form-data">
 <label for="titulo">Título de la película</label>
 <br>
 <input type="text" name="titulo" value="<?php if(isset($_POST["titulo"])){ echo $_POST["titulo"]; }?>">
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
 <input type="text" name="director" value="<?php if(isset($_POST["director"])){ echo $_POST["director"]; } ?>">
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
 <input type="text" name="tematica" value="<?php if(isset($_POST["tematica"])){ echo $_POST["tematica"]; }?>">
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
 <textarea cols="50" rows="10" name="sinopsis"><?php if(isset($_POST["sinopsis"])){ echo $_POST["sinopsis"]; }?></textarea>
 <?php
 if(isset($errorSinopsis) && $errorSinopsis){
     if($_POST["sinopsis"] == ""){
         echo "<span>la tematica no puede estar vacia</span>";
     }
 }
 ?>
 <br>
 
 <label for="fotoCaratula">Insertar caratula en la pelicula: </label>
 <input type="file" name="fotoCaratula" accept="img">

 <br>
 <button type='submit' name="insertarNuevaPelicula" value="">Insertar pelicula</button>
 <button type='submit'>Atras</button>
 <br>
 <br>
 </form>