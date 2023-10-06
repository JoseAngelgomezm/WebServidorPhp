<?php
// funciones usadas para validar el dni
require ("src/funciones.php");

if (isset($_POST["guardar"])) { // compruebo errores al mandar el formulario

    $error_nombre = $_POST["nombre"] == "";
    $error_apellidos = $_POST["apellidos"] == "";
    $error_contraseña = $_POST["contraseña"] == "";
    $error_sexo = !isset($_POST["sexo"]);
    $error_comentario = $_POST["comentarios"] == "";
    $error_imagen = $_FILES["image"]["name"] != "" && ($_FILES["image"]["error"] || !getimagesize($_FILES["image"]["tmp_name"]) || $_FILES["image"]["size"] > 500 * 1024);
    $error_dni = $_POST["dni"] == "" || !dni_bien_escrito(strtoupper($_POST["dni"])) || !dni_valido(strtoupper($_POST["dni"]));
    
    $error_formulario = $error_nombre || $error_apellidos || $error_contraseña || $error_sexo || $error_comentario || $error_imagen || $error_dni;// si hay un error en un campo, hay error en el formulario

}

// si se ha pulsado el boton y no hay errores
if (isset($_POST["guardar"]) && !$error_formulario) {
    // mostrar los datos recogidos
    require "vista_recogida.php";

} else {
    // sino mostrar el formulario de nuevo
    require "vista_formulario.php";

}

    

    