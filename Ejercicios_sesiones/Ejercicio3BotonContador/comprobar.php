<?php
    session_name("Ejercicio2BotonContador");
    session_start();

    if($_POST["accion"] == "mas"){
        $_SESSION["contador"] += 1;
    }else if($_POST["accion"] == "menos"){
        $_SESSION["contador"] -= 1;
    }else if($_POST["accion"] == "reiniciar"){
        session_destroy();
    }

    header("location:index.php");
    exit();
?>