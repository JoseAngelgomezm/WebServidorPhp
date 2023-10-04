<?php
 
require ("src/funciones.php");


if (isset($_POST["guardar"])) { // compruebo errores al mandar el formulario

    $error_nombre = $_POST["nombre"] == "";
    $error_apellidos = $_POST["apellidos"] == "";
    $error_contraseña = $_POST["contraseña"] == "";
    $error_sexo = !isset($_POST["sexo"]);
    $error_comentario = $_POST["comentarios"] == "";
    $error_dni = $_POST["dni"] == "" || !dni_bien_escrito(strtoupper($_POST["dni"])) || !dni_valido(strtoupper($_POST["dni"]));

    $error_formulario = $error_nombre || $error_apellidos || $error_contraseña || $error_sexo || $error_comentario;

}

if (isset($_POST["guardar"]) && !$error_formulario) {

    require "vista_recogida.php";

} else {

    require "vista_formulario.php";

}

    

    