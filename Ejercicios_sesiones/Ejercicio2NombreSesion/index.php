<?php
// abrir la sesion con el nombre que trabajamos
session_name("Ejercicio2ExcepcionNombre");
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario nombre 1</title>
    <style>
        .centrado{
            text-align: center;
        }
    </style>
</head>

<body>
    <h1 class="centrado">Formulario nombre 1 (Ejercicio 1)</h1>
    <?php
    if(isset($_SESSION["nombre"])){
        echo "<p>Su nombre es : ".$_SESSION["nombre"]."</p>";
    }
    ?>
    <form action="sesiones02_2.php" method="post">
    <p>Escriba su nombre:</p>
    <p>
        <label for="nombre"><strong>Nombre: </strong></label>
        <input type="text" name="nombre" id="nombre"></input>
        <?php
            if(isset($_SESSION["error"])){
                echo "<span>No has tecleado nada</span>";
                session_destroy();
            }
        ?>
    </p>
    <p>
        <button type="submit" name="btnSiguiente">Siguiente</button>
        <button type="submit" name="btnBorrarSesion">Borrar Sesion</button>
    </p>
    </form>
</body>

</html>