<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>teoria arrays</title>
</head>

<body>
    <h1>Teoria arrays</h1>
    <?php

    // añadir elementos a un array
    $nota[0] = 5;
    $nota[1] = 9;
    $nota[2] = 8;
    $nota[3] = 5;
    $nota[4] = 6;
    $nota[5] = 7;

    // imprimir array
    echo "<h1>Imprimir array con var_dump</h1>";
    print_r($nota);
    echo "<br>";
    echo "<br>";
    // imprimir array de forma mas completa
    var_dump($nota);

    // los arrays son multitipo, se puede crear un array que contenga varios tipos de datos
    $valor[0] = 18;
    $valor[1] = "Hola";
    $valor[2] = true;
    $valor[3] = 3.4;
    echo "<br>";
    echo "<br>";
    var_dump($valor);


    // tambien se puede agregar valor al array sin especificar el indice
    // y se guardaran segun el orden se van añadiendo datos
    echo "<h1>Imprimir array sin indice</h1>";
    $valor2[] = 18;
    $valor2[] = "Hola";
    $valor2[] = true;
    $valor2[] = 3.4;
    echo "<br>";
    echo "<br>";
    var_dump($valor2);


    // tambien se puede agregar valor al array especificando un indice y sin especificar, 
    // pero en este caso, dejamos valores sin definir
    $valor3[] = 18;
    $valor3[3] = "Hola";
    $valor3[] = true;
    $valor3[] = 3.4;
    echo "<br>";
    echo "<br>";
    var_dump($valor3);

    // recorrer un array escalar con indices correlativos
    echo "<h1>Recorrido de un array de forma escalar (for)</h1>";
    for ($i = 0; $i < count($nota); $i++) {
        echo "<p> En la posicion " . $i . ", tiene el valor: " . $nota[$i] . "</p>";
    }
    echo "<br>";
    echo "<br>";


    // si tenemos indices en el array sin definir y otros definidos,
    // lo ideal es recorrer con for each
    $valor4[] = 18;
    $valor4[3] = "Hola";
    $valor4[] = true;
    $valor4[] = false;
    $valor4[] = 3.4;
    // en $contenido, guardara el valor que encuentre en cada posicion de $valor
    // las variables booleanas false, aparecen como vacias, no se imprimen en pantalla
    echo "<h1>Recorrido de un array NO correaltivo (for each sin indcie)</h1>";
    foreach ($valor4 as $contenido) {
        echo "<p> Contenido: " . $contenido . "</p>";
    }
    echo "<br>";
    echo "<br>";

    // para obtener el indice con un foreach, agregar una variable que guarde el indice
    echo "<h1>Recorrido de un array NO correaltivo (for each con indice)</h1>";
    foreach ($valor4 as $indice => $contenido) {
        echo "<p> Contenido de " . $indice . ": " . $contenido . "</p>";
    }
    echo "<br>";
    echo "<br>";

    // definir array mediante un constructor
    echo "<h1>Recorrido de un array creado con constructor</h1>";
    $valor5 = array(5, 9, 8, 6, 7);
    foreach ($valor5 as $indice => $contenido) {
        echo "<p> Contenido de " . $indice . ": " . $contenido . "</p>";
    }
    echo "<br>";
    echo "<br>";

    // definir array mediante un constructor asginando un valor a un indice concreto
    echo "<h1>Recorrido de un array creado con constructor, asginando valor a indice especifico</h1>";
    $valor6 = array(5, 99 => "hola", false, 200 => 3.4);
    foreach ($valor6 as $indice => $contenido) {
        echo "<p> Contenido de " . $indice . ": " . $contenido . "</p>";
    }
    echo "<br>";
    echo "<br>";

    // Arrays asociativos, definir un array, pasando un nombre al indice, en vez de una posicion
    echo "<h1>Recorrido de un array asociativo, asignando un nombre a la posicion del array</h1>";
    $valor7["Antonio"] = 5;
    $valor7["Luis"] = 9;
    $valor7["Ana"] = 8;
    $valor7["Eloy"] = 5;
    $valor7["Gabriela"] = 6;
    $valor7["Berta"] = 7;
    foreach ($valor7 as $indice => $contenido) {
        echo "<p> Contenido de " . $indice . ": " . $contenido . "</p>";
    }
    echo "<br>";
    echo "<br>";

    // Arrays bidimensionales, definir un array bidimensional para guardar mas de un valor en los mismo indices
    // pasando un nombre al indice, en vez de una posicion
    echo "<h1>Recorrido de un array asociativo bidimensional</h1>";

    $valor8["Antonio"]["DWESE"] = 5;
    $valor8["Antonio"]["DWEC"] = 9;
    $valor8["Ana"]["DWESE"] = 8;
    $valor8["Ana"]["DWEC"] = 5;
    $valor8["Luis"]["DWESE"] = 6;
    $valor8["Luis"]["DWEC"] = 7;
    $valor8["Eloy"]["DWESE"] = 9;
    $valor8["Eloy"]["DWEC"] = 5;
    
    // pasamos el array que contiene los demas arrays
    // por cada indice, nos quedamos con el array de asiganturas
    foreach($valor8 as $nombre => $asignatura){
        // mostramos cada uno de los nombre en un ul
        echo "<p>" .$nombre. "<ul>";
        // pasamos el array de asignaturas al siguiente foreach
        // y por cada nombre, obtenemos el valor que es la nota
        // y mostramos en un li, cada nombre de asignatura con su valor
        foreach($asignatura as $nombre_asignatura => $nota){
            echo "<li>".$nombre_asignatura.": ".$nota."</li>";
            
        }
        echo "</ul> </p>";
    }

    echo "<br>";
    echo "<br>";

    // formas predefinidas de recorrer un array
    echo "<h1>Formas predefinidas de recorrer un array</h1>";
    $capital = array("catilla y Leon" => "Valladolid", "Asturias" => "Oviedo", "Aragón" => "Zaragoza");

    echo "<p>Obtener el ultimo valor de un array (el puntero se mantiene en el final): ".end($capital)."<p/>";
    echo "<p>Obtener el valor, donde se encuentra el puntero (este caso el penultimo por la llamada de end): ".current($capital)."<p/>";
    echo "<p>Retroceder una posicion (saldra el penultimo, el puntero apunta al ultimo): ".prev($capital)."<p/>";
    echo "<p>Obtener nombre del indice al que apunta el puntero(en este caso, obtiene el nombre del indice del ultimo): ".key($capital)."<p/>";
    echo "<p>Volver a apuntar al primer valor con el puntero: ".reset($capital)."<p/>";
    echo "<p>Avanzar una posicion hacia adelante con el valor: ".next($capital)."<p/>";

    // recorrer un array asociativo con los metodos predefinidos
    // mientras el current contenga algo
    reset($capital);
    while(current($capital)){
        // imprimo el valor
        echo "".current($capital)." ";
        // se pasa al siguiente valor
        next($capital);
    }

    ?>
</body>

</html>