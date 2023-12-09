<?php
session_name("Ejercicio5MoverPunto2D");
session_start();

if (isset($_POST["accion"])) {
    if ($_POST["accion"] == "izquierda") {

        $_SESSION["posicionX"] -= 20;

    } else if ($_POST["accion"] == "derecha") {

        $_SESSION["posicionX"] += 20;

    } else if ($_POST["accion"] == "arriba") {

        $_SESSION["posicionY"] -= 20;

    } else if ($_POST["accion"] == "abajo") {

        $_SESSION["posicionY"] += 20;

    } else if ($_POST["accion"] == "reiniciar") {

        session_destroy();

    }

    if ($_SESSION["posicionX"] > 400) {

        $_SESSION["posicionX"] = 0;

    } else if ($_SESSION["posicionY"] > 400) {

        $_SESSION["posicionY"] = 0;

    } else if ($_SESSION["posicionX"] < 0) {

        $_SESSION["posicionX"] = 400;

    } else if ($_SESSION["posicionY"] < 0) {

        $_SESSION["posicionY"] = 400;

    }
}

header("location:index.php");
exit();
?>