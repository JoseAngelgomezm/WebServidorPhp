<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $ciudades["MD"] = "Madrid";
    $ciudades["BC"] = "Barcelona";
    $ciudades["L"] = "Londres";
    $ciudades["NW"] = "New york";
    $ciudades["LA"] = "Los Angeles";
    $ciudades["CH"] = "Chicago";


    foreach($ciudades as $indice => $valor){
        echo "El indice del array que contiene como valor ".$valor." es ".$indice."";
        echo "<br>";
    }

    ?>
</body>

</html>