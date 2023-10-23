<?php
if(file_exists("../Ficheros/horarios.txt")){
    require "vistaExiste.php";
}else{
    require "vistaNoExiste.php";
}

?>