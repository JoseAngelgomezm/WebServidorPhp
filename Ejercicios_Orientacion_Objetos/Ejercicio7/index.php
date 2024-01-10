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
    $pelicula = new Pelicula("El padrino", "Francis Ford Coppola", 20.5, true, "2020/12/10");

    echo "<p>Director: " . $pelicula->getDirector() . "</p>";
    echo "<p>Precio: " . $pelicula->getPrecio() . "</p>";

    if ($pelicula->getAlquilada()) {
        echo "<p>Estado: Alquilada</p>";
    } else {
        echo "<p>Estado: Disponible</p>";
    }

    echo "<p>Fecha prevista entrega: " . $pelicula->getFechaPrevistaDevolucion() . "</p>";
    echo "<p>Recargo: " . $pelicula->getRecargo() . "€</p>";

    ?>
</body>

</html>