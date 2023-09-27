<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $lenguaje_cliente = array("LC1" => "JavaScript", "LC2" => "HTML", "LC3" => "CSS", "LC4" => "TypeScript", "LC5" => "Swift");
    $lenguaje_servidor = array("LS1" => "Python", "LS2" => "Node.js", "LS3" => "Ruby", "LS4" => "PHP", "LS5" => "Java");

    // de este modo , solo funciona si los nombre de los indices no se repite
    // $lenguajes = $lenguaje_cliente + $lenguaje_servidor
    
    // hacer una copia de lenguaje cliente para obtener los datos
    $lenguajes = $lenguaje_cliente;

    // añadir al array lenguaje todos los valores del array lenguaje cliente
    foreach ($lenguaje_cliente as $indice => $valor) {
        array_push($lenguajes, $valor);
    }

    // mostrar la tabla
    echo "<table border=1px>";
    // titulos de la tabla
    echo "<tr><th>Lenagujes</th></tr>";
    // por cada lenguaje mostrar una columna con una fila que contiene el valor
    foreach ($lenguajes as $valor) {
        echo "<tr><td>" . $valor . "</td></tr>";
    }
    echo "</table>";

    ?>
</body>

</html>