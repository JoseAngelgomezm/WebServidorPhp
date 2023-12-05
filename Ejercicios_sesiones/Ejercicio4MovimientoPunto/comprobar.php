<?php
session_name("Ejercicio4MoverPunto");
session_start();

if (isset($POST["accion"]) == "izquierda") {
    $_SESSION["posicion"] -= 20;

} else if (isset($_POST["accion"]) == "derecha") {
    $_SESSION["posicion"] += 20;
}else if(isset($_POST["reiniciar"])){
    session_destroy();
}

header("location:index.php");
exit();
?>