<?php
session_name("Ejercicio4MoverPunto");
session_start();

if (isset($_POST["accion"])) {
    if($_POST["accion"] == "izquierda"){
        $_SESSION["posicion"] -= 20;
    }else if($_POST["accion"] == "derecha"){
        $_SESSION["posicion"] += 20;
    }else if($_POST["accion"] == "reiniciar"){
       session_destroy();
    }
}

header("location:index.php");
exit();
?>