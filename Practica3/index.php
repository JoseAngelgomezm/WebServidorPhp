<?php

if (isset($_POST["guardar"])) { // compruebo errores al mandar el formulario

    $error_nombre = $_POST["nombre"] == "";
    $error_apellidos = $_POST["apellidos"] == "";
    $error_contraseña = $_POST["contraseña"] == "";
    $error_sexo = !isset($_POST["sexo"]);
    $error_comentario = $_POST["comentarios"] == "";
    $error_imagen = $_FILES["image"]["name"] != "" && ($_FILES["image"]["error"] || !getimagesize($_FILES["image"]["tmp_name"]) || $_FILES["archivo"]["size"] > 500 * 1024);
    $error_formulario = $error_nombre || $error_apellidos || $error_contraseña || $error_sexo || $error_comentario;

}

if (isset($_POST["guardar"]) && !$error_formulario) {

    require "vista_recogida.php";

} else {

    require "vista_formulario.php";

}

    

    