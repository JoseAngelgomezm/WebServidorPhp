<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Bienvenido <?php echo $datosUsuario->lector ." tipo: ".$_SESSION["tipo"].""?></h2>
    <form action="index.php">

    <button type="submit" name="salir">Salir</button>
    </form>

    <?php
        require("vistas/vista_obtener_libros.php");
    ?>
</body>

</html>