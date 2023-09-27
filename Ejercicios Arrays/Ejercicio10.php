<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $enteros = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");
    $suma = 0;

    foreach ($enteros as $indice => $valor) {
        // si el resto de la division entre 2 es 0, es par
        if ($valor % 2 == 0) {
            // ir sumando los valores
            $suma += $valor;
            // sino mostrar el valor por pantalla
        } else {
            echo "$valor ";
        }

    }
    // obtener la media, contando el length del array y diviendola entre 2, obtenemos la cantidad
    // de numeros que hemos sumado, diviendo suma / cantidad, obtenemos la media 
    $media = $suma / (count($enteros) / 2);
    echo "<br/>La media de los pares es: $media";
    ?>
</body>

</html>