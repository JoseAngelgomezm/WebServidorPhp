<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 7</h1>
    <?php
    require 'clase_pelicula.php';
    $pelicula = new Pelicula("El padrino","Francis Ford Coppola",20.5,true,"2020/12/10");

    if($pelicula->getAlquilada()){
        echo "Alquilada";
    }else{
        echo "Disponible";
    }

    echo "fecha prevista";
    echo "Recargo";
    ?>
</body>

</html>