<?php
session_name("Ejercicio1ExcepcionNombre");
session_start();

if (isset($_POST["nombre"])) {
    if ($_POST["nombre"] == "") {
        // unset destruye la session de inmediato
        unset($_SESSION["nombre"]);
    } else {
        $_SESSION["nombre"] = $_POST["nombre"];
    }
}

if (isset($_POST["btnBorrarSesion"])) {
    // session destroy en esta ejecucion, sigue existiendo, hasta que haga un redireccionamiento
    session_destroy();
    header("location:index.php");
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesiones 01_2</title>
</head>

<body>
    <p>Su nombre es:
        <strong>
            <?php
            if (isset($_SESSION["nombre"]) && $_SESSION["nombre"] !== "") {
                echo $_SESSION["nombre"];
            } else {
                echo "<span>No has escrito ningun nombre<span>";
            }
            ?>
        </strong>
    </p>
    <p>
        <a href="index.php">Volver a primera pagina<a>
    </p>
</body>

</html>