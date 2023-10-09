<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $animales = array("Lagartija", "Araña", "Perro", "Gato", "Raton");
    $numeros = array("12", "34", "45", "12");
    $arboles = array("Sauce", "Pino", "Naranjo", "Chopo", "Perro", "34");

    $todo = array();

    foreach ($animales as $indice => $valor) {
        array_push($todo, $valor);
    }

    foreach ($numeros as $indice => $valor) {
        array_push($todo, $valor);
    }

    foreach ($arboles as $indice => $valor) {
        array_push($todo, $valor);
    }

    foreach ($todo as $indice => $valor) {
        echo "$valor <br/>";
    }
    ?>
</body>

</html>