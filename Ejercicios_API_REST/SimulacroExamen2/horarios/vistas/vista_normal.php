<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Horario de los profesores</h2>
    <h3>Bienvenido <?php echo $datosUsuario->usuario." - "; echo $datosUsuario->tipo ?></h3>
    <form action="index.php" method="post">
        <button type="submit" name="salir">Salir</button>
    </form>
</body>

</html>