<?php

// si se ha enviado un formulario
if (isset($_POST["enviar"])) {

    // comprobar los errores:
    // si el nombre esta vacio es un error
    $error_nombre = $_POST["nombre"] == "";
    // si el sexo no existe es un error
    $error_sexo = !isset($_POST["sexo"]);

    // si error nombre es falso o error sexo es falso, habra un error en el formulario
    $error_formulario = $error_nombre || $error_sexo;
}

// si se ha pulsado enviar y no hay errores en el formulario
if(isset($_POST["enviar"]) && !$error_formulario){
    //mostrar los resultados del formulario recogido
    require  "vista_recogida.php";
}else{
    //sino mostrar el formulario
    require "vista_formulario.php";
}

?>