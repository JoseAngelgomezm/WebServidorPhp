<?php
// funciones usadas para validar el dni

// funcion que devuelve una letra segun el numero de dni
function LetraNIF($dni){
    return substr("TRWAGMYFPDXBNJZSQVHLCKEO", $dni % 23, 1);
 }

 // funcion que devuelve true o false si el dni esta bien escrito
 function dni_bien_escrito($texto){
   $bien_escrito = strlen($texto) == 9 && is_numeric(substr($texto,0,8)) && substr($texto,-1) >="A" && substr($texto,-1)<="Z";
   return $bien_escrito;
 }

 // funcion que devuelve true o false si la letra introducida y coincide con la que devuelve la funcion y 
 // comprueba si es de tamaño 8 los numeros del dni
 function dni_valido($texto){
    $numero = substr($texto,0,8);
    $letra = substr($texto, -1);
    $valido = LetraNIF($numero) == $letra;
    return $valido;

    // return LetraNIF(substr($texto,0,8)) == substr($texto, -1);
 }

 

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

    

    