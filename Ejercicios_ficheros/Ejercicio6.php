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

    // saltar la primera linea
    $primeraFila = fgets($fd);
    while ($linea = fgets($fd)) {
        $arrayLineas = explode("\t", $linea);
        $arrayPartesLinea = explode(",", $arrayLineas[0]);
        $zonas[] = $arrayPartesLinea[2];
        // preguntar por lo que trae el select , si es lo que hemos seleccionado, añadirle los datos a arrayDatosPaisSeleccionado
        if (isset($_POST["paises"]) && $_POST["paises"] == $arrayPartesLinea[2]) {
            $arrayDatosPaisSeleccionado = $arrayLineas;
        }
    }
    fclose($fd);
    ?>

    <form action="#" method="post">
        <p>
            <label for="pasies">Selecciona una zona</label>
            <select name="paises">
                <?php
                for ($i = 0; $i < count($zonas); $i++) {
                    if (isset($_POST["paises"]) && $_POST["paises"] == $zonas[$i]) {
                        echo "<option selected value'" . $zonas[$i] . "'>" . $zonas[$i] . "</option>";
                    } else {
                        echo "<option value'" . $zonas[$i] . "'>" . $zonas[$i] . "</option>";
                    }

                }
                ?>
            </select>
        </p>
        <p>
            <button type="submit" name="buscar" id="buscar">Buscar</button>
        </p>
    </form>
    <?php
    if (isset($_POST["buscar"])) {
        echo "<h2>PIB PER CAPITA de " . $_POST["paises"] . "</h2>";
        // dividir la primera fila por tabulador para obtener cada uno de los años
        $datosPrimeraFila = explode("\t", $primeraFila);
        // saber cuantas posiciones tiene para poner tantos td como posiciones
        $numeroAños = count($datosPrimeraFila) - 1;

        echo "<table border='1px'>";
        echo "<tr>";
        // empezar por la 1 porque no nos sirve la posicion 0
        for ($i = 1; $i <= $numeroAños; $i++) {
            echo "<th>";
            echo $datosPrimeraFila[$i];
            echo "</th>";
        }
        echo "</tr>";

        echo "<tr>";
        for ($i = 1; $i <= $numeroAños; $i++) {
            if(isset($arrayDatosPaisSeleccionado[$i])){
                echo "<td>".$arrayDatosPaisSeleccionado[$i]."</td>"; 
            }else{
                echo "<td></td>";
            }
        }
        echo "</tr>";
        echo "</table>";
    }


    ?>
</body>

</html>