<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $ciudades[] = "Madrid";
    $ciudades[] = "Barcelona";
    $ciudades[] = "Londres";
    $ciudades[] = "New york";
    $ciudades[] = "Los Angeles";
    $ciudades[] = "Chicago";


    foreach ($ciudades as $indice => $valor) {
        echo "La ciudad con el indice " . $indice . " tiene el nombre de " . $valor . "";
        echo "<br>";
    }

    ?>
</body>

</html>