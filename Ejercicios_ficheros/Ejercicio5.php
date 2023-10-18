<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 5 - Ficheros</h1>


    <?php
    // abrir el archivo en modo lectura
    @$fd = fopen("http://dwese.icarosproject.com/PHP/datos_ficheros.txt", "r");
    if (!$fd) {
        die("<p>no se ha podido abrir el archivo</p>");
    }
    echo "<table border='1px'>";
    //por cada fila obtener el explode de cada una 
    while ($linea = fgets($fd)) {
        $arrayFila = explode("\t", $linea);
        echo "<tr>";
        for ($i = 0; $i < count($arrayFila); $i++) {
            if ($i == 0) {
                echo "<th>" . $arrayFila[$i] . "</th>";
            }else{
                echo "<td>" . $arrayFila[$i] . "</td>";
            }
        }

        if(count($arrayFila) < 21) {
            for($j=0;$j < 21 - count($arrayFila); $j++){
                echo "<td></td>";
            }
        }
        echo "</tr>";
    }
    fclose($fd);
    echo "</table>";
    ?>
</body>

</html>