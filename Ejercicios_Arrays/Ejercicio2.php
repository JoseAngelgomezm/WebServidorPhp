<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $v[1] = 90;
    $v[30] = 7;
    $v["e"] = 99;
    $v["hola"] = 90;

    foreach ($v as $indice => $valor) {
        // si $indice es numerico, no ponerle comillas
        if (is_numeric($indice)) {
            echo "<p>El valor de " . $indice . " es: " . $valor . "</p>";
        } else {
            echo "<p>El valor de '" . $indice . "' es: " . $valor . "</p>";
        }

    }
    ?>
</body>

</html>