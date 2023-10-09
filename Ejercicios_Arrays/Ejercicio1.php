<!DOCTYPE html>
<html lang="en">
<?php
// funcion que devuleve una cantidad de numeros segun el parametro de entrada
function obtenerPares($cantidadPares)
{
    // definir la variables numeroPares que guardara todos los valores pares en un array
    $numeroPares = array();
    // la variable $i ira siendo siempre numeros pares hasta el numero indicado en el parametro de entrada *2
    for ($i = 0; $i < $cantidadPares * 2; $i += 2) {
        // guardar la $i en orden de posicion del array
        $numeroPares[] = $i;
    }
    return $numeroPares;
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    define("CANTIDAD_PARES", 10);
    // pasamos el array que obtenemos de la llamada a la funcion al foreach con la cantidad de pares que queremos
    foreach ($paresObtenidos = obtenerPares(CANTIDAD_PARES) as $valor) {
        echo "<p>" . $valor . "</p>";
    }
    ?>
</body>

</html>