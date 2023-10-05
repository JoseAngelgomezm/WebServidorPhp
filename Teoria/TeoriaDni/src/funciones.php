<?php 
function LetraNIF($dni){
    return substr("TRWAGMYFPDXBNJZSQVHLCKEO", $dni % 23, 1);
 }

 function dni_bien_escrito($texto){
   $bien_escrito = strlen($texto) == 9 && is_numeric(substr($texto,0,8)) && substr($texto,-1) >="A" && substr($texto,-1)<="Z";
   return $bien_escrito;
 }

 function dni_valido($texto){
    $numero = substr($texto,0,8);
    $letra = substr($texto, -1);
    $valido = LetraNIF($numero) == $letra;
    return $valido;

    // return LetraNIF(substr($texto,0,8)) == substr($texto, -1);
 }

 ?>