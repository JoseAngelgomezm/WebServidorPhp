<?php
function myExplode($texto, $separador)
{
    $contadorPalabra = 0;
    $arrayPalabras = [""];
    $nLetra = 0;
    while (isset($texto[$nLetra]) && $texto[$nLetra] == $separador) {
        $nLetra++;
    }

    while (isset($texto[$nLetra])) {
        // si la posicion de la palabra no es el separador
        if ($separador != $texto[$nLetra]) {
            // ir quedandonos con las letras de la palabra y añadiendola al array
            $arrayPalabras[$contadorPalabra] = $arrayPalabras[$contadorPalabra] . $texto[$nLetra];
        }
        // si encontramos un separador, aumentar en 1 la posicion en la que concatenamos las letras
        else if ($separador == $texto[$nLetra]) {
            $contadorPalabra++;
            $arrayPalabras[$contadorPalabra] = "";
        }
        $nLetra++;
    }
    return $arrayPalabras;
}

@$fd = fopen("../Ficheros/horarios.txt", "r");
if (!$fd) {
    die("el fichero no se ha podido abrir");
} else {
    $linea = "";
    $arrayNombres = [];
    $arrayHorario = [];
    while ($linea = fgets($fd)) {
        $arrayLinea = myExplode($linea, "\t");
        $arrayNombres[] = $arrayLinea[0];
        $arrayHorario[] = $linea;
    }
}
fclose($fd);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicio 4</h1>
    <?php
    ?>
    <form action="#" method="post" enctype="multipart/form-data">
        <p>
            <label for="profesor">Horario del profesor: </label>
            <select name="profesor" id="profesor">
                <?php
                for ($a = 0; $a < count($arrayNombres); $a++) {
                    if ($a == $_POST["profesor"]) {
                        echo "<option selected value='" . $a . "'>" . $arrayNombres[$a] . "</option>";
                    } else {
                        echo "<option value='" . $a . "'>" . $arrayNombres[$a] . "</option>";
                    }

                }
                ?>
            </select>
        </p>
        <p>
            <button name="ver" id="ver">Ver Horario</button>
        </p>
    </form>
    <?php
    if (isset($_POST["ver"])) {
        $posicion = $_POST["profesor"];
        $arrayHorarioDividido = myExplode($arrayHorario[$posicion], "\t");
        for($i = 1 ; $i<count($arrayHorarioDividido); $i+=3) {
            if(isset($arrayHorarioFinal[$arrayHorarioDividido[$i]][$arrayHorarioDividido[$i+1]])){
                $arrayHorarioFinal[$arrayHorarioDividido[$i]][$arrayHorarioDividido[$i+1]] .="/".$arrayHorarioDividido[$i+2];
            }else{
                $arrayHorarioFinal[$arrayHorarioDividido[$i]][$arrayHorarioDividido[$i+1]] = $arrayHorarioDividido[$i+2];
            }
            
        }
        
        
        echo "<h3 class='centrador'>Horario del profesor: " . $arrayNombres[$_POST["profesor"]] . "</h3>";
        $horas[1] = "8:15 - 9:15";
        $horas[] = "9:15 - 10:15";
        $horas[] = "9:15 - 10:15";
        $horas[] = "10:15 - 11:15";
        $horas[] = "11:15 - 11:45";
        $horas[] = "11:45 - 12:45";
        $horas[] = "12:45 - 13:45";
        $horas[] = "13:45 - 14:45";

        echo "<table border='1px'>";
            echo "<tr>";
                echo "<th></th>";
                echo "<th>Lunes</th>";
                echo "<th>Martes</th>";
                echo "<th>Miercoles</th>";
                echo "<th>Jueves</th>";
                echo "<th>viernes</th>";
            echo "</tr>";
            for($hora = 1;$hora<=7; $hora++){
                echo "<tr>";
                    echo "<th>".$horas[$hora]."</th>";
                    if($hora == 4){
                        echo "<td colspan=5>RECREO</td>";
                    }else{
                        for($dia = 1;$dia<=5; $dia++){
                            if(isset($arrayHorarioFinal[$dia][$hora])){
                                echo "<td>".$arrayHorarioFinal[$dia][$hora]."</td>";
                            }else{
                                echo "<td></td>";
                            }
                        
                        }
                    }
                echo "</tr>";
            }
        echo "</table>";



        echo "<br>";
        echo "<table border='1px'>";
        echo "<tr>";
        echo "<th></th>";
        echo "<th>Lunes</th>";
        echo "<th>Martes</th>";
        echo "<th>Miercoles</th>";
        echo "<th>Jueves</th>";
        echo "<th>viernes</th>";
        echo "</tr>";

        echo "<tr>";
        echo "<th>8:15 - 9:15</th>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<th>9:15 - 10:15</th>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<th>10:15 - 11:15</th>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<th>11:15 - 11:45</th>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<th>11:45 - 12:45</th>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<th>12:45 - 13:45</th>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<th>13:45 - 14:45</th>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "</tr>";
        echo "</table>";
    }
    ?>
</body>

</html>