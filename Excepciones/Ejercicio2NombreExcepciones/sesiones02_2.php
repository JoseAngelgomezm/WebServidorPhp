<?php
// abrir la sesion con el nombre que trabajamos
session_name("Ejercicio2ExcepcionNombre");
session_start();

// si pulso siguiente o borrar
if (isset($_POST["btnSiguiente"]) || isset($_POST["btnBorrarSesion"])) {
    // si pulso borrar
    if (isset($_POST["btnBorrarSesion"])) {
        // destruyo la sesion
        session_destroy();
    
    } else {
        // sino, miro si el nombre esta vacio
        if($_POST["nombre"] == ""){
            $_SESSION["error"] = "no has tecleado nada";
        // si no lo esta, añado el nombre al session
        }else{
            $_SESSION["nombre"] = $_POST["nombre"];
        }
    }
}
// siempre salto al index
header("location:index.php");
exit();


?>