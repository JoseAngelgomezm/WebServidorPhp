<?php
    $user = "admin";
    $password = "1234";

    if($_GET["usuario"] == $user && $_GET["contraseña"] == $password){
        echo "Credenciales válidas";
    }else{
        echo "Credenciales no válidas";
    }
?>