<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 5 - Ficheros</h1>
    <?php
    // abrir el archivo en modo lectura
    @$fd = fopen("http://dwese.icarosproject.com/PHP/datos_ficheros.txt", "r");
    if (!$fd) {
        die("<p>no se ha podido abrir el archivo</p>");
    }
    ?>
    <form action="#" method="post">
        <select name="paises">
        
        </select>
    </form>
    <?php
    fclose($fd);
    ?>

</body>

</html>